<?php
/**
 * Puppeteering service for delivering views from boosts
 *
 * @package PuppetService
 * @author kevin olson <acidjazz@gmail.com>
 * @version 0.1
 * @copyright (C) 2018 kevin olson <acidjazz@gmail.com>
 * @license APACHE
 */

namespace App\Services;

use Aws\Ec2\Ec2Client;

use App\Models\Boost;
use Carbon\Carbon;

class PuppetService {

    /**
     * IAM assigned to each instance
     *
     * @var string
     */
    const IAM_ARN = 'arn:aws:iam::751311555268:instance-profile/api';

    /**
     * Region instances are deployed and checked on
     *
     * @var string
     */
    const REGION = 'us-west-1';

    /**
     * Array of boosts
     *
     * @var array
     */
    private $boosts;

    /**
     * Count of machines to spawn
     * @var integer
     */
    private $machines;

    /**
     * AWS SDK EC2 Client instnace
     *
     * @var  Aws\Ec2\Ec2Client
     */
    private $client;

    /**
     * List of potential regions available
     *
     * @var array
     */
    private $regions = [
        'us-east-1' => [
            'SubnetId' => 'subnet-a3ceaefa',
            'ImageId' => '',
        ],
        'us-east-2' => [
            'SubnetId' => 'subnet-3ce31955',
            'ImageId' => 'ami-0ba503d981a89b66b',
        ],
        'us-west-1' => [
            'SubnetId' => 'subnet-28b51870',
            'ImageId' => 'ami-09ee583639c586d6a',
        ],
    ];

    /**
     * Initiate the client, populating $this->client
     *
     */
    public function __construct()
    {

        $this->client = new Ec2Client([
            'region' => self::REGION,
            'version' => 'latest',
            'credentials' => [
                'key' => config('services.ses.key'),
                'secret' => config('services.ses.secret'),
            ],
        ]);
    }

    /**
     * If available, return all instances still running
     *
     * @return [Boolean, Array]
     */
    public function describe()
    {

        $filters = [
            'Filters' => [
                [
                    'Name' => 'instance-state-name',
                    'Values' => ['pending','running','stopped','stopping','shutting-down'],
                ],
            ],
        ];

        $result = $this->client->describeInstances($filters);

        if (
            !isset($result['Reservations']) ||
            !isset($result['Reservations'][0]) ||
            !isset($result['Reservations'][0]['Instances'])
        ) {
            return false;
        }
        $instances = [];

        foreach ($result['Reservations'][0]['Instances'] as $instance) {
            $start = new Carbon($instance['LaunchTime']);
            $runtime = $start->diffInSeconds(new Carbon());
            $instances[$instance['InstanceId']] = [
                'InstanceId' => $instance['InstanceId'],
                'State' => $instance['State']['Name'],
                'Runtime' => $runtime,
                'Domain' => $instance['PublicDnsName'],
            ];
        };

        return $instances;
   }

    public function terminate($ids)
    {
        return $this->client->terminateInstances([
            'DryRun' => false,
            'InstanceIds' => $ids,
        ]);
    }

    /**
     * Deploy puppets to satisfy active boosts
     *
     * @param array $boost_ids
     * @param integer $machines
     *
     * @return array
     */
    public function deploy($boost_ids,$machines=1)
    {
        $this->boosts = Boost::with('video')->whereIn('id', $boost_ids)->get();
        $this->machines = $machines;

        $result = $this->client->runInstances([
            'ImageId'    => $this->regions[self::REGION]['ImageId'],
            'MinCount'  => $this->machines,
            'MaxCount'   => $this->machines,
            'IamInstanceProfile' => [
                'Arn' => self::IAM_ARN,
            ],
            'InstanceInitiatedShutdownBehavior' => 'terminate',
            'InstanceType'  => 't2.small',
            'KeyName'   => 'tj',
            'SubnetId' => $this->regions[self::REGION]['SubnetId'],
            // 'SecurityGroups'    => ['default'],
            'UserData' => base64_encode($this->userData()),
        ]);

        $ids = [];
        foreach ($result['Instances'] as $instance) {
            $ids[] = $instance['InstanceId'];
        }

        /* this fails too much, and we dont need it
        $this->client->createTags([
            'Resources' => $ids,
            'Tags' => [[
                'Key' => 'Name',
                'Value' => 'puppet',
                ]],
        ]);
        */

        return $result;
    }

    private function env()
    {
        switch (config('app.env')) {
            case 'local':
                return 'api-dev';
                break;
            case 'staging':
                return 'api-staging';
                break;
            case 'production':
                return 'api-master';
                break;
        }
    }

    /**
     * User Data - cloudinit bash script run on instance
     *
     * @return string
     */
    private function userData()
    {

        $env = $this->env();
        $json = str_replace("\\'", "'", addslashes($this->boosts->makeHidden(['created_at','updated_at'])->toJson()));
        return  <<<EOT
#!/bin/bash
aws iam attach-role-policy --role-name api --policy-arn arn:aws:iam::aws:policy/service-role/AWSConfigRole
echo "$json" > /tmp/boosts.json
su - ec2-user -c "
cd ~/.
aws s3 cp s3://trendjet-vault/envs/{$this->env()} .env
aws s3 cp s3://trendjet-vault/driver/index.php index.php
php index.php "
shutdown -h now
EOT;
    }
}

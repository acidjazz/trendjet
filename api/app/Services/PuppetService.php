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
    const REGION = 'us-east-2';

    /**
     * Array of boost Ids machines will report to
     *
     * @var array
     */
    private $boost_ids;

    /**
     * Array of Video Ids machines will report to
     *
     * @var array
     */
    private $video_ids;

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
            'ImageId' => 'ami-017ed5b753ea0a814',
        ],
        'us-east-2' => [
            'SubnetId' => 'subnet-3ce31955',
            'ImageId' => 'ami-044c204615efb74f5',
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
                'key' => env('AWS_KEY'),
                'secret' => env('AWS_SECRET'),
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
            $instances[$instance['InstanceId']] = [
                'InstanceId' => $instance['InstanceId'],
                'State' => $instance['State']['Name'],
            ];
        };

        return $instances;
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
        $this->boost_ids = $boost_ids;
        $this->video_ids = Boost::whereIn('id', $this->boost_ids)->pluck('video_id')->toArray();

        $this->boost_str = implode(',', $this->boost_ids);
        $this->video_str = implode(',', $this->video_ids);

        $this->machines = $machines;

        $result = $this->client->runInstances([
            'ImageId'    => $this->regions[self::REGION]['ImageId'],
            'MinCount'  => $this->machines,
            'MaxCount'   => $this->machines,
            'IamInstanceProfile' => [
                'Arn' => self::IAM_ARN,
            ],
            'InstanceInitiatedShutdownBehavior' => 'terminate',
            'InstanceType'  => 't2.nano',
            // 'KeyName'   => 'sugar',
            'SubnetId' => $this->regions[self::REGION]['SubnetId'],
            // 'SecurityGroups'    => ['default'],
            'UserData' => base64_encode($this->userData()),
        ]);

        $ids = [];
        foreach ($result['Instances'] as $instance) {
            $ids[] = $instance['InstanceId'];
        }

        $this->client->createTags([
            'Resources' => $ids,
            'Tags' => [[
                'Key' => 'Name',
                'Value' => 'puppet',
                ]],
        ]);

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
        return  <<<EOT
#!/bin/bash
aws iam attach-role-policy --role-name api --policy-arn arn:aws:iam::aws:policy/service-role/AWSConfigRole
su - ec2-user -c "
cd ~/.
aws s3 cp s3://trendjet-vault/envs/{$this->env()} .env
aws s3 cp s3://trendjet-vault/puppet/index.js index.js
node index.js {$this->video_str} {$this->boost_str} "
shutdown -h now
EOT;
    }
}

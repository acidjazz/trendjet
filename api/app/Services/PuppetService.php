<?php
/**
 * Short description for PuppetService.php
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

    const IAM_ARN = 'arn:aws:iam::751311555268:instance-profile/api';
    const REGION = 'us-east-2';

    private $boost_ids;
    private $video_ids;
    private $machines;
    private $client;
    private $endpoint_boost;
    private $endpoint_shot;

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
     * Create instance
     *
     * @param Array $ids
     */
    public function __construct($boost_ids,$machines=1)
    {
        $this->boost_ids = $boost_ids;
        $this->video_ids = Boost::whereIn('id', $this->boost_ids)->pluck('video_id')->toArray();

        $this->boost_str = implode(',', $this->boost_ids);
        $this->video_str = implode(',', $this->video_ids);

        $this->machines = $machines;

        $this->client = new Ec2Client([
            'region' => self::REGION,
            'version' => '2016-11-15',
            'profile' => 'default',
        ]);
    }

    public function deploy()
    {

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

    /**
     * User Data - cloudinit bash script run on instance
     *
     */
    private function userData()
    {

        return  <<<EOT
#!/bin/bash
aws iam attach-role-policy --role-name api --policy-arn arn:aws:iam::aws:policy/service-role/AWSConfigRole
su - ec2-user -c "
cd ~/.
aws s3 cp s3://trendjet-vault/envs/api-dev .env
aws s3 cp s3://trendjet-vault/puppet/index.js index.js
node index.js {$this->video_str} {$this->boost_str} "
shutdown -h now
EOT;
    }
}

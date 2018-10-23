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

    const IMAGE_ID = 'ami-0aa2cacdc4fcb1340';
    const IAM_ARN = 'arn:aws:iam::751311555268:instance-profile/api';

    private $boost_ids;
    private $video_ids;
    private $machines;
    private $client;
    private $endpoint_boost;
    private $endpoint_shot;

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
            'region' => 'us-east-1',
            'version' => '2016-11-15',
            'profile' => 'default',
        ]);
    }

    /**
     * User Data - cloudinit bash script run on instance
     *
     */
    private function userData()
    {

        $endpoint = config('app.url').'shot?apikey='.config('app.apikey');

        $userData = <<<EOT
#!/bin/bash
aws iam attach-role-policy --role-name api --policy-arn arn:aws:iam::aws:policy/service-role/AWSConfigRole
su - ec2-user -c "
cd ~/.
node index.js {$this->video_str} {$this->boost_str} "
for file in /home/ec2-user/*.jpg; do
  aws s3 cp \$file s3://trendjet-shots/ --grants read=uri=http://acs.amazonaws.com/groups/global/AllUsers
  curl -X POST -d file=\$file {$endpoint}
done
EOT;

        foreach ($this->boost_ids as $id) {
            $endpoint = config('app.url').'boost/'.$id.'/?apikey='.config('app.apikey');
            $userData .= "\ncurl -X PUT {$endpoint}\n";
        }

        $userData.= "\nshutdown -h now\n";

        return $userData;

    }

    public function deploy()
    {

        $result = $this->client->runInstances([
            'ImageId'    => self::IMAGE_ID,
            'MinCount'  => $this->machines,
            'MaxCount'   => $this->machines,
            'IamInstanceProfile' => [
                'Arn' => self::IAM_ARN,
            ],
            'InstanceInitiatedShutdownBehavior' => 'terminate',
            'InstanceType'  => 't2.nano',
            'KeyName'   => 'sugar',
            'SubnetId' => 'subnet-a3ceaefa',
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
}

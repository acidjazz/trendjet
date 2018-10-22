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

class PuppetService {

    const IMAGE_ID = 'ami-03577c2f4765ee608';
    const IAM_ARN = 'arn:aws:iam::751311555268:instance-profile/api';

    private $ids = [];
    private $imploded;
    private $client;

    /**
     * Create instance
     *
     * @param Array $ids
     */
    public function __construct($ids)
    {
        $this->ids = $ids;
        $this->imploded = implode(',', $this->ids);

        $this->client = new Ec2Client([
            'region' => 'us-east-1',
            'version' => '2016-11-15',
            'profile' => 'default',
        ]);
    }

    private function endpoint()
    {
        return config('app.url').'/boost?apikey='.config('app.apikey');
    }

    /**
     * User Data - cloudinit bash script run on instance
     *
     */
    private function userData()
    {

        return <<<EOT
#!/bin/bash
aws iam attach-role-policy --role-name api --policy-arn arn:aws:iam::aws:policy/service-role/AWSConfigRole
echo {$this->imploded} > /tmp/ids.txt
su - ec2-user -c "
cd ~/.
node index.js `cat /tmp/ids.txt`
"
for file in /home/ec2-user/*.jpg; do
  aws s3 cp \$file s3://trendjet-shots/ --grants read=uri=http://acs.amazonaws.com/groups/global/AllUsers
done
EOT;
    }


    public function deploy()
    {

        dd($this->endpoint());

        $result = $this->client->runInstances([
            'ImageId'    => self::IMAGE_ID,
            'MinCount'  => 1,
            'MaxCount'   => 1,
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

        $this->client->createTags([
            'Resources' => [$result['Instances'][0]['InstanceId']],
            'Tags' => [[
                'Key' => 'Name',
                'Value' => 'puppet',
                ]],
        ]);

        return $result;
    }
}

<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use Aws\Ec2\Ec2Client;

class SendPuppet extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send:puppet {video}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send a puppet to watch a video';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {

        $ec2Client = new Ec2Client([
            'region' => 'us-east-1',
            'version' => '2016-11-15',
            'profile' => 'default',
        ]);

        $UserData = <<<EOT
#!/bin/bash
aws iam attach-role-policy --role-name api --policy-arn arn:aws:iam::aws:policy/service-role/AWSConfigRole
echo {$this->argument('video')} > /tmp/id.txt
su - ec2-user -c "
cd ~/.
node index.js `cat /tmp/id.txt`
"
aws s3 cp `ls /home/ec2-user/*.jpg` s3://trendjet-shots/ --grants read=uri=http://acs.amazonaws.com/groups/global/AllUsers
EOT;

        $result = $ec2Client->runInstances([
            'ImageId'    => 'ami-023d7936f01bc2993',
            'MinCount'  => 1,
            'MaxCount'   => 1,
            'IamInstanceProfile' => [
                'Arn' => 'arn:aws:iam::751311555268:instance-profile/api',
            ],
            'InstanceInitiatedShutdownBehavior' => 'terminate',
            'InstanceType'  => 't2.nano',
            'KeyName'   => 'sugar',
            'SubnetId' => 'subnet-a3ceaefa',
            // 'SecurityGroups'    => ['default'],
            'UserData' => base64_encode($UserData),
        ]);

        $ec2Client->createTags([
            'Resources' => [$result['Instances'][0]['InstanceId']],
            'Tags' => [[
                'Key' => 'Name',
                'Value' => 'puppet',
                ]],
        ]);
        $this->info('Puppet instance launched successfully');
    }
}

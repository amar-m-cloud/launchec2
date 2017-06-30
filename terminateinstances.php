<?php

use Aws\Ec2\Ec2Client;

require 'aws-autoloader.php';

//Collect POST parameters
$accesskeyid = $_POST['accesskeyid'];
$secretaccesskey = $_POST['secretaccesskey'];

//Create InstanceIDs array with one instance ID
$instanceIds = [];
$instanceid = $_POST['instanceid'];
array_push($instanceIds, $instanceid);

//Create an instance of EC2Client
$ec2client = new Ec2Client([
    'version' => 'latest',
    'region' => 'ap-southeast-2',
    'credentials' => [
        'key' => $accesskeyid,
        'secret' => $secretaccesskey
    ],
    'http' => [
        'verify' => false
    ]
        ]);

try {

    //Call terminateInstances
    $terminateInstancesResult = $ec2client->terminateInstances([
        'InstanceIds' => $instanceIds
    ]);
    
    //Wait until the instance state transitions to TERMINATED
    $ec2client->waitUntil('InstanceTerminated', [
        'InstanceIds' => $instanceIds
    ]);
    echo "Instance terminated successfully";
} catch (EC2Exception $e) {
    echo $e->getMessage(); //Catch EC2 Exception
}

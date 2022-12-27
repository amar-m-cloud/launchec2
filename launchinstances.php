<!DOCTYPE html>
<!--
Launchinstances.php

This page launches AWS instances based on the input parameters.
Below instance details will be displayed once the instances transition into RUNNING state
- Instance Type
- Public DNS Name
- Public IP Address
- Private IP Address

An option to delete the instances will be provided for each instance.
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title>Launch EC2 Instances</title>
        <!-- Bootstrap core CSS -->
        <link href="css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
        <!-- Bootstrap theme -->
        <link href="css/bootstrap-theme.min.css" rel="stylesheet" type="text/css"/>
        <link href="css/theme.css" rel="stylesheet" type="text/css"/>

        <script type = "text/javascript">
            function terminateEC2Instance(instanceid, accesskeyid, secretaccesskey) {
                $("#message").html("<p>Terminating instance " + instanceid + ". Please wait ... </p>"); //Terminating message while instances are getting terminated
                $("#btn-terminate").html("Terminating"); //Change terminate button text to terminating
                $("#btn-terminate").prop('disabled', true); //Disable terminate button while instances are getting terminated
                var confirmDelete = confirm("Are you sure you want to terminate the instance (" + instanceid + ")?"); //Confirmation to terminate the instance
                if (confirmDelete == true) { //If termination confirmed
                    $.ajax({type: 'POST',
                        data: {accesskeyid: accesskeyid,
                            secretaccesskey: secretaccesskey,
                            instanceid: instanceid
                        },
                        url: 'terminateinstances.php',
                        success: function (data) {
                            $("#message").html("<p>Instance " + instanceid + " terminated successfully.</p>");
                            $("#btn-terminate").html("Terminated");
                            $("#btn-terminate").prop('disabled', true);
                        },
                        error: function () {
                            alert("Error");
                        }
                    }); //Call terminateinstances.php
                } else { //If termination not confirmed
                    $("#message").html("");
                    return false;
                }
            }
        </script>

    </head>
    <body>
        <nav class="navbar navbar-inverse navbar-fixed-top">
            <div class="container">
                <div class="navbar-header">
                    <a class="navbar-brand" href="#">Launch EC2 Instances | Amar Mattaparthi</a>
                </div>

                <!-- Collect the nav links, forms, and other content for toggling -->
                <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                    <ul class="nav navbar-nav navbar-right">
                        <li><a href="index.php">Home</a></li>
                    </ul>
                </div><!-- /.navbar -->
            </div><!-- /.container -->
        </nav>
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="well well-lg text-center">
                        <?php
                        //Collect POST parameters
                        $instancecount = $_POST["instancecount"];
                        $instancename = $_POST["instancename"];
                        $accesskeyid = $_POST["accesskeyid"];
                        $secretaccesskey = $_POST["secretaccesskey"];
                        
                        //Checking if InstanceCount is a prime
                        $isprime = '';
                        $counter = 0;
                        for ($i = 1; $i <= $instancecount; $i++) {
                            if ($instancecount % $i == 0) {
                                $counter++;
                            }
                        }
                        
                        //If counter is 2, the number os prime. Primes require two dividers - 1 and itself
                        if ($counter == 2) {
                            $isprime = 'is a prime number';
                        } else {
                            $isprime = 'is not prime number';
                        }
                        ?>
                        <!--Echo if the InstanceCount is a prime number -->
                        <h4>Instance count (<?php echo $instancecount; ?>) <?php echo $isprime; ?></h4>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 text-center">
                    <span id="message"></span>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <?php

                    use Aws\Ec2\Ec2Client;

                    require 'aws-autoloader.php';
                    
                    //Create an instance of EC2Client with default region as ap-southeast-2.
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
                        //Call runInstances
                        $launchInstancesResult = $ec2client->runInstances([
                            'ImageId' => 'ami-10918173',
                            'InstanceType' => 't2.micro',
                            'MaxCount' => $instancecount,
                            'MinCount' => $instancecount,
                            'Monitoring' => [
                                'Enabled' => true,
                            ],
                            'TagSpecifications' => [
                                    [
                                    'ResourceType' => 'instance',
                                    'Tags' => [
                                            [
                                            'Key' => 'Name',
                                            'Value' => $instancename,
                                        ],
                                    ]
                                ]
                            ]
                        ]);
                        
                        //Create an array of Instance IDs
                        $instanceIds = [];
                        foreach ($launchInstancesResult['Instances'] as $newInstance) {
                            array_push($instanceIds, $newInstance['InstanceId']);
                        }
                        
                        //Wait untill all the instances are in running state
                        $ec2client->waitUntil('InstanceRunning', [
                            'InstanceIds' => $instanceIds
                        ]);
                        
                        //Get the Instance Details from InstanceIDs Array
                        $describeInstancesResult = $ec2client->describeInstances([
                            'InstanceIds' => $instanceIds
                        ]);
                        
                        //Get Instances array from describeInstancesResult
                        $instances = $describeInstancesResult['Reservations'][0]['Instances'];
                    } catch (EC2Exception $e) {
                        echo $e->getMessage(); //Catch any EC2 Exception
                    }
                    
                    //Iterate through the Instances array and echo the instance details inside the table.
                    foreach ($instances as $instance) {
                        ?>

                        <div class="panel panel-primary">
                            <div class="panel-heading">
                                <h4 class="panel-title">Instance ID: <?php echo $instance['InstanceId']; ?> <button id="btn-terminate" type="button" class="btn btn-danger btn-xs" onclick="terminateEC2Instance('<?php echo $instance['InstanceId']; ?>', '<?php echo $accesskeyid; ?>', '<?php echo $secretaccesskey; ?>')">Terminate Instance</button></h4>
                            </div>
                            <table class="table table-bordered">
                                <tr>
                                    <td class="active">
                                        Instance Type
                                    </td>
                                    <td>
                                        <?php echo $instance['InstanceType']; ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="active">
                                        Public DNS Name
                                    </td>
                                    <td>
                                        <?php echo $instance['PublicDnsName']; ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="active">
                                        Public IP Address
                                    </td>
                                    <td>
                                        <?php echo $instance['PublicIpAddress']; ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="active">
                                        Private IP Address
                                    </td>
                                    <td>
                                        <?php echo $instance['PrivateIpAddress']; ?>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        </br>

                        <?php
                    }
                    ?>
                </div>
            </div>
        </div>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
        <script>window.jQuery || document.write('<script src="../../assets/js/vendor/jquery.min.js"><\/script>')</script>
        <script src="js/bootstrap.js" type="text/javascript"></script>
        <script src="js/bootstrap.min.js" type="text/javascript"></script>
    </body>
</html>

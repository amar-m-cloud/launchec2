#LAUNCH EC2 APPLICATION - PRASAD DOMALA
This application is used to launch specified number of EC2 instances on AWS Cloud.
Instance details will be displayed once the instances transition into Running state.
Launched instances can be terminated individually.

#Input Parameters
InstanceCount: Number of EC2 Instances to launch.
InstanceName: Name of the EC2 Instance. This name is used as an Instance Tag on AWS
AccessKeyID: Your AWS AccessKeyID used to connect to AWS Cloud
SecretAccessKey: You SecretAccessKey used to connect to AWS Cloud

#Application Pages
index.php: This is the home page of the application which accepts inputs
launchinstances.php: This page launches requested instances and displays the Instance
                     details once the instances are transitioned into Running state.
terminateinstances.php: This page is used to terminate the selected instances launched
                        using this application.

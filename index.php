<!DOCTYPE html>
<!--
This is the index page of Launch EC2 Application.
It accepts InstanceCount, InstanceName, AccessKeyID & SecretAccessKey as input parameters and creates specified number of instances on AWS.
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
        <!-- Script to collect input parameters and post them to launchinstances.php-->
        <script type = "text/javascript">
            function launchInstances(form) {
                $("#launching").show(); //Show launching message while the instances transition into RUNNING state

                var url = form.attr("action"); //Post URL
                var formData = {}; //Initialize form data
                $(form).find("input[name]").each(function (index, node) {
                    formData[node.name] = node.value;
                }); //Populate form data with input parameters
                
                $.ajax({type: 'POST',
                    data: formData,
                    url: url,
                    error: function () {
                        alert("Error");
                    }
                }); //AJAX post input to launchinstances.php
            }
        </script>

    </head>
    <body>

        <nav class="navbar navbar-inverse navbar-fixed-top">
            <div class="container">
                <div class="navbar-header">
                    <a class="navbar-brand" href="#">Launch EC2 Instances | Amar Mattaparthi</a>
                </div>

                <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                    <ul class="nav navbar-nav navbar-right">
                        <li><a href="index.php">Home</a></li>
                    </ul>
                </div><!-- /.navbar-collapse -->
            </div><!-- /.container -->
        </nav>
        <form action="launchinstances.php" method="post">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <div class="well well-lg text-center">
                            <h4>This application creates specified number of EC2 instances along with a name of your choice.</h4>
                            <h4>Instance details will be displayed on the next page once all the instances are in running state.</h4>
                            <h4>Make sure you provide your AWS AccessKeyID and SecretAccessKey</h4>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6" style="padding-left: 20px; padding-right: 20px">
                        <div class="form-group row">
                            <label for="instancecount" class="col-2 col-form-label">Instance Count</label>
                            <div class="col-10">
                                <!-- Instance Count input -->
                                <input class="form-control" type="text" value="1" id="instancecount" name='instancecount'>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6" style="padding-left: 20px; padding-right: 20px">
                        <div class="form-group row">
                            <label for="instancename" class="col-2 col-form-label">Instance Name</label>
                            <div class="col-10">
                                <!-- Instance Name Input -->
                                <input class="form-control" type="text" value="MyEC2Instance" id="instancename" name='instancename'>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6" style="padding-left: 20px; padding-right: 20px">
                        <div class="form-group row">
                            <label for="accesskeyid" class="col-2 col-form-label">Access Key ID</label>
                            <div class="col-10">
                                <!-- Access Key ID Input -->
                                <input class="form-control" type="text" value="" id="accesskeyid" name='accesskeyid'>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6" style="padding-left: 20px; padding-right: 20px">
                        <div class="form-group row">
                            <label for="secretaccesskey" class="col-2 col-form-label">Secret Access Key</label>
                            <div class="col-10">
                                <!-- Secret Access Key input -->
                                <input class="form-control" type="text" value="" id="secretaccesskey" name='secretaccesskey'>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 text-center">
                        <span id="launching" style="display: none"><p>Launching instances.Please wait.....</p></span>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 text-center">
                        <!-- Call launchinstances.php -->
                        <button id="btn-launch" type="submit" class="btn btn-primary" onclick="launchInstances(this.form)">Launch EC2 Instances</button>
                    </div>
                </div>
            </div>
        </form>


        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
        <script>window.jQuery || document.write('<script src="../../assets/js/vendor/jquery.min.js"><\/script>')</script>
        <script src="js/bootstrap.js" type="text/javascript"></script>
        <script src="js/bootstrap.min.js" type="text/javascript"></script>
    </body>
</html>

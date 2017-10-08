<?php
/**********SETUP***********/
require 'soap_class.php';
$oSoap = new SOAP;
/**********INPUT***********/
	$sUsername = isset($_POST['txtuser']) ? htmlspecialchars(trim($_POST['txtuser'])) : NULL;
	$sPassword = isset($_POST['txtpass']) ? htmlspecialchars(trim($_POST['txtpass'])) : NULL;
	$sCommand  = isset($_POST['command']) ? $_POST['command'] : NULL;
	
/********OPERATION********/

if($sCommand =='Log in'){
	$oSoap->login_to_portal($sUsername,$sPassword);
}
	
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Portal Website</title>
<link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" />
</head>

<body>
	<div class="container">
    	<div class="row">
            <div class="col-md-4 column">
                    <div class="panel panel-default">
                        <div class="panel-heading" width="150" height="150">
                            <h3 class="panel-title" align="center">
                            	Login Page
                            </h3><!--End panel title-->
                        </div><!--End panel header-->
                        
                        <div class="panel-body">
                            <form action="" method="post" role="form" id="form">
                                 <div class="form-group">
                                     <input id='user' type="text" class="form-control"  name="txtuser" placeholder="Username" required />  
                                </div><!--End form group-->
                                <div class="form-group">
                                    <input id='pass' type="password" class="form-control" name="txtpass" placeholder="Password" required />                                    
                                </div><!--End form group-->
                                <div class="form-group">
                                     <button class="btn btn-lg btn-block" type="Submit" name="command" value="Log in"> Log in &emsp;<i class="glyphicon glyphicon-log-in"></i></button>
                                </div><!--End form group-->
                            </form>
                        </div><!--End panel body-->
                    </div><!--End panel default-->
                </div><!--End col-md-4-->
        </div>
    </div><!--End container-->       
</body>

</html>
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<?php
// This example header.inc.php is intended to be modfied for your application.
use QCubed as Q;
?>
<!DOCTYPE html>
<html>
<head>

	<meta charset="<?php echo(QCUBED_ENCODING); ?>"/>
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="Pragma" content="no-cache">
	<meta http-equiv="Expires" content="-1">
	<?php if (isset($strPageTitle)){ ?><title><?php _p($strPageTitle); ?></title>
	<?php } ?>
	<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet"/>
	<link href="../assets/smartmenus-1.1.0/addons/bootstrap/jquery.smartmenus.bootstrap.css" rel="stylesheet"/>
	<link href="../assets/css/font-awesome.min.css" rel="stylesheet"/>
	<link href="../assets/css/style.css" rel="stylesheet"/>
	<link href="../assets/css/menuexample.css" rel="stylesheet"/>
	<!--<link href="../../../qcubed/application/assets/css/jquery-ui.css" rel="stylesheet"/>-->
	<link href="https://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,400,600,700,300" rel="stylesheet" type="text/css"/>
</head>
	<body>
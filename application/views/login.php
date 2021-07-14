<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>New-app</title>

	<!-- Libraries and custom CSS -->
	<link rel="stylesheet" href="<?=base_url()?>assets/css/font-awesome/css/all.min.css">
	<link rel="stylesheet" href="<?=base_url()?>assets/css/bootstrap.min.css">
	<link rel="stylesheet" href="<?=base_url()?>assets/css/custom.css?<?=rand()?>">

	<!-- Libraries and custom JS -->
	<script src="<?=base_url()?>assets/js/jquery.min.js"></script>
	<script src="<?=base_url()?>assets/js/bootstrap.bundle.min.js"></script>
	<script src="<?=base_url()?>assets/js/sweetalert.js"></script>
	<script src="<?=base_url()?>assets/js/angularjs.js"></script>
	<script src="<?=base_url()?>assets/js/routes.js"></script>
	
	<!-- MY APP -->
	<script src="<?=base_url()?>App/routeLogin.js"></script>

	<!-- APP CONTROLLER -->
	<script src="<?=base_url()?>App/ItemController.js"></script>

</head>
<body ng-app="main-App">
		<div class="container login">
			<ng-view></ng-view>
		</div>
</body>
</html>
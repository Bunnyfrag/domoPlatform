<?php setcookie( 'last_page', $_SERVER['REQUEST_URI'], ( time() + 3600), '/' ); ?>

<!DOCTYPE html>
<html lang="fr">
	<head>
		<meta charset="UTF-8" />
		<title>GestHome</title>

		<link rel="stylesheet" type="text/css" media="all" href="css/font-awesome.css" />
		<link rel="stylesheet" type="text/css" media="all" href="css/datatable.css" />
		<link rel="stylesheet" type="text/css" media="all" href="css/style.css" />
		<link rel="stylesheet" type="text/css" media="all" href="css/sweetalert.css" />

		<script type="text/javascript" src="js/jquery.min.js"></script>
		<script type="text/javascript" src="js/jquery.form.min.js"></script>
		<script type="text/javascript" src="js/jquery.datatable.min.js"></script>
		<script type="text/javascript" src="https://unpkg.com/sweetalert2"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/core-js/2.4.1/core.js"></script>
	</head>

	<body>

		<div id="header">
			Gestion Maison
		</div>

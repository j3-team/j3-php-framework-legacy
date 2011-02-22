<html>
<head>
	<?php echo APP_BASE; ?>
	<title><?php echo APP_TITLE; ?> - Probando > Perro</title>
</head>
<body>
	
	<?php echo $this->variable ?>
	<?php echo $_SESSION["nombres"]." ".$_SESSION["apellidos"];?>
</body>
</html>

<?php require_once('../core.php'); ?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
	<title>Guest - Portal</title>


	<!-- JQuery -->
	<script src="assets/plugins/jquery/jquery-1.12.3.min.js"></script>

	<!-- Bootstrap core CSS -->
	<link href="assets/plugins/bootstrap-3.3.7-dist/css/bootstrap.min.css" rel="stylesheet">
	<script src="assets/plugins/bootstrap-3.3.7-dist/js/bootstrap.min.js"></script>

	<!-- Font Awsome -->
	<link href="assets/plugins/font-awesome-4.7.0/css/font-awesome.min.css" rel="stylesheet">
	<link href="assets/plugins/awesome-bootstrap-checkbox/awesome-bootstrap-checkbox.css" rel="stylesheet">

	<link href="css/theme.css" rel="stylesheet">
	<script src="js/theme.js"></script>

	<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
	<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
	<!--[if lt IE 9]>
		<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
		<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	<![endif]-->
</head>
<body>

	<div class="container">
		<?php
			// Include script
			$default = "start";
			$directory	= 'pages';		// mappa filene dine ligger i.
			$extension	= "php";		// filendingen på filene dine.

			if(isset($_GET['page'])) {
				$page = $_GET['page'];

				// for å hindre at det inkluderes fra uønskede plasser (stopper hackerne)
				if (preg_match('/(http:\/\/|^\/|\.+?\/)/', $page)) echo "Error"; 

				elseif (!empty($page))											// sjekke at variabelen ikke er tom.
				{
					if (file_exists("$directory/$page.$extension")) {
						include("$directory/$page.$extension");
					}				
					else														// hvis ikke,
						echo "<h2>Error 404</h2>\n<p>". _('Can not find the page you are looking for') ."!</p>\n";	// skriv en feilmelding.
				}
			}
			else {

				include("$directory/$default.$extension");						// inkluder fila som definert som $default.
			}													// eller,
		?>
	</div>



	<footer>
		Wireless network delivered by <a href="<?php echo COMPANY_URL; ?>"><?php echo COMPANY_NAME; ?></a><br />
		<!-- <a href="?page=add_device">Add device by MAC</a> -->
	</footer>
</body>
</html>
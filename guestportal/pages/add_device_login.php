<script>
	$(document).ready(function() {
		$( "#btn-login-form" ).click(function() {
			console.log('Btn pushed');
			$( "#login-form" ).slideToggle(300);
		});

		$( "#btn-skole-form" ).click(function() {
			$( "#skole-form" ).slideToggle(300);
		});
	});
</script>

<style type="text/css">
	.login-form {
		padding:20px;
		margin: 10px 0px;
		background-color: #fdfdfd;
		border:1px solid #e8e8e8;
	}
</style>

<?php
	
	if (isset($_SESSION['auth_user']) && !empty($_SESSION['auth_user'])) {
		header('Location: ?page=add_device_device_info');
		exit();
	}


	$_SESSION['_csrf'] = hash('sha256', rand(1111,99999).time().$config['blowfish_secret']);

	if (isset($_GET['username'])) $username = $_GET['username'];
	else $username = '';



	$login_style = 'display:none;';
	$skole_style = 'display:none;';

	if (isset($_GET['domain'])) {
		if ($_GET['domain'] == 'login') $login_style = '';
		if ($_GET['domain'] == 'skole') $skole_style = '';
	}
?>

<div style="margin:0 auto; max-width:500px;">

	<h1>Legg til enhet</h1>

	<br /><br />

	<p>
		Velg påloggingsmetode:
	</p>

	<br />

	<?php if (isset($_GET['msg'])): ?>
		<?php if ($_GET['msg'] == 1): ?>
			<div class="alert alert-danger">
				<i class="fa fa-warning"></i> <b>Sesjon utgått</b> &nbsp; Vennligst prøv på nytt...
			</div>

		<?php elseif ($_GET['msg'] == 2): ?>
			<div class="alert alert-danger">
				<i class="fa fa-warning"></i> <b>Feil brukernavn og/eller passord</b> &nbsp; Vennligst prøv på nytt...
			</div>

		<?php endif ?>

		<br />
	<?php endif ?>


	<a class="btn btn-default btn-lg btn-block" href="#" id="btn-login-form">
		Innlogging (LOGIN-domenet) &nbsp; <i class="fa fa-chevron-right"></i>
	</a>

	<div class="login-form" id="login-form" style="<?php echo $login_style; ?>">
		<form action="?page=add_device_device_info&action=auth&domain=login" method="POST">
			<div class="form-group">
				<label for="username">Brukernavn</label>
				<input type="text" class="form-control input-lg" name="username" id="username" placeholder="Username" value="<?php echo $username; ?>">
			</div>

			<div class="form-group">
				<label for="password">Passord</label>
				<input type="password" class="form-control input-lg" name="password" id="password" placeholder="Passord">
			</div>

			<input type="hidden" name="_csrf" value="<?php echo $_SESSION['_csrf']; ?>">

			<div class="form-btns">
				<div class="login-btn">
					<button class="btn btn-primary btn-block btn-lg">
						Logg inn
					</button>
				</div>
			</div>
		</form>
	</div>




	<br />




	<a class="btn btn-default btn-lg btn-block" href="#" id="btn-skole-form">
		Innlogging (SKOLE-domenet) &nbsp; <i class="fa fa-chevron-right"></i>
	</a>


	<div class="login-form" id="skole-form" style="<?php echo $skole_style; ?>">
		<form action="?page=add_device_device_info&action=auth&domain=skole" method="POST">
			<div class="form-group">
				<label for="username">Brukernavn</label>
				<input type="text" class="form-control input-lg" name="username" id="username" placeholder="Username" value="<?php echo $username; ?>">
			</div>

			<div class="form-group">
				<label for="password">Passord</label>
				<input type="password" class="form-control input-lg" name="password" id="password" placeholder="Passord">
			</div>

			<input type="hidden" name="_csrf" value="<?php echo $_SESSION['_csrf']; ?>">

			<div class="form-btns">
				<div class="login-btn">
					<button class="btn btn-primary btn-block btn-lg">
						Logg inn
					</button>
				</div>
			</div>
		</form>
	</div>
</div>
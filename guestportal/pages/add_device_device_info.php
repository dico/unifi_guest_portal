<?php
	
	if ($_GET['action'] == 'auth') {


		$domain = $_GET['domain'];
		$username = $_POST['username'];
		$password = $_POST['password'];
		$_csrf = $_POST['_csrf'];


		if ($_csrf != $_SESSION['_csrf']) {
			header('Location: ?page=add_device_login&msg=1');
			exit();
		}

		if ($domain == 'login') {
			//echo "<h2>Login</h2>";
			$result = $objAuth->intern_auth_user_only($username, $password);
			/*echo '<pre>';
				print_r($result);
			echo '</pre>';*/
		}

		elseif ($domain == 'skole') {
			//echo "<h2>Skole</h2>";
			$result = $objAuth->school_auth_user_only($username, $password);
			/*echo '<pre>';
				print_r($result);
			echo '</pre>';*/
		}


		if ($result['status'] == 'success') {
			$_SESSION['auth_user'] = mb_strtoupper($domain).'\\'.$username;
		}

		if ($result['status'] == 'error') {
			header('Location: ?page=add_device_login&msg=2&username='.$username.'&domain='.$domain);
			exit();
		}

	}



	elseif (!isset($_SESSION['auth_user']) || empty($_SESSION['auth_user'])) {

		unset($_SESSION['auth_user']);

		header('Location: ?page=add_device');
		exit();

		echo '<pre>';
			print_r('Not authed');
		echo '</pre>';
	}

?>



<div style="margin:0 auto; max-width:500px;">

	<h1>Legg til enhet</h1>

	<div style="margin-bottom:20px;">
		Pålogged som <?php echo $_SESSION['auth_user']; ?>
	</div>


	<?php if (isset($_GET['msg'])): ?>
		<?php if ($_GET['msg'] == 1): ?>
			<div class="alert alert-success">
				<i class="fa fa-check"></i> <b>OK: Enhet lagt til</b> &nbsp; Du kan nå lukke siden eller legge til flere om ønskelig.
			</div>

		<?php elseif ($_GET['msg'] == 2): ?>
			<div class="alert alert-danger">
				<i class="fa fa-warning"></i> <b>Uffda, en feil oppstod!</b> &nbsp; Vennligst prøv på nytt... (Controller-status: <?php echo $_GET['controller_status']; ?>)
			</div>

		<?php endif ?>

		<br />
	<?php endif ?>


	<div style="color:red; margin:10px 0px;">
		<b>MERK:</b> Utstyret må være tilkoblet gjestenettet før du fullfører dette skjemaet!
	</div>

	<?php
		$sites = array(
			'k1e9zmf2' => array('1624RAD', 'Rissa kommune, Rådhuset', ''),
			'default'  => array('1624ASL', 'Rissa kommune, Åsly skole', ''),
			'isf6m2u8' => array('3008SKG', 'Rissa kommune, Skogly barnehage', ''),
			'zsz7rsa7' => array('1624FAG', 'Rissa kommune, Fagerenget skole', ''),
			'ls4j645v' => array('1633RHT', 'Rissa kommune, Rissa helsetun', ''),
			'gs0ptwcb' => array('1627SEN', 'Bjugn kommune, sentrum', 'Sentrum.. De har veldig mange bygg med fiber til Rådhus'),
			'07cg3g8h' => array('1718RAD', 'Leksvik kommune, sentrum', 'Alle lokasjoner i Leksvik sentrum og omegn'),
			'7m082kh4' => array('1718VAN', 'Leksvik kommune, Vanvikan', 'Alle lokasjoner i Vavikan'),
			'77rby6yt' => array('1633RAD', 'Osen kommune, Rådhuset', ''),
		);
	?>

	<form action="<?php echo URL; ?>core/handlers/Add_device.php?action=add_device" method="POST">

		<?php
			if (isset($_SESSION['site_id'])) $set_site_id = $_SESSION['site_id'];
			else $set_site_id = '';

			if (isset($_SESSION['mac'])) $set_mac = $_SESSION['mac'];
			else $set_mac = '';

			if (isset($_SESSION['comment'])) $set_comment = $_SESSION['comment'];
			else $set_comment = '';
		?>

		<div class="form-group">
			<label for="selSite">Velg lokasjon</label>
			<select class="form-control input-lg" name="selSite" id="selSite" required="required">
				<option>-- Velg lokasjon</option>

				<?php if (count($sites) > 0): ?>
					<?php foreach ($sites as $siteID => $data): ?>

						<?php if ($set_site_id == $siteID): ?>
							<option value="<?php echo $siteID; ?>" selected="selected">
								<?php echo $data[1]; ?> (<?php echo $data[0]; ?>)
							</option>
						<?php else: ?>
							<option value="<?php echo $siteID; ?>">
								<?php echo $data[1]; ?> (<?php echo $data[0]; ?>)
							</option>
						<?php endif ?>

					<?php endforeach ?>
				<?php endif ?>

				<!-- <option value="07cg3g8h">Leksvik (1718RAD)</option>
				<option value="default">Åsly skole (1624ASL)</option>
				<option value="isf6m2u8">3008SKG</option>
				<option value="k1e9zmf2">1624RAD</option>
				<option value="77rby6yt">1633RAD</option>
				<option value="zsz7rsa7">1624FAG</option>
				<option value="gs0ptwcb">1627SEN</option>
				<option value="7m082kh4">1718VAN</option> -->
			</select>
		</div>

		<div class="form-group">
			<label for="inputMac">MAC adresse</label>
			<input type="text" class="form-control input-lg" name="inputMac" id="inputMac" placeholder="Eksempel: 00:20:18:61:f1:8a" value="<?php echo $set_mac; ?>" required>
		</div>

		<div class="form-group">
			<label for="inputComment">Kommentar / Navn på utstyr</label>
			<input type="text" class="form-control input-lg" name="inputComment" id="inputComment" placeholder="F.eks.: Apple TV, Blåheia møterom" value="<?php echo $set_comment; ?>" required>
		</div>


		<button type="submit" class="btn btn-success btn-lg btn-block">Legg til enhet</button>


	</form>

</div>
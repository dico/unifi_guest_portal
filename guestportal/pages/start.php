<div class="page-container">
	<div class="page-container-inner">



		<div class="row">
			<div class="col-sm-6 col-md-6">
				<?php
					$objAuth = new GuestPortal\Auth\Auth(); 
					$get_login_attempts = $objAuth->get_login_attempts($_SESSION['controller']['id']);
					$login_attempts_remaining = ($config['max_allowed_attempts'] - $get_login_attempts);


					$_SESSION['_csrf'] = hash('sha256', rand(1111,99999).time().$config['blowfish_secret']);
				?>

				<div class="logo">
					<a href="index.php">
						<img src="assets/images/mobile_wifi.png"><br />
						<h1><?php echo _('Guest network'); ?></h1>
					</a>
				</div>

				<?php
					if (!isset($_SESSION['controller']['id']) || empty($_SESSION['controller']['id'])) {
						header('Location: '.URL_GUESTPORTAL.'?page=no_id');
						exit();
					}
				?>
			</div>


			<div class="col-sm-6 col-md-6">
				<?php if ($get_login_attempts < $config['max_allowed_attempts']): ?>
					
				

					<?php if (isset($_GET['msg']) && $_GET['msg'] == 1): ?>


						<!--
							Connection load screen
						-->
						<script type="text/javascript">
							function countdown() {
								var i = document.getElementById('counter');
								if (parseInt(i.innerHTML)<=0) {
									location.href = '<?php echo $_SESSION['controller']['refURL']; ?>';
								}

								if (parseInt(i.innerHTML)>0) {
									i.innerHTML = parseInt(i.innerHTML)-1;
								}
							}

							setInterval(function(){ countdown(); },1000);
						</script>
						
						<div style="text-align:center; margin-top:15%;">
							<div>
								<img src="assets/images/loaders/380.gif">
							</div>

							<div style="font-size:16px; margin-top:25px;">
								<?php echo _('Connection to Internet'); ?>,<br /> <?php echo _('Please wait'); ?>...
							</div>


							<div style="margin-top:35px;">
								<?php echo _('you will be connected in'); ?>
							</div>
							<div style="font-size:35px;">
								<span id="counter">10</span>
							</div>
						</div>

					<?php else: ?>

						<div class="login-container">

							<?php if (isset($_GET['msg']) && $_GET['msg'] == 8): ?>
								<div class="alert alert-warning">
									<i class="fa fa-warning"></i> <b><?php echo _('Session expired'); ?>!</b><br />
									<?php echo _('An error occured on your login session'); ?> :-(<br />
									<?php echo _('Please try again'); ?>...
								</div>
							<?php elseif (isset($_GET['msg']) && $_GET['msg'] == 9): ?>
								<div class="alert alert-danger">
									<i class="fa fa-warning"></i> <b><?php echo _('Wrong username and/or password'); ?>!</b><br /><?php echo _('Please try again'); ?>...
									<br /><br />
									<span style="font-size:10px;">
										<?php echo _('You have'); ?> <?php echo $login_attempts_remaining; ?> <?php echo _('tries left'); ?>.
									</span>
								</div>
							<?php endif ?>

							



							<!--
								Select login type
							-->
							<div class="login-selection" id="login-selection">

								<a class="choice-btn" href="javascript:login_form('mobile');">
									<div class="row">
										<div class="col-xs-3 col-sm-3 col-md-3" style="text-align:center; font-size:60px;">
											<i class="fa fa-fw fa-mobile"></i>
										</div>
										<div class="col-xs-9 col-sm-9 col-md-9">
											<div class="title"><?php echo _('Visitor'); ?></div>
											<div class="desc"><?php echo _('Log in with SMS'); ?></div>
										</div>
									</div>
								</a>

								<a class="choice-btn" href="javascript:login_form('room');">
									<div class="row">
										<div class="col-xs-3 col-sm-3 col-md-3" style="text-align:center; font-size:60px;">
											<i class="fa fa-fw fa-building-o"></i>
										</div>
										<div class="col-xs-9 col-sm-9 col-md-9">
											<div class="title"><?php echo _('Room'); ?></div>
											<div class="desc"><?php echo _('Room guest access'); ?></div>
										</div>
									</div>
								</a>

							</div>





							<!--
								SMS login
							-->
							<div class="login-form-container" id="login-mobile" style="display:none;">

								<div class="desc">
									<i class="fa fa-info-circle"></i> <?php echo _('All fields are required. After submitting the form, a one-time SMS code will be sent to your phone.'); ?>
								</div>

								<form action="app/controllers/Auth/Auth.php?action=mobile_auth_send_code" method="POST">
									<div class="form-group">
										<label for="firstname"><?php echo _('Firstname'); ?></label>
										<input type="text" class="form-control input-lg" name="firstname" id="firstname" placeholder="<?php echo _('Enter your firstname'); ?>">
									</div>

									<div class="form-group">
										<label for="lastname"><?php echo _('Lastname'); ?></label>
										<input type="text" class="form-control input-lg" name="lastname" id="lastname" placeholder="<?php echo _('Enter your lastname'); ?>">
									</div>

									<div class="form-group">
										<label for="mobile"><?php echo _('Mobile'); ?></label>
										<input type="tel" class="form-control input-lg" name="mobile" id="mobile" placeholder="900 00 000">
									</div>

									<input type="hidden" name="_csrf" value="<?php echo $_SESSION['_csrf']; ?>">

									<div class="checkbox checkbox-success">
										<input type="checkbox" id="terms_accept" required>
										<label for="terms_accept" style="vertical-align: top;">
											<?php echo _('Accept terms'); ?>
										</label>

										<div style="display:inline-block; margin-left:10px;">
											<a href="#" class="terms-btn"><?php echo _('Show terms'); ?></a>
										</div>
									</div>

									<div class="terms">
										<?php echo _('Your connection will last for 24 hours'); ?><br />
										<?php echo _('Internet connection is delivered by'); ?> <?php echo COMPANY_NAME; ?>. 
										<?php echo _('Guest access will be logged according to what is permitted in the law.'); ?>
									</div>

									<div class="form-btns">
										<div class="login-btn">
											<button class="btn btn-primary btn-block btn-lg">
												<?php echo _('Send me a SMS code'); ?>
											</button>
										</div>

										<div class="back-btn">
											<a href="javascript:login_form('select');">
												<i class="fa fa-arrow-left"></i> <?php echo _('Back'); ?>
											</a>
										</div>
									</div>
								</form>
							</div>




							<!--
								Room login
							-->
							<div class="login-form-container" id="login-room" style="display:none;">

								<div class="desc">
									<i class="fa fa-info-circle"></i> <?php echo _('Login with your lastname and pin-code'); ?>. <?php echo _('You can request a pin-code at the front desk.'); ?>
								</div>

								<form action="app/controllers/Auth/Auth.php?action=pasient_auth_user" method="POST">
									<div class="form-group">
										<label for="lastname"><?php echo _('Lastname'); ?></label>
										<input type="text" class="form-control input-lg" name="lastname" id="lastname" placeholder="<?php echo _('Enter your lastname'); ?>" required>
									</div>

									<div class="form-group">
										<label for="pin"><?php echo _('Pin-code'); ?></label>
										<input type="tel" class="form-control input-lg" name="pin" id="pin" placeholder="<?php echo _('Enter received pin-code'); ?>" required>
									</div>

									<input type="hidden" name="_csrf" value="<?php echo $_SESSION['_csrf']; ?>">

									<div class="checkbox checkbox-success">
										<input type="checkbox" id="terms_accept" required>
										<label for="terms_accept" style="vertical-align: top;">
											<?php echo _('Accept terms'); ?>
										</label>

										<div style="display:inline-block; margin-left:10px;">
											<a href="#" class="terms-btn"><?php echo _('Show terms'); ?></a>
										</div>
									</div>

									<div class="terms">
										<?php echo _('Duration of this connection will last 1 year.'); ?>.
										<?php echo _('Internet connection is delivered by'); ?> <?php echo COMPANY_NAME; ?>. 
										<?php echo _('Guest access will be logged according to what is permitted in the law.'); ?>

									</div>


									<div class="form-btns">
										<div class="login-btn">
											<button class="btn btn-primary btn-block btn-lg">
												<?php echo _('Connect'); ?>
											</button>
										</div>

										<div class="back-btn">
											<a href="javascript:login_form('select');">
												<i class="fa fa-arrow-left"></i> <?php echo _('Back'); ?>
											</a>
										</div>
									</div>
								</form>
							</div>

						<?php endif; ?>

					</div>



				<?php else: ?>

					<div style="text-align:center; color:#e29612;">
						
						<div style="font-size:100px;">
							<i class="fa fa-info-circle"></i>
						</div>

						<div style="margin-top:30px; font-size:22px;">
							 <?php echo _('An error has occured'); ?>!
						</div>

						<div style="margin-top:15px; font-size:14px;">
							<?php echo _('We have logged to many requests from your device at this moment'); ?>.
							<br /><br />
							Vennligst vent noen minutter før du forsøker igjen.
							<?php echo _('Please wait a few minutes before you try again'); ?>.
						</div>

					</div>

				<?php endif ?>
			</div> <!-- end column -->
		</div> <!-- end row -->


	</div> <!-- end container -->
</div> <!-- end container -->
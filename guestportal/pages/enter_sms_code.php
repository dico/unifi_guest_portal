<div class="page-container">
	<div class="page-container-inner">



		<div class="row">

			<div class="col-sm-5 col-md-5">
				<div class="logo">
					<a href="index.php">
						<img src="assets/images/mobile_wifi.png"><br />
						<h1><?php echo _('Guest network'); ?></h1>
					</a>
				</div>
			</div>



			<div class="col-sm-5 col-md-5" style="padding-top:65px;">

				<?php if (isset($_GET['msg']) && $_GET['msg'] == 1): ?>
					<div class="alert alert-danger">
						<i class="fa fa-warning"></i> <b><?php echo _('Wrong or expired code'); ?>.</b> &nbsp; <?php echo _('Please try again'); ?>.
					</div>
				<?php elseif (isset($_GET['msg']) && $_GET['msg'] == 9): ?>
					<div class="alert alert-danger">
						<i class="fa fa-warning"></i> <b><?php echo _('An error occured'); ?>!</b> &nbsp; <?php echo _('Please try again'); ?>.
					</div>
				<?php endif ?>

				<form action="app/controllers/Auth/Auth.php?action=confirm_sms_code&authID=<?php echo $_GET['authID']; ?>" method="POST">
					<div class="form-group">
						<label for="code"><?php echo _('Enter SMS code'); ?></label>
						<input type="tel" class="form-control input-lg" name="code" id="code" placeholder="xxxx" autofocus>
					</div>



					<div class="form-btns">
					
						<div class="login-btn">
							<button class="btn btn-success btn-block btn-lg">
								<?php echo _('Give me Internet-access'); ?>
							</button>
						</div>

						<br />

						<div class="back-btn">

							<a style="display:block; padding:6px;" href="app/controllers/Auth/Auth.php?action=mobile_auth_send_code&authID=<?php echo $_GET['authID']; ?>">
								<i class="fa fa-fw fa-mobile"></i> <?php echo _('Send me a new code'); ?>
							</a>

							<a style="display:block; padding:6px;" href="index.php">
								<i class="fa fa-fw fa-arrow-left"></i> <?php echo _('Back'); ?>
							</a>
						</div>
					</div>
				</form>
			</div> <!-- end column -->

		</div> <!-- end row -->

	</div>
</div>
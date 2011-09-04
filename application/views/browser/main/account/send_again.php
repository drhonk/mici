			<div class="container_alpha slogan">
				<h1><strong>ESSpree</strong>&nbsp; Account</h1>
			</div>
			<div class="container_gamma breadcrumbs">
				<p>
					<a href="<?PHP echo $base_url; ?>">Home</a>
					<span><?PHP echo PAGE_TITLE_SEP; ?></span>
					<a href="<?PHP echo $base_url; ?>account">Account</a>
					<span><?PHP echo PAGE_TITLE_SEP; ?></span>
					<a href="<?PHP echo $base_url; ?>account/send_again">Resend Email Confirmation</a>
				</p>
			</div>
			<div class="container_omega">
				<div class="gs_2">&nbsp;</div>
				<div class="gs_8">
					<h2>Resend Email Confirmation</h2>
					<p style="margin-bottom: 20px;">Before you can login, we will need you to confirm you email address by clicking the confirmation link that was sent to you.  If you did not receive this email, please let us resend an email confirmation to you.</p>
					<?PHP
		           	$attributes = array(
		               'name' => 'resend_form',
		               'id' => 'resend_form',
		               'onsubmit' => 'return validate_resend();'
		           	);
		           	echo form_open(current_url(), $attributes);
		           	?>
						<?PHP foreach($errors as $error): ?>
						<p class="error"><?PHP echo $error; ?></p>
						<?PHP endforeach; ?>
						<table width="100%">
							<tr>
								<td width="130"><label for="email">My Email Address:</label></td>
								<td>
									<input type="text" name="email" id="email" value="<?php echo $userdata['email']; ?>" title="Email" autocomplete="off"/>
									<?PHP echo form_error('email', '<p class="error">', '</p>'); ?>
								</td>
							</tr>
						<tr>
							<td colspan="2">
								<?PHP echo $recaptcha_html; ?>
								<p style="float: right;"><button type="submit" class="black superbutton">Resend Confirmation</button></p>
							</td>
						</tr>
					</table>
					<?PHP echo form_close(); ?>
				</div>
				<div class="gs_2">&nbsp;</div>
			</div>
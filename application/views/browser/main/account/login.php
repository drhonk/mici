			<div class="container_alpha slogan">
				<h1>ESSpree <strong>Login</strong></h1>
			</div>
			<div class="container_gamma breadcrumbs">
				<p>
					<a href="<?PHP echo $base_url; ?>">Home</a>
					<span><?PHP echo PAGE_TITLE_SEP; ?></span>
					<a href="<?PHP echo $base_url; ?>login">Login</a>
				</p>
			</div>
			<div class="container_omega">
				<div class="gs_6">
					<h2>Login with your ESSpree Account</h2>
					<br />
					<?PHP
		            $attributes = array(
		                'name' => 'login_form',
		                'id' => 'login_form',
		 				'autocomplete' => 'off',
		                'onsubmit' => 'return validate_login();'
		            );
		            echo form_open(current_url(), $attributes);
		            ?>
						<?PHP foreach($errors as $error): ?>
						<p class="error"><?PHP echo $error; ?></p>
						<?PHP endforeach; ?>
						<?PHP if($message) echo '<p class="error">'.$message.'</p>'; ?>
						<table width="100%">
							<tr>
								<td width="75"><label for="login">Username:</label></td>
								<td>
									<input type="text" name="login" id="login" title="User Name" value="<?php echo set_value('login'); ?>" autofocus="autofocus" autocomplete="off" maxlength="<?PHP echo $this->config->item('username_max_length'); ?>" />
									<?PHP echo form_error('login', '<p class="error">', '</p>'); ?>
								</td>
							</tr>
							<tr>
								<td><label for="password">Password:</label></td>
								<td>
									<input type="password" name="password" id="password" value="<?php echo set_value('password'); ?>" title="Password" />
									<?PHP echo form_error('password', '<p class="error">', '</p>'); ?>
								</td>
							</tr>
							<tr>
								<td>&nbsp;</td>
								<td>
									<p style="float: right;"><label for="remember"><input type="checkbox" name="remember" id="remember" value="1" style="width: 20px; vertical-align: text-top;" <?PHP echo set_checkbox('remember', '1'); ?> /> Remember Me</label></p>
								</td>
							</tr>
							<tr>
								<td colspan="2">
									<?PHP echo $recaptcha_html; ?>
									<p style="float: right;"><a href="<?PHP echo $base_url; ?>account/forgot_password" class="superbutton white">Forgot Password</a><button type="submit" class="black superbutton">Login</button></p>
								</td>
							</tr>
						</table>
					</form>
				</div>
				<div class="gs_6 omega">
					<img src="<?PHP echo $media_url; ?>browser/images/account/or.png" />
					<?PHP echo $this->engage->embed('login'); ?>
				</div>
			</div>
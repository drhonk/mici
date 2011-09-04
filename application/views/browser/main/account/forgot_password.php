			<div class="container_alpha slogan">
				<h1><strong>ESSpree:</strong>&nbsp; Forgot Password</h1>
			</div>
			<div class="container_gamma breadcrumbs">
				<p>
					<a href="<?PHP echo $base_url; ?>">Home</a>
					<span><?PHP echo PAGE_TITLE_SEP; ?></span>
					<a href="<?PHP echo $base_url; ?>account">Account</a>
					<span><?PHP echo PAGE_TITLE_SEP; ?></span>
					<a href="<?PHP echo $base_url; ?>account/forgot_password">Forgot Password</a>
				</p>
			</div>
			<div class="container_omega">
				<div class="gs_2">&nbsp;</div>
				<div class="gs_8">
					<h2>Recover your ESSpree Account</h2>
					<br />
					<?PHP
		            $attributes = array(
		                'name' => 'forgot_form',
		                'id' => 'forgot_form',
		 				'autocomplete' => 'off',
		                'onsubmit' => 'return validate_forgot();'
		            );
		            echo form_open(current_url(), $attributes);
		            ?>
						<table width="100%">
							<tr>
								<td width="100"><label for="login">Email Address:</label></td>
								<td><input type="text" name="login" id="login" title="User Name" value="<?php echo set_value('login'); ?>" autofocus="autofocus" autocomplete="off" /></td>
							</tr>
							<tr>
								<td colspan="2">
									<div id="form_errors" style="color: #968080; border: 1px solid #968080; background-color: #ffecec; padding: 6px 0px; text-align: center; margin-bottom: 8px; display: <?PHP $err = form_error('login').form_error('password'); echo (!empty($err) || !empty($errors)) ? 'block':'none'; ?>">
										<?PHP echo validation_errors('<div>', '</div>'); ?>
										<?PHP
											if( !empty($errors['login']))
											{
												echo '<div>'.$errors['login'].'</div>';
											}
											if( !empty($errors['password']))
											{
												echo '<div>'.$errors['password'].'</div>';
											}
										?>
									</div>
									<p style="float: right;"><a href="<?PHP echo current_url(); ?>" class="black superbutton" onclick="jQuery('#forgot_form').submit(); return false;">Get a New Password</a></p>
								</td>
							</tr>
						</table>
					</form>
				</div>
				<div class="gs_2 omega">&nbsp;</div>
			</div>
			<div class="container_alpha slogan">
				<h1><strong>Account:</strong>&nbsp; Change Password</h1>
			</div>
			<div class="container_gamma breadcrumbs">
				<p>
					<a href="<?PHP echo $base_url; ?>">Home</a>
					<span><?PHP echo PAGE_TITLE_SEP; ?></span>
					<a href="<?PHP echo $base_url; ?>account">Account</a>
					<span><?PHP echo PAGE_TITLE_SEP; ?></span>
					<a href="<?PHP echo $base_url; ?>account/change_password">Change Password</a>
				</p>
			</div>
			<div class="container_omega">
<?PHP echo $account_menu; ?>
				<div class="gs_9 omega"> 
					<h2>Change your ESSpree Password</h2>
					<br />
					<?PHP
		            $attributes = array(
		                'name' => 'password_form',
		                'id' => 'password_form',
		 				'autocomplete' => 'off',
		                //'onsubmit' => 'return validate_password();'
		            );
		            echo form_open(current_url(), $attributes);
		            ?>
						<table width="100%">
							<tr>
								<td width="125"><label for="old_password">Old Password:</label></td>
								<td>
									<input type="password" name="old_password" id="old_password" title="Old Password" value="<?php echo set_value('old_password'); ?>" autofocus="autofocus" />
									<?PHP echo form_error('old_password', '<p class="error">', '</p>'); ?>
								</td>
							</tr>
							<tr>
								<td><label for="new_password">New Password:</label></td>
								<td>
									<input type="password" name="new_password" id="new_password" title="New Password" value="<?php echo set_value('new_password'); ?>" />
									<?PHP echo form_error('new_password', '<p class="error">', '</p>'); ?>
								</td>
							</tr>
							<tr>
								<td><label for="confirm_new_password">Confirm Password:</label></td>
								<td>
									<input type="password" name="confirm_new_password" id="confirm_new_password" title="Confirm Password" value="<?php echo set_value('confirm_new_password'); ?>" />
									<?PHP echo form_error('confirm_new_password', '<p class="error">', '</p>'); ?>
								</td>
							</tr>
							<tr>
								<td colspan="2">
									<p style="float: right;"><button type="submit" class="black superbutton">Change Password</button></p>
								</td>
							</tr>
						</table>
					</form>
				</div>
			</div>
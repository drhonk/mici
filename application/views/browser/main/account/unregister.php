			<div class="container_alpha slogan">
				<h1><strong>Account:</strong>&nbsp; Close Account</h1>
			</div>
			<div class="container_gamma breadcrumbs">
				<p>
					<a href="<?PHP echo $base_url; ?>">Home</a>
					<span><?PHP echo PAGE_TITLE_SEP; ?></span>
					<a href="<?PHP echo $base_url; ?>account">Account</a>
					<span><?PHP echo PAGE_TITLE_SEP; ?></span>
					<a href="<?PHP echo $base_url; ?>account/unregister">Close Account</a>
				</p>
			</div>
			<div class="container_omega">
<?PHP echo $account_menu; ?>
				<div class="gs_9 omega"> 
					<h2>Close your ESSpree Account</h2>
					<br />
					<?PHP
		            $attributes = array(
		                'name' => 'close_account_form',
		                'id' => 'close_account_form',
		 				'autocomplete' => 'off',
		                'onsubmit' => 'return validate_login();'
		            );
		            echo form_open(current_url(), $attributes);
		            ?>
						<?PHP foreach($errors as $error): ?>
						<p class="error"><?PHP echo $error; ?></p>
						<?PHP endforeach; ?>
						<table width="100%">
							<tr>
								<td width="125"><label for="password">Current Password:</label></td>
								<td>
									<input type="text" name="password" id="password" title="Current Password" value="" autofocus="autofocus" autocomplete="off" maxlength="100" />
									<?PHP echo form_error('password', '<p class="error">', '</p>'); ?>
								</td>
							</tr>
							<tr>
								<td colspan="2">
									<p style="float: right;"><button type="submit" class="red superbutton" onclick="return confirm('Are you sure you want to Close Your ESSpree Account? This cannot be undone!');">Close Account</button></p>
								</td>
							</tr>
						</table>
					</form>
				</div>
			</div>
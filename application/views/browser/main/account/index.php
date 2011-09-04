			<div class="container_alpha slogan">
				<h1><strong>ESSpree:</strong>&nbsp; Account</h1>
			</div>
			<div class="container_gamma breadcrumbs">
				<p>
					<a href="<?PHP echo $base_url; ?>">Home</a>
					<span><?PHP echo PAGE_TITLE_SEP; ?></span>
					<a href="<?PHP echo $base_url; ?>account">Account</a>
				</p>
			</div>
			<div class="container_omega">
<?PHP echo $account_menu; ?>
				<div class="gs_9 omega"> 
					<h2>Your ESSpree Account Information</h2>
					<br />
					<?PHP
		            $attributes = array(
		                'name' => 'profile_form',
		                'id' => 'profile_form',
		 				'autocomplete' => 'off',
		                'onsubmit' => 'return validate_profile();'
		            );
		            echo form_open(current_url(), $attributes);
		            ?>
						<?PHP foreach($errors as $error): ?>
						<p class="error"><?PHP echo $error; ?></p>
						<?PHP endforeach; ?>
						<table width="100%">
							<tr>
								<td width="100"><label for="display_name">Display Name:</label></td>
								<td>
									<input type="text" name="display_name" id="display_name" title="Display Name" value="<?php echo $userdata['name']; ?>" autocomplete="off" maxlength="100" />
									<?PHP echo form_error('display_name', '<p class="error">', '</p>'); ?>
								</td>
							</tr>
							<tr>
								<td colspan="2">
									<?PHP echo $recaptcha_html; ?>
									<p style="float: right;"><button type="submit" class="black superbutton">Update</button></p>
								</td>
							</tr>
						</table>
					</form>
				</div>
			</div>
			<div class="container_alpha slogan">
				<h1>ESSpree <strong>Register</strong></h1>
			</div>
			<div class="container_gamma breadcrumbs">
				<p>
					<a href="<?PHP echo $base_url; ?>">Home</a>
					<span><?PHP echo PAGE_TITLE_SEP; ?></span>
					<a href="<?PHP echo $base_url; ?>account">Account</a>
					<span><?PHP echo PAGE_TITLE_SEP; ?></span>
					<a href="<?PHP echo $base_url; ?>account/register">Register</a>
				</p>
			</div>
		<?PHP
          	$attributes = array(
              'name' => 'register_form',
              'id' => 'register_form',
              'onsubmit' => 'return validate_register();'
          	);
          	echo form_open(current_url(), $attributes);
          	?>
			<div class="container_omega" id="step_1">
				<div class="gs_6">					
					<h2>Create your ESSpree Account</h2>
						<?PHP foreach($errors as $error): ?>
						<p class="error"><?PHP echo $error; ?></p>
						<?PHP endforeach; ?>
						<?PHP if($message) echo '<p class="error">'.$message.'</p>'; ?>
						<input type="hidden" name="role" id="role" value="<?php echo set_value('role'); ?>" />
						<input type="hidden" name="engage_identifier" id="engage_identifier" value="<?php echo set_value('engage_identifier', $engage_identifier); ?>" />
						
						<table width="100%">
							<tr>
								<td width="75"><label for="username">User Name:</label></td>
								<td>
									<input type="text" name="username" id="username" value="<?php echo set_value('username', $engage_username); ?>" title="User Name"<?PHP if(empty($engage_username)) echo ' autofocus="autofocus"'; ?> autocomplete="off" />
									<?PHP echo form_error('username', '<p class="error">', '</p>'); ?>
								</td>
							</tr>
							<tr>
								<td><label for="fname">First Name:</label></td>
								<td>
									<input type="text" name="fname" id="fname" value="<?php echo set_value('fname', $engage_fname); ?>" title="First Name" autocomplete="off" />
									<?PHP echo form_error('fname', '<p class="error">', '</p>'); ?>
								</td>
							</tr>
							<tr>
								<td><label for="lname">Last Name:</label></td>
								<td>
									<input type="text" name="lname" id="lname" value="<?php echo set_value('lname', $engage_lname); ?>" title="Last Name" autocomplete="off" />
									<?PHP echo form_error('lname', '<p class="error">', '</p>'); ?>
								</td>
							</tr>
							<tr>
								<td><label for="email">Email:</label></td>
								<td>
									<input type="text" name="email" id="email" value="<?php echo set_value('email', $engage_email); ?>" title="Email"  autocomplete="off"/>
									<?PHP echo form_error('email', '<p class="error">', '</p>'); ?>
								</td>
							</tr>
							<tr>
								<td><label for="password">Password:</label></td>
								<td>
									<input type="password" name="password" id="password" value="<?php echo set_value('password'); ?>" title="Password" autocomplete="off" />
									<?PHP echo form_error('password', '<p class="error">', '</p>'); ?>
								</td>
							</tr>
							<tr>
								<td><label for="confirm_password">Confirm:</label></td>
								<td>
									<input type="password" name="confirm_password" id="confirm_password" value="<?php echo set_value('confirm_password'); ?>" title="Confirm Password" autocomplete="off" />
									<?PHP echo form_error('confirm_password', '<p class="error">', '</p>'); ?>
								</td>
							</tr>
							<tr>
								<td colspan="2">
									<p style="color: #999; text-align: center; padding: 5px;">An email will be sent to complete the registration process.</p>
								</td>
							</tr>
							<tr>
								<td colspan="2">
									<center><?PHP echo $recaptcha_html; ?></center>
									<?PHP echo form_error('captcha', '<p class="error">', '</p>'); ?>
									<p style="float: right;"><button type="button" class="black superbutton" onclick="select_account(); return false;">Next &raquo;</a></p>
								</td>
							</tr>
						</table>
				</div>
				<div class="gs_6 omega">
					<div style="padding-top: 55px;">
<?PHP if($engage_access): ?>
						<p class="confirmation">Thanks! we're almost done... Please verify your information and fill in anything we missed to complete the registration.</p>
<?PHP else: ?>
						<img src="<?PHP echo $media_url; ?>browser/images/account/or.png" />
						<?PHP echo $this->engage->embed('register'); ?>
<?PHP endif; ?>
					</div>
				</div>
			</div>
			<div class="container_omega" id="step_2" style="display: none;">
				<h2>Choose an Account Type:</h2>
				<div class="gs_6">
					<p>Curabitur a velum purus. Nam vel risus a elit malesuada dictum. Quisque rutrum neque nec tortor mollis ut vulputate lectus rutrum. Suspendisse non quam enim. Nam elit orci, vestibulum et mattis ullamcorper, venenatis a urna. Donec vel ullamcorper dui. Fusce lacinia sagittis sem, ac viverra risus molestie non. Cras fringilla ultricies feugiat. Donec in velit ligula. Curabitur orci nunc, dictum ac consequat nec, ornare et sem. Morbi hendrerit aliquam tortor, sed convallis sapien bibendum sed.</p>
					<p class="price_details buyer"><b>BUYER DETAILS:</b><br />Cras fringilla ultricies feugiat. Donec in velit ligula. Curabitur orci nunc, dictum ac consequat nec, ornare et sem. Morbi hendrerit aliquam tortor, sed convallis sapien bibendum sed.</p>
					<p class="price_details agent"><b>AGENT DETAILS:</b><br />Fusce lacinia sagittis sem, ac viverra risus molestie non. </p>
					<p class="price_details merchant"><b>MERCHANT DETAILS:</b><br />Nam vel risus a elit malesuada dictum. Quisque rutrum neque nec tortor mollis ut vulputate lectus rutrum. Suspendisse non quam enim.</p>
				</div>
				<div class="gs_6 omega">
					<div class="pricing">
						<div class="pricing_column buyer">
							<div class="pricing_blurb blue"><h3>BUYER</h3><h2>FREE</h2></div>
							<div class="specs"><p>Forever</p></div>
							<div class="specs"><p><img src="<?PHP echo $media_url; ?>browser/css/img/check.png" alt="" /> Purchase Deals</p></div>
							<div class="specs"><p><img src="<?PHP echo $media_url; ?>browser/css/img/check.png" alt="" /> Exclusive Offers</p></div>
							<div class="specs"><p><img src="<?PHP echo $media_url; ?>browser/css/img/check.png" alt="" /> Special Invites</p></div>
							<div class="buyme"><p><button type="submit" onclick="choose_account('buyer');" class="superbutton black">Choose</button></p></div>
						</div>
						<div class="pricing_column agent">
							<div class="pricing_blurb blue"><h3>AGENT</h3><h2>$100</h2></div>
							<div class="specs"><p>Per year</p></div>
							<div class="specs"><p><img src="<?PHP echo $media_url; ?>browser/css/img/check.png" alt="" /> Detail One</p></div>
							<div class="specs"><p><img src="<?PHP echo $media_url; ?>browser/css/img/check.png" alt="" /> Detail Two</p></div>
							<div class="specs"><p><img src="<?PHP echo $media_url; ?>browser/css/img/check.png" alt="" /> Detail Three</p></div>
							<div class="buyme"><p><button type="submit" onclick="choose_account('agent');" class="superbutton black">Choose</button></p></div>
						</div>
						<div class="pricing_column merchant">
							<div class="pricing_blurb blue"><h3>MERCHANT</h3><h2>$100</h2></div>
							<div class="specs"><p>Per year</p></div>
							<div class="specs"><p><img src="<?PHP echo $media_url; ?>browser/css/img/check.png" alt="" /> Detail One</p></div>
							<div class="specs"><p><img src="<?PHP echo $media_url; ?>browser/css/img/check.png" alt="" /> Detail Two</p></div>
							<div class="specs"><p><img src="<?PHP echo $media_url; ?>browser/css/img/check.png" alt="" /> Detail Three</p></div>
							<div class="buyme"><p><button type="submit" onclick="choose_account('merchant');" class="superbutton black">Choose</button></p></div>
						</div>
					</div>
				</div>
			</div>
		</form>
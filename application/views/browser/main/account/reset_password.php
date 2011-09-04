			<div class="container_alpha slogan">
				<h1><strong>ESSpree:</strong>&nbsp; Privacy Policy</h1>
			</div>
			<div class="container_gamma breadcrumbs">
				<p>
					<a href="<?PHP echo $base_url; ?>">Home</a>
					<span><?PHP echo PAGE_TITLE_SEP; ?></span>
					<a href="<?PHP echo $base_url; ?>privacy">Privacy Policy</a>
				</p>
			</div>
			<div class="container_omega">
				<?PHP echo form_open($this->uri->uri_string()); ?>
				<table>
					<tr>
						<td><?PHP echo form_label('New Password', $new_password['id']); ?></td>
						<td><?PHP echo form_password($new_password); ?></td>
						<td style="color: red;"><?PHP echo form_error($new_password['name']); ?><?PHP echo isset($errors[$new_password['name']])?$errors[$new_password['name']]:''; ?></td>
					</tr>
					<tr>
						<td><?PHP echo form_label('Confirm New Password', $confirm_new_password['id']); ?></td>
						<td><?PHP echo form_password($confirm_new_password); ?></td>
						<td style="color: red;"><?PHP echo form_error($confirm_new_password['name']); ?><?PHP echo isset($errors[$confirm_new_password['name']])?$errors[$confirm_new_password['name']]:''; ?></td>
					</tr>
				</table>
				<?PHP echo form_submit('change', 'Change Password'); ?>
				<?PHP echo form_close(); ?>

				<p><?PHP echo anchor('/', 'Home'); ?></p>
			</div>
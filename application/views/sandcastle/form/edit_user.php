<?php echo validation_errors(); ?>
<?php echo form_open(); ?>
	<fieldset>
		<legend>Edit User</legend>
		<label for="name">Display Name</label>
		<input type="text" name="name" placeholder="John Doe" autofocus="true" required="true" value="<?php echo $user->name; ?>" />
		<label for="email">Email</label>
		<input type="name" name="email" placeholder="j.doe@example.com" required="true" value="<?php echo $user->email; ?>" />
		<label for="confirm_email">Confirm Email</label>
		<input type="text" name="confirm_email" required="true" placeholder="j.doe@example.com" value="<?php echo $user->email; ?>" />
		<p>Leave password fields blank if you do not wish to change the user password</p>
		<label for="password">Password</label>
		<input type="password" name="password" placeholder="password" />
		<label for="confirm_password">Confirm Password</label>
		<input type="password" name="confirm_password" placeholder="password" />
		<label for="status">Status</label>
		<select name="status">
			<?php foreach($statuses as $code => $human): ?>
			<option value="<?php echo $code; ?>"<?php echo ($user->status == $code) ? ' selected="true"' : NULL; ?>><?php echo $human; ?></option>
			<?php endforeach; ?>
		</select>
		<button type="submit">Edit User</button>
	</fieldset>
</form>
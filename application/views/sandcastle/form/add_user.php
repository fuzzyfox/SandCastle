<!doctype html>
<meta charset="utf-8">
<link href="http://kevinburke.bitbucket.org/markdowncss/markdown.css" rel="stylesheet"></link>
<?php echo validation_errors(); ?>
<?php echo form_open(); ?>
	<fieldset>
		<legend>Add User</legend>
		<label for="name">Display Name</label>
		<input type="text" name="name" placeholder="John Doe" autofocus="true" required="true" />
		<label for="email">Email</label>
		<input type="name" name="email" placeholder="j.doe@example.com" required="true" />
		<label for="confirm_email">Confirm Email</label>
		<input type="text" name="confirm_email" required="true" placeholder="j.doe@example.com" />
		<label for="password">Password</label>
		<input type="password" name="password" placeholder="password" required="true" />
		<label for="confirm_password">Confirm Password</label>
		<input type="password" name="confirm_password" placeholder="password" required="true" />
		<label for="status">Status</label>
		<select name="status">
			<?php foreach($statuses as $code => $human): ?>
			<option value="<?php echo $code; ?>"><?php echo $human; ?></option>
			<?php endforeach; ?>
		</select>
		<button type="submit">Add User</button>
	</fieldset>
</form>
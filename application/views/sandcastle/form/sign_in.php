<!doctype html>
<meta charset="utf-8">
<link href="http://kevinburke.bitbucket.org/markdowncss/markdown.css" rel="stylesheet"></link>
<?php echo validation_errors(); ?>
<?php echo form_open(); ?>
	<fieldset>
		<legend>Sign In</legend>
		<label for="email">Email</label><input type="text" name="email" required="true" autofocus="true" />
		<label for="password">Password</label><input type="password" name="password" required="true" />
		<button type="submit">Sign In</button>
	</fieldset>
</form>
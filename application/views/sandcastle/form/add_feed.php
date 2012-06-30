<?php echo validation_errors(); ?>
<?php echo form_open(); ?>
	<fieldset>
		<legend>Add Feed</legend>
		<label for="email">Email</label><input type="text" name="email" placeholder="j.doe@example.com" autofocus="true" />
		<label for="feed_url">Feed URL</label><input type="text" name="feed_url" placeholder="http://www.example.com/path/to/feed.format" required="true" />
		<button type="submit">Add</button>
	</fieldset>
</form>
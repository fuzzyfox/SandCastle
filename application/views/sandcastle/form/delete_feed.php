<!doctype html>
<meta charset="utf-8">
<link href="http://kevinburke.bitbucket.org/markdowncss/markdown.css" rel="stylesheet"></link>
<?php echo validation_errors(); ?>
<?php echo form_open(); ?>
	<fieldset>
		<legend>Confirm Delete</legend>
		<input type="hidden" name="feed_url" value="<?php echo $feed_url; ?>" />
		<p>Please confirm that you wish to delete the feed "<?php echo $feed_title; ?>" at "<?php echo $feed_url; ?>" from the planet.</p>
		<button type="submit" name="confrim" value="true">Delete Feed</button>
		<?php echo anchor($this->uri->segment(1) . '/feeds', 'Cancel'); ?>
	</fieldset>
</form>
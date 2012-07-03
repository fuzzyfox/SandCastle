<!doctype html>
<meta charset="utf-8">
<link href="http://kevinburke.bitbucket.org/markdowncss/markdown.css" rel="stylesheet"></link>
<?php echo validation_errors(); ?>
<?php echo form_open(); ?>
	<fieldset>
		<legend>Add Event</legend>
		<p>Required fields indicated with a *</p>
		<label for="name">Event Name *</label>
		<input type="text" name="name" placeholder="Awesome Meetup" autoselect="true" required="true" />
		<label for="url">Event URL *</label>
		<input type="text" name="url" placeholder="http://www.awesomemeetup.com/" required="true" />
		<label for="description">Event Description *</label>
		<textarea name="description" cols="30" rows="10" placeholder="This is an awesome chance to meet some awesome people in an awesome venue" required="true"></textarea>
		<label for="start_date">Start Date *</label>
		<input type="text" name="start_date" placeholder="yyyy-mm-dd" required="true" />
		<label for="finish_date">Finish Date</label>
		<input type="text" name="finish_date" placeholder="yyyy-mm-dd" />
		<label for="tags">Event Tags</label>
		<input type="text" name="tags" placeholder="place, tags, here" />
		<button type="submit">Add Event</button>
	</fieldset>
</form>
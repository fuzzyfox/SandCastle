<?php echo validation_errors(); ?>
<?php echo form_open(); ?>
	<fieldset>
		<legend>Confirm Delete</legend>
		<input type="hidden" name="event_id" value="<?php echo $event->event_id; ?>" />
		<p>Please confirm that you wish to delete the event "<?php echo $event->event_name; ?>" on "<?php echo date('l, dS F, Y', $event->start_date); ?>".</p>
		<button type="submit" name="confrim" value="true">Delete Event</button>
		<?php echo anchor($this->uri->segment(1) . '/events', 'Cancel'); ?>
	</fieldset>
</form>
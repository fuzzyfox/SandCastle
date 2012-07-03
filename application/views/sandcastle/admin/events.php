<table>
	<thead>
		<tr>
			<th>Name</th>
			<th>URL</th>
			<th>Description</th>
			<th>Start Date</th>
			<th>Finish Date</th>
			<th>Tags</th>
			<th>Action</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach($events as $event): ?>
		<tr>
			<td><?php echo $event->event_name; ?></td>
			<td><?php echo $event->event_url; ?></td>
			<td><?php echo $event->event_description; ?></td>
			<td><?php echo date('l, dS F, Y', $event->start_date); ?></td>
			<td><?php echo date('l, dS F, Y', $event->finish_date); ?></td>
			<td>
				<?php
					$tags = array();
					foreach($event->tags as $tag)
					{
						$tags[] = $tag->tag_name;
					}
					echo implode(', ', $tags);
				?>
			</td>
			<td><a href="<?php echo site_url() . $this->uri->segment(1) . '/delete_event/' . $event->event_id; ?>">Delete</a></td>
		</tr>
		<?php endforeach; ?>
	</tbody>
</table>
<?php echo anchor($this->uri->segment(1) . '/add_event', 'Add Event'); ?>
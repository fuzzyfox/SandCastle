<!doctype html>
<meta charset="utf-8">
<link href="http://kevinburke.bitbucket.org/markdowncss/markdown.css" rel="stylesheet"></link>
<table>
	<thead>
		<tr>
			<th>Email</th>
			<th>URL</th>
			<th>Action</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach($feeds as $feed): ?>
		<tr>
			<td><?php echo $feed->email; ?></td>
			<td><?php echo $feed->feed_url; ?></td>
			<td><a href="<?php echo site_url() . $this->uri->segment(1) . '/delete_feed?feed_url=' . $feed->feed_url; ?>">Delete</a></td>
		</tr>
		<?php endforeach; ?>
	</tbody>
</table>
<?php echo anchor($this->uri->segment(1) . '/add_feed', 'Add Feed'); ?>
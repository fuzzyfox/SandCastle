<table>
	<thead>
		<tr>
			<th>Name</th>
			<th>Email</th>
			<th>Status</th>
			<th>Action</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach($users as $user): ?>
		<tr>
			<td><?php echo $user->name; ?></td>
			<td><?php echo $user->email; ?></td>
			<td><?php echo $user->human_status; ?></td>
			<td>
				<a href="<?php echo site_url() . $this->uri->segment(1) . '/edit_user?email=' . $user->email; ?>">Edit</a>
				<a href="<?php echo site_url() . $this->uri->segment(1) . '/delete_user?email=' . $user->email; ?>">Delete</a>
			</td>
		</tr>
		<?php endforeach; ?>
	</tbody>
</table>
<?php echo anchor($this->uri->segment(1) . '/add_user', 'Add User'); ?>
<table class="table table-bordered table-striped table-condensed" style="margin-top:10px">
	<thead>
		<th style="width: 75%;">Description</th>
		<th>Actions</th>
	</thead>
	<tbody>
		<?php foreach ($data as $todo): ?>
			<tr>
				<td><p style="margin:5px"><strong><?php echo $todo->description; ?></strong></p></td>
				<td>
					<button class="btn btn-primary" type="button">Edit</button>
					<button class="btn btn-danger" type="button">Delete</button>
					<button class="btn btn-success" type="button">Completed</button>
				</td>
			</tr>
		<?php endforeach; ?>
	</tbody>
</table>
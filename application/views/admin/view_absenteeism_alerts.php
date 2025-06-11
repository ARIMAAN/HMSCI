<div class="box">
	<div class="box-header">
		<div class="title">Absenteeism Alerts</div>
	</div>
	<div class="box-content">
		<table class="table table-bordered">
			<thead>
				<tr>
					<th>Doctor ID</th>
					<th>Absent Days</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($alerts as $alert): ?>
					<tr>
						<td><?php echo $alert['doctor_id']; ?></td>
						<td><?php echo $alert['absent_days']; ?></td>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
	</div>
</div>

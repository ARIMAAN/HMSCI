<div class="box">
	<div class="box-header">
		<!------CONTROL TABS START------->
		<ul class="nav nav-tabs nav-tabs-left">
			<li class="<?php echo isset($selected_doctor_id) ? '' : 'active'; ?>">
				<a href="#list" data-toggle="tab" class="tab-link"><i class="icon-align-justify"></i> 
					<?php echo ('Doctor List');?>
				</a>
			</li>
			<li class="<?php echo isset($selected_doctor_id) ? 'active' : ''; ?>">
				<a href="#attendance" data-toggle="tab" class="tab-link"><i class="icon-align-justify"></i> 
					<?php echo ('Attendance List');?>
				</a>
			</li>
		</ul>
		<!------CONTROL TABS END------->
	</div>
	<div class="box-content padded">
		<div class="tab-content">
			<!----TABLE LISTING STARTS--->
			<div class="tab-pane box <?php echo isset($selected_doctor_id) ? '' : 'active'; ?>" id="list">
				<table cellpadding="0" cellspacing="0" border="0" class="dTable responsive table-hover">
					<thead>
						<tr>
							<th><div>#</div></th>
							<th><div><?php echo ('Doctor ID');?></div></th>
							<th><div><?php echo ('Doctor Name');?></div></th>
							<th><div><?php echo ('Email');?></div></th>
							<th><div><?php echo ('PHC ID');?></div></th>
							<th><div><?php echo ('Options');?></div></th>
						</tr>
					</thead>
					<tbody>
						<?php $count = 1; foreach($doctors as $row): ?>
						<tr>
							<td><?php echo $count++; ?></td>
							<td><?php echo $row['doctor_id']; ?></td>
							<td><?php echo $row['name']; ?></td>
							<td><?php echo $row['email']; ?></td>
							<td><?php echo $this->crud_model->get_type_name_by_id('phc', $row['phc_id'], 'phc_id'); ?></td>
							<td align="center">
								<a href="<?php echo base_url(); ?>index.php?admin/manage_attendance/view/<?php echo $row['doctor_id']; ?>" class="btn btn-info view-attendance" data-doctor-id="<?php echo $row['doctor_id']; ?>">
									<i class="icon-eye-open"></i> <?php echo ('View Attendance');?>
								</a>
							</td>
						</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
			</div>
			<!----TABLE LISTING ENDS--->

			<!----ATTENDANCE LISTING STARTS--->
			<div class="tab-pane box <?php echo isset($selected_doctor_id) ? 'active' : ''; ?>" id="attendance">
				<div id="attendance_calendar"></div>
			</div>
			<!----ATTENDANCE LISTING ENDS--->
		</div>
	</div>
</div>

<script>
	$(document).ready(function() {
		// Automatically switch to the Attendance tab if a doctor is selected
		if ("<?php echo isset($selected_doctor_id) ? 'true' : 'false'; ?>" === "true") {
			$('.nav-tabs a[href="#attendance"]').tab('show');
		}

		// Initialize the calendar
		$('#attendance_calendar').fullCalendar({
			header: {
				left: "prev,next",
				center: "title",
				right: "month,agendaWeek,agendaDay"
			},
			editable: false,
			droppable: false,
			events: [] // Placeholder for events
		});

		// Handle View Attendance button click
		$('.view-attendance').on('click', function(e) {
			e.preventDefault();
			const doctorId = $(this).data('doctor-id'); // Get doctor_id from data attribute
			$.ajax({
				url: "<?php echo base_url(); ?>index.php?admin/manage_attendance/view/" + doctorId,
				type: 'GET',
				dataType: 'json',
				success: function(response) {
					if (response.success) {
						// Clear existing events
						$('#attendance_calendar').fullCalendar('removeEvents');

						// Add new events to the calendar
						const events = response.attendances.map(function(attendance) {
							return {
								title: attendance.status.charAt(0).toUpperCase() + attendance.status.slice(1),
								start: attendance.attendance_date,
								description: `Duration: ${attendance.duration} minutes`,
								color: attendance.status === 'present' ? 'green' : 'red' // Set color based on status
							};
						});
						$('#attendance_calendar').fullCalendar('addEventSource', events);

						// Switch to the Attendance tab
						$('.nav-tabs a[href="#attendance"]').tab('show');
					} else {
						alert(response.message || 'No attendance data found for the selected doctor.');
					}
				},
				error: function(xhr, status, error) {
					console.error('Error:', error);
					console.error('Response:', xhr.responseText);
					alert('An error occurred while fetching attendance data. Please try again.');
				}
			});
		});
	});
</script>
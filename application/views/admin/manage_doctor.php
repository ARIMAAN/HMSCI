<div class="box">
	<div class="box-header">
		<!------CONTROL TABS START------->
		<ul class="nav nav-tabs nav-tabs-left">
			<li class="active">
				<a href="#list" data-toggle="tab"><i class="icon-align-justify"></i> 
					<?php echo ('Doctor List');?>
				</a>
			</li>
			<li>
				<a href="#add" data-toggle="tab"><i class="icon-plus"></i>
					<?php echo ('Add Doctor');?>
				</a>
			</li>
		</ul>
		<!------CONTROL TABS END------->
	</div>
	<div class="box-content padded">
		<div class="tab-content">
			<!----TABLE LISTING STARTS--->
			<div class="tab-pane box active" id="list">
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
								<a href="#" rel="tooltip" data-placement="top" data-original-title="<?php echo ('Edit'); ?>" class="btn btn-primary">
									<i class="icon-wrench"></i>
								</a>
								<a href="<?php echo base_url(); ?>index.php?admin/manage_doctor/delete/<?php echo $row['doctor_id']; ?>" onclick="return confirm('delete?')" rel="tooltip" data-placement="top" data-original-title="<?php echo ('Delete'); ?>" class="btn btn-danger">
									<i class="icon-trash"></i>
								</a>
							</td>
						</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
			</div>
			<!----TABLE LISTING ENDS--->

			<!----CREATION FORM STARTS---->
			<div class="tab-pane box" id="add" style="padding: 5px">
				<div class="box-content">
					<?php echo form_open('admin/manage_doctor/create/', array('class' => 'form-horizontal validatable', 'id' => 'addDoctorForm')); ?>
					<div class="padded">
						<div class="control-group">
							<label class="control-label"><?php echo ('Doctor ID');?></label>
							<div class="controls">
								<input type="text" class="validate[required]" name="doctor_id"/>
							</div>
						</div>
						<div class="control-group">
							<label class="control-label"><?php echo ('Name');?></label>
							<div class="controls">
								<input type="text" class="validate[required]" name="name"/>
							</div>
						</div>
						<div class="control-group">
							<label class="control-label"><?php echo ('Email');?></label>
							<div class="controls">
								<input type="text" class="validate[required]" name="email"/>
							</div>
						</div>
						<div class="control-group">
							<label class="control-label"><?php echo ('Password');?></label>
							<div class="controls">
								<input type="password" class="validate[required]" name="password"/>
							</div>
						</div>
						<div class="control-group">
							<label class="control-label"><?php echo ('Address');?></label>
							<div class="controls">
								<input type="text" class="" name="address"/>
							</div>
						</div>
						<div class="control-group">
							<label class="control-label"><?php echo ('Phone');?></label>
							<div class="controls">
								<input type="text" class="" name="phone"/>
							</div>
						</div>
						<div class="control-group">
							<label class="control-label"><?php echo ('PHC ID');?></label>
							<div class="controls">
								<select name="phc_id" class="uniform" style="width:100%;">
									<?php 
									$phcs = $this->db->get('phc')->result_array();
									foreach($phcs as $phc):
									?>
										<option value="<?php echo $phc['phc_id'];?>"><?php echo $phc['phc_id'];?></option>
									<?php endforeach; ?>
								</select>
							</div>
						</div>
					</div>
					<div class="form-actions">
						<button type="submit" class="btn btn-success"><?php echo ('Add Doctor');?></button>
					</div>
					<?php echo form_close(); ?>
				</div>
			</div>
			<!----CREATION FORM ENDS--->
		</div>
	</div>
</div>

<script>
	$('#addDoctorForm').on('submit', function(e) {
		e.preventDefault();
		$.ajax({
			url: $(this).attr('action'),
			type: 'POST',
			data: $(this).serialize(),
			success: function(response) {
				alert('Doctor added successfully!');
				location.reload();
			},
			error: function() {
				alert('An error occurred while adding the doctor.');
			}
		});
	});
</script>
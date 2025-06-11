<div class="box">
	<div class="box-header">
		<!------CONTROL TABS START------->
		<ul class="nav nav-tabs nav-tabs-left">
			<?php if(isset($edit_profile)):?>
			<li class="active">
				<a href="#edit" data-toggle="tab"><i class="icon-wrench"></i> 
					<?php echo ('Edit Appointment');?>
				</a>
			</li>
			<?php endif;?>
			<li class="<?php if(!isset($edit_profile))echo 'active';?>">
				<a href="#list" data-toggle="tab"><i class="icon-align-justify"></i> 
					<?php echo ('Appointment List');?>
				</a>
			</li>
			<li>
				<a href="#add" data-toggle="tab"><i class="icon-plus"></i>
					<?php echo ('Add Appointment');?>
				</a>
			</li>
		</ul>
		<!------CONTROL TABS END------->
	</div>
	<div class="box-content padded">
		<div class="tab-content">
			<!----EDITING FORM STARTS---->
			<?php if(isset($edit_profile)):?>
			<div class="tab-pane box active" id="edit" style="padding: 5px">
				<div class="box-content">
					<?php foreach($edit_profile as $row):?>
					<?php echo form_open('nurse/manage_appointment/edit/do_update/'.$row['appointment_id'] , array('class' => 'form-horizontal validatable'));?>
						<div class="padded">
							<div class="control-group">
								<label class="control-label"><?php echo ('Doctor');?></label>
								<div class="controls">
									<select class="chzn-select" name="doctor_id">
										<?php 
										$doctors = $this->db->get('doctor')->result_array();
										foreach($doctors as $doctor):
										?>
										<option value="<?php echo $doctor['doctor_id'];?>" <?php if($doctor['doctor_id'] == $row['doctor_id'])echo 'selected';?>>
											<?php echo $doctor['name'];?>
										</option>
										<?php endforeach;?>
									</select>
								</div>
							</div>
							<div class="control-group">
								<label class="control-label"><?php echo ('Patient');?></label>
								<div class="controls">
									<select class="chzn-select" name="patient_id">
										<?php 
										$patients = $this->db->get('patient')->result_array();
										foreach($patients as $patient):
										?>
										<option value="<?php echo $patient['patient_id'];?>" <?php if($patient['patient_id'] == $row['patient_id'])echo 'selected';?>>
											<?php echo $patient['name'];?>
										</option>
										<?php endforeach;?>
									</select>
								</div>
							</div>
							<div class="control-group">
								<label class="control-label"><?php echo ('Date');?></label>
								<div class="controls">
									<input type="text" class="datepicker fill-up" name="appointment_timestamp" value="<?php echo date('m/d/Y', $row['appointment_timestamp']);?>"/>
								</div>
							</div>
						</div>
						<div class="form-actions">
							<button type="submit" class="btn btn-primary"><?php echo ('Edit Appointment');?></button>
						</div>
					<?php echo form_close();?>
					<?php endforeach;?>
				</div>
			</div>
			<?php endif;?>
			<!----EDITING FORM ENDS---->
			
			<!----TABLE LISTING STARTS---->
			<div class="tab-pane box <?php if(!isset($edit_profile))echo 'active';?>" id="list">
				<table cellpadding="0" cellspacing="0" border="0" class="dTable responsive table-hover">
					<thead>
						<tr>
							<th><div>#</div></th>
							<th><div><?php echo ('Date');?></div></th>
							<th><div><?php echo ('Patient');?></div></th>
							<th><div><?php echo ('Doctor');?></div></th>
							<th><div><?php echo ('Options');?></div></th>
						</tr>
					</thead>
					<tbody>
						<?php $count = 1;foreach($appointments as $row):?>
						<tr>
							<td><?php echo $count++;?></td>
							<td><?php echo date('d M,Y', $row['appointment_timestamp']);?></td>
							<td><?php echo $this->crud_model->get_type_name_by_id('patient',$row['patient_id'],'name');?></td>
							<td><?php echo $this->crud_model->get_type_name_by_id('doctor',$row['doctor_id'],'name');?></td>
							<td align="center">
								<a href="<?php echo base_url();?>index.php?nurse/manage_appointment/edit/<?php echo $row['appointment_id'];?>"
									rel="tooltip" data-placement="top" data-original-title="<?php echo ('Edit');?>" class="btn btn-primary">
									<i class="icon-wrench"></i>
								</a>
								<a href="<?php echo base_url();?>index.php?nurse/manage_appointment/delete/<?php echo $row['appointment_id'];?>" onclick="return confirm('delete?')"
									rel="tooltip" data-placement="top" data-original-title="<?php echo ('Delete');?>" class="btn btn-danger">
									<i class="icon-trash"></i>
								</a>
							</td>
						</tr>
						<?php endforeach;?>
					</tbody>
				</table>
			</div>
			<!----TABLE LISTING ENDS---->
			
			<!----CREATION FORM STARTS---->
			<div class="tab-pane box" id="add" style="padding: 5px">
				<div class="box-content">
					<?php echo form_open('nurse/manage_appointment/create/' , array('class' => 'form-horizontal validatable'));?>
						<div class="padded">
							<div class="control-group">
								<label class="control-label"><?php echo ('Doctor');?></label>
								<div class="controls">
									<select class="chzn-select" name="doctor_id">
										<?php 
										$doctors = $this->db->get('doctor')->result_array();
										foreach($doctors as $doctor):
										?>
										<option value="<?php echo $doctor['doctor_id'];?>"><?php echo $doctor['name'];?></option>
										<?php endforeach;?>
									</select>
								</div>
							</div>
							<div class="control-group">
								<label class="control-label"><?php echo ('Patient');?></label>
								<div class="controls">
									<select class="chzn-select" name="patient_id">
										<?php 
										$patients = $this->db->get('patient')->result_array();
										foreach($patients as $patient):
										?>
										<option value="<?php echo $patient['patient_id'];?>"><?php echo $patient['name'];?></option>
										<?php endforeach;?>
									</select>
								</div>
							</div>
							<div class="control-group">
								<label class="control-label"><?php echo ('Date');?></label>
								<div class="controls">
									<input type="text" class="datepicker fill-up" name="appointment_timestamp"/>
								</div>
							</div>
						</div>
						<div class="form-actions">
							<button type="submit" class="btn btn-success"><?php echo ('Add Appointment');?></button>
						</div>
					<?php echo form_close();?>                
				</div>                
			</div>
			<!----CREATION FORM ENDS---->
		</div>
	</div>
</div>

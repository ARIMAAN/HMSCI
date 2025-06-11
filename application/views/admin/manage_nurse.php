<div class="box">
	<div class="box-header">
		<ul class="nav nav-tabs nav-tabs-left">
			<?php if (isset($edit_profile)): ?>
			<li class="active">
				<a href="#edit" data-toggle="tab"><i class="icon-wrench"></i> <?php echo ('Edit Nurse'); ?></a>
			</li>
			<?php endif; ?>
			<li class="<?php if (!isset($edit_profile)) echo 'active'; ?>">
				<a href="#list" data-toggle="tab"><i class="icon-align-justify"></i> <?php echo ('Nurse List'); ?></a>
			</li>
			<li>
				<a href="#add" data-toggle="tab"><i class="icon-plus"></i> <?php echo ('Add Nurse'); ?></a>
			</li>
		</ul>
	</div>
	<div class="box-content padded">
		<div class="tab-content">
			<?php if (isset($edit_profile)): ?>
			<div class="tab-pane box active" id="edit" style="padding: 5px">
				<div class="box-content">
					<?php foreach ($edit_profile as $row): ?>
					<?php echo form_open('admin/manage_nurse/edit/do_update/' . $row['nurse_id'], array('class' => 'form-horizontal validatable')); ?>
					<div class="padded">
                            <div class="control-group">
                                <label class="control-label"><?php echo ('Name');?></label>
                                <div class="controls">
                                    <input type="text" class="validate[required]" name="name" value="<?php echo $row['name'];?>"/>
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label"><?php echo ('Email');?></label>
                                <div class="controls">
                                    <input type="text" class="validate[required]" name="email" value="<?php echo $row['email'];?>"/>
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label"><?php echo ('Password');?></label>
                                <div class="controls">
                                    <input type="password" class="validate[required]" name="password" value="<?php echo $row['password'];?>"/>
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label"><?php echo ('Address');?></label>
                                <div class="controls">
                                    <input type="text" class="" name="address" value="<?php echo $row['address'];?>"/>
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label"><?php echo ('Phone');?></label>
                                <div class="controls">
                                    <input type="text" class="" name="phone" value="<?php echo $row['phone'];?>"/>
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label"><?php echo ('PHC ID');?></label>
                                <div class="controls">
                                    <select name="phc_id" class="uniform" style="width:100%;">
                                        <?php foreach ($this->db->get('phc')->result_array() as $phc): ?>
                                        <option value="<?php echo $phc['phc_id']; ?>" <?php echo isset($row['phc_id']) && $row['phc_id'] == $phc['phc_id'] ? 'selected' : ''; ?>>
                                            <?php echo $phc['phc_name']; ?>
                                        </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label"><?php echo ('Nurse ID');?></label>
                                <div class="controls">
                                    <input type="text" class="validate[required]" name="nur_id" value="<?php echo isset($row['nur_id']) ? $row['nur_id'] : ''; ?>"/>
                                </div>
                            </div>
                        </div>
					<?php echo form_close(); ?>
					<?php endforeach; ?>
				</div>
			</div>
			<?php endif; ?>
			<div class="tab-pane box <?php if (!isset($edit_profile)) echo 'active'; ?>" id="list">
				<table class="dTable responsive table-hover">
					<thead>
						<tr>
							<th>#</th>
							<th><?php echo ('Nurse ID'); ?></th>
							<th><?php echo ('Name'); ?></th>
							<th><?php echo ('Email'); ?></th>
							<th><?php echo ('PHC ID'); ?></th>
							<th><?php echo ('Options'); ?></th>
						</tr>
					</thead>
					<tbody>
						<?php $count = 1; foreach ($nurses as $row): ?>
						<tr>
							<td><?php echo $count++; ?></td>
							<td><?php echo $row['nurse_id']; ?></td>
							<td><?php echo $row['name']; ?></td>
							<td><?php echo $row['email']; ?></td>
							<td><?php echo $row['phc_id']; ?></td>
							<td>
								<a href="<?php echo base_url(); ?>index.php?admin/manage_nurse/edit/<?php echo $row['nurse_id']; ?>" class="btn btn-primary"><i class="icon-wrench"></i></a>
								<a href="<?php echo base_url(); ?>index.php?admin/manage_nurse/delete/<?php echo $row['nurse_id']; ?>" onclick="return confirm('delete?')" class="btn btn-danger"><i class="icon-trash"></i></a>
							</td>
						</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
			</div>
			<div class="tab-pane box" id="add" style="padding: 5px">
				<div class="box-content">
					<?php echo form_open('admin/manage_nurse/create/', array('class' => 'form-horizontal validatable')); ?>
					<div class="padded">
						<div class="control-group">
							<label class="control-label"><?php echo ('Nurse ID'); ?></label>
							<div class="controls">
								<input type="text" class="validate[required]" name="nurse_id" />
							</div>
						</div>
						<div class="control-group">
							<label class="control-label"><?php echo ('Name'); ?></label>
							<div class="controls">
								<input type="text" class="validate[required]" name="name" />
							</div>
						</div>
						<div class="control-group">
							<label class="control-label"><?php echo ('Email'); ?></label>
							<div class="controls">
								<input type="email" class="validate[required]" name="email" />
							</div>
						</div>
						<div class="control-group">
							<label class="control-label"><?php echo ('Password'); ?></label>
							<div class="controls">
								<input type="password" class="validate[required]" name="password" />
							</div>
						</div>
						<div class="control-group">
							<label class="control-label"><?php echo ('Address'); ?></label>
							<div class="controls">
								<input type="text" name="address" />
							</div>
						</div>
						<div class="control-group">
							<label class="control-label"><?php echo ('Phone'); ?></label>
							<div class="controls">
								<input type="text" name="phone" />
							</div>
						</div>
						<div class="control-group">
							<label class="control-label"><?php echo ('PHC ID'); ?></label>
							<div class="controls">
								<select name="phc_id" class="uniform" style="width:100%;">
									<?php foreach ($this->db->get('phc')->result_array() as $phc): ?>
									<option value="<?php echo $phc['phc_id']; ?>"><?php echo $phc['phc_id']; ?></option>
									<?php endforeach; ?>
								</select>
							</div>
						</div>
					</div>
					<div class="form-actions">
						<button type="submit" class="btn btn-success"><?php echo ('Add Nurse'); ?></button>
					</div>
					<?php echo form_close(); ?>
				</div>
			</div>
		</div>
	</div>
</div>

<?php
if ($this->input->post()) {
    $data = array(
        'name' => $this->input->post('name'),
        'email' => $this->input->post('email'),
        'password' => $this->input->post('password'),
        'address' => $this->input->post('address'),
        'phone' => $this->input->post('phone'),
        'phc_id' => $this->input->post('phc_id'),
        'nur_id' => $this->input->post('nur_id')
    );
    $this->db->insert('nurse', $data);
    redirect('admin/manage_nurse');
}
?>
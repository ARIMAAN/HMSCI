<div class="box">

	<div class="box-header">

    

    	<!------CONTROL TABS START------->

		<ul class="nav nav-tabs nav-tabs-left">

        	<?php if(isset($edit_profile)):?>

			<li class="active">

            	<a href="#edit" data-toggle="tab"><i class="icon-wrench"></i> 

					<?php echo ('Edit Patient');?>

                    	</a></li>

            <?php endif;?>

			<li class="<?php if(!isset($edit_profile))echo 'active';?>">

            	<a href="#list" data-toggle="tab"><i class="icon-align-justify"></i> 

					<?php echo ('Patient List');?>

                    	</a></li>

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

                    <?php echo form_open('doctor/manage_patient/edit/do_update/'.$row['patient_id'] , array('class' => 'form-horizontal validatable'));?>

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

                                <label class="control-label"><?php echo ('Sex');?></label>

                                <div class="controls">

                                    <select name="sex" class="uniform" style="width:100%;">

                                    	<option value="male" <?php if($row['sex']=='male')echo 'selected';?>><?php echo ('Male');?></option>

                                    	<option value="female" <?php if($row['sex']=='female')echo 'selected';?>><?php echo ('Female');?></option>

                                    </select>

                                </div>

                            </div>

                            <div class="control-group">

                                <label class="control-label"><?php echo ('Birth Date');?></label>

                                <div class="controls">

                                    <input type="date" class="" name="birth_date" value="<?php echo $row['birth_date'];?>"/>

                                </div>

                            </div>

                            <div class="control-group">

                                <label class="control-label"><?php echo ('Age');?></label>

                                <div class="controls">

                                    <input type="text" class="" name="age" value="<?php echo $row['age'];?>"/>

                                </div>

                            </div>

                            <div class="control-group">

                                <label class="control-label"><?php echo ('Blood Group');?></label>

                                <div class="controls">

                                    <select name="blood_group" class="uniform" style="width:100%;">

                                    	<option value="A+" <?php if($row['blood_group']=='A+')echo 'selected';?>>A+</option>

                                        <option value="A-" <?php if($row['blood_group']=='A-')echo 'selected';?>>A-</option>

                                        <option value="B+" <?php if($row['blood_group']=='B+')echo 'selected';?>>B+</option>

                                        <option value="B-" <?php if($row['blood_group']=='B-')echo 'selected';?>>B-</option>

                                        <option value="AB+" <?php if($row['blood_group']=='AB+')echo 'selected';?>>AB+</option>

                                        <option value="AB-" <?php if($row['blood_group']=='AB-')echo 'selected';?>>AB-</option>

                                        <option value="O+" <?php if($row['blood_group']=='O+')echo 'selected';?>>O+</option>

                                        <option value="O-" <?php if($row['blood_group']=='O-')echo 'selected';?>>O-</option>

                                    </select>

                                </div>

                            </div>

                        </div>

                        <div class="form-actions">

                            <button type="submit" class="btn btn-primary"><?php echo ('Edit Patient');?></button>

                        </div>

                    <?php echo form_close();?>

                    <?php endforeach;?>

                </div>

			</div>

            <?php endif;?>

            <!----EDITING FORM ENDS--->

            

            <!----TABLE LISTING STARTS--->

            <!----DISPLAY PHC ID STARTS---->
            <?php if ($this->session->userdata('phc_id')): ?>
            <div class="alert alert-info">
                <strong><?php echo ('PHC ID:');?></strong> <?php echo $this->session->userdata('phc_id'); ?>
            </div>
            <?php else: ?>
            <div class="alert alert-warning">
                <strong><?php echo ('PHC ID not set in session. Please ensure the login process sets it correctly.');?></strong>
            </div>
            <?php endif; ?>
            <!----DISPLAY PHC ID ENDS---->
            <div class="tab-pane box <?php if(!isset($edit_profile))echo 'active';?>" id="list">

				

                <table cellpadding="0" cellspacing="0" border="0" class="dTable responsive table-hover">

                	<thead>

                		<tr>

                    		<th><div>#</div></th>

                    		<th><div><?php echo ('Patient Name');?></div></th>

                    		<th><div><?php echo ('Age');?></div></th>

                    		<th><div><?php echo ('Sex');?></div></th>

                    		<th><div><?php echo ('Blood Group');?></div></th>

                    		<th><div><?php echo ('Birth Date');?></div></th>

                    		<th><div><?php echo ('Options');?></div></th>

						</tr>

					</thead>

                    <tbody>

                        <?php 
                        $doctor_phc_id = $this->session->userdata('phc_id'); // Assuming 'phc_id' is stored in session
                        $patients = $this->db->get_where('patient', array('phc_id' => $doctor_phc_id))->result_array();
                        $count = 1;
                        foreach($patients as $row): ?>
                        <tr>
                            <td><?php echo $count++;?></td>
                            <td><?php echo $row['name'];?></td>
                            <td><?php echo $row['age'];?></td>
                            <td><?php echo $row['sex'];?></td>
                            <td><?php echo $row['blood_group'];?></td>
                            <td><?php echo $row['birth_date'];?></td>
                            <td align="center">
                                <a href="<?php echo base_url();?>index.php?doctor/manage_patient/edit/<?php echo $row['patient_id'];?>"
                                    rel="tooltip" data-placement="top" data-original-title="<?php echo ('Edit');?>" class="btn btn-primary">
                                    <i class="icon-wrench"></i>
                                </a>
                                <a href="<?php echo base_url();?>index.php?doctor/manage_patient/delete/<?php echo $row['patient_id'];?>" onclick="return confirm('delete?')"
                                    rel="tooltip" data-placement="top" data-original-title="<?php echo ('Delete');?>" class="btn btn-danger">
                                    <i class="icon-trash"></i>
                                </a>
                            </td>
                        </tr>
                        <?php endforeach;?>
                    </tbody>

                </table>

			</div>

            <!----TABLE LISTING ENDS--->

            

		</div>

	</div>

</div>
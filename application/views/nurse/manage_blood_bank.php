<div class="box">

	<div class="box-header">

    	<!------CONTROL TABS END------->

        

	</div>

	<div class="box-content padded">

		<div class="tab-content">

        	<!----EDITING FORM STARTS---->

        	<?php if(isset($edit_profile)):?>

			<div class="tab-pane box active" id="edit" style="padding: 5px">

                <div class="box-content">

                	<?php foreach($edit_profile as $row):?>

                    <?php echo form_open('nurse/manage_blood_bank/edit/do_update/'.$row['blood_group_id'] , array('class' => 'form-horizontal validatable'));?>

                        <div class="padded">

                            <div class="control-group">

                                <label class="control-label"><?php echo $row['blood_group'];?></label>

                                <div class="controls">

                                    <input type="text" class="" name="status" value="<?php echo $row['status'];?>"/>

                                </div>

                            </div>

                        </div>

                        <div class="form-actions">

                            <button type="submit" class="btn btn-primary"><?php echo ('Edit Blood Bank');?></button>

                        </div>

                    <?php echo form_close();?>

                    <?php endforeach;?>

                </div>

			</div>

            <?php endif;?>

            <!----EDITING FORM ENDS--->

            

            <!----TABLE LISTING STARTS--->

            <div class="tab-pane box <?php if(!isset($edit_profile))echo 'active';?>" id="list">

				

                <table cellpadding="0" cellspacing="0" border="0" class="dTable responsive table-hover">

                	<thead>

                		<tr>

                    		<th><div>#</div></th>

                    		<th><div><?php echo ('Blood Group');?></div></th>

                    		<th><div><?php echo ('Status');?></div></th>

                    		<th><div><?php echo ('Options');?></div></th>

						</tr>

					</thead>

                    <tbody>

                    	<?php $count = 1;foreach($blood_bank as $row):?>

                        <tr>

                            <td><?php echo $count++;?></td>

							<td><?php echo $row['blood_group'];?></td>

							<td><?php echo $row['status'];?></td>

							<td align="center">

                            	<a href="<?php echo base_url();?>index.php?nurse/manage_blood_bank/edit/<?php echo $row['blood_group_id'];?>"

                                	rel="tooltip" data-placement="top" data-original-title="<?php echo ('Edit');?>" class="btn btn-primary">

                                		<i class="icon-wrench"></i>

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
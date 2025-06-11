<div class="container-fluid padded">
    <div class="box">
        <div class="box-header">
            <!------CONTROL TABS START------->
            <ul class="nav nav-tabs nav-tabs-left">
                <?php if(isset($edit_profile)):?>
                <li class="active">
                    <a href="#edit" data-toggle="tab">
                        <i class="icon-wrench"></i> <?php echo ('Edit PHC');?>
                    </a>
                </li>
                <?php endif;?>
                <li class="<?php if(!isset($edit_profile))echo 'active';?>">
                    <a href="#list" data-toggle="tab">
                        <i class="icon-align-justify"></i> <?php echo ('PHC List');?>
                    </a>
                </li>
                <li>
                    <a href="#add" data-toggle="tab">
                        <i class="icon-plus"></i> <?php echo ('Add PHC');?>
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
                        <?php echo form_open('admin/manage_phc/edit/do_update/'.$row['phc_id'] , array('class' => 'form-horizontal validatable'));?>
                            <div class="padded">
                                <div class="control-group">
                                    <label class="control-label"><?php echo ('PHC Name');?></label>
                                    <div class="controls">
                                        <input type="text" class="validate[required]" name="phc_name" value="<?php echo $row['phc_name'];?>"/>
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
                                    <label class="control-label"><?php echo ('Latitude');?></label>
                                    <div class="controls">
                                        <input type="text" name="latitude" value="<?php echo $row['latitude'];?>"/>
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label"><?php echo ('Longitude');?></label>
                                    <div class="controls">
                                        <input type="text" name="longitude" value="<?php echo $row['longitude'];?>"/>
                                    </div>
                                </div>
                            </div>
                            <div class="form-actions">
                                <button type="submit" class="btn btn-primary"><?php echo ('Edit PHC');?></button>
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
                                <th><div><?php echo ('PHC ID');?></div></th>
                                <th><div><?php echo ('PHC Name');?></div></th>
                                <th><div><?php echo ('Email');?></div></th>
                                <th><div><?php echo ('Latitude');?></div></th>
                                <th><div><?php echo ('Longitude');?></div></th>
                                <th><div><?php echo ('Options');?></div></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $count = 1; foreach($phcs as $row): ?>
                            <tr>
                                <td><?php echo $count++; ?></td>
                                <td><?php echo $row['phc_id']; ?></td>
                                <td><?php echo $row['phc_name']; ?></td>
                                <td><?php echo $row['email']; ?></td>
                                <td><?php echo $row['latitude']; ?></td>
                                <td><?php echo $row['longitude']; ?></td>
                                <td align="center">
                                    <a href="<?php echo base_url(); ?>index.php?admin/manage_phc/edit/<?php echo $row['phc_id']; ?>"
                                       rel="tooltip" data-placement="top" data-original-title="<?php echo ('Edit'); ?>" class="btn btn-primary">
                                        <i class="icon-wrench"></i>
                                    </a>
                                    <a href="<?php echo base_url(); ?>index.php?admin/manage_phc/delete/<?php echo $row['phc_id']; ?>" onclick="return confirm('delete?')"
                                       rel="tooltip" data-placement="top" data-original-title="<?php echo ('Delete'); ?>" class="btn btn-danger">
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
                <div class="tab-pane box" id="add">
                    <div class="box-content">
                        <?php echo form_open('admin/manage_phc/create', array('class' => 'form-horizontal validatable')); ?>
                            <div class="padded">
                                <div class="control-group">
                                    <label class="control-label"><?php echo ('PHC ID');?></label>
                                    <div class="controls">
                                        <input type="text" class="validate[required]" name="phc_id" required/>
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label"><?php echo ('PHC Name');?></label>
                                    <div class="controls">
                                        <input type="text" class="validate[required]" name="phc_name"/>
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label"><?php echo ('Latitude');?></label>
                                    <div class="controls">
                                        <input type="text" class="validate[required]" name="latitude"/>
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label"><?php echo ('Longitude');?></label>
                                    <div class="controls">
                                        <input type="text" class="validate[required]" name="longitude"/>
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label"><?php echo ('Email');?></label>
                                    <div class="controls">
                                        <input type="email" class="validate[required]" name="email"/>
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label"><?php echo ('Password');?></label>
                                    <div class="controls">
                                        <input type="password" class="validate[required]" name="password"/>
                                    </div>
                                </div>
                            </div>
                            <div class="form-actions">
                                <button type="submit" class="btn btn-success"><?php echo ('Add PHC');?></button>
                            </div>
                        <?php echo form_close(); ?>
                    </div>
                </div>
                <!----CREATION FORM ENDS--->
            </div>
        </div>
    </div>
</div>

<style>
/* Custom styles to match template */
.box {
    background: #fff;
    border-radius: 3px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    margin-bottom: 20px;
}

.box-header {
    padding: 10px;
    background: #f7f7f7;
    border-bottom: 1px solid #ddd;
}

.box-content {
    padding: 15px;
}

.nav-tabs-left {
    border-bottom: 0;
    border-right: 1px solid #ddd;
}

.nav-tabs-left > li > a {
    border-radius: 4px 0 0 4px;
    margin-right: 0;
}

.form-horizontal .control-group {
    margin-bottom: 15px;
}

.form-horizontal .control-label {
    width: 140px;
    padding-top: 5px;
}

.form-horizontal .controls {
    margin-left: 160px;
}

.form-actions {
    padding: 15px;
    margin: 15px -15px -15px;
    background: #f5f5f5;
    border-top: 1px solid #ddd;
}

.table {
    margin-bottom: 0;
}

.table th {
    background: #f5f5f5;
}

.btn {
    margin: 0 2px;
}
</style>
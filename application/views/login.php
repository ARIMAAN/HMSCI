<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html>
    <head>
        <meta name="viewport" content="width=device-width, maximum-scale=1, initial-scale=1, user-scalable=0">
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <meta content="IE=edge,chrome=1" http-equiv="X-UA-Compatible">
		<?php include 'includes.php';?>
        <title><?php echo ('Login');?> | <?php echo $system_title;?></title>
    </head>
	<body>
		<?php if($this->session->flashdata('flash_message') != ""):?>
 		<script>
			$(document).ready(function() {
				Growl.info({title:"<?php echo $this->session->flashdata('flash_message');?>",text:" "})
			});
		</script>
        <?php endif;?>
        <div class="navbar navbar-top navbar-inverse">
            <div class="navbar-inner">
                <div class="container-fluid">
                    <a class="brand" href="<?php echo base_url();?>"><?php echo $system_name;?></a>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="span4 offset4">
                <div class="padded">
                    <center>
                        <!-- <img src="<?php echo base_url();?>uploads/cdhsp.png" style="height:80px;" /> -->
                        <h4>Hospital Management System</h4>
                    </center>
                    <div class="login box" style="margin-top: 10px;">
                        <div class="box-header">
                            <span class="title"><?php echo ('Login Panel');?></span>
                        </div>
                        <div class="box-content padded">
                        <i class="m-icon-swapright m-icon-white"></i>
                        	<?php echo form_open('login' , array('class' => 'separate-sections'));?>
                                <div class="">
                                    
                                    <select class="validate[required]" name="login_type" style="width:100%;">
                                        <option value=""><?php echo ('--- Select Account Type ---');?></option>
                                        <option value="admin"><?php echo ('Admin');?></option>
                                        <option value="doctor"><?php echo ('Doctor');?></option>
                                        <option value="patient"><?php echo ('Patient');?></option>
                                        <option value="nurse"><?php echo ('Nurse');?></option>
                                        <option value="pharmacist"><?php echo ('Pharmacist');?></option>
                                        <option value="laboratorist"><?php echo ('Laboratorist');?></option>
                                    </select>
    
                                </div>
                                <div class="input-prepend">
                                    <span class="add-on" href="#">
                                    <i class="icon-envelope"></i>
                                    </span>
                                    <input name="email" type="text" placeholder="<?php echo ('Email');?>">
                                </div>
                                <div class="input-prepend">
                                    <span class="add-on" href="#">
                                    <i class="icon-key"></i>
                                    </span>
                                    <input name="password" type="password" placeholder="<?php echo ('Password');?>">
                                </div>
                                <div>
                                    <button type="submit" class="btn btn-success btn-block" >
                                        <?php echo ('Login');?>
                                    </button>
                                </div>
                            <?php echo form_close();?>
                            <div>
                                <a  data-toggle="modal" href="#modal-simple">
                                    <?php echo ('Forgot Password?');?>
                                </a>
                            </div>
                        </div>
                    </div>
                    <?php include 'application/views/footer.php';?>
                </div>
            </div>
        </div>
        
        <?php
        if (!$this->session->userdata('phc_id')) {
            $login_type = $this->session->userdata('login_type');
            $login_user_id = $this->session->userdata('login_user_id');
        
            if ($login_type == 'doctor') {
                $phc_id = $this->db->get_where('doctor', array('doctor_id' => $login_user_id))->row()->phc_id;
            } elseif ($login_type == 'nurse') {
                $phc_id = $this->db->get_where('nurse', array('nurse_id' => $login_user_id))->row()->phc_id;
            } elseif ($login_type == 'pharmacist') {
                $phc_id = $this->db->get_where('pharmacist', array('pharmacist_id' => $login_user_id))->row()->phc_id;
            } elseif ($login_type == 'laboratorist') {
                $phc_id = $this->db->get_where('laboratorist', array('laboratorist_id' => $login_user_id))->row()->phc_id;
            } else {
                $phc_id = null;
            }
        
            if ($phc_id) {
                $this->session->set_userdata('phc_id', $phc_id);
            } else {
                log_message('error', 'PHC ID not found for user type: ' . $login_type . ' with ID: ' . $login_user_id);
            }
        }
        ?>
        
        <!-----------password reset form ------>
        <div id="modal-simple" class="modal hide fade">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h6 id="modal-tablesLabel"><?php echo ('Reset Password');?></h6>
          </div>
          <div class="modal-body">
            <?php echo form_open('login/reset_password');?>
            	<select class="validate[required]" name="account_type"  style="margin-bottom: 0px !important;">
                    <option value=""><?php echo ('Account Type');?></option>
                    <option value="admin"><?php echo ('Admin');?></option>
                    <option value="doctor"><?php echo ('Doctor');?></option>
                    <option value="patient"><?php echo ('Patient');?></option>
                    <option value="nurse"><?php echo ('Nurse');?></option>
                    <option value="pharmacist"><?php echo ('Pharmacist');?></option>
                    <option value="laboratorist"><?php echo ('Laboratorist');?></option>
                    <option value="accountant"><?php echo ('Accountant');?></option>
                </select>
                <input type="email" name="email"  placeholder="<?php echo ('Email');?>"  style="margin-bottom: 0px !important;"/>
                <input type="submit" value="<?php echo ('Reset');?>"  class="btn btn-blue btn-medium"/>
            <?php echo form_close();?>
          </div>
          <div class="modal-footer">
            <button class="btn btn-default" data-dismiss="modal">Close</button>
          </div>
        </div>
        <!-----------password reset form ------>
        
        
	</body>
</html>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">



<html>

    <head>

        <meta name="viewport" content="width=device-width, maximum-scale=1, initial-scale=1, user-scalable=0">

        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>

        <meta content="IE=edge,chrome=1" http-equiv="X-UA-Compatible">

		<?php include 'includes.php';?>

        <link href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css" rel="stylesheet">
        <link href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.dataTables.min.css" rel="stylesheet">
        <link href="<?php echo base_url(); ?>assets/css/custom.css" rel="stylesheet">

        <title><?php echo $page_title;?> | <?php echo $system_title;?></title>

    </head>

    

    

<body>

    <?php include 'header.php';?>

    <?php include $this->session->userdata('login_type').'/navigation.php';?>

    <div class="main-content">

		<?php include 'page_info.php';?>

        <div class="container-fluid padded">

            <?php 
            // Update $page_name to replace 'manage_department' with 'manage_phc'
            $page_name = str_replace('manage_department', 'manage_phc', $page_name); 
            include $this->session->userdata('login_type').'/'.$page_name.'.php';
            ?>

        </div>        

	<?php include 'footer.php';?>

    </div>

    

</body>

</html>
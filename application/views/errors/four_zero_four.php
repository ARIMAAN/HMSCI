<?php
// Ensure variables are defined
$system_title = isset($system_title) ? $system_title : 'System Title';
$system_name = isset($system_name) ? $system_name : 'System Name';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $system_title; ?> | Page Not Found</title>
</head>
<body>
    <div id="container">
        <h1>404 - Page Not Found</h1>
        <p>Sorry, the page you are looking for does not exist.</p>
        <a href="<?php echo base_url(); ?>">Back to Dashboard</a>
    </div>
</body>
</html>
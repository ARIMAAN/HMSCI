<?php
include('../db.php');
session_start();
if(!isset($_SESSION['admin_id']))
{
header("location:index.php");
}
$admin_id=$_SESSION['admin_id'];
$phc_id=$_GET['phc_id'];
$sql="select * from phc where phc_id='$phc_id'";
$result=mysqli_query($conn,$sql);
$row=mysqli_fetch_array($result);
$phc_name=$row['phc_name'];
$latitude=$row['latitude'];
$longitude=$row['longitude'];
$password=$row['password'];
$email=$row['email'];

if (isset($_POST['department_id'])) {
    $department_id = $_POST['department_id'];
} else {
    $department_id = null; // or a default value
}

if (isset($_POST['name'])) {
    $name = $_POST['name'];
} else {
    $name = ''; // or a default value
}

if (isset($_POST['description'])) {
    $description = $_POST['description'];
} else {
    $description = ''; // or a default value
}

$sql="update phc set department_id='$department_id',name='$name',description='$description' where phc_id='$phc_id'";
if(mysqli_query($conn,$sql))
{
echo "<script>alert('Successfully Updated');</script>";
}
else
{
echo "<script>alert('Not Updated');</script>";
}
?>
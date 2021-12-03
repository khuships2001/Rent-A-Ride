<?php
$cycid = ($_GET['cycid']);
$query="DELETE FROM cycles WHERE cycle_id='".$cycid."';";
// echo mysqli_error($con);
$con = mysqli_connect('localhost','root','','epiz_23676572_gocycledata');
$result=mysqli_query($con,$query);
echo mysqli_error($con);
header("location: manage.php")
?>

<?php
$con = mysqli_connect("localhost","root","","epiz_23676572_gocycledata");
$cycle_id = $_GET['cycle_id'];
echo $cycle_id;
$sql = "DELETE FROM bidding WHERE cycle_id = '".$cycle_id."' ; ";
$output = '{"Success":0}';
$result = mysqli_query($con,$sql);
if(mysqli_errno($con)==0){
	$output = '{"Success":1}';
	header('location: sellcycle.php');
}
echo mysqli_error($con);
mysqli_close($con);
echo $output;
?>
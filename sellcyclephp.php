<?php
$con = mysqli_connect("localhost","root","","epiz_23676572_gocycledata");
$cycle_id = $_GET['cycle_id'];
$price = ($_GET['price']);
$date = date("Y/m/d");
echo $cycle_id;
echo $price;
echo $date;
$sql = "INSERT into bidding VALUES ('".$cycle_id."','".$price."', DATE '".$date."');";
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
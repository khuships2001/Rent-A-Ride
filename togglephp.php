<?php

function get_stat($cycle_id){
	$con = mysqli_connect("localhost","root","","epiz_23676572_gocycledata");
    $sql = "SELECT * FROM status WHERE cycle_id='".$cycle_id."';";
    $result = mysqli_query($con,$sql);
    if($result -> num_rows > 0){
        return "YES";
    }else{
        return "NO";
    }
}

function giveride($cycle_id , $lat , $lng){
	$con = mysqli_connect("localhost","root","","epiz_23676572_gocycledata");
	if (!$con) {
	  die("Not connected : " . mysqli_error($con));
	}

	$query = "SELECT sum(rating)/COUNT(*) AS avg_rating FROM history inner join feedback WHERE cycle_id = '".$cycle_id."' ; ";
	$result = mysqli_query($con,$query);
	if (!$result) {
  		die("Invalid query: " . mysqli_error($con));
	}

	while ($row = mysqli_fetch_assoc($result)){
		$avg_rating = $row['avg_rating'];
	}

	$query = "INSERT INTO status VALUES ( '".$cycle_id."' , '".$avg_rating."' , '".$lat."' , '".$lng."' );";
	$result = mysqli_query($con,$query);
	if (!$result) {
  		die("Invalid query: " . mysqli_error($con));
	}
}

function stopgiveride($cycle_id){
	$con = mysqli_connect("localhost","root","","epiz_23676572_gocycledata");
	if (!$con) {
	  die("Not connected : " . mysqli_error($con));
	}
	$query = "SELECT cycle_id FROM Rides WHERE cycle_id = '".$cycle_id."'";
	$temp = mysqli_query($con,$query);
	if($result -> num_rows == 0){
        $query = "DELETE FROM status WHERE cycle_id = '".$cycle_id."' ; ";
		$result = mysqli_query($con,$query);
		if (!$result) {
	  		die("Invalid query: " . mysqli_error($con));
		}
    }
}

$cycid = ($_GET['cycleid']);
$lat = $_COOKIE['lat'];
$lon = $_COOKIE['lon'];

if(get_stat($cycid)=="NO"){
	giveride($cycid,$lat,$lon);
	header("location: manage.php");
}else{
	stopgiveride($cycid);
	header("location: manage.php");
}
?>
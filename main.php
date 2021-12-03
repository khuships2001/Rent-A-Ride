<?php
// Local database connection
$con = mysqli_connect('localhost','root','',"epiz_23676572_gocycledata");

// Hosting Database connection. Uncomment for hosting
// $con = mysqli_connect('sql305.epizy.com','epiz_23682498','SpkOfRZFt',"epiz_23682498_gocycledata");

$function_name = $_GET['f'];
switch ($function_name) {
	case 'getuserdetails':
		getuserdetails($con);
		break;	
	case 'getcycledetails':
		getcycledetails($con);
		break;
	case 'updateprofile':
		updateprofile($con);
		break;
	case 'registercycle':
		registercycle($con);
		break;
	case 'filereport':
		filereport($con);
		break;
	case 'signup':
		signup($con);
		break;
	case 'locaterides':
		locaterides($con);
		break;
	case 'login':
		login($con);
		break;
	case 'getcyclelocation':
		getcyclelocation($con);
		break;
	case 'sellcycle':
		sellcycle($con);
		break;
	case 'revertsell':
		revertsell($con);
		break;
	case 'toggle':
		toggle($con);
		break;
	case 'getownerdetails':
		getownerdetails($con);
		break;
	case 'getphones':
		getphones($con);
		break; 
	case 'startride':
		startride($con);
		break;
	case 'ridekhatam':
		ridekhatam($con);
		break;
	case 'filecomplaint':
		filecomplaint($con);
		break;
	default:
		break;
}

function getuserdetails($con){
	$user = $_GET['user_id'];
	$sql = "SELECT * from users where user_id='".$user."';";
	$result = mysqli_query($con,$sql);
	$row = mysqli_fetch_assoc($result);
	echo "[".json_encode($row)."]";
}

function getcycledetails($con){
	$cycle_id = $_GET['cycle_id'];
	$sql = "SELECT * from cycles where cycle_id='".$cycle_id."';";
	$result = mysqli_query($con,$sql);
	$row = mysqli_fetch_assoc($result);
	echo "[".json_encode($row)."]";
}
function updateprofile($con){
	$owner_id = $_GET['user_id'];
	$first_name = $_GET['first_name'];
	$last_name = $_GET['last_name'];
	$rollno = $_GET['rollno'];
	$date_of_birth = $_GET['date_of_birth'];
	$email_id = $_GET['email_id'];
	$address = $_GET['address'];
	$newpassword = $_GET['newpassword'];
	$output = '[{"Success":0}]';
	$sql="";
	if($newpassword!=''){
		$sql = "UPDATE users set first_name = '".$first_name."',last_name = '".$last_name."', rollno='".$rollno."', date_of_birth = '".$date_of_birth."',email_id='".$email_id."',address='".$address."', password='".$newpassword."' where user_id='".$owner_id."';";
	}
	else{
		$sql = "UPDATE users set first_name = '".$first_name."',last_name = '".$last_name."', rollno='".$rollno."', date_of_birth = '".$date_of_birth."',email_id='".$email_id."',address='".$address."' where user_id='".$owner_id."';";
	}
	$result = mysqli_query($con,$sql);
	if(mysqli_errno($con)==0){
		$output = '[{"Success":1}]';
	}
	echo $output;
}
function registercycle($con){
	$owner_id = $_GET['user_id'];
	$model = ($_GET['model']);
	$color = ($_GET['color']);
	$hascarrier = ($_GET['hascarrier']);
	$sql = "INSERT into cycles(owner_id,model,color,iscarrier) VALUES ('".$owner_id."','".$model."','".$color."','".$hascarrier."');";
	$output = '[{"Success":0}]';
	$result = mysqli_query($con,$sql);
	if(mysqli_errno($con)==0){
		$output = '[{"Success":1}]';
	}
	echo $output;
}
function filereport($con){
	$owner_id = $_GET['user_id'];
	$subject = ($_GET['subject']);
	$description = ($_GET['description']);
	$sql="INSERT into reports(owner_id,subject,description) VALUES ('".$owner_id."','".$subject."','".$description."');";
	$output = '{"Success":0}';
	$result = mysqli_query($con,$sql);
	if(mysqli_errno($con)==0){
		$output = '[{"Success":1}]';
	}
	echo $output;
}

function initializawallet($con,$user_id){
	$query = "INSERT INTO wallet VALUES ('".$user_id."',100,0);";
	$result = mysqli_query($con,$query);
}

function signup($con){
	$username = ($_GET['username']);
	$password = ($_GET['password']);
	$email = ($_GET['email']);
	$sql = "INSERT into users(user_id,password,email_id) VALUES ('".$username."','".$password."','".$email."');";
	$output = '[{"Success":0}]';
	$result = mysqli_query($con,$sql);
	if(mysqli_errno($con)==0){
		initializawallet($con,$username);
		$output = '[{"Success":1}]';
	}
	echo $output;
}

function locaterides($con){
	$dom = new DOMDocument("1.0");
	$node = $dom->createElement("markers");
	$parnode = $dom->appendChild($node);
	$center_lat = $_GET["lat"];
	$center_lng = $_GET["lng"];
	$radius = $_GET["radius"];
	$query = sprintf("SELECT cycle_id, lat, lng, ( 3959 * acos( cos( radians('%s') ) * cos( radians( lat ) ) * cos( radians( lng ) - radians('%s') ) + sin( radians('%s') ) * sin( radians( lat ) ) ) ) AS distance FROM Status HAVING distance < '%s' ORDER BY distance LIMIT 0 , 20",
	  mysqli_real_escape_string($con,$center_lat),
	  mysqli_real_escape_string($con,$center_lng),
	  mysqli_real_escape_string($con,$center_lat),
	  mysqli_real_escape_string($con,$radius));
	$result = mysqli_query($con,$query);
	header("Content-type:text/xml");
	while ($row = @mysqli_fetch_assoc($result)){
	  $cycle_id = $row['cycle_id'];
	  $query1 = "SELECT user_id,first_name,last_name FROM Cycles,Users WHERE Users.user_id = Cycles.owner_id AND cycle_id = '".$cycle_id."' ; ";
	  $result1 = mysqli_query($con,$query1);
	  while($row1 = mysqli_fetch_assoc($result1)){
	    $user_id = $row1['user_id'];
	    $first_name = $row1['first_name'];
	    $last_name = $row1['last_name'];
	  }
	  $node = $dom->createElement("Status");
	  $newnode = $parnode->appendChild($node);
	  $newnode->setAttribute("cycle_id", $row['cycle_id']);
	  $newnode->setAttribute("lat", $row['lat']);
	  $newnode->setAttribute("lng", $row['lng']);
	  $newnode->setAttribute("distance", $row['distance']);
	  $newnode->setAttribute("user_id", $user_id);
	  $newnode->setAttribute("name" , $first_name." ".$last_name);
	}
	echo $dom->saveXML();
}
function login($con){
	$username = ($_GET['username']);
	$password = ($_GET['password']);
	$sql = "SELECT * from users where user_id='".$username."' and password='".$password."';";
	$output = '[{"Success":0}]';
	$result = mysqli_query($con,$sql);
	$no_of_rows = mysqli_num_rows($result);
	echo mysqli_error($con);
	if(mysqli_errno($con)==0&&$no_of_rows==1){
		$output = '[{"Success":1}]';
	}
	echo $output;
}
function getcyclelocation($con){
	$cycle_id = ($_GET['cycle_id']);
	$sql = "SELECT lat,lng from status where cycle_id='".$cycle_id."';";
	$result = mysqli_query($con,$sql);
	$row = mysqli_fetch_assoc($result);
	echo "[".json_encode($row)."]";
}

function sellcycle($con){
	$cycle_id = $_GET['cycle_id'];
	$price = ($_GET['price']);
	$date = date("Y/m/d");
	$sql = "INSERT into bidding VALUES ('".$cycle_id."','".$price."', DATE '".$date."');";
	$output = '{"Success":0}';
	$result = mysqli_query($con,$sql);
	if(mysqli_errno($con)==0){
		$output = '{"Success":1}';
		header('location: sellcycle.php');
	}
	echo $output;
}

function revertsell($con){
	$cycle_id = $_GET['cycle_id'];
	$sql = "DELETE FROM bidding WHERE cycle_id = '".$cycle_id."' ; ";
	$output = '{"Success":0}';
	$result = mysqli_query($con,$sql);
	if(mysqli_errno($con)==0){
		$output = '{"Success":1}';
		header('location: sellcycle.php');
	}
	echo $output;
}

function get_stat($cycle_id,$con){
    $sql = "SELECT * FROM status WHERE cycle_id='".$cycle_id."';";
    $result = mysqli_query($con,$sql);
    if($result -> num_rows > 0){
        return "YES";
    }else{
        return "NO";
    }
}

function giveride($cycle_id , $lat , $lng,$con){
	$query = "SELECT sum(rating)/COUNT(*) AS avg_rating FROM history inner join feedback WHERE cycle_id = '".$cycle_id."' ; ";
	$result = mysqli_query($con,$query);
	while ($row = mysqli_fetch_assoc($result)){
		$avg_rating = $row['avg_rating'];
	}

	$query = "INSERT INTO status VALUES ( '".$cycle_id."' , '".$avg_rating."' , '".$lat."' , '".$lng."' );";
	$result = mysqli_query($con,$query);
}
function stopgiveride($cycle_id,$con){
	$query = "SELECT cycle_id FROM Rides WHERE cycle_id = '".$cycle_id."'";
	$temp = mysqli_query($con,$query);
	if($result -> num_rows == 0){
        $query = "DELETE FROM status WHERE cycle_id = '".$cycle_id."' ; ";
		$result = mysqli_query($con,$query);
    }
}

function toggle($con){
	$cycid = ($_GET['cycleid']);
	$lat = $_COOKIE['lat'];
	$lon = $_COOKIE['lon'];
	if(get_stat($cycid,$con)=="NO"){
		giveride($cycid,$lat,$lon,$con);
		header("location: manage.php");
	}else{
		stopgiveride($cycid,$con);
		header("location: manage.php");
	}
}

function getownerdetails($con){
	$cycle_id = $_GET['cycle_id'];
	$sql = "SELECT * from cycles,users where cycle_id='".$cycle_id."' and users.user_id=cycles.owner_id;";
	$result = mysqli_query($con,$sql);
	echo mysqli_error($con);
	$row = mysqli_fetch_assoc($result);
	echo "[".json_encode($row)."]";
}
function getphones($con){
	$cycle_id = $_GET['cycle_id'];
	$sql = "SELECT phone_no from phone where user_id = (select owner_id from cycles where cycle_id='".$cycle_id."');";
	$result = mysqli_query($con,$sql);
	$arr = array();
	while($row = mysqli_fetch_assoc($result))
		array_push($arr,$row);
	echo json_encode($arr);
}

function startride($con){
	$cycle_id = $_GET['cycle_id'];
	$user_id = $_GET['user_id'];
	date_default_timezone_set('Asia/Calcutta');
	$da = date('Y-m-d H:i:s');
	$output = '{"Success":0}';
	$sql = "INSERT into rides VALUES ('".$user_id."','".$cycle_id."','".$da."');";
	$result = mysqli_query($con,$sql);
	if(mysqli_errno($con)==0){
		$output = '{"Success":1}';
	}
	echo "[".$output."]";
}

function wallet($con){	
	$query = "SELECT * FROM transaction ORDER BY transaction_id DESC LIMIT 0,1";
	$result = mysqli_query($con,$query);
	while ($row = mysqli_fetch_assoc($result)){
		$transaction_id = $row['transaction_id'];
		$sender = $row['sender_id'];
		$receiver = $row['receiver_id'];
		$amount = $row['amount'];
	}
	$query = "UPDATE wallet SET balance = balance + '".$amount."' , last_transaction = '".$transaction_id."' WHERE user_id = '".$receiver."' ; ";
	$result1 = mysqli_query($con,$query);
	$query = "UPDATE wallet SET balance = balance - '" .$amount. "', last_transaction = '" .$transaction_id. "' WHERE user_id = '".$sender."' ; ";
	$result1 = mysqli_query($con,$query);
}

function endride($user_id , $cycle_id , $end_time , $start_time,$con){
	$query = "DELETE FROM rides WHERE user_id = '".$user_id."' AND cycle_id =  '".$cycle_id."' and start_time= '".$start_time."';";
	$start_time =  strtotime($start_time);
	$end_time = $end_time->getTimestamp();
	$result = mysqli_query($con,$query);
	$diffInSeconds = $end_time-$start_time;
    $cost =  $diffInSeconds/120;
	$query1 = "INSERT INTO history (user_id,cycle_id,start_time,end_time,cost) VALUES ('".$user_id."', '".$cycle_id."' , '".$start_time."' , '".$end_time."' , '".$cost."' ) ; ";
	$result = mysqli_query($con,$query);
	$query = "SELECT owner_id FROM cycles WHERE cycle_id = '".$cycle_id."' ; ";
	$result = mysqli_query($con,$query);
	while ($row = mysqli_fetch_assoc($result)){
		$owner = $row['owner_id'];	
	}
	$a=date("Y-m-d H:i:s");
	$query = "INSERT INTO transaction(sender_id,receiver_id,date_time,amount) VALUES ('".$user_id."', '".$owner."' , '".$a."' , '".$cost."' ) ; ";
	$result = mysqli_query($con,$query);
	wallet($con);
	$balance = 0;
	$query = "SELECT balance FROM wallet WHERE user_id = '".$user_id."';";
	$result = mysqli_query($con,$query);
	while ($row = mysqli_fetch_assoc($result)){
		$balance = $row['balance'];
	}
	$arr = array();
	array_push($arr,$cost);
	array_push($arr,$cost *2);
	array_push($arr,$balance);
	echo json_encode($arr);
	echo mysqli_error($con);
}
function ridekhatam($con){
	date_default_timezone_set('Asia/Calcutta');
	$user_id = $_GET['user_id'];
	$cycle_id = $_GET['cycle_id'];
	$end_time = new DateTime();
	$query = "SELECT * FROM rides WHERE user_id = '".$user_id."' AND cycle_id = '".$cycle_id."' ORDER BY start_time; ";
	$result = mysqli_query($con,$query);
	$row = mysqli_fetch_assoc($result);
	endride($user_id , $cycle_id , $end_time , $row['start_time'],$con);
}

function filecomplaint($con){
	$user_id = $_GET['user_id'];
	$subject = $_GET['subject'];
	$desc = $_GET['desc'];
	$query = "INSERT into report values ('".$user_id."','".$subject."','".$desc."');";
	$result = mysqli_query($con,$query);
	echo '[{"Success":1}]';
}

?>
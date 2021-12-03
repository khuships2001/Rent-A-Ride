<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<style>
.card {
  box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2);
  max-width: 300px;
  max-height: 300px;
  margin: auto;
  text-align: center;
  font-family: arial;
}

.title {
  color: grey;
  font-size: 18px;
}

button {
  border: none;
  outline: 0;
  display: inline-block;
  padding: 8px;
  color: white;
  background-color: #000;
  text-align: center;
  cursor: pointer;
  width: 100%;
  font-size: 18px;
}

a {
  text-decoration: none;
  font-size: 22px;
  color: black;
}

button:hover, a:hover {
  opacity: 0.7;
}
</style>
</head>
<body>

<h2 style="text-align:center">Seller Details</h2>

<div class="card">
  <img src="/img/img1.jpg" alt="Photo Not Uploaded" style="width:100%">
  <?php

  function getowneraddress($con){
    $cycle_id = $_GET['cycleid'];
    $sql = "SELECT * from cycles natural join users where cycle_id='".$cycle_id."';";
    $result = mysqli_query($con,$sql);
    $row = mysqli_fetch_assoc($result);
    return $row['address'];
  }

  function getphones($con){
    $cycle_id = $_GET['cycleid'];
    $sql = "SELECT phone_no from phone where user_id = (select owner_id from cycles where cycle_id='".$cycle_id."');";
    $result = mysqli_query($con,$sql);
    $arr = "";
    while($row = mysqli_fetch_assoc($result))
      $arr=$arr." ".$row['phone_no'];
    return $arr;
  }

  $con = mysqli_connect("localhost","root","","epiz_23676572_gocycledata");
  echo "<h1>".$_GET['own_name']."</h1>
  <p class='title'>".getowneraddress($con)."</p>
  <p>".(string)getphones($con)."</p>
  "?>
  <p><a href="menu.html"><button>Thank You for the transaction</button></a></p>"
</div>
</body>
</html>

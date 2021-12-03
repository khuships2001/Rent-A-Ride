<!DOCTYPE html>
<html lang="en">

<head>
    <link href="css/manage.css" rel="stylesheet">
    <h2>Manage your cycles</h2>
    <script src="http://code.jquery.com/jquery-1.11.0.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery-cookie/1.4.1/jquery.cookie.min.js"></script>

    <table class="table table-dark">
        <thead>
            <tr>
                <th scope="col">Cycle-id</th>
                <th scope="col">Model</th>
                <th scope="col">Color</th>
                <th scope="col">Carrier</th>
                <th scope="col">Available</th>
                <th scope="col">Toggle</th>
                <th scope="col">Remove Cycle</th>
            </tr>
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
            $conn = mysqli_connect("localhost","root","","epiz_23676572_gocycledata");
            $owner_id = $_COOKIE['user_id'];
            $sql = "SELECT * FROM cycles WHERE owner_id='".$owner_id."';";
            $result = mysqli_query($conn,$sql);
            if($result -> num_rows > 0 ){
                while($row=$result -> fetch_assoc()){
                    echo "<tr><td>".$row["cycle_id"]."</td><td>".$row["model"]."</td><td>".$row["color"]."</td><td>".$row["iscarrier"]."</td><td>".get_stat($row["cycle_id"])."</td>

                    <td><form action='togglephp.php'>
                        <input type='hidden'  value='toggle' name='f'>
                        <input type='hidden'  value=".$row["cycle_id"]." name='cycleid' required>
                        <input type='submit' value='Toggle'>
                    </form></td>

                    <td><form action='removecycle.php'>
                        <input type='hidden'  value=".$row["cycle_id"]." name='cycid' required>
                        <input type='submit' value='Remove'>
                    </form></td>";
                }
            }
            ?>
        </thead>
    </table>
    <a href="menu.html" >Go Back</a>

	<p id="demo"></p>

	<script>

	var x = document.getElementById("demo");

	console.log("Hello World");
	if (navigator.geolocation) {
	    navigator.geolocation.getCurrentPosition(redirectToPosition);
	} else { 
	    x.innerHTML = "Geolocation is not supported by this browser.";
	}

	function redirectToPosition(position) {
        $.cookie("lat",position.coords.latitude);
        $.cookie("lon",position.coords.longitude);
		// window.location='updloc.php?lat='+position.coords.latitude+'&long='+position.coords.longitude;
	}
	</script>

    </body>

</html>
<!DOCTYPE html>
<html lang="en">

<head>
    <link href="css/manage.css" rel="stylesheet">
    <h2>Sell Cycle</h2>
    <table class="table table-dark">
        <thead>
            <tr>
                <th scope="col">Cycle-id</th>
                <th scope="col">Model</th>
                <th scope="col">Color</th>
                <th scope="col">Carrier</th>
                <th scope="col">Available for Sell</th>
                <th scope="col">Price</th>
                 <th scope="col">Operation</th>
            </tr>
            <?php
            function get_stat($cycle_id){
                $con = mysqli_connect("localhost","root","","epiz_23676572_gocycledata");
                $sql = "SELECT * FROM bidding WHERE cycle_id='".$cycle_id."';";
                $result = mysqli_query($con,$sql);
                if($result -> num_rows > 0){
                    return "YES";
                }else{
                    return "NO";
                }
            }
            function get_cost($cycle_id){
                $con = mysqli_connect("localhost","root","","epiz_23676572_gocycledata");
                $sql = "SELECT * FROM bidding WHERE cycle_id='".$cycle_id."';";
                $result = mysqli_query($con,$sql);
                while($row=$result -> fetch_assoc()){
                    return $row["price"];
                }
                return 0;
            }
            $conn = mysqli_connect("localhost","root","","epiz_23676572_gocycledata");
            $owner_id = $_COOKIE['user_id'];
            $sql = "SELECT * FROM cycles WHERE owner_id='".$owner_id."';";
            $result = mysqli_query($conn,$sql);
            if($result -> num_rows > 0 ){
                while($row=$result -> fetch_assoc()){
                    echo "<tr><td>".$row["cycle_id"]."</td><td>".$row["model"]."</td><td>".$row["color"]."</td><td>".$row["iscarrier"]."</td><td>".get_stat($row["cycle_id"])."</td>";
                    if(get_stat($row["cycle_id"])=="NO"){
                      echo "<form action='sellcyclephp.php'><td>
                        <input type='hidden'  value=".$row["cycle_id"]." name='cycle_id' required>
                        <input type='text' name='price' placeholder='0'>
                      </td>
                      <td>
                          <input type='submit' value='Sell'>
                      </td></form>";
                    }else{
                      echo "<td>
                        <input type='text' name='priceset' value=".get_cost($row["cycle_id"]).">
                      </td>
                      <td>
                      <form action='revertsellcycle.php'>
                          <input type='hidden'  value=".$row["cycle_id"]." name='cycle_id' required>
                          <input type='submit' value='Cancel'></form>
                      </td>";
                    }
                }
            }
            ?>
        </thead>
    </table>
    <a href="menu.html">Go Back</a>
    </body>
</html>
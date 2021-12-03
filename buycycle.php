<!DOCTYPE html>
<html lang="en">
<head>
    <link href="css/manage.css" rel="stylesheet">
    <h2>Manage your cycles</h2>
    <table class="table table-dark">
        <thead>
            <tr>
                <th scope="col">Cycle ID</th>
                <th scope="col">Price</th>
                <th scope="col">Posting Date</th>
                <th scope="col">Buy</th>
            </tr>
            <?php
            $conn = mysqli_connect("localhost","root","","epiz_23676572_gocycledata");
            $owner_id = $_COOKIE['user_id'];
            $sql = "SELECT * FROM bidding WHERE cycle_id NOT IN (SELECT cycle_id FROM cycles WHERE owner_id = '".$owner_id."') ORDER BY price";
            $result = mysqli_query($conn,$sql);
            if(mysqli_num_rows($result) > 0 ){
                while($row=$result -> fetch_assoc()){
                    echo "<tr><td>".$row["cycle_id"]."</td><td>".$row["price"]."</td><td>".$row["posting_date"]."</td>
                    <td><form action='details.php'>
                        <input type='hidden'  value=".$row["cycle_id"]." name='cycleid' required>
                        <input type='submit' value='Buy'>
                    </form></td>";
                }
            }
            ?>
        </thead>
    </table>
    </body>

</html>
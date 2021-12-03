<!doctype html>
<html lang="en">

<head>
    <link href="css/details.css" rel="stylesheet">
<body background="img/slider/pic3.jpg">
    <div class="container">
      <form action="finalDet.php">
            <h1>Details</h1>
            <?php
            $con = mysqli_connect("localhost","root","","epiz_23676572_gocycledata");
            $cycle_id = $_GET['cycleid'];
            $sql = "SELECT  first_name, last_name, model, color, iscarrier FROM users,cycles WHERE cycles.owner_id = users.user_id AND cycles.cycle_id = '".$cycle_id."' ; ";
            $output = '{"Success":0}';
            $result = mysqli_query($con,$sql);
            if(mysqli_errno($con)==0){
                $output = '{"Success":1}';
            }
            echo mysqli_error($con);
            while ($row = mysqli_fetch_assoc($result)){
                
                echo "
                <input type='hidden' id='cycid' name='cycleid' value='".$cycle_id."'>

                <label for='name'><i class='fa fa-envelope'></i> Seller</label>
                <input type='text' id='name' name='own_name' value='".$row['first_name']." ".$row['last_name']."'>

                <label for='adr'><i class='fa fa-address-card-o'></i> Model</label>
                <input type='text' id='model' name='model' value='".$row['model']."'>

                <label for='name><i class='fa fa-envelope'></i> Color</label>
                <input type='text' id='color' name='color' value='".$row['color']."'>

                <label for='city'><i class='fa fa-institution'></i> Carrier</label>
                <input type='text' id='carrier' name='carrier' value='".($row['iscarrier']!=0?"YES":"NO")."'>

                <input type='submit' value='Continue to checkout' class='btn'>";
            }
            ?>
      </form>
</div>
    </body>

</html>


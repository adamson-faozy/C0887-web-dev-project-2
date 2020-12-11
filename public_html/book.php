<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php
    echo '';
    ?>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="mystyles.css">
    <title>Booking</title>

</head>

<body>
    <div class="blogo">
        <img class="clogo" src="THE COSIMO THEATRES.png" width="30px" height="100px" alt="company logo">
        <h1 id="cname"> The Cosimo</h1>
    </div>

    <hr class="divide1">

    <div class ="content">
    <div class =intro>
        <script type="text/javascript">
        price = [];
    function tot() {
        var total = 0;
        for (i = 0; i < price.length; i++) { // iterate through price value array
            var intprice = parseInt(price[i]) // convert price values from string to int
            var total = total + intprice; // sum up total price values present in price array
            document.getElementById('totprice').innerHTML = total; // show the user total price
        }
    }
    </script>
    <?php
        echo "<h3> Hello  ". $_SESSION['name']. "  ".
         "Thank you for booking with us. </h3>";
            echo "<h3>Enjoy our world class performance theatres, get an experience of a lifetime.</h3>";
         echo "<h3>Booking summary for</h3><br><h2>  ". $_SESSION['ptitle'] . "  on  " . $_SESSION['pdate']   ."  " . $_SESSION['ptime'].": </h4>";
        
    ?>
    
    
    </div>
    <?php
    // pull session variables
    $email = $_SESSION['email'];
    $pdate = $_SESSION['pdate'];
    $ptime = $_SESSION['ptime'];
    $ptitle =  $_SESSION['ptitle'];
    $pprice = $_SESSION['pprice'];

    require('DBconnect.php');

    try {
        foreach ($_POST as $key => $value) {

            $sql = "INSERT INTO Booking
 VALUES (:email, :pdate, :ptime,:keys );";
            $handle = $conn->prepare($sql);
            $handle->execute(array(":email" => $email, ":pdate" => $pdate, ":ptime" => $ptime, ":keys" => $key));
        }
        $conn = NULL;
        $total = 0;
        // show seat booked summary
        foreach ($_POST as $key => $value) {
            echo "<ul>";
            echo "<li>" . "Seat " . $key . " Sucessfully booked for:  " . $pdate ."  ". $ptime . ". </li>";
            echo "</ul>";
            $total += $value;  
        }
        echo "<h3> Total Paid =  " . number_format($total, 2) . "</h3>";

        echo "<p> Thank you for booking with us. </p>";
    } catch (PDOException $e) {
        echo "PDOException: " . $e->getMessage();
    }



    ?>

    </div>
</body>
<?php?>
</html>
<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="mystyles.css">
    <title>Seats</title>

</head>


<body class="odd">
    <div class="simg">
        <img class="clogo" src="THE COSIMO THEATRES.png" width="30px" height="100px" alt="company logo">
        <h1 id="cname"> The Cosimo</h1>
    </div>
    <hr class="divide1">

    <div class="content">
        <script type="text/javascript">

        </script>

        <?php
        // creat an array to save the seats for the selected performances
        $seats = array();

        // storring data recieved from performance page for easy access
        $_SESSION['parray'] = $_POST;
        $_SESSION['pdate'] = $pdate = $_POST['perfdate'];
        $_SESSION['ptime']  = $ptime = $_POST['perftime'];
        $_SESSION['ptitle'] =  $ptitle = $_POST['ptitle'];
        $_SESSION['pprice'] = $pprice = $_POST['tckprice'];

        echo " <div id = 'intro'><h3> AVAILABLE SEATS FOR PERFORMANCE  " . $ptitle . " on  " . $pdate . "</h3>";
        echo "<h4> Your Booking Summary
            <ul>
            <li id = \"seatsum\" padding = 5px; ></li>
            <li class =\"b\" padding = 5px; ><strong>Total price:</strong> <span id = \"totprice\"> </span></li><br>

            <form action= 'book.php' method='post' id= 'form1' onsubmit = 'valseat()'>
                                    <input type='submit' value='Book Seats'  >
                                    
                                    </form>
            </ul>
            <h5 id = 'val'></h5>
    </h4></div>";
        require('DBconnect.php');
        try {
            $sql = 'SELECT Seat.RowNumber, ROUND(Zone.PriceMultiplier * :bprice,2) AS SeatPrice
                FROM Seat JOIN Zone ON Zone.Name=Seat.Zone
    WHERE Seat.RowNumber NOT IN
    (SELECT Booking.RowNumber FROM Booking
    WHERE Booking.PerfTime= :ptime
    AND Booking.PerfDate= :pdate)';
            $handle = $conn->prepare($sql);
            $handle->execute(array(":pdate" => $pdate, ":ptime" => $ptime, ":bprice" => $pprice));
            $seatsT = $handle->fetchAll();
            $conn = NULL;


            echo "<div class = \"seattable\" >";

            echo "<table class ='seats'>
                    <thead>
                            <tr colspan='2' rowspan = '2'>
                                <th colspan='3' rowspan = '2'> Seats: </th>
                                 <th colspan='3' rowspan = '2'> Price: </th>
                                 <th colspan='3' rowspan='2'></th>
                                 </tr>
                                 </thead>
                                    <tbody>";
            echo "<script type = text/javascript>
            // create seat and price array for summary functionality
                seat = [\"Seats:  \"];
                price = [];

        </script>";

            foreach ($seatsT as $seat) {

                $seatRow = $seat['RowNumber'];
                $seatPrice = $seat['SeatPrice'];

                array_push($seats, $seatRow);
                echo "<tr>" .
                    "<td colspan='2'>" . $seat['RowNumber'] . "<td>" .
                    "<td colspan='2'>" . $seat['SeatPrice'] . "<td>" . "<td>" .
                    "<div class='seats'>
                    <form  form = 'form1' >
                    <input id = 'check' form = 'form1' type = 'checkbox' name='$seatRow' value='$seatPrice'
                     onchange=' 
                    
                    if(this.checked){// ensure the user selected the seat
                         seat.push(this.name); // add selected seat to seat array
                         seatr(); 
                  }else{// remove unselected seats
                        var rms = seat.indexOf(this.name);
                        if(rms > -1){ seat.splice(rms, 1);
                        seatr();}
                    };
                  if(this.checked){
                         price.push(this.value); //add selected price to array
                    tot();//seee script tag below
                 }
                    else{// remove unselected prices
                        var rm = price.indexOf(this.value);
                        if(rm > -1){ price.splice(rm, 1);
                        var total = total - parseInt(this.value);// if a seat is unsulected subtract the price fom previous total value
                        tot();
                      }
                    };  '>
                    </form>
                </div>" . "<td>"
                    .
                    "</tr>";
            }
            echo "</tbody>
                        </table>";
            echo "</div>";
        } catch (PDOException $e) {
            echo "PDOException: " . $e->getMessage();
        }
        echo ''
        ?>
        <?php $_SESSION['seats'] = $seats; ?>

    </div>

</body>
<script type="text/javascript">
    if (seat.length == 1) {
        document.getElementById('totprice').innerHTML = "........";
    }
    /**  for (i = 0; i < seat.length; i++) {
    //    if (this.name == seat[i]){
        alert(duplicate value)

    }
  //  }*/

    // takes each individual seat price converts them to an integer and adds all selected seat prices
    function tot() {
        var total = 0;
        for (i = 0; i < price.length; i++) { // iterate through price value array
            var intprice = parseInt(price[i]) // convert price values from string to int
            var total = total + intprice; // sum up total price values present in price array
            document.getElementById('totprice').innerHTML = total.toFixed(2); // show the user total price
        }
    }

    function valseat() {

        if (seat.length > 1) {
            this.submit();
        } else {
            event.preventDefault();
            alert('Please Select a Seat!');
            document.getElementById('val') = 'Please Select a seat!';
        }

    }
    /** print out seats selected to user.
    function seatr() {
        document.getElementById('seatsum').innerHTML = seat.join("-");
        } */
</script>

<?php
echo " <script>
    
    function  tot(){
    var total = 0;
    for(i=0; i< price.length; i++){
        var intprice = parseInt(price[i])
         var total = total + intprice;
        document.getElementById('totprice').innerHTML = total.toFixed(2);
    }
}

// print out seats selected to user.
function seatr(){
document.getElementById('seatsum').innerHTML = seat.join(\"-\");
}    
</script>";
?>

</html>
<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="mystyles.css">
    <title>Cosimo's Theatre</title>

    <?php require('DBconnect.php'); ?>
</head>

<body>
    <div class="header">
        <img class="clogo" src="THE COSIMO THEATRES.png" width="30px" height="100px" alt="company logo">
        <h1 id="cname"> The Cosimo</h1>
    </div>
    <hr class="divide1">

    <div class ="content">
    
    <?php
    /// test input for validation.
    function testdata($data)
    {
        $data = stripslashes($data);
        $data = trim($data);         
        $data = htmlspecialchars($data);
        return $data;
    };

    // if a session is already started by the same user then welcome them back
    echo "<div id = 'intro'>";
    if (isset($_SESSION['name']) && $_SESSION['name'] == $_POST['name'] && $_SESSION['email'] == $_POST['email']) {
        echo '<h2> Welcome back    ' . '<strong><em>' . $_SESSION['name'] . '</em></strong>' . '</h2>';
        echo "<h3>Here are a selection of our performances</h3>";
        echo "<h3>Click on the \"Show Availabilty \" button to veiw available seats for a performance</h3> ";
    } else {
        //if a session hasn't been started or if it's a different user ovewrite previous session data
        $_SESSION['name'] = testdata($_POST['name']);
        $_SESSION['email'] = $_POST['email'];
        echo '<h2> Welcome     ' . '<strong><em>' . $_SESSION['name'] . '</em></strong>' . '</h2>';
        echo "<h3>Here are a selection of our performances</h3>";
        echo "<h3>Click on the \"Show Availabilty \" button to veiw our available seats for a performance</h3> ";
    }
    echo "</div>";

    require('DBconnect.php'); // php file containing database connection code
    try {
        $sql = 'SELECT * FROM Performance p JOIN Production r
    ON p.Title=r.title';
        $handle = $conn->prepare($sql);
        $handle->execute();
        // res stores the performance table from sql
        $res = $handle->fetchAll();
        $conn = NULL; // close connection

        // store the performance data in a session variable incase if needed i.e post breaks down some data can still be rerieved
        $_SESSION['res'] = array();
        foreach ($res as $row) {
            array_push($_SESSION['res'], $row);
        }
        // create table to display performances to user
        echo "<table class='perftable'>";
        echo "<thead>
        <tr class='tabletitle'>
            <th colspan='2'>Title:</th>
            <th colspan='2'>Date:</th>
            <th colspan='2'>Time:</th>
            <th colspan='2'>   </th>
        </tr>
    </thead>";
        echo "<tbody>
            ";
        // looping through each performance data stored in res to create the table
        foreach ($res as $row) {
            $pdate = $row['PerfDate']; // performance date
            $ptime = $row['PerfTime']; // performance time
            $ptitle = $row['Title']; // performance  title
            $pprice = $row['BasicTicketPrice']; // basic ticket price for each performance.
            echo "<tr>" . "<td>" . $row['Title'] . "<td>" .
                "<td>" . $row['PerfDate'] . "<td>" .
                "<td>" . $row['PerfTime'] . "<td>" .
                "<td>" . "<div class='seatform'><form action= \"<?php htmlspecialchars(seats.php)?>\" method = \"post\"><input formaction=\"seats.php\" id=\"availability\"  type = \"submit\" value='Show Availability'>
            <input type='hidden' name='perfdate' value='$pdate'>
            <input type='hidden' name='perftime' value='$ptime'>
            <input type='hidden' name='ptitle' value='$ptitle'>
            <input type='hidden' name='tckprice' value='$pprice'>
            </form></div>" . "<td>"
                . "</tr>";
        }
        echo "
        </tbody>
        </table>";
    } catch (PDOException $e) {
        echo "PDOException: " . $e->getMessage();
    }
    ?>
    </div>
</body>

</html>
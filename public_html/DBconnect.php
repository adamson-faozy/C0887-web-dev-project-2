<?php
// connect to sql data base
$host = 'dragon.ukc.ac.uk';
$dbname = 'oa408';
$user = 'oa408';
$pwd = 'ortuil_';
try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $user, $pwd);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    if ($conn) {
        return $conn;
    } else {
        echo 'Failed to connect';
    }
} catch (PDOException $e) {
    echo "PDOException: " . $e->getMessage(); }
    ?>
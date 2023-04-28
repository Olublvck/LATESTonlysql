<?php
require_once('config.php');

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die($conn->connect_error);
} else {
    //echo "database connected";
}
?>
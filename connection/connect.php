<?php
$con = mysqli_connect('localhost', 'root', '', 'ioplantcare');

if (!$con) {
    die('Database connection failed: ' . mysqli_connect_error());
}
?>

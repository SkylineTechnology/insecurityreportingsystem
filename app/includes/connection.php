<?php

$sitename = "Insecurity Reporting System";
$conn = mysqli_connect("localhost", "root", "", "insecurityreportingsys_db");
if (!$conn) {
    die(mysqli_error($conn) . "Error connecting Database!");
}
?>
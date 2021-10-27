<?php 

    /** DATABASE SETUP **/
include("credentials.php"); // define variables

/** SETUP **/
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
$mysqli = new mysqli($host, $username, $password, $dbname);
// $db = new mysql("localhost", "root", "", "dbname"); // XAMPP Settings
$user = null;

session_start();

    if(isset($_GET["petID"])){
        $insert = $mysqli->prepare("delete from pet2user where petID = ? AND userID = ?");
        $insert->bind_param("ii", $_GET["petID"], $_SESSION["userID"]);
        if (!$insert->execute()) {
            $error_msg = "Error adding pet to pet2user";
        } 
        header("Location: profile.php");
    }

?>
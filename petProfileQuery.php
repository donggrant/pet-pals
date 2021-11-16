<?php
/** DATABASE SETUP **/
include("credentials.php"); // define variables

/** SETUP **/
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
$mysqli = new mysqli($host, $username, $password, $dbname);
// $db = new mysql("localhost", "root", "", "dbname"); // XAMPP Settings
$user = null;

session_start();
// Example using URL rewriting: we add the user information
// directly to the URI with a query string (GET parameters)

// Deal with the current session 
if(isset($_POST["petID"])) {
    $stmt = $mysqli->prepare("SELECT * FROM pets WHERE petID = ?;");
    $stmt->bind_param("i", $_POST["petID"]);
    if (!$stmt->execute()) {
        $error_msg = "Error finding favorite pets for user";
    } 
    $res = $stmt->get_result();
    $pets = $res->fetch_all(MYSQLI_ASSOC);

    echo json_encode($pets); 
    
} else {
    echo "";
}

?> 



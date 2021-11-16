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
if (isset($_SESSION["userID"])) { // validate the email coming in 
    $stmt = $mysqli->prepare("SELECT * FROM pets NATURAL JOIN pet2user WHERE userID = ?;");
    $stmt->bind_param("i", $_SESSION["userID"]);
    if (!$stmt->execute()) {
        $error_msg = "Error finding favorite pets for user";
    } 
    $res = $stmt->get_result();
    $pets = $res->fetch_all(MYSQLI_ASSOC);
    echo json_encode($pets); 
} else {
    // User did not supply email GET parameter, so send them
    // to the login page
    header("Location: index.html");
    exit();
}

?> 



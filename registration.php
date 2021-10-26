<?php
/** DATABASE SETUP **/
include("credentials.php"); // define variables

/** SETUP **/
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
$mysqli = new mysqli($host, $username, $password, $dbname); 
// $db = new mysql("localhost", "root", "", "dbname"); // XAMPP Settings 
$error_msg = "";

session_start();

// Check if the user submitted the form (the form in the HTML below
// submits back to this page, which is okay for now.  We will check for
// form data and determine whether to re-show this form with a message
// or to redirect the user to the trivia game.
if (isset($_POST["email"])) { // validate the email coming in
    $stmt = $mysqli->prepare("SELECT * FROM users natural join adopter WHERE email = ?;");
    $stmt->bind_param("s", $_POST["email"]);
    if (!$stmt->execute()) {
        $error_msg = "Error checking for user";
    } else { 
        // result succeeded
        $res = $stmt->get_result();
        $data = $res->fetch_all(MYSQLI_ASSOC);
        $hash = password_hash($_POST["password"], PASSWORD_DEFAULT);

        if (empty($data)) { 
            // User was not found.  For our game, we'll just insert them!
            $insert = $mysqli->prepare("insert into users (name, email, password) values (?, ?, ?);");
            $insert->bind_param("sss", $_POST["name"], $_POST["email"], $hash);
            if (!$insert->execute()) {
                $error_msg = "Error creating new user";
            } 

            // insert into adopter's profile
            $insert = $mysqli->prepare("insert into adopter (aboutMe, hobbies, habits, userID) values (?, ?, ?, (SELECT userID FROM users WHERE users.email = ?));");
            $insert->bind_param("ssss", $_POST["aboutMe"], $_POST["hobbies"], $_POST["habits"], $_POST["email"]);
            if (!$insert->execute()) {
                $error_msg = "Error creating new user";
            } 
        } else if(!password_verify($_POST["password"], $data[0]["password"])) {
            header("Location: signIn.php");
            exit();
        }

        $stmt = $mysqli->prepare("SELECT * FROM users natural join adopter WHERE email = ?;");
        $stmt->bind_param("s", $_POST["email"]);
        if (!$stmt->execute()) {
            $error_msg = "Error checking for user";
        } else { 
            $res = $stmt->get_result();
            $data = $res->fetch_all(MYSQLI_ASSOC);
            // Send them to the game (with a GET parameter containing their email)   
            $_SESSION["name"] = $data[0]["name"];
            $_SESSION["email"] = $data[0]["email"];
            $_SESSION["aboutMe"] = $data[0]["aboutMe"];  
            $_SESSION["hobbies"] = $data[0]["hobbies"];
            $_SESSION["habits"] = $data[0]["habits"];
            header("Location: profile.php");
            exit();
        }
    }

}  

?>

<!DOCTYPE html>
<html lang="en">
	<head>
		<title>Pet Pals - Registration</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" 
        rel="stylesheet" integrity="sha384-F3w7mX95PdgyTmZZMECAngseQB83DfGTowi0iMjiWaeVhAn4FJkqJByhZMI3AhiU" 
        crossorigin="anonymous">
		<link rel="stylesheet" href="styles/main.css">
        <link rel="stylesheet" href="styles/registration.css">

		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1"> 

		<meta name="author" content="Grant Dong and Hunter Vaccaro">
		<meta name="description" content="pet matching application">
		<meta name="keywords" content="pet pals animals matching adopting">   
    
	</head>  
	<body class="registration">
		<header>
			<nav>
                <img src="images/pet-pals-icon.png" alt="Pet Pals Icon"/>
                <a href="petSearch.html"><h4>Find a Pet</h4></a>
                <a href="adopterSearch.html"><h4>Adopters</h4></a>
                <a href="breederSearch.html"><h4>Breeders</h4></a>
                <a href="chat.html"><h4>My Chats</h4></a>
			</nav>
		</header>
        <a class="btn btn-primary" href="index.html"><h4>Back</h4></a>
        <form action="registration.php" method="post">
            <div class="form-group">
                <label>Name:</label>
                <input type="text" class="form-control" id="name" name="name">
            </div>    
            <div class="form-group">
                <label>About Me:</label>
                <textarea class="form-control" id="aboutMe" name="aboutMe"></textarea>
            </div>       
            <div class="form-group">
                <label>Hobbies and Lifestyle:</label>
                <textarea class="form-control" id="lifestyle" name="hobbies"></textarea>
            </div>      
            <div class="form-group">
                <label>Habits and Experience:</label>
                <textArea class="form-control" id="experience" name="habits"></textArea>
            </div>   
            <div class="form-group">
                <label>Email address:</label>
                <input type="email" class="form-control" id="email" name="email">
            </div>
            <div class="form-group">
                <label for="pwd">Password:</label>
                <input type="password" class="form-control" id="pwd" name="password">
            </div>
            <div class="checkbox">
                <label><input type="checkbox">I agree to the terms of service.</label>
            </div>
            <button type="submit">Submit</button>
        </form>
		<footer>
			<small>Copyright &copy; 2021 Grant Dong and Hunter Vaccaro. All Rights Reserved</small>
		</footer>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
	</body>
</html>
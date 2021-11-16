<?php
/** DATABASE SETUP **/
include("credentials.php"); // define variables

/** SETUP **/
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
$mysqli = new mysqli($host, $username, $password, $dbname); 
// $db = new mysql("localhost", "root", "", "dbname"); // XAMPP Settings 
$errorMessage = "";

session_start();

// Check if the user submitted the form (the form in the HTML below
// submits back to this page, which is okay for now.  We will check for
// form data and determine whether to re-show this form with a message
// or to redirect the user to the trivia game. 
if (!empty($_POST)) { // validate the email coming in
    $stmt = $mysqli->prepare("SELECT * FROM users WHERE email = ?;");
    $stmt->bind_param("s", $_SESSION["email"]);
    if (!$stmt->execute()) {
        die("Error checking for user");
    } else { 
        // result succeeded
        $res = $stmt->get_result();
        $data = $res->fetch_all(MYSQLI_ASSOC);
        
        if (empty($data)) {
            // user was NOT found!
            header("Location: index.html");
            exit();
        } 
        // The user WAS found (SECURITY ALERT: we only checked against
        // their email address -- this is not a secure method of
        // keeping track of users!  We more likely want a unique
        // session ID for this user instead!
        $user = $data[0];
        $_SESSION["name"] = $data[0]["name"];
        $_SESSION["email"] = $data[0]["email"];
        $_SESSION["aboutMe"] = $data[0]["aboutMe"];  
        $_SESSION["hobbies"] = $data[0]["hobbies"];
        $_SESSION["habits"] = $data[0]["habits"];
        $_SESSION["type"] = $data[0]["type"];
        $_SESSION["userID"] = $data[0]["userID"];
    }

    if($_POST["name"] === "" || $_POST["species"] === "" || $_POST["sex"] === "" || $_POST["personality"] === "") {
        $errorMessage="<div class = 'alert alert-danger'>Please provide all your information.</div>"; 
    } else { 
        // User was not found.  For our game, we'll just insert them!
        $insert = $mysqli->prepare("insert into pets (name, species, age, size, weight, sex, personality) values (?, ?, ?, ?, ?, ?, ?);");
        $insert->bind_param("ssiiiss", $_POST["name"], $_POST["species"], $_POST["age"], $_POST["size"], $_POST["weight"], $_POST["sex"], $_POST["personality"]);
        if (!$insert->execute()) {
            $errorMessage = "<div class = 'alert alert-danger'>Error creating new user</div>";
        }

        $pet_id = $insert->insert_id;
    
        $insert = $mysqli->prepare("insert into pet2user (userID, petID) values (?, ?);");
        $insert->bind_param("ii", $_SESSION["userID"], $pet_id);
        if (!$insert->execute()) {
            $errorMessage = "<div class = 'alert alert-danger'>Error creating new user</div>";
        }

        header("Location: profile.php");
        exit();
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
                <a href="petSearch.php"><h4>Find a Pet</h4></a>
                <a href="profile.php"><h4>Adopters</h4></a>
                <a href="profile.php"><h4>Owners</h4></a>
                <a href="chat.php"><h4>My Chats</h4></a>
			</nav>
		</header>
        <a class="btn btn-primary" href="profile.php"><h4>Back</h4></a>
        <form action="addPet.php" method="post">
            <?=$errorMessage?>
            <div class="form-group">
                <label>Name:</label>
                <input type="text" class="form-control" id="name" name="name">
            </div>   
            <div class="form-group">
                <label>Personality:</label>
                <textarea class="form-control" id="personality" name="personality"></textarea>
            </div>       
            <div class="form-group">
                <label>Species:</label>
                <input class="form-control" id="species" name="species"></input>
            </div>      
            <div class="form-group">
                <label>Age:</label>
                <input class="form-control" type="number" id="age" name="age"></input>
            </div> 
            <div class="form-group">
                <label>Size:</label>
                <input class="form-control" type="number" id="size" name="size"></input>
            </div> 
            <div class="form-group">
                <label>Weight:</label>
                <input class="form-control" type="number" id="weight" name="weight"></input>
            </div> 
            <div class="form-group">
                <p>Sex:</p>
                <select multiple class="form-control" id="sex" name="sex">
                    <option value="m" selected>Male</option>
                    <option value="f">Female</option>
                </select>
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
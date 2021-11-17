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
if (isset($_SESSION["email"])) { // validate the email coming in
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
    }

    if(isset($_GET["petID"])){
        $insert = $mysqli->prepare("insert into pet2user (petID, userID) values (?, ?);");
        $insert->bind_param("ii", $_GET["petID"], $_SESSION["userID"]);
        if (!$insert->execute()) {
            $error_msg = "Error adding pet to pet2user";
        } 
        header("Location: profile.php");
    }

    $stmt = $mysqli->prepare("SELECT * FROM pets NATURAL JOIN pet2user WHERE userID = ?;");
    $stmt->bind_param("i", $_SESSION["userID"]);
    if (!$stmt->execute()) {
        $error_msg = "Error finding favorite pets for user";
    } 
    $res = $stmt->get_result();
    $pets = $res->fetch_all(MYSQLI_ASSOC);
    
} else {
    // User did not supply email GET parameter, so send them
    // to the login page
    header("Location: index.html");
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
	<head>
		<title>Pet Pals - Profile</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" 
        rel="stylesheet" integrity="sha384-F3w7mX95PdgyTmZZMECAngseQB83DfGTowi0iMjiWaeVhAn4FJkqJByhZMI3AhiU" 
        crossorigin="anonymous">
		<link rel="stylesheet" href="styles/main.css"> 
        <link rel="stylesheet" href="styles/profile.css">

		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1"> 

		<meta name="author" content="Grant Dong and Hunter Vaccaro">
		<meta name="description" content="pet matching application">
		<meta name="keywords" content="pet pals animals matching adopting">  
    
	</head>  
	<body class="profile">
		<header>
			<nav>
                <img src="images/pet-pals-icon.png" alt="pet pal icon"/>
                <a href="petSearch.php"><h4>Find a Pet</h4></a>
                <a href="peopleSearch.html"><h4>People Search</h4></a>
                <a href="chat.php"><h4>My Chats</h4></a>
                <a href="profile.php"><h4>My Profile</h4></a>
                <a href="signOut.php"><h4>Sign Out</h4></a>
			</nav>
		</header>
        <section> 
            <div class = "profile-info-group">
            <img class="profile-icon" src="images/profile-icon.png" alt="profile icon"> 
                <div class = "biography-text" >
                    <div class ="profile-name">
                        <h2><?php echo $_SESSION["name"]; ?></h2> 
                    </div>
                    <div class = "profile-about">  
                        <h3>About Me</h3>   
                        <br>
                        <h5><?php echo $_SESSION["aboutMe"]; ?></h5> 
                    </div>
                </div> 
            </div>
            <div class = "profile-detail-group">  
                <div class = "profile-hobbies">
                    <h3>Hobbies &amp; Lifestyle</h3> 
                    <br>
                    <h5><?php echo $_SESSION["hobbies"]; ?></h5>
                </div>
                <div class = "profile-experience">
                    <h3>Habits &amp; Experience</h3> 
                    <br>
                    <h5><?php echo $_SESSION["habits"]; ?></h5>
                </div>
            </div>
        </section>
        <section class="favorites">
            <?php if($_SESSION["type"] == "adopter"){?>
            <h1><a href="petStats.php">Favorite Pets</a></h1>
            <?php } else { ?>
            <h1><a href="petStats.php">Your Pets</a></h1>
            <a href="addPet.php" class="btn btn-success">Register a New Pet</a>
            <?php } ?>
		    <div class="container">
                <div class="row row-cols-sm-2 row-cols-md-3 row-cols-lg-5">
                    <?php foreach($pets as $pet){ ?>
                        <div class="col">
                        <div class="card text-center">
                            <a href="petProfile.php?petID=<?=$pet["petID"]?>"><img class="card-img-top" src="<?= $pet["picture"]?>" alt="<?=$pet["name"]?>"></a>
                            <div class="card-body">
                            <h5 class="card-title"><?= $pet["name"]?> (<?= $pet["species"]?>)</h5>
                            <?php if($_SESSION["type"] == "adopter"){?>
                            <a href="removeFavorite.php?petID=<?= $pet["petID"]?>" class="btn btn-primary">Unfavorite</a>
                            <?php } else { ?>
                                <a href="removeFavorite.php?petID=<?= $pet["petID"]?>" class="btn btn-primary">Take-off Market</a>
                            <?php } ?>
                            </div>
                        </div>
                        </div>
                    <?php } ?>
                </div>
            </div>  
		</section>
		<footer> 
        <small>Copyright &copy; 2021 Grant Dong and Hunter Vaccaro. All Rights Reserved</small>
		</footer>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
	</body>
</html>














 
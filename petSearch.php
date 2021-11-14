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
    $stmt = $mysqli->prepare("SELECT * FROM pets WHERE petID != ?;");
    $stmt->bind_param("i", $_SESSION["userID"]);
    if(!$stmt->execute()){
      $error_msg = "Query failed";
    }
    $res = $stmt->get_result();
    $pets = $res->fetch_all(MYSQLI_ASSOC);

    $stmt = $mysqli->prepare("SELECT * FROM pet2user NATURAL JOIN pets WHERE userID = ?;");
    $stmt->bind_param("i", $_SESSION["userID"]);
    if(!$stmt->execute()){
      $error_msg = "Query failed";
    }
    $res = $stmt->get_result();
    $favoritePets = $res->fetch_all(MYSQLI_ASSOC);

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
		<title>Pet Pals - Pet Search</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" 
        rel="stylesheet" integrity="sha384-F3w7mX95PdgyTmZZMECAngseQB83DfGTowi0iMjiWaeVhAn4FJkqJByhZMI3AhiU" 
        crossorigin="anonymous">
		<link rel="stylesheet" href="styles/main.css"> 
        <link rel="stylesheet" href="styles/petSearch.css">

		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1"> 

		<meta name="author" content="Grant Dong and Hunter Vaccaro">
		<meta name="description" content="pet matching application">
		<meta name="keywords" content="pet pals animals matching adopting">  
    
	</head>  
	<body class="petSearch">
		<header>
			<nav>
                <img src="images/pet-pals-icon.png" alt="pet pals logo"/>
                <a href="petSearch.php"><h4>Find a Pet</h4></a>
                <a href="profile.php"><h4>Adopters</h4></a>
                <a href="profile.php"><h4>Owners</h4></a>
                <a href="chat.php"><h4>My Chats</h4></a>
                <a href="profile.php"><h4>My Profile</h4></a>
                <a href="signOut.php"><h4>Sign Out</h4></a>
			</nav>
		</header>
		<div class="filters"> 
			<button class="btn btn-primary">Species</button>
			<button class="btn btn-primary">Price</button>
			<button class="btn btn-primary">Age</button>
			<button class="btn btn-primary">Sex</button>
			<button class="btn btn-primary">Size</button>
			<button class="btn btn-primary">Weight</button>
			<button class="btn btn-primary">Maintenance</button>
		</div>
		<section class="results">
			<div class="container">
        <div class="row row-cols-sm-2 row-cols-md-3 row-cols-lg-5">
          <?php foreach($pets as $pet){ ?>
            <div class="col">
              <div class="card text-center">
                <img class="card-img-top" src="<?= $pet["picture"]?>" alt="Clifford">
                <div class="card-body">
                  <h5 class="card-title"><?= $pet["name"]?> (<?= $pet["species"]?>)</h5>
                  <a href="profile.php?petID=<?= $pet["petID"]?>" class="btn btn-primary">Favorite</a>
                </div>
              </div>
            </div>
          <?php } ?>
          <?php foreach($favoritePets as $pet){ ?>
            <div class="col">
              <div class="card text-center">
                <img class="card-img-top" src="<?= $pet["picture"]?>" alt="Clifford">
                <div class="card-body">
                  <h5 class="card-title"><?= $pet["name"]?> (<?= $pet["species"]?>)</h5>
                  <button class="btn btn-secondary">Favorite</button>
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














 
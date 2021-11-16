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
    //$stmt = $mysqli->prepare("SELECT * FROM pets WHERE petID != ?;");
    $stmt = $mysqli->prepare("SELECT * FROM pets where petID not in (Select petID from pet2user where userID = ?)");

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
			<button class="btn btn-primary" id="age">Age</button>
			<button class="btn btn-primary" id="sex">Sex</button>
			<button class="btn btn-primary" id="size">Size</button>
			<button class="btn btn-primary" id="weight">Weight</button>
		</div>
		<section class="results">
			<div class="container">
        <div class="row row-cols-sm-2 row-cols-md-3 row-cols-lg-5" id="display">
          <?php foreach($pets as $pet){ ?>
            <div class="col">
              <div class="card text-center">
                <a href="petProfile.php?petID=<?=$pet["petID"]?>"><img class="card-img-top" src="<?= $pet["picture"]?>" alt="<?=$pet["name"]?>"></a>
                <div class="card-body">
                  <h5 class="card-title"><?= $pet["name"]?> (<?= $pet["species"]?>)</h5>
                  <?php if($_SESSION["type"] == "adopter"){?>
                  <a href="profile.php?petID=<?= $pet["petID"]?>" class="btn btn-primary">Favorite</a>
                  <?php } ?>
                </div>
              </div>
            </div>
          <?php } ?>
          <?php foreach($favoritePets as $pet){ ?>
            <div class="col">
              <div class="card text-center">
                <img class="card-img-top" src="<?= $pet["picture"]?>" alt="<?=$pet["name"]?>">
                <div class="card-body">
                  <h5 class="card-title"><?= $pet["name"]?> (<?= $pet["species"]?>)</h5>
                  <?php if($_SESSION["type"] == "adopter"){?>
                  <btn class="btn btn-secondary">Favorite</btn>
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
    <script>
      function displayPets(petList, favoritedPets){
        document.getElementById("display").innerHTML = "";
        for (i = 0; i < petList.length; i++){
          var option = "";
          if(<?php echo json_encode($_SESSION["type"]); ?> === "adopter"){
            option = "<a href='profile.php?petID=" + petList[i].petID + "' class='btn btn-primary'>Favorite</a>";
          }
          document.getElementById("display").innerHTML += 
          "<div class='col'>" + 
            "<div class='card text-center'>" +
              "<a href='petProfile.php?petID=<?=$pet["petID"]?>'><img class='card-img-top' src='" + (petList[i].picture == null ? "": petList[i].picture) + "' alt='" + petList[i].name + "'></a>" +
              "<div class='card-body'>" +
                "<h5 class='card-title'>" + petList[i].name + "(" + petList[i].species + ")</h5>" +
                  option +
              "</div>" + 
            "</div>" + 
          "</div>";
        }
        for (i = 0; i < favoritedPets.length; i++){
          var option = "";
          if(<?php echo json_encode($_SESSION["type"]); ?> === "adopter"){
            option = "<a href='profile.php?petID=" + favoritedPets[i].petID + "' class='btn btn-secondary'>Favorite</a>";
          } else {
            option = "<a href='removeFavorite.php?petID=" + favoritedPets[i].petID + "' class='btn btn-primary'>Take-off Market</a>";
          }
          document.getElementById("display").innerHTML +=
          "<div class='col'>" + 
              "<div class='card text-center'>" +
                "<img class='card-img-top' src='" + (favoritedPets[i].picture == null ? "": favoritedPets[i].picture) + "' alt='" + favoritedPets[i].name + "'>" +
                "<div class='card-body'>" +
                  "<h5 class='card-title'>" + favoritedPets[i].name + "(" + favoritedPets[i].species + ")</h5>" +
                  option +
                "</div>" +
              "</div>" +
            "</div>";
        }
      }

      var ageButton = document.getElementById("age");
      var sizeButton = document.getElementById("size");
      var weightButton = document.getElementById("weight");
      var sexButton = document.getElementById("sex");

      var colorWheel = ["green", "red", "rgb(11, 94, 215)"]

      ageButton.addEventListener("click", (event) => {
        var petList = <?php echo json_encode($pets); ?>;
        var favoritedPets = <?php echo json_encode($favoritePets); ?>;
        ageButton.style.backgroundColor = colorWheel[ (colorWheel.indexOf(ageButton.style.backgroundColor) + 1) % colorWheel.length]
        if(ageButton.style.backgroundColor == "red") {
          petList.sort((a, b) => (a.age < b.age) ? 1 : -1); 
        }
        else if(ageButton.style.backgroundColor == "green") {
          petList.sort((a, b) => (a.age > b.age) ? 1 : -1); 
        }

        // Refresh Pet List With New Ordering
        displayPets(petList, favoritedPets);
      }); 

      sizeButton.addEventListener("click", (event) => {
        var petList = <?php echo json_encode($pets); ?>;
        var favoritedPets = <?php echo json_encode($favoritePets); ?>;
        sizeButton.style.backgroundColor = colorWheel[ (colorWheel.indexOf(sizeButton.style.backgroundColor) + 1) % colorWheel.length]
        if(sizeButton.style.backgroundColor == "red") {
          petList.sort((a, b) => (a.size < b.size) ? 1 : -1); 
        }
        else if(sizeButton.style.backgroundColor == "green") {
          petList.sort((a, b) => (a.size > b.size) ? 1 : -1); 
        }
        // Refresh Pet List With New Ordering
        displayPets(petList, favoritedPets);
      }); 

      weightButton.addEventListener("click", (event) => {
        var petList = <?php echo json_encode($pets); ?>;
        var favoritedPets = <?php echo json_encode($favoritePets); ?>;
        weightButton.style.backgroundColor = colorWheel[ (colorWheel.indexOf(weightButton.style.backgroundColor) + 1) % colorWheel.length]
        if(weightButton.style.backgroundColor == "red") {
          petList.sort((a, b) => (a.weight < b.weight) ? 1 : -1); 
        }
        else if(weightButton.style.backgroundColor == "green") {
          petList.sort((a, b) => (a.weight > b.weight) ? 1 : -1); 
        }

        // Refresh Pet List With New Ordering
        displayPets(petList, favoritedPets);
      }); 
      
      sexButton.addEventListener("click", (event) => {
        var petList = <?php echo json_encode($pets); ?>;
        var favoritedPets = <?php echo json_encode($favoritePets); ?>;
        sexButton.style.backgroundColor = colorWheel[ (colorWheel.indexOf(sexButton.style.backgroundColor) + 1) % colorWheel.length]
        if(sexButton.style.backgroundColor == "red") {
          petList = petList.filter((a) => (a.sex === "f")); 
        }
        else if(sexButton.style.backgroundColor == "green") {
          petList = petList.filter((a) => (a.sex === "m")); 
        }

        // Refresh Pet List With New Ordering
        displayPets(petList, favoritedPets);
      }); 

    </script>
	</body>
</html>














 
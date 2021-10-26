<?php
    include("databaseCredentials.php");
     
    /** SETUP **/
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
    $mysqli = new mysqli($host, $username, $password, $dbname); 
    // $db = new mysql("localhost", "root", "", "dbname"); // XAMPP Settings 
    $error_msg = ""; 

    session_start();

    if(isset($_POST["email"])) {
        $stmt = $mysqli->prepare("select * from users where email = ?;");
        $stmt->bind_param("s", $_POST["email"]);
        
        if (!$stmt->execute()) {
            $error_msg = "Error checking for user";
        } else { 
            // result succeeded
            $res = $stmt->get_result();
            $data = $res->fetch_all(MYSQLI_ASSOC); 
 
            if (empty($data)) { 
                // User was not found
                header("Location: registration.php");
                exit();
            } 
            
            $user_info = $data[0]; 
            if(!password_verify($_POST["password"], $user_info["password"])) { 
                //invalid password 
                header("Location: signIn.php");
                exit();
            }
              
            $_SESSION["userID"] = $user_info["userID"]; 
            $_SESSION["name"] = $user_info["name"]; 
            $_SESSION["email"] = $user_info["email"];  
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
        <link rel="stylesheet" href="styles/signIn.css">

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
        <form action="signIn.php" method="post">
            <div><?=$error_msg?></div>
            <div class="form-group">
                <label>Email:</label>
                <input type="email" class="form-control" id="email" name="email">
            </div>    
            <div class="form-group">
                <label>Password:</label>
                <input type="password" class="form-control" id="password" name="password">
            </div>  
            <button type="submit">Sign In</button>
        </form>
		<footer>
			<small>Copyright &copy; 2021 Grant Dong and Hunter Vaccaro. All Rights Reserved</small>
		</footer>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
	</body>
</html>
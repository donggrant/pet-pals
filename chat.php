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
	
	if (isset($_POST['submit'])){
		   
		// Escape user inputs for security
		$un= mysqli_real_escape_string(
				$mysqli, $_REQUEST['uname']);
		$m = mysqli_real_escape_string(
				$mysqli, $_REQUEST['msg']);
				 
		date_default_timezone_set('Asia/Kolkata');
		$ts=date('y-m-d h:ia');
		   
		// Attempt insert query execution
		$sql = "INSERT INTO chats (uname, msg, dt)
					VALUES ('$un', '$m', '$ts')";
		if(mysqli_query($mysqli, $sql)){
			;
		} else{
			echo "ERROR: Message not sent!!!";
		}
		  
		// Close connection
		mysqli_close($mysqli);
	}
}
else {
    header("Location: index.html");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
	<head>
		<title>Pet Pals - Chat</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" 
        rel="stylesheet" integrity="sha384-F3w7mX95PdgyTmZZMECAngseQB83DfGTowi0iMjiWaeVhAn4FJkqJByhZMI3AhiU" 
        crossorigin="anonymous">
		<link rel="stylesheet" href="styles/main.css"> 
        <link rel="stylesheet" href="styles/chat.css">

		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1"> 

		<meta name="author" content="Grant Dong and Hunter Vaccaro">
		<meta name="description" content="pet matching application">
		<meta name="keywords" content="pet pals animals matching adopting">  
    
	</head>  
	<body onload="show_func()">
		<header>
				<nav>
					<img src="images/pet-pals-icon.png" alt="pet pals logo"/>
					<a href="petSearch.php"><h4>Find a Pet</h4></a>
					<a href="http://localhost:4200/"><h4>People Search</h4></a>
					<a href="chat.php"><h4>My Chats</h4></a>
					<a href="profile.php"><h4>My Profile</h4></a>
					<a href="signOut.php"><h4>Sign Out</h4></a>
				</nav>
		</header>
		<div id="container">
			<main>
				<header>
					<?=$_SESSION["name"] ?>
				</header>		
		<script>
		function show_func(){
		
		var element = document.getElementById("chathist");
			element.scrollTop = element.scrollHeight;
		
		}
		</script>
		
		<form id="myform" action="chat.php" method="POST" >
		<div class="inner_div" id="chathist">
		<?php
		$host = "localhost";
		$user = "root";
		$pass = "";
		$db_name = "pet_pals";
		$con = new mysqli($host, $user, $pass, $db_name);
		
		$query = "SELECT * FROM chats";
		$run = $con->query($query);
		$i=0;
		
		while($row = $run->fetch_array()) :
		if($i==0){
		$i=5;
		$first=$row;
		?>
		<div id="triangle1" class="triangle1"></div>
		<div id="message1" class="message1">
		<span style="color:white;float:right;">
		<?php echo $row['msg']; ?>
		</span> <br/>
		<div>
		<span style="color:black;float:left;
		font-size:10px;clear:both;">
		<?php echo $row['uname']; ?>, <?php echo $row['dt']; ?>
		</span>
		</div>
		</div>
		<br/><br/>
		<?php
		}
		else
		{
		if($row['uname']!=$first['uname'])
		{
		?>
		<div id="triangle" class="triangle"></div>
		<div id="message" class="message">
		<span style="color:white;float:left;">
		<?php echo $row['msg']; ?></span> <br/>
		<div>
		<span style="color:black;float:right;
				font-size:10px;clear:both;">
		<?php echo $row['uname']; ?>,
				<?php echo $row['dt']; ?>
		</span>
		</div>
		</div>
		<br/><br/>
		<?php
		}
		else
		{
		?>
		<div id="triangle1" class="triangle1"></div>
		<div id="message1" class="message1">
		<span style="color:white;float:right;">
		<?php echo $row['msg']; ?></span> <br/>
		<div>
		<span style="color:black;float:left;
				font-size:10px;clear:both;">
			<?php echo $row['uname']; ?>,
				<?php echo $row['dt']; ?>
		</span>
		</div>
		</div>
		<br/><br/>
		<?php
		}
		}
		endwhile; ?>
		</div>
			<footer>
				<table>
				<tr>
					<th>
					<input  class="input1" type="text" id="uname"
					name="uname" placeholder="From"></input>
					</th>
					<th>
					<textarea id="msg" name="msg" rows='3'
					cols='50' placeholder="Type your message">
					</textarea>
					</th>
					<th>
					<input class="input2" type="submit" id="submit"
					name="submit" value="send"></input>
					</th>               
				</tr>
				</table>               
			</footer>
		</form>
		</main>   
		</div>
		<footer>
			<small>Copyright &copy; 2021 Grant Dong and Hunter Vaccaro. All Rights Reserved</small>
		</footer>
	</body>
</html>
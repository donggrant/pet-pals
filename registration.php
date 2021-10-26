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
        <form action="profile.php" method="post">
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
                <textarea class="form-control" id="lifestyle" name="lifestyle"></textarea>
            </div>      
            <div class="form-group">
                <label>Habits and Experience:</label>
                <textArea class="form-control" id="experience" name="experience"></textArea>
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
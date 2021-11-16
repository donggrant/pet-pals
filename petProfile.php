<!DOCTYPE html>
<html lang="en">
	<head>
		<title>Pet Pals - Pet Page</title>
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
                <a href="profile.php"><h4>Adopters</h4></a>
                <a href="profile.php"><h4>Owners</h4></a>
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
                     </div>
                    <div class = "profile-about">  
                        <h3>About Me</h3>   
                        <br>
                     </div>
                </div> 
            </div>
            <div class = "profile-detail-group">  
                <div class = "profile-hobbies">
                    <h3>Hobbies &amp; Lifestyle</h3> 
                    <br>
                 </div>
                <div class = "profile-experience">
                    <h3>Habits &amp; Experience</h3> 
                    <br>
                 </div>
            </div>
        </section>
        
		<footer> 
        <small>Copyright &copy; 2021 Grant Dong and Hunter Vaccaro. All Rights Reserved</small>
		</footer>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
        <script>
            const queryString = window.location.search;
            const urlParams = new URLSearchParams(queryString);

            var ajax = new XMLHttpRequest();
            var method = "POST";
            var path = "petProfileQuery.php";
            var asynchronous = true;
            var pets = [];

            // asynchronously waits for a json response
            ajax.onreadystatechange = function() {
                if(this.readyState == 4 && this.status == 200) {
                    //pets = JSON.parse(this.responseText);
                    alert(this.responseText); 
                }
            }
            
            ajax.open(method, path, asynchronous);
            ajax.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            ajax.send("petID="+urlParams.get('petID'));

        </script>
	</body>
</html>














 
<!DOCTYPE html>
<html lang="en">
	<head>
		<title>Pet Pals - Pets</title>
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
                <a href="http://localhost:4200/"><h4>People Search</h4></a>
                <a href="chat.php"><h4>My Chats</h4></a>
                <a href="profile.php"><h4>My Profile</h4></a>
                <a href="signOut.php"><h4>Sign Out</h4></a>
			</nav>
		</header>
        <section> 
            <h1> Pets </h1>
            <table id="pet_table">
                <thead> 
                </thead>
                <tbody>
                </tbody>
            </table>
        </section> 
		<footer> 
        <small>Copyright &copy; 2021 Grant Dong and Hunter Vaccaro. All Rights Reserved</small>
		</footer>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
        <script>
            var ajax = new XMLHttpRequest();
            var method = "GET";
            var path = "petStatsQuery.php";
            var asynchronous = true;
            var pets = [];
            ajax.open(method, path, asynchronous);

            ajax.send();

            // asynchronously waits for a json response
            ajax.onreadystatechange = function() {
                if(this.readyState == 4 && this.status == 200) {
                    pets = JSON.parse(this.responseText);
                    displayPetData(pets);
                }
            }

            /*
                From array of objects, displays the information found from pets into a table 
            */
            function displayPetData(pets) {
                var table = document.getElementById("pet_table");
                table.removeChild(table.getElementsByTagName("tbody")[0]);
                var body = document.createElement("tbody");
                for(var i = 0; i < pets.length; i++) {
                    var pet = pets[i];
                    var row = document.createElement("tr");    
                    for(const properties in pet) {
                        var property = document.createElement("td");
                        var property_text = document.createTextNode(pet[properties]);

                        property.appendChild(property_text);
                        row.appendChild(property);
                    }
                    body.appendChild(row);
                }
                table.appendChild(body);
            }
        </script>
    </body>
    
</html>














 
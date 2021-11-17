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

$q = $_GET['q'];

$sql="SELECT * FROM users WHERE name like '%$q%'";
$result = mysqli_query($mysqli,$sql);

echo "<table>
<tr>
<th>Name</th>
<th>Email</th>
<th>User Type</th>
</tr>";
while($row = mysqli_fetch_array($result)) {
  echo "<tr>";
  echo "<td>" . $row['name'] . "</td>";
  echo "<td>" . $row['email'] . "</td>";
  echo "<td>" . $row['type'] . "</td>";
  echo "</tr>";
}
echo "</table>";

mysqli_close($mysqli);
?>
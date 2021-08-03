<?php 

$hostname = "localhost";
$username = "user";
$password = "password";
$dbname = "newtest";

$conn = new mysqli($hostname, $username, $password, $dbname);

if ($conn->connect_error){
	die("Connection Failed: " . $conn->connect_error);
}

$sql = "SELECT comments FROM sweetwater_test";
$result = $conn->query($sql);

$call_comments = array();
$misc_comments = array();

if ($result->num_rows > 0) {
  // output data of each row
  while($row = $result->fetch_assoc()) {
  	if (strpos("$row[comments]", "call")){
  		array_push($call_comments, "$row[comments]");
  	}
  	else{
  		array_push($misc_comments, "$row[comments]");
  	}
  }
} else {
  echo "0 results";
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title></title>
</head>
<body>
<h1>Comments</h1>
<h2>Call Comments</h2>
<ul>
	<?php
	foreach($call_comments as $call_comment){
		echo("<li>" . $call_comment . "</li>");
	}
	?>

</ul>
<h2>Miscellaneous Comments</h2>
<ul>
	<?php
	foreach($misc_comments as $misc_comment){
		echo("<li>" . $misc_comment . "</li>");
	}
	?>

</ul>
</body>
</html>
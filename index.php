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

$candy_comments = array();
$call_comments = array();
$refer_comments = array();
$sign_comments = array();
$misc_comments = array();

if ($result->num_rows > 0) {
  // output data of each row
  while($row = $result->fetch_assoc()) {
  	$lower_comment = strtolower("$row[comments]");
  	if ((strpos($lower_comment, "candy") !== false) || (strpos($lower_comment, "smarties") !== false) || (strpos($lower_comment, "taffy") !== false)){
  		array_push($candy_comments, "$row[comments]");
  	}
  	else if (strpos($lower_comment, "call") !== false){
  		array_push($call_comments, "$row[comments]");
  	}
  	else if (strpos($lower_comment, "refer") !== false){
  		array_push($refer_comments, "$row[comments]");
  	}
  	else if (strpos($lower_comment, "sign") !== false){
  		array_push($sign_comments, "$row[comments]");
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
<h2>Candy Comments</h2>
<ul>
	<?php
	foreach($candy_comments as $candy_comment){
		echo("<li>" . $candy_comment . "</li>");
	}
	?>

</ul>
<h2>Call Comments</h2>
<ul>
	<?php
	foreach($call_comments as $call_comment){
		echo("<li>" . $call_comment . "</li>");
	}
	?>

</ul>
<h2>Referral Comments</h2>
<ul>
	<?php
	foreach($refer_comments as $refer_comment){
		echo("<li>" . $refer_comment . "</li>");
	}
	?>

</ul>
<h2>Signature Comments</h2>
<ul>
	<?php
	foreach($sign_comments as $sign_comment){
		echo("<li>" . $sign_comment . "</li>");
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
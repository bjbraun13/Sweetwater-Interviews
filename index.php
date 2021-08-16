<?php 

$hostname = "localhost";
$username = "root";
$password = "";
$dbname = "newtest";

$conn = new mysqli($hostname, $username, $password, $dbname);

if ($conn->connect_error){
	die("Connection Failed: " . $conn->connect_error);
}

$sql = "SELECT comments, orderid FROM sweetwater_test";
$result = $conn->query($sql);

//arrays to store grouped comments
$candy_comments = array();
$call_comments = array();
$refer_comments = array();
$sign_comments = array();
$misc_comments = array();

if ($result->num_rows > 0) {
  // output data of each row
  while($row = $result->fetch_assoc()) {
  	//Group comments based on contents
  	$lower_comment = strtolower("$row[comments]");  //not case sensitive
  	$candy_pattern = "/candy|smarties|taffy|reese(')*s|kit(\s)*kat/i";
  	$call_pattern = "/\bcall(s*)\b|comunicarse|llámame/i";

  	//Candy group
  	if (preg_match($candy_pattern, $lower_comment)){
  		array_push($candy_comments, "$row[comments]");
  	}

  	//Call group
  	else if (preg_match($call_pattern, $lower_comment)){
  		array_push($call_comments, "$row[comments]");
  	}

  	//Referral group
  	else if (strpos($lower_comment, "refer") !== false){
  		array_push($refer_comments, "$row[comments]");
  	}

  	//Signature Requirements group
  	else if (strpos($lower_comment, "sign") !== false){
  		array_push($sign_comments, "$row[comments]");
  	}

  	//All other comments
  	else{
  		array_push($misc_comments, "$row[comments]");
  	}


  	//Parse expected shipment date and update corresponding database entry
  	if (strpos("$row[comments]", "Expected Ship Date: ") !== false){ //20 chars
  		$posstart = strpos("$row[comments]", "Expected Ship Date: ") + 20;
  		$shipdate = substr("$row[comments]", $posstart, 8);
  		$myd = DateTime::createFromFormat('m/d/y', $shipdate)->format('Y-m-d');
  		$update = "UPDATE sweetwater_test SET shipdate_expected='$myd' WHERE orderid='$row[orderid]'";

  		if ($conn->query($update) !== true){
  			echo "Error updating database." . "<br>";
  		}
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
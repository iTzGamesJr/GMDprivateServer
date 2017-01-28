<?php
include "../connection.php";
$query = $db->prepare("SELECT * FROM users");
$query->execute();
$result = $query->fetchAll();
//getting users
foreach($result as $user){
//getting starred lvls count
$query2 = $db->prepare("SELECT userID FROM levels WHERE userID = '".$user["userID"]."' AND starStars != 0");
$query2->execute();
$creatorpoints = $query2->rowCount();
//getting featured lvls count
$query3 = $db->prepare("SELECT userID FROM levels WHERE userID = '".$user["userID"]."' AND starFeatured != 0");
$query3->execute();
$creatorpoints = $creatorpoints + $query3->rowCount();
//getting epic lvls count
$query3 = $db->prepare("SELECT userID FROM levels WHERE userID = '".$user["userID"]."' AND starEpic != 0");
$query3->execute();
$creatorpoints = $creatorpoints + $query3->rowCount() + $query3->rowCount();
//inserting cp value
$query4 = $db->prepare("UPDATE users SET creatorPoints='$creatorpoints' WHERE userID='".$user["userID"]."'");
$query4->execute();
if($creatorpoints != 0){
echo $user["userName"] . " now has ".$creatorpoints." creator points... <br>";	
}
}
/*
	NOW to update GAUNTLETS CP
*/
echo "<hr><h1>GAUNTLETS UPDATE</h1><hr>";
$query = $db->prepare("SELECT * FROM gauntlets");
$query->execute();
$result = $query->fetchAll();
//getting gauntlets
foreach($result as $gauntlet){
//getting lvls
for($x = 0; $x < 6; $x++){
$query = $db->prepare("SELECT userID, levelID FROM levels WHERE levelID = '".$gauntlet["level".$x]."'");
$query->execute();
$result = $query->fetchAll();
$result = $result[0];
//getting users
if($result["userID"] != ""){
$query = $db->prepare("SELECT userName, userID, creatorPoints FROM users WHERE userID = ".$result["userID"]);
$query->execute();
$result = $query->fetchAll();
$user = $result[0];
$creatorpoints = $user["creatorPoints"];
$creatorpoints++;
//inserting cp value
$query4 = $db->prepare("UPDATE users SET creatorPoints='$creatorpoints' WHERE userID='".$user["userID"]."'");
$query4->execute();	
echo $user["userName"] . " now has ".$creatorpoints." creator points... <br>";	
}
}
}
/*
	DONE
*/
echo "<hr>done";
$query4 = $db->prepare("UPDATE users SET creatorPoints='0' WHERE userName='Ramppi'");
$query4->execute();
?>
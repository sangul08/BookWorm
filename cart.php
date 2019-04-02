<!DOCTYPE html>
<html lang="en">
<head>
  <title>BookWorm</title>
  <link rel="stylesheet" href="Includes/Index.css">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  <link href="https://fonts.googleapis.com/css?family=Rammetto+One" rel="stylesheet">
</head>
<body>

<?php
include_once("LIB_project1.php");
require_once("PDO.DB.class.php");

session_name("Books");
session_start();

//checking of the user has already logged in
if(!isset($_SESSION["user_type"])) {
	header("Location:login.php");
}

//checking if logout button has been clicked
if(isset($_POST["logout"])){
	unset($_SESSION);
	session_destroy();
	header("Location:login.php"); 
}

//checking the user type - admin or normal user
if($_SESSION["user_type"] == "A"){
	echo nav_bar("A");
} 
else {
	echo nav_bar("U");
}

 $db = new DB();

//checking if clear cart button is pressed
if(isset($_POST["delete_cart"]))  {
    	$message = $db-> remove_cart_items();
        echo "<script>alert('$message');</script>";
}

//checking if the user wants to delete a particular cart item and has clicked remove_item_from_cart button
if(isset($_POST["remove_item_from_cart"]))  {
	$message = $db-> removeItem($_GET['id']);
	echo "<script>alert('$message');</script>";
}

$totalPrice = 0;

?>
<div  id='enclosure_div'>

<?php 
//displaying cart items
$cart_data  = $db-> getCartItems($_SESSION);
echo"<div class='container-fluid' id='cart_items'>";
echo "<form style='float:right' method='post'>";
echo "<input type='submit' class='btn btn-md btn-danger' name='delete_cart' value='Clear Cart' />";
echo "</form>";		
echo "<h2 class='heading_index'>Books in Cart</h2>";

$arr = display_cart_items($cart_data);
echo $arr[0];
echo "<h2 class='heading_index'>Total Amount: $$arr[1]</h2>";
echo "</div>";
?>

</div>
</body>
</html>
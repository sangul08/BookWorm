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

//checking if the user has not logged in before.
if(!isset($_SESSION["user_type"])) {
	header("Location:login.php");
}

//when logout button is pressed.
if(isset($_POST["logout"])){
	unset($_SESSION);
	session_destroy();
	header("Location:login.php"); 
}

//to check if the user is an admin or a normal user
if($_SESSION["user_type"] == "A"){
	echo nav_bar("A");
} 
else {
	echo nav_bar("U");
}

//getting the current page number
$db = new DB();
$current_page = 1;
if(isset($_GET["page"]))	$current_page = $_GET["page"];
$page = 0;
if($current_page == "" || $current_page == 1){
	$page=0;
}
else {
	$page = ($current_page*5)-5;
}

$_SESSION["page"] = $current_page;

//after clicking Add to Cart
if(isset($_POST["add_to_cart"]))  
{
	$message = $db->addToCart($_GET['id']);
	echo "<script>alert('$message');</script>";
}
?>

<div id='enclosure_div'>

<?php
//displaying catalog items
$data =  $db->getItems($page);
echo"<div class='container-fluid' id='items'>";		
echo "<h2 class='heading_index'>Books available</h2>";
echo display_items($data);


//for counting page numbers.
$for_page_count = $db->getPages();  
$cou = count($for_page_count);
$pages = ceil($cou / 5);

//managinf previous and next button
$prev = "1"; $next = $pages;
if(($_SESSION["page"] - 1) >= 1){
	$prev = $_SESSION["page"] - 1;
}
if(($_SESSION["page"] + 1) <= $pages){
	$next = $_SESSION["page"] + 1;
}
//dynamically creating page number links
echo "</div>";
echo "<ul class='fixed-bottom pagination'>";
echo "	<li class='page-item'> \n
      		<a class='page-link' href='index.php?page={$prev}' aria-label='Previous'> \n
        		<span aria-hidden='true'>&laquo;</span> \n
        		<span class='sr-only'>Previous</span> \n
      		</a> \n
    	</li>";
for($p= 1; $p <= $pages; $p++) 
{
	echo "<li><a href='index.php?page=$p'>$p</a></li>";
}
echo "	<li class='page-item'> \n
      		<a class='page-link' href='index.php?page={$next}' aria-label='Next'> \n
        		<span aria-hidden='true'>&raquo;</span> \n
        		<span class='sr-only'>Next</span> \n
      		</a> \n
    	</li>";
echo "</ul>";
echo "<hr>";

//displaying sale items
$sale_data  = $db-> getSaleItems();
echo"<div class='container-fluid' id='sale_items'>";		
echo "<h2 class='heading_index'>Books on sale</h2>";
echo display_sale_items($sale_data);
echo "</div>";
?>
</div>

</body>
</html>

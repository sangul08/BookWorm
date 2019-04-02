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

//includes
require_once("PDO.DB.class.php");
require_once("LIB_project1.php");

session_name("Books");
session_start();

//check if the user has logged in or not
if(!isset($_SESSION["user_type"])) {
  header("Location:login.php");
}

//checking if the user wants to logout
if(isset($_POST["logout"])){
  unset($_SESSION);
  session_destroy();
  header("Location:login.php"); 
}

//creating navigation bar.
echo nav_bar("A");

$db = new DB();

//checking if form reset button has been clicked
if(isset($_POST["reset_form"])) {
  header("Location: admin.php"); 
}

echo "<div id='enclosure_div'>";
//displaying items
$data =  $db->selectMenuNames();    
echo "<h3 class='heading_index'>Choose from the list of books to edit information about a book.</h3>";
echo create_option_menus($data);

//after edit_item form is submitted
if(isset($_POST["edit_item"])) {
  $message = "";
  $arr = array();
  $arr["Name"] = $_POST['name'];
  $arr["Description"] = $_POST['description'];
  $arr["Price"] = $_POST['price']; 
  $arr["Intake"] = $_POST['intake'];
  $arr["Sale"] = $_POST['sale'];
  $arr["Id"] = $_POST['ID'];
  if(isset($_POST['edit_item'])){ $sale = $_POST['sale']; } else {$sale =  $input['Sale'];}

  if(empty($_POST["name"]) || empty($_POST["description"]) || empty($_POST["price"]) || empty($_POST["intake"]) || !isset($_POST["sale"]))
  {
    $message = "Please fill in all fields.";
  }
  else
  {

    if (!numeric_cost($_POST["price"]) || !numeric_cost($_POST["sale"])){
          $message = "Please enter a valid cost for the book!";
          
    }elseif(!numeric($_POST["intake"])){
      $message = "Please enter a valid numeric quantity of the book in stock!";
    }elseif($_POST["description"] !="" && (sqlMetaChars($_POST["description"]) || sqlInjection($_POST["description"]) || sqlInjectionUnion($_POST["description"]) || sqlInjectionSelect($_POST["description"]) || sqlInjectionInsert($_POST["description"]) || sqlInjectionDelete($_POST["description"]) || sqlInjectionUpdate($_POST["description"]) || sqlInjectionDrop($_POST["description"]) || crossSiteScripting($_POST["description"]) ||crossSiteScriptingImg($_POST["description"]))) 
      {
          $message = "Please enter a valid description!";
      }elseif($_POST["name"] !="" && (sqlMetaChars($_POST["name"]) || sqlInjection($_POST["name"]) || sqlInjectionUnion($_POST["name"]) || sqlInjectionSelect($_POST["name"]) || sqlInjectionInsert($_POST["name"]) || sqlInjectionDelete($_POST["name"]) || sqlInjectionUpdate($_POST["name"]) || sqlInjectionDrop($_POST["name"]) || crossSiteScripting($_POST["name"]) ||crossSiteScriptingImg($_POST["name"]))) 
      {
          $message = "Please enter a valid item name!";
      }else {
       $message = $db->editItem();
    }
  }
  echo "<script type='text/javascript'>alert('$message');</script>";
  echo display_edit_item_form($arr);
  echo "<hr class='style-two'>";
}

//after add item form is edited
if(isset($_POST["add_item"])) {
  $message = "";
  if(empty($_POST["add_name"]) || empty($_POST["add_description"]) || empty($_POST["add_price"]) || empty($_POST["add_image"]) || empty($_POST["add_intake"]) || !isset($_POST["add_sale"]))
  {
    $message = "Please fill in all fields.";
  }
  else
  {
    if (!numeric_cost($_POST["add_price"]) || !numeric_cost($_POST["add_sale"])){
          $message = "Please enter a valid cost or sale cost for the book!";
    }elseif(!numeric($_POST["add_intake"])){
      $message = "Please enter a valid numeric quantity of the book in stock!";
    }elseif($_POST["add_description"] !="" && (sqlMetaChars($_POST["add_description"]) || sqlInjection($_POST["add_description"]) || sqlInjectionUnion($_POST["add_description"]) || sqlInjectionSelect($_POST["add_description"]) || sqlInjectionInsert($_POST["add_description"]) || sqlInjectionDelete($_POST["add_description"]) || sqlInjectionUpdate($_POST["add_description"]) || sqlInjectionDrop($_POST["add_description"]) || crossSiteScripting($_POST["add_description"]) ||crossSiteScriptingImg($_POST["add_description"]))) 
      {
          $message = "Please enter a valid description!";
      }elseif($_POST["add_name"] !="" && (sqlMetaChars($_POST["add_name"]) || sqlInjection($_POST["add_name"]) || sqlInjectionUnion($_POST["add_name"]) || sqlInjectionSelect($_POST["add_name"]) || sqlInjectionInsert($_POST["add_name"]) || sqlInjectionDelete($_POST["add_name"]) || sqlInjectionUpdate($_POST["add_name"]) || sqlInjectionDrop($_POST["add_name"]) || crossSiteScripting($_POST["add_name"]) ||crossSiteScriptingImg($_POST["add_name"]))) 
      {
          $message = "Please enter a valid item name!";
      }else {
       $message = $db->addItem();
    }
  }
  //$message = $db->addItem();
  echo "<script type='text/javascript'>alert('$message');</script>";
}

//generate the edit item form after user selects an item from select menu
if(isset($_POST["select_item"])) 
{
    $r = $db->generateEditForm($_POST["items"]);
    echo display_edit_item_form($r);
    echo "<hr class='style-two'>";
}
//form for adding new item
echo "<h3 class='heading_index'>Add new Book!</h3>";

echo display_add_item_form();

echo "</div>";

?>

</body>
</html>
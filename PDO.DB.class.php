<?php
require_once("LIB_project1.php");

class DB {
	private $dbh;
  function __construct() {
    try {
        $this->dbh = new PDO("mysql:host={$_SERVER['DB_SERVER']};dbname={$_SERVER['DB']}",$_SERVER['DB_USER'],$_SERVER['DB_PASSWORD']);
        $this->dbh->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
    } catch (PDOexception $e) {
      echo($e->getMessage());
      die();
    }
  }

  //function to get catalog items from database
	function getItems($a) {
   try {
      include "Classes/Item.class.php";
      $data = array();
      $stmt = $this->dbh->prepare("select * from Course where Sale = 0 limit $a, 5");
      $stmt->execute();
      $stmt->setFetchMode(PDO::FETCH_CLASS,"Item");
      while($row = $stmt->fetch()){
        $data[] = $row;
      }
      return $data;
    }catch(PDOexception $e) {
      echo $e->getMessage();
      die();
    } 
}

// function to get number of pages depending upon data in catalog
function getPages() {
	 try {
      $data = array();
      $stmt = $this->dbh->prepare("select * from Course where Sale = 0");
      $stmt->execute();
      while($row = $stmt->fetch()){
        $data[] = $row;
      }
      return $data;
    }catch(PDOexception $e) {
      echo $e->getMessage();
      die();
    }
}

//function to get sale items
function getSaleItems() {
   		try {
	      include "Classes/SaleItem.class.php";
	      $data = array();
	      $stmt = $this->dbh->prepare("select * from Course where Sale > 0");
	      $stmt->execute();
	      $stmt->setFetchMode(PDO::FETCH_CLASS,"SaleItem");
	      while($row = $stmt->fetch()){
	        $data[] = $row;
	      }
	      return $data;
	    }
	    catch(PDOexception $e) {
	      echo $e->getMessage();
	      die();
		} 
	}

//function to add items to cart
function addToCart($id) {
	try {
    //echo $_SESSION["username"];
	  //to update the intake number
      $stmt = $this->dbh->prepare("update Course set Intake = Intake-1 where Id = :id");
      $stmt->execute(array("id"=>$id));

      //to add the data in Cart table
      $data = array();
      $stmt1 = $this->dbh->prepare("Select Name, Description, Price, Sale from Course where Id = :id");
      $stmt1->execute(array("id"=>$id));
      $stmt1->setFetchMode(PDO::FETCH_ASSOC);
      while($row = $stmt1->fetch()){
        
        $data[] = $row;
      }
      	foreach( $data as  $row) {

		      if($row['Sale'] == 0){
		      	$stmt2 = $this->dbh->prepare("insert into Cart(Name, Description, Quantity, Price, Cart_id) values(:name,:description,:quantity,:price,:Cid )");
		      	$stmt2->execute(array("name"=>$row["Name"],"description"=>$row["Description"],"quantity"=>1, "price"=>$row["Price"], "Cid"=>$_SESSION["username"]));
		      	return "New item added to Cart!";
		      }
		      else {
		      	$stmt2 = $this->dbh->prepare("insert into Cart(Name, Description, Quantity, Price, Cart_id) values(:name,:description,:quantity,:price,:Cid )");
		      	$stmt2->execute(array("name"=>$row["Name"],"description"=>$row["Description"],"quantity"=>1, "price"=>$row["Sale"],"Cid"=>$_SESSION["username"] ));
		      	return "New item added to Cart!";
		      }
  		}
      
    }catch(PDOexception $e) {
      echo $e->getMessage();
      die();
    }
}

//function to get items in the shopping cart of a particular user
function getCartItems() {
   		try {
	      include "Classes/CartItem.class.php";
	      $data = array();
	      $stmt = $this->dbh->prepare("select * from Cart where Cart_id = :Cid");
	      $stmt->execute(array("Cid"=>$_SESSION["username"]));
	      $stmt->setFetchMode(PDO::FETCH_CLASS,"CartItem");
	      while($row = $stmt->fetch()){
	        $data[] = $row;
	      }
	      return $data;
	    }
	    catch(PDOexception $e) {
	      echo $e->getMessage();
	      die();
		} 
	}

//function to clear all items in the shopping cart of a particular person
function remove_cart_items() {
	try {
      $data = array();
      $stmt = $this->dbh->prepare("delete from Cart where Cart_id = :Cid");
      $stmt->execute(array("Cid"=>$_SESSION["username"]));
      return "Cart emptied";
    }catch(PDOexception $e) {
      echo $e->getMessage();
      die();
    }
}

//function to clear a particular item from cart
function removeItem($id){
	try {
      $data = array();
      $stmt = $this->dbh->prepare("delete from Cart where Id = :id");
      $stmt->execute(array("id"=>$id));
      return "Item removed from Cart.";
    }catch(PDOexception $e) {
      echo $e->getMessage();
      die();
    }
}

//function to create select option menus depending upon the number of items in the database
function selectMenuNames() {
	 $data = array();
     $stmt1 = $this->dbh->prepare("Select Name from Course");
     $stmt1->execute(array());
     $stmt1->setFetchMode(PDO::FETCH_ASSOC);
     while($row = $stmt1->fetch()){
       $data[] = $row;
	}
	return $data;
}

//function to generate edit form data
function generateEditForm($name){
	try {
	        $stmt = $this->dbh->prepare("Select * from Course where Name = :name");
	        $stmt->execute(array("name"=>$name));
	        $stmt->setFetchMode(PDO::FETCH_ASSOC);
		      $row = $stmt->fetch();
		    return $row;
		}catch(PDOexception $e) {
		      echo $e->getMessage();
		      die();
		} 
}

//function to edit information about an item ensuring the number of items in sale requirement is not voiated
function editItem() {
  try {
    //to update the item
      $name = sanitize($_POST["name"]);
      $des = sanitize($_POST["description"]);
      $price = sanitize($_POST["price"]);
      $intake = sanitize($_POST["intake"]);
      $sale = sanitize($_POST["sale"]);
      $id = $_POST["ID"];
      $num = 0;
      $stmt1 = $this->dbh->prepare("select count(*) from Course where Sale > 0");
      $stmt1->execute();
      $num = $stmt1->fetchColumn(); 
      //echo $num;
      $row = array();
      $stmt2 = $this->dbh->prepare("Select Sale from Course where Id = :id");
      $stmt2->execute(array("id"=>$id));
      $stmt2->setFetchMode(PDO::FETCH_ASSOC);
      $row = $stmt2->fetch();
      if($row["Sale"] <> $sale && $row["Sale"] == 0) 
      {
        if($num == 5){
          return "Update cannot be made.There must be a minimum of 3 items in sale and a maximum of 5 items.";
        }
        else {
            $stmt = $this->dbh->prepare("Update Course set Name = :name, Description = :description, Price = :price, Intake = :intake, Sale = :sale where Id = :id");
            $stmt->execute(array("name"=>$name, "description"=>$des, "price"=>$price, "intake"=>$intake, "sale"=>$sale,"id"=>$id)); 
            return "Item updated successfully";
        }
      }
      elseif($row["Sale"] <> $sale && $row["Sale"] <> 0) 
      {
        if($sale == 0) {
          if($num == 3)
          {
            return "Update cannot be made.There must be a minimum of 3 items in sale and a maximum of 5 items.";
          }
          else {
            $stmt = $this->dbh->prepare("Update Course set Name = :name, Description = :description, Price = :price, Intake = :intake, Sale = :sale where Id = :id");
            $stmt->execute(array("name"=>$name, "description"=>$des, "price"=>$price, "intake"=>$intake, "sale"=>$sale,"id"=>$id)); 
            return "Item updated successfully"; 
          }
        }
        else {
            $stmt = $this->dbh->prepare("Update Course set Name = :name, Description = :description, Price = :price, Intake = :intake, Sale = :sale where Id = :id");
            $stmt->execute(array("name"=>$name, "description"=>$des, "price"=>$price, "intake"=>$intake, "sale"=>$sale,"id"=>$id)); 
            return "Item updated successfully"; 
          }
      } 
      else {
        $stmt = $this->dbh->prepare("Update Course set Name = :name, Description = :description, Price = :price, Intake = :intake, Sale = :sale where Id = :id");
            $stmt->execute(array("name"=>$name, "description"=>$des, "price"=>$price, "intake"=>$intake, "sale"=>$sale,"id"=>$id)); 
            return "Item updated successfully"; 
      }
    }catch(PDOexception $e) {
      echo $e->getMessage();
      die();
    }
}

//function to a new item to the database
function addItem() {
  try{
      $name = sanitize($_POST["add_name"]);
      $des = sanitize($_POST["add_description"]);
      $price = sanitize($_POST["add_price"]);
      $intake = sanitize($_POST["add_intake"]);
      $sale = sanitize($_POST["add_sale"]);
      $img = sanitize($_POST["add_image"]);
      $num = 0;
      $stmt1 = $this->dbh->prepare("select count(*) from Course where Sale > 0");
      $stmt1->execute();
      $num = $stmt1->fetchColumn(); 
      if($num == 5 && $sale <> 0){
        return "Cannot add item to Sale Category. There can be a maximum of 5 items on Sale at a time.";
      }
      else {
        $stmt = $this->dbh->prepare("insert into Course (Name, Description, Price, Intake, ImageName, Sale) values (:name,:desc,:price,:intake,:imgName,:sale)");
        $stmt->execute(array("name"=>$name, "desc"=>$des, "price"=>$price, "intake"=>$intake, "imgName"=>$img, "sale"=>$sale));
        return "New item added successfully";
      }
    }catch(PDOexception $e) {
      echo $e->getMessage();
      die();
  }
}

//function to check the username, password and user type submitted by the user
function checkCredentials($name, $pass, $type) {
   try{
      $stmt = $this->dbh->prepare("select * from Users where Username = :name");
      $stmt->execute(array("name"=>$name));
      $stmt->setFetchMode(PDO::FETCH_ASSOC);
      $row = $stmt->fetch();

      if($row["Password"] == $pass && $row["Type"] == $type ){
        return 1;
      }
      else {
        return 0;
      }
    }catch(PDOexception $e) {
      echo $e->getMessage();
      die();
  }
}

}//class







?>
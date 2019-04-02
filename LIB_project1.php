<?php

//function for generating nav_bar
function nav_bar ($s) {
	$bigString = "";
	$bigString .= "	<nav class='navbar navbar-inverse'> \n
					  	<div class='container-fluid'> \n
					    	<div class='navbar-header'> \n
					      		<a class='navbar-brand' id='brand_name' href='index.php'>BookWorm - All your most wanted books, under one hat!</a> \n
					    	</div> \n
						    <ul class='nav navbar-nav navbar-right'> \n
						      <li class='active'><a href='index.php'>Home</a></li> \n
						      <li><a href='cart.php'>Cart</a></li>";
	if($s == "A"){
		$bigString .= 		" <li><a href='admin.php'>Admin</a></li> \n
						    </ul> \n
						    <form class='navbar-form navbar-right' method='post'> \n
						      <div class='form-group'> \n
						        <input type='submit' name='logout' id='logout_btn' class='btn btn-primary' value='Logout'> \n
						      </div> \n
						</form> \n
					  	</div>\n
					</nav> ";
	}
	else {
		$bigString .=  " </ul> \n
							<form class='navbar-form navbar-right' method='post'> \n
						      <div class='form-group'> \n
						        <input type='submit' name='logout' id='logout_btn' class='btn btn-primary' value='Logout'> \n
						      </div> \n
						    </form> \n
					  	</div>\n
					</nav> ";
	}		     
	return $bigString;
}

//function for displaying catalog items
function display_items($arr) {
	$bigString = "";
	foreach($arr as $row)
	{
		$bigString .=  "<form method='post' action='index.php?page=".$_SESSION["page"]."&id={$row->getId()}'> \n 
                                        <div class='card' style='width: 28rem; margin-bottom:10px; border-radius:'> \n
                                            <img class='card-img-top' src='Assets/{$row->getImageName()}' alt='{$row->getImageName()}'> \n
                                            <div class='card-body' style='background-color:white;margin:0;height:200px'> \n
                                                <h5 class='card-title' style='text-align:center; font-weight:bolder;margin:0'>{$row->getName()}</h5><hr style='margin:5px'>\n
                                                <p class='card-text' style='text-align:center;margin:0; font-size:12px;'>{$row->getDescription()}</p> \n
                                            </div> \n
                                            <ul class='list-group list-group-flush' style='margin:0'>\n
                                                <li class='list-group-item'>Quantity: {$row->getIntake()}</li>\n
                                                <li class='list-group-item'>Price: {$row->getPrice()}</li>\n
                                            </ul>\n
                                            <div class='card-body' style='background-color:white;margin:0'>\n
                                                <input type='submit' name = 'add_to_cart' id='add_to_cart_btn' class='btn btn-primary' value='Add to cart'>\n
                                            </div>\n
                                        </div></form>";

	}
	 return $bigString;
}

//function for displaying sale items
function display_sale_items($arr) {
	$bigString = "";
	foreach($arr as $row)
	{
		$bigString .=  "<form method='post' action='index.php?page=".$_SESSION["page"]."&id={$row->getId()}'> \n 
                                        <div class='card' style='width: 28rem; margin-bottom:10px; border-radius:'> \n
                                            <img class='card-img-top' src='Assets/{$row->getImageName()}' alt='{$row->getImageName()}'> \n
                                            <div class='card-body' style='background-color:white;margin:0;height:200px'> \n
                                                <h5 class='card-title' style='text-align:center; font-weight:bolder;margin:0'>{$row->getName()}</h5><hr style='margin:5px'>\n
                                                <p class='card-text' style='text-align:center;margin:0; font-size:12px;'>{$row->getDescription()}</p> \n
                                            </div> \n
                                            <ul class='list-group list-group-flush' style='margin:0'>\n
                                                <li class='list-group-item'>Quantity: {$row->getIntake()}</li>\n
                                                <li class='list-group-item'>Price:{$row->getPrice()}</li>\n
                                                <li class='list-group-item'>Sale Price: {$row->getSale()}</li>\n
                                            </ul>\n
                                            <div class='card-body' style='background-color:white;margin:0'>\n
                                                <input type='submit' name = 'add_to_cart' id='add_to_cart_btn' class='btn btn-primary' value='Add to cart'>\n
                                            </div>\n
                                        </div></form>";

	}
	 return $bigString;
}

//function for displaying cart items
function display_cart_items($input) {
	$arr = array();
	$totalPrice = 0;
	$bigString = "";
	foreach($input as $row)
	{
		$bigString .=  "<form method='post' action='cart.php?id={$row->getId()}'> \n  
                                        <div class='card' style='width: 28rem; margin-bottom:10px; border-radius:'> \n
                                             <h3 class='card-title' style='margin:0;background-color:#0275d8'>Item</h3> \n
                                            <div class='card-body' style='background-color:white;margin:0;height:200px;'> \n
                                                <h5 class='card-title' style='text-align:center; font-weight:bolder;margin:0'>{$row->getName()}</h5><hr style='margin:5px'> \n
                                                <p class='card-text' style='text-align:center;margin:0; font-size:12px;'>{$row->getDescription()}</p> \n
                                            </div> \n
                                            <ul class='list-group list-group-flush' style='margin:0'>\n
                                                <li class='list-group-item'>Quantity: {$row->getQuantity()}</li>\n
                                                <li class='list-group-item'>Price: {$row->getPrice()} </li>\n
                                            </ul>\n
                                            <div class='card-body' style='background-color:white;margin:0'>\n
                                                 <input type='submit' name = 'remove_item_from_cart' id='remove_from_cart_btn' class='btn btn-danger'value='Remove from cart'>\n
                                            </div>\n
                                        </div> </form>";
       
        $totalPrice += $row->getPrice();
	}
	 $arr[] = $bigString;
	 $arr[] = $totalPrice;
	 return $arr;
}

//function for dynamically creatinf select option menu for items
function create_option_menus($input) {
	$bigString = "";
	$bigString .= " <form method='post' action='admin.php' style='width:100%;padding-left:2px;'>\n
				    <select name='items' style='margin-left:10px;height:35px; width: 80%;border-radius:4px;'>";
	foreach($input as $row ) {
		$bigString .=  "<option name ='{$row["Name"]}' value= '{$row["Name"]}'>{$row['Name']}</option>";
	}
	$bigString .=  "</select> \n
					<input type='submit' class='btn btn-md btn-primary' name='select_item' value='Select an item' /> \n
					</form>";

	return $bigString;
	
}

//function for displaying edit item form
function display_edit_item_form ($input) {
  if(isset($_POST["select_item"])){

  }
	$bigString  =               "<div class='container' id='edit_item_cont'> \n
									<form id='edit_form' method='post' action='admin.php'> \n 
                                             <div class='form-group row'> \n
                                                <label class='control-label col-md-2' for='Name'>Name:</label> \n
                                                <div class='col-md-10'> \n
                                                  <input type='text' name ='name' class='form-control' id='name' value='{$input["Name"]}'> \n
                                                </div> \n
                                              </div> \n
                                              <div class='form-group row'> \n
                                                <label class='control-label col-md-2' for='Description'>Description:</label> \n
                                                <div class='col-md-10'> \n 
                                                  <input type='text' name ='description' class='form-control' id='description' value='{$input["Description"]}'> \n
                                                </div> \n
                                              </div> \n
                                              <div class='form-group row'> \n
                                                <label class='control-label col-md-2' for='Price'>Price:</label> \n
                                                <div class='col-md-10'> \n 
                                                  <input type='text' name ='price' class='form-control' id='price' value='{$input["Price"]}'> \n
                                                </div> \n
                                              </div> \n
                                              <div class='form-group row'> \n
                                                <label class='control-label col-md-2' for='Intake'>Quantity:</label> \n
                                                <div class='col-md-10'> \n 
                                                  <input type='text' name ='intake' class='form-control' id='intake' value='{$input["Intake"]}'> \n
                                                </div> \n
                                              </div> \n
                                              <div class='form-group row'> \n
                                                <label class='control-label col-md-2' for='Image'>Image:</label> \n
                                                <div class='col-md-10'> \n 
                                                  <input type='file' class='form-control' id='image' name='image' accept='image/*'> \n
                                                </div> \n
                                              </div> \n
                                              <div class='form-group row'> \n
                                                <label class='control-label col-md-2' for='Sale'>Sale Price:</label> \n
                                                <div class='col-md-10'> \n 
                                                  <input type='text' name ='sale' class='form-control' id='sale' value='{$input["Sale"]}'> \n
                                                  <input type='hidden' name ='ID' class='form-control' id='sale' value='{$input["Id"]}'> \n
                                                </div> \n
                                              </div> \n
                                              <div class='form-group row'> \n 
                                                <div class='col-md-offset-2 col-md-10'> \n
                                                  <button name = 'edit_item' type='submit' class='btn btn-md btn-primary'>Submit</button> \n
                                                </div> \n
                                              </div> \n
                                        </form></div>";
    return $bigString;
}

//function for displaying add item form
function display_add_item_form () {
  $Name = ""; $des= ""; $price = ""; $intake = ""; $sale = "";
  if(isset($_POST['add_name'])) $Name =  $_POST['add_name'];
  if(isset($_POST['add_description'])) $des =  $_POST['add_description'];
  if(isset($_POST['add_price'])) $price =  $_POST['add_price'];
  if(isset($_POST['add_intake'])) $intake =  $_POST['add_intake'];
  if(isset($_POST['add_sale'])) $sale =  $_POST['add_sale'];
	$bigString =   "<div class='container' id='add_item_cont'> \n
					<form id='add_item_form' method='post' action='admin.php'> \n 
                                             <div class='form-group row'> \n
                                                <label class='col-md-2' for='Name'>Name:</label> \n
                                                <div class='col-md-10'> \n
                                                  <input type='text' name ='add_name' class='form-control' id='name' value='{$Name}'> \n
                                                </div> \n
                                              </div> \n
                                              <div class='form-group row'> \n
                                                <label class='control-label col-md-2' for='Description'>Description:</label> \n
                                                <div class='col-md-10'> \n 
                                                  <input type='text' name ='add_description' class='form-control' id='description' value='{$des}'> \n
                                                </div> \n
                                              </div> \n
                                              <div class='form-group row'> \n
                                                <label class='control-label col-md-2' for='Price'>Price:</label> \n
                                                <div class='col-md-10'> \n 
                                                  <input type='text' name ='add_price' class='form-control' id='price' value='{$price}'> \n
                                                </div> \n
                                              </div> \n
                                              <div class='form-group row'> \n
                                                <label class='control-label col-md-2' for='Intake'>Quantity:</label> \n
                                                <div class='col-md-10'> \n 
                                                  <input type='text' name ='add_intake' class='form-control' id='intake' value='{$intake}'> \n
                                                </div> \n
                                              </div> \n
                                              <div class='form-group row'> \n
                                                <label class='control-label col-md-2' for='Image'>Image:</label> \n
                                                <div class='col-md-10'> \n 
                                                  <input type='file' class='form-control' id='image' name='add_image' accept='image/*'> \n
                                                </div> \n
                                              </div> \n
                                              <div class='form-group row'> \n
                                                <label class='control-label col-md-2' for='Sale'>Sale Price:</label> \n
                                                <div class='col-md-10'> \n 
                                                  <input type='text' name ='add_sale' class='form-control' id='sale' value='{$sale}'> \n
                                                </div> \n
                                              </div> \n
                                              <div class='form-group row'> \n 
                                                <div class='col-md-offset-2 col-md-10'> \n
                                                  <button name = 'reset_form' type='submit' class='btn btn-md btn-secondary '>Reset</button> \n
                                                  <button name = 'add_item' type='submit' class='btn btn-md btn-primary'>Submit</button> \n
                                                </div> \n
                                              </div> \n
                                        </form></div>";
    return $bigString;
}

//function to santize input
function sanitize($value) {
  $value = trim($value);
    $value = stripslashes($value);
    $value = htmlspecialchars($value);
    $value = strip_tags($value);
    return $value;
}

//function to match alphabetic input
function alphabetic($value) {
  $reg = "/^[A-Za-z]+$/";
  return preg_match($reg,$value);
}

//function to match numeric input
function numeric($value) {
  $reg = "/^[0-9]+$/";
  return preg_match($reg,$value);
}

//function for checking cost value (int or float)
function numeric_cost($value) {
  $reg = "/(^-?\d\d*\.\d*$)|(^-?\d\d*$)|(^-?\.\d\d*$)/";
  return preg_match($reg,$value);
}

function sqlMetaChars($value) {
  $reg = "/((\%3D)|(=))[^\n]*((\%27)|(\')|(\-\-)|(\%3B)|(;))/i";
  return preg_match($reg,$value);
}

function sqlInjection($value) {
  $reg = "/\w*((\%27)|(\'))((\%6F)|o|(\%4F))((\%72)|r|(\%52))/i";
  return preg_match($reg,$value);
}

function sqlInjectionUnion($value) {
  $reg = "/((\%27)|(\'))union/i";
  return preg_match($reg,$value);
}

function sqlInjectionSelect($value) {
  $reg = "/((\%27)|(\'));\s*select/i";
  return preg_match($reg,$value);
}

function sqlInjectionInsert($value) {
  $reg = "/((\%27)|(\'));\s*insert/i";
  return preg_match($reg,$value);
}

function sqlInjectionDelete($value) {
  $reg = "/((\%27)|(\'));\s*delete/i";
  return preg_match($reg,$value);
}

function sqlInjectionDrop($value) {
  $reg = "/((\%27)|(\'));\s*drop/i";
  return preg_match($reg,$value);
}

function sqlInjectionUpdate($value) {
  $reg = "/((\%27)|(\'));\s*update/i";
  return preg_match($reg,$value);
}

function crossSiteScripting($value) {
  $reg = "/((\%3C)|<)((\%2F)|\/)*[a-z0-9\%]+((\%3E)|>)/i";
  return preg_match($reg,$value);
}

function crossSiteScriptingImg($value) {
  $reg = "/((\%3C)|<)((\%69)|i|(\%49))((\%6D)|m|(\%4D))((\%67)|g|(\%47))[^\n]+((\%3E)|>)/i";
  return preg_match($reg,$value);
}












?>
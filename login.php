<!DOCTYPE html>
<html lang="en">
<head>
  <title>BookWorm</title>
  <link rel="stylesheet" href="Includes/Index.css">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</head>
<body>

<?php
	include_once("LIB_project1.php");
	require_once("PDO.DB.class.php");

	session_name("Books");
	session_start();

	$NErr = ""; 
	$PErr = "";

		//to check if the user has already logged in
		if(isset($_SESSION["user_type"])) {
			header("Location:index.php");
		}

		$db = new DB();

		//after submitting the login form
		if(isset($_POST["login_form"])) {
			$flag = true;
			if (empty($_POST["name"])) 
			{
				echo "here";
				$NErr = "Name is required"; 
				echo $NErr;
				$flag = false;		    
			}
			else 
			{
				$Name = sanitize($_POST["name"]);
				if (!alphabetic($Name)) 
				{
				   	$NErr = "Please enter a valid username. Only alphabets allowed.";
				   	$flag = false;
				}
			} 
			if (empty($_POST["pwd"])) 
			{
				$PErr = "Password is required"; 
				$flag = false;		    
			} 
			if($flag) {
				$d = $db->checkCredentials($_POST['name'],$_POST['pwd'],$_POST['user_type'] );

				if($d == 1) {
				$_SESSION["user_type"] = $_POST['user_type'];
				$_SESSION["username"] = $_POST['name'];
				header("Location:index.php");
				}
				else {
					echo "<h4 style='color:red'>Username, password or user-type incorrect.</h4>";
				}
			}
}

?>

<div class= "container" style="margin-top: 20px; width:50%"><img style="height:60%;border-radius:5px" src="Assets/login_img.jpg">
<div class="container" id="login_form_cont">
	<form id="login_form" method="post" <?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>>
	  <div class="form-group row">
	    <label for="email" class="col-md-2">Username:</label>
	    <div class="col-md-8">
	    	<input type="text" class="form-control" name="name" id="name">
	    </div>
	    <span class="col-sm-2"style="color:red;">* <?php echo $NErr ?></span>
	  </div>
	  <div class="form-group row">
	    <label class="col-md-2" for="pwd">Password:</label>
	    <div class="col-md-8">
	    	<input type="password" class="form-control" name = "pwd" id="pwd"  >
	    </div>
	    <span class="col-sm-2"style="color:red;">* <?php echo $PErr ?></span>
	  </div>
	  <div class="radio row">
	  	<label class="col-md-2" style="font-weight:700;padding-left:10px;">Type of User:</label>
	    <label class="col-md-1"><input value = "A" name = "user_type" type="radio" checked>Admin</label>
	    <label class="col-md-2"><input value ="U" name = "user_type" type="radio">User</label>
	  </div>
	  <button type="submit" name="login_form" class="btn btn-md btn-success" style="width:200px">Login</button>
	</form>
</div>
</div>

</body>
</html>

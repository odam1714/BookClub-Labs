<?php
include ('config.php');
if(!isset($_SESSION))
	session_start();

//Open the database
@ $db = new mysqli($dbserver, $dbuser, $dbpass, $dbname);
	$wrongPassword = false;
//Check if can connect
if($db->connect_error){
  echo "Connection error: " . $db->connect_error;
  exit();
}

// function to escape string
function cleanString($val){
	$value = trim($val);
	$value = strip_tags($value);
	$value = htmlspecialchars($value);
	$value = mysqli_real_escape_string($GLOBALS['db'], $value);
	return $value;
}
	
	if(isset($_POST) && !empty($_POST)){
			
		$loginuser = cleanString($_POST['username']);
		$loginpass = cleanString($_POST['password']);

		$hashedloginpass = hash("gost", $loginpass);
	

		$query="SELECT Users.user_ID, Users.hashedpwd, Users.role FROM Users
		WHERE username ='".$loginuser."' ";
		$statement = $db->prepare ($query);
		$statement->bind_result($userid, $password, $role);
		$statement->execute();

		//check if the password is the same with a "while" statement, so we ask the DB ifthere is a username with that name to search for the password, otherwise don't check
		while($statement->fetch()){
			if($hashedloginpass == $password){
				$_SESSION['role'] = $role;
				$_SESSION['user'] = $userid;
				if($role == 1) header('location:users.php');
				if($role == 2) header('location:books.php');
				if($role == 3) header('location:mybooks.php');
			} else {
				$wrongPassword = true;
			}
		}
	}

 	include 'header.php';?>
			
	<div class="centerBlock">
		<h2>Login</h2>
		<?php echo $wrongPassword ? '<p class="error">Wrong password</p>' : ''; ?> 
		<form action="#" method="POST">
			<div class="form-row">
				<div class="form-group">
					<label for="username">Username</label>
					<input type="text" placeholder="Enter Username" name="username" required>
				</div>
				<div class="form-group">
					<label for="password">Password</label>
					<input type="password" placeholder="Enter Password" name="password" required>
				</div>
				<button class="submit-margins" type="submit" name="submit" value="login">Login</button>
			</div>
		</form>
	</div>


	<?php include 'footer.php';?>
	</body>
</html>

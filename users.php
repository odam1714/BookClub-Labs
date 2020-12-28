<?php
include ('functions.php');

$failedInsert = false; 
$failedDelete = false;
$failedToInserteMessage = '';
$failedToDeleteMessage = '';

if(isset($_POST['adduser_btn'])) {
	//define to escape values
	$username = cleanString($_POST['username']);
	$role = cleanString($_POST['role']);
	$password_1 = cleanString($_POST['password_1']);
	$password_2 = cleanString($_POST['password_2']);

	if($password_1 == $password_2) {
		$hashed = hash("gost", $password_1);
		$addUser = addUser($username, $hashed, $role); 
		if (count($deletedUser) == 0) { // if array empty it succeeded!
			header('location: users.php');
		} else {
			$failedInsert = true;
			$failedToInserteMessage = $addUser['message'];
		}
	} else {
		$failedInsert = true;
		$failedToInserteMessage = "Passwords aren't matching";
	}
}

if(isset($_POST['delete_user'])) {
	$user_id = cleanString($_POST['delete_user']);

	$deletedUser = deleteUser($user_id);
	if (count($deletedUser) == 0) { // if array empty it succeeded!
		header('location: users.php');
	} else {
		$failedDelete = true;
		$failedToDeleteMessage = $deletedUser['message'];
	}
}

$users = getUsers();
 
include("header.php");
?>
	<div class="centerBlock">
		<h2>Users</h2>
		<table cellpadding="6" id="adminbooks">
			<thead>
				<tr>
					<td>User ID</td>
					<td>Username</td>
					<td>Action</td>
				</tr>
			</thead>
			<tbody>
				<?php 
					foreach($users as $user) {
						echo "<tr>
										<td>".$user['user_id']."</td>
										<td>".$user['username']."</td>
										<td>";
						if(!$user['has_books']) {
							echo "<form method='POST' action='users.php'>
										<button name='delete_user' value='".$user['user_id']."' type='submit'>Delete</button>
									</form>";
						} else {
							echo "User has books";
						}
						echo "</td>
								</tr>";	
					}
				?>
			</tbody>
		</table>
	</div>
	<div class="centerBlock">
	<h2>Add New User</h2>
	<?php echo $failedInsert ? $failedToInserteMessage : ''; ?> 
	<form name="test" action="users.php" method="POST">
		<div>
			<label class="label">Username</label>
			<input type="text" name="username" placeholder="Choose username" value="">
		</div>
		<div>
			<label class="label">User type</label>
			<input type="number" name="role" placeholder="Check note" max="3">
			<span class="note"><b>Note:</b> admin=1, moderator=2, user=3</span>
		</div>
		<div >
			<label class="label">Password</label>
			<input type="password" name="password_1" placeholder="Choose password">
		</div>
		<div>
			<label class="label">Confirm password</label>
			<input type="password" name="password_2" placeholder="Repeat password">
		</div>
	<div>
		<input type="submit" class="btn" name="adduser_btn" value="Add user"/>
	</div>
		</form>
	</div>
	
</body>

<?php include("footer.php") ?>

</html>
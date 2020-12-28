<?php 
	include 'functions.php';

	$failedToReturn = false;
	$failedToReturnMessage = '';

	if(isset($_POST['return_book'])) {
		$reservedid = trim($_POST['return_book']);
		$return = returnBook($reservedid);
		if (count($return) == 0) { // if array empty it succeeded!
			header('location: mybooks.php');
		} else {
			$failedToReturn = true;
			$failedToReturnMessage = $return['message'];
		}
	}

	$books = getMyBooks();

	include 'header.php';
?>

		<div class="infoblock">
			<h2>My Books</h2>
			<p>Find your personal library right here at your fingertips. You can never 
			have too many books, so add everything your heart desires. Finished 
			reading a book? Select the button to return it. As simple as that! 
			Running out of books to read? No worries! <b>Find your next read <a href="browse.php" class="welcomelink">here!</a></b></p>
		</div>
		
		
		<div class="centerBlock">
			<h2>Reserved</h2>
		<?php echo $failedToReturn ? $failedToReturnMessage : ''; ?>
			<table cellpadding="7">
				<thead>
					<tr>
						<td>ID</td>
						<td>Title</td>
						<td>Author</td>
						<td>ISBN</td>
						<td>Reserved</td>
					</tr>
				</thead>
				<tbody>
				<?php	
					foreach($books as $book) {
						echo "
						<tr>
							<td>".$book['bookid']."</td>
							<td>".$book['title']."</td>
							<td>".$book['author_first']." ".$book['author_last']."</td>
							<td>".$book['isbn']."</td>
							<td>
								<form method='POST' action='mybooks.php'>
									<button name='return_book' value='".$book['reservedid']."' type='submit'>Return</button>
								</form>
							</td>
						</tr>";
					}
				?>
				</tbody>
			</table> 
		</div>
	<?php include 'footer.php';?>
	</body>
</html>
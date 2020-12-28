<?php 
	include 'functions.php';

	$searchtitle = "";
	$searchauthor = "";

	$failedToReserve = false;
	$failedReserveMessage = '';

	if(isset($_POST['reserve_book'])) {
		$bookid = cleanString($_POST['reserve_book']);
		$reserveBook = reserveBook($bookid);
		if (count($reserveBook) == 0) { // if array empty it succeeded!
			header('location: browse.php');
		} else {
			$failedToReserve = true;
			$failedReserveMessage = $reserveBook['message'];
		}
	}

	if(isset($_POST['title']) || isset($_POST['author'])){
		$searchtitle = cleanString($_POST['title']);
		$searchauthor = cleanString($_POST['author']);
	} 

	$books = getBooksForBrowsing($searchtitle, $searchauthor);

	include 'header.php';

?>

	<div class="infoblock">
		<h2>Browse Books</h2>
		<p>Looking for a brand new read? Or maybe you're more interested in the classics? Whatever you book preference, find it here! With thousands and thousands of books available in our library, you are guaranteed to find something you love! <b>Can't find what you're looking for? Reach out to us <a href="contact.php" class="welcomelink">here!</a></b></p>
	</div>

	<div class="centerBlock">
		<h2>Browse</h2>
			<form action="" method="post">
				<div class="form-row">
					<div class="form-group"> 
						<label class="search-label">Search by Author:</label>
						<input type="text" id= "author" name="author" placeholder="Enter Author's Name">
					</div>
					<div class="form-group"> 
						<label class="search-label">Search by Title:</label>
						<input type="text" id= "title" name="title"  placeholder="Enter Book Title">
					</div>
						<input class="submit-margins" type="submit" name="Search">
				</div> 
			</form>	
	
		<?php echo $failedToReserve ? $failedReserveMessage : '';?>
		<table cellpadding="7">
			<thead>
				<tr>
					<td>ID</td>
					<td>Title</td>
					<td>Author</td>
					<td>ISBN</td>
					<td>Reserve</td>
				</tr>
			</thead>
		<?php
		foreach($books as $book) {
				echo "
					<tr>
						<td>".$book['bookid']."</td>
						<td>".$book['title']."</td>
						<td>".$book['author_first']." ".$book['author_last']."</td>
						<td>".$book['isbn']."</td>
						<td>";
					
					if($user == 0) {
						echo "Log in first";
					} else if($book['user_reserved'] > 0) {
						echo "Reserved by you";
					} else if($book['available'] > 0) {
						echo "<form method='POST' action='browse.php'>
										<button name='reserve_book' value='".$book['bookid']."' type='submit'>Reserve</button>
									</form>";
					} else {
						echo "Not available";
					}
				echo "		
						</td>
					</tr>";
		}
		?>

		</table>
		
	</div>
	
	<?php include 'footer.php';?>

	</body>

</html>
<?php
	include ('functions.php');
	
	$failedInsert = false;
	$failedCopies = false;
	$failedDelete = false;
	$failedToInsertMessage = '';
	$failedToInsertCopyMessage = '';
	$failedToDeleteMessage = '';
	
	if(isset($_POST['add_book_btn'])) {
		$title = cleanString($_POST['title']);
		$pages = cleanString($_POST['pages']);
		$isbn = cleanString($_POST['isbn']);
		$date = cleanString($_POST['date']);
		$author = cleanString($_POST['author']);
		$publisher = cleanString($_POST['publisher']);
		$edition = cleanString($_POST['edition']);

		$addBook = addBook($title, $pages, $isbn, $date, $author, $publisher, $edition);
		if (count($addBook) == 0) { // if array empty it succeeded!
			header('location: books.php');
		} else {
			$failedInsert = true;
			$failedToInsertMessage = $addBook['message'];
		}
	}

	if(isset($_POST['add_copy_btn'])) {
		$book = cleanString($_POST['book']);
		$nr_copies = cleanString($_POST['nr_copies']);

		$add_copies = addCopies($book, $nr_copies);
		if (count($add_copies) == 0) { // if array empty it succeeded!
			header('location: books.php');
		} else {
			$failedCopies = true;
			$failedToInsertCopyMessage = $add_copies['message'];
		}
	}

	if(isset($_POST['delete_book'])) {
		$book_id = cleanString($_POST['delete_book']);
		$deletedBook = deleteBookFromLibrary($book_id);
		if (count($deletedBook) == 0) { // if array empty it succeeded!
			header('location: books.php');
		} else {
			$failedDelete = true;
			$failedToDeleteMessage = $deletedBook['message'];
		}
	}

	$books = getAllBooks();
	$authors = getAllAuthors();
	$publishers = getAllPublishers();
	$books_copy = getBooksForAddingCopies();

	include("header.php");
?>

	<div class="centerBlock">
		<h2>Current books</h2>
		<?php echo $failedDelete ? $failedToDeleteMessage : ''; ?> 
		<table cellpadding="6" class="currentBooks">
			<thead>
				<tr>
					<td>Book ID</td><td>Title</td> <td>Author</td> <td>ISBN</td> <td>Publisher</td> <td>Year published</td> <td>Action</td>
				</tr>
			</thead>
			<tbody>
			<?php
					foreach($books as $book) {
						echo "<tr>
							<td>".$book['id']."</td>
							<td>".$book['title']."</td>
							<td>".$book['author_first']." ".$book['author_last']."</td>
							<td>".$book['isbn']."</td>
							<td>".$book['publisher']."</td>
							<td>".$book['published']."</td>
							<td>";
							if(!$book['is_reserved']) {
								echo "<form method='POST' action='books.php'>
										<button name='delete_book' value='".$book['id']."' type='submit'>Delete</button>
									</form>";
							} else {
								echo "Reserved";
							}
							echo "</td>
						</tr>";
					}
			?>
			</tbody>
		</table>
	</div>
	<div class="centerBlock">
		<h2>Add book</h2>
		<?php echo $failedInsert ? $failedToInsertMessage : '';?> 
		<form action="books.php" method="POST">
				<div>
					<label class="label">Title</label>
					<input type="text" id="title" name="title" placeholder="Add book title" value="">
				</div>
				<div>
					<label class="label">Pages</label>
					<input type="number" id="pages" name="pages" placeholder="Add page amount" value="">
				</div>
				<div>
					<label class="label">ISBN</label>
					<input type="number" id="isbn" name="isbn" placeholder="Add ISBN" value="">
				</div>
				<div>
					<label class="label">Edition number</label>
					<input type="number" id="isbn" name="edition" placeholder="Add edition" value="">
				</div>
				<div>
					<label class="label">Publication year</label>
					<input type="number" id="date" name="date" placeholder="Year published" value="">
				</div>
				<div>
					<label class="label">Author</label>
					<select name="author">
						<option value="">Select Author</option>
						<?php
							foreach($authors as $author) {
								echo "<option value='".$author['id']."'>"
								.$author['first_name']." ".$author['last_name']
								."</option>";
							}
						?>
					</select>
				</div>
				<div>
					<label class="label">Publisher</label>
					<select name="publisher">
					<option value="">Select Publisher</option> 
					<?php 
						foreach($publishers as $publisher) {
							echo "<option value='".$publisher['id']."'>".$publisher['publisher_name']."</option>";
						}
					?>
					</select>
				</div>
				<input type="submit" class="btn" name="add_book_btn" value="Add book" />
		</form>
	</div>
	<div class="centerBlock">
		<h2>Add copy</h2>
		<?php echo $failedCopies ? $failedToInsertCopyMessage : '';?> 
		<form action="books.php" method="POST">
			<div>
				<label class="label">Book</label>
				<select name="book" onchange="test()" id="copy">
					<option value="">Select book</option>
					<?php
						foreach($books_copy as $book) {
							echo "<option value='".$book['id']."'>"
							.$book['title'].", ".$book['published'].", ".$book['edition'].", " .$book['isbn']
							."</option>";
						}
					?>
				</select>
				<div>
					<label class="label">Number of copies</label>
					<input type="number" name="nr_copies" placeholder="Number of copies" value="">
				</div>
			</div>
				Chosen book
				<div class="choosen-book"><div>Title:</div><div id="c-title"></div> </div>
				<div class="choosen-book"><div>Published: </div><div id="c-published"></div> </div>
				<div class="choosen-book"><div>Edition: </div><div id="c-edition"></div> </div>
				<div class="choosen-book"><div>ISBN: </div><div id="c-isbn"></div> </div>
				
				
			<div>
			</div>
				<input type="submit" class="btn" name="add_copy_btn" value="Add copy" />
		</form>
	</div>
	<br/>
</body>
<?php include("footer.php") ?>

<script>
	var books = <?php echo json_encode($books_copy); ?>;
	function test() {
		var bookid = document.getElementById('copy').value;
		var book = books.filter(book => book.id == bookid)[0];
		document.getElementById('c-title').innerText = book.title;
		document.getElementById('c-published').innerText = book.published;
		document.getElementById('c-edition').innerText = book.edition;
		document.getElementById('c-isbn').innerText = book.isbn;
	}
</script>

</html>
<?php 
include ('config.php');

// check if session is started
if(!isset($_SESSION))
	session_start();

// set user (check if logged in)
$user = 0;
if(isset($_SESSION['user'])) {
	$user = $_SESSION['user'];
}

//Open the database
$db = new mysqli($dbserver, $dbuser, $dbpass, $dbname);

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

/* ------ users.php ------ */
function getUsers() {
	$query = "SELECT Users.user_ID, Users.username, has_reserved_books
						FROM Users 
						LEFT JOIN (
							SELECT count(reserved_id) as has_reserved_books, user_id
							FROM user_reserved_book
							GROUP BY user_id
						) book ON book.user_id = Users.user_id";
	$stmt = $GLOBALS['db']->prepare($query);
	$stmt->bind_result($user_ID, $username, $has_books);
	$stmt->execute();

	$users = [];
	while ($stmt->fetch()) {
		array_push($users, [
			'user_id' => $user_ID, 
			'username' => $username,
			'has_books' => $has_books
		]);
	}
	return $users;
}

function deleteUser($user_id) {
	$query = "DELETE FROM users WHERE user_id = ?";
	$stmt = $GLOBALS['db']->prepare($query);
	$stmt->bind_param('s', $user_id);
	$stmt->execute();

	if($stmt->affected_rows < 1) {
		return ['message' => "Oops! Couldn't delete user."];
	} 
	$stmt->close();
	
	return [];
}

function addUser($username, $hashed, $role) {
	$query = "INSERT INTO Users (username, hashedpwd, role) 
						VALUES(?, ?, ?)";
	$stmt = $GLOBALS['db']->prepare($query);
	$stmt->bind_param("sss", $username, $hashed, $role);
	$stmt->execute();

	if($stmt->affected_rows < 1) {
		return ['message' => "Oops! Couldn't add user."];
	} 
	$stmt->close();
	
	return [];
}


/* ------ books.php ------ */
function getAllBooks() {
	$query = "SELECT Books.book_ID, Books.book_title, Books.ISBN, Authors.first_name, Authors.last_name, publisher.publisher_name, Books.year_published, reserved_books.is_reserved
	FROM Books
	JOIN book_author ON Books.book_ID = book_author.book_ID
	JOIN publisher ON Books.publishing_company_id = publisher.publisher_id
	JOIN Authors ON Authors.author_ID = book_author.author_ID
	LEFT JOIN (
							SELECT count(status_id) as is_reserved, book_id
							FROM book_status
							WHERE reserved = 1
							GROUP BY book_id
						) reserved_books ON reserved_books.book_id = Books.book_ID
	ORDER BY Books.book_ID";


	$stmt = $GLOBALS['db']->prepare($query);
	$stmt->bind_result($ID, $title, $isbn, $author_first, $author_last, $publisher, $date, $is_reserved);
	$stmt->execute();

	$books = [];
	while ($stmt->fetch()) {
		array_push($books, [
			'id' => $ID, 
			'title' => $title, 
			'isbn' => $isbn,
			'author_first' => $author_first,
			'author_last' => $author_last,
			'publisher' => $publisher, 
			'published' => $date,
			'is_reserved' => $is_reserved
		]);
	}
	
	$stmt->close();
	return $books;
}

function getBooksForAddingCopies() {
	$query = "SELECT Books.book_ID, Books.book_title, Books.ISBN, Books.edition_number, Books.year_published
	FROM Books
	ORDER BY Books.book_title";


	$stmt = $GLOBALS['db']->prepare($query);
	$stmt->bind_result($ID, $title, $isbn, $edition, $date);
	$stmt->execute();

	$books = [];
	while ($stmt->fetch()) {
		array_push($books, [
			'id' => $ID, 
			'title' => $title, 
			'isbn' => $isbn,
			'edition' => $edition,
			'published' => $date,
		]);
	}
	
	$stmt->close();
	return $books;
}

function getAllAuthors() {
	$query = "SELECT author_id, first_name, last_name FROM authors";
	$stmt = $GLOBALS['db']->prepare($query);
	$stmt->bind_result($author_ID, $first_name, $last_name);
	$stmt->execute();
	$authors = [];
	while ($stmt->fetch()) {
		array_push($authors, [
			'id' => $author_ID, 
			'first_name' => $first_name, 
			'last_name' => $last_name,
		]);
	}
	$stmt->close();

	return $authors;
}

function getAllPublishers() {
	$query = "SELECT publisher_id, publisher_name FROM publisher";
	$stmt = $GLOBALS['db']->prepare($query);
	$stmt->bind_result($publisher_ID, $publisher_name);
	$stmt->execute();

	$publishers = [];
	while ($stmt->fetch()) {
		array_push($publishers, [
			'id' => $publisher_ID, 
			'publisher_name' => $publisher_name
		]);
	}

	$stmt->close();

	return $publishers;
}

// add book section
function addCopies($book_id, $nr_copies) {
	$fail = 0;
	for($i = 0; $i < $nr_copies; $i++) {
		$addStatus = addStatusBook($book_id);
		if(!$addStatus) {
			$fail = $fail + 1;
		}
	}
	if($fail == $nr_copies) {
		return ['message' => "Something went wrong. We couldn't add any of the copies!"];
	}
	if($fail > 0) {
		return ['message' => "Something went wrong. We couldn't add ".$fail." number of to the copies!"];
	}
	return [];
}

function addBook($title, $pages, $isbn, $date, $author, $publisher, $edition) {
	$book_id = addBookData($isbn, $title, $pages, $edition, $date, $publisher);
	if(!$book_id) {
		return ['message' => "Something went wrong. We couldn't add the book!"];
	}
	$addAuthor = addAuthorToBook($author, $book_id);
	if(!$addAuthor) {
		return ['message' => "Something went wrong. We couldn't add the author to the book!"];
	}
	$addStatus = addStatusBook($book_id);
	if(!$addStatus) {
		return ['message' => "Something went wrong. We couldn't add status to the book!"];
	}
	return [];
}

function addBookData($isbn, $title, $pages, $edition, $date, $publisher) {
	$query = "INSERT INTO books (isbn, book_title, total_pages, edition_number, year_published, publishing_company_id) 
	VALUES(?, ?, ?, ?, ?, ?)";
	$stmt = $GLOBALS['db']->prepare($query);
	$stmt->bind_param('ssssss', $isbn, $title, $pages, $edition, $date, $publisher);
	$stmt->execute();

	if($stmt->affected_rows < 1) {
		return false;
	} 
	$book_id = $stmt->insert_id;
	$stmt->close();

	return $book_id;
}

function addAuthorToBook($author, $book_id) {
	$query =  "INSERT INTO book_author (author_id, book_id) VALUES(?, ?)";
	$stmt = $GLOBALS['db']->prepare($query);
	$stmt->bind_param('ss', $author, $book_id);
	$stmt->execute();

	if($stmt->affected_rows < 1) {
	return false;
	} 
	$stmt->close();

	return true;
}

function addStatusBook($book_id) {
	$insertDate = date('Y-m-d');
	$shelfId = getNextShelfId();
	$barcode = getNextBarCode();
	if(!$shelfId || !$barcode) {
		echo 'in here';
		return false;
	}

	$query =  "INSERT INTO book_status (book_id, shelf_id, unique_barcode, date_added, reserved) 
	VALUES(?, ?, ?, ?, 0)";
	$stmt = $GLOBALS['db']->prepare($query);
	$stmt->bind_param('ssss', $book_id, $shelfId, $barcode, $insertDate);
	$stmt->execute();

	if($stmt->affected_rows < 1) {
		return false;
	} 
	$stmt->close();

	return true;
}

function getNextShelfId() {
	$query = "SELECT shelf_id FROM book_status ORDER BY shelf_id DESC LIMIT 1";
	$stmt = $GLOBALS['db']->prepare($query);
	$stmt->bind_result($shelf_id);
	$stmt->execute();

	$stmt->store_result();
	if($stmt->num_rows < 1) {
		return false;
	} 
	$shelf_id = 0;
	while ($stmt->fetch()) {
		$shelf_id = $shelf_id;
	}
	$stmt->close();
	
	$shelf_id = $shelf_id+1;
	return $shelf_id;
}

function getNextBarCode() {
	$query = "SELECT unique_barcode FROM book_status ORDER BY unique_barcode DESC LIMIT 1";
	$stmt = $GLOBALS['db']->prepare($query);
	$stmt->bind_result($unique_barcode);
	$stmt->execute();

	$stmt->store_result();
	if($stmt->num_rows < 1) {
		return false;
	} 
	$unique_barcode = 0;
	while ($stmt->fetch()) {
		$unique_barcode = $unique_barcode;
	}
	$stmt->close();
	
	$unique_barcode = $unique_barcode+1;
	return $unique_barcode;
}

// end of add book section

// delete books section
function deleteBookFromLibrary($book_id) {
	$deletedAuthorConn = deleteBookAuthor($book_id);
	if(!$deletedAuthorConn) {
		return ['message' => "Ooops! Couldn't delete the author."];
	}
	$deleteStatusConn = deleteStatusData($book_id);
	if(!$deleteStatusConn) {
		return ['message' => "Ooops! Couldn't delete the status data."];
	}
	$deleteBookData = deleteBookData($book_id);
	if(!$deleteBookData) {
		return ['message' => "Ooops! Couldn't delete the book data."];
	}
	return [];
}

function deleteBookAuthor($book_id) {
	$query = "DELETE FROM book_author WHERE book_id = ?";
	$stmt = $GLOBALS['db']->prepare($query);
	$stmt->bind_param('s', $book_id);
	$stmt->execute();

	if($stmt->affected_rows < 1) {
		return false;
	} 
	$stmt->close();
	
	return true;
}

function deleteStatusData($book_id) {
	$query = "DELETE FROM book_status WHERE book_id = ?";
	$stmt = $GLOBALS['db']->prepare($query);
	$stmt->bind_param('s', $book_id);
	$stmt->execute();

	if($stmt->affected_rows < 1) {
		return false;
	} 
	$stmt->close();
	
	return true;
}

function deleteBookData($book_id) {
	$query = "DELETE FROM books WHERE book_id = ?";
	$stmt = $GLOBALS['db']->prepare($query);
	$stmt->bind_param('s', $book_id);
	$stmt->execute();

	if($stmt->affected_rows < 1) {
		return false;
	} 
	$stmt->close();
	
	return true;
}
// end of delete books section

/* ------ browse.php ------ */

function getBooksForBrowsing($searchtitle, $searchauthor) {
	$query = "SELECT Books.book_id, Books.book_title, Books.ISBN, Authors.first_name, Authors.last_name, b_status.available, b_reserved.reserved  
						FROM Books
						JOIN book_author ON Books.book_id = book_author.book_id
						JOIN Authors ON Authors.author_id = book_author.author_id
						LEFT JOIN (
								SELECT count(reserved) as available, book_id 
								FROM book_status 
								WHERE reserved = 0 
								GROUP BY book_id
							) b_status ON b_status.book_id = Books.book_id
						LEFT JOIN (
							SELECT count(reserved) as reserved, book_id 
							FROM book_status s
								INNER JOIN user_reserved_book u ON u.status_id = s.status_id
							WHERE s.reserved = 1 AND u.user_id = ?
							GROUP BY book_id
						) b_reserved ON b_reserved.book_id = Books.book_id
						";

	if ($searchtitle || $searchauthor){
		$query = $query . " WHERE ";
		if($searchtitle) {
			$query = $query . "Books.book_title LIKE '%" . $searchtitle . "%'";
		}
		if($searchtitle && $searchauthor) {
			$query = $query . " AND ";
		}
		if($searchauthor) {
			$query = $query . "Authors.first_name LIKE '%" . $searchauthor . "%'";
		}
	}
	$query = $query . " ORDER BY Books.book_id";
	
	$stmt = $GLOBALS['db']->prepare($query);
	$stmt->bind_param('s', $GLOBALS['user']); 
	$stmt->bind_result($bookid, $title, $isbn, $author_first, $author_last, $available, $user_reserved);
	$stmt->execute();

	$books = [];
	while ($stmt->fetch()) {
		array_push($books, [
			'bookid' => $bookid, 
			'title' => $title, 
			'isbn' => $isbn,
			'author_first' => $author_first,
			'author_last' => $author_last,
			'available' => $available,
			'user_reserved' => $user_reserved
		]);
	}
	
	$stmt->close();
	
	return $books;
}

// reserve book section
function reserveBook($book_id) {
	$status_id = getAvailableBook($book_id);
	if(!$status_id) {
		return ['message' => "Ooops! Seems like we don't have a copy available right now."];
	}
	$bookReserved = setBookToReserved($status_id);
	if(!$bookReserved) {
		return ['message' => "Ooops! Seems like we can't reserve this book right now."];
	}
	$bookRegisteredUser = addBookToUser($status_id);
	if(!$bookReserved) {
		setBookBackToAvailable($status_id); // if we can't reserve book, we need to change status
		return ['message' => "Ooops! Seems like we can't reserve this book to you right now."];
	}
	return [];
}

function getAvailableBook($book_id) {
	$query = "SELECT status_id FROM book_status WHERE book_id = ? AND reserved = 0 LIMIT 1";
	$stmt = $GLOBALS['db']->prepare($query);
	$stmt->bind_param('s', $book_id); 
	$stmt->bind_result($status_id);
	$stmt->execute();

	$stmt->store_result();
	if($stmt->num_rows < 1) {
		return false;
	} 
	$status_id = '';
	while ($stmt->fetch()) {
		$status_id = $status_id;
	}
	$stmt->close();

	return $status_id;
}

function setBookToReserved($status_id) {
	$query = "UPDATE book_status SET reserved = 1 WHERE status_id = ?";
	$stmt = $GLOBALS['db']->prepare($query);
	$stmt->bind_param('s', $status_id);
	$stmt->execute(); 
	
	if($stmt->affected_rows < 1) {
		return false;
	} 
	$stmt->close();
	
	return true;
}

function addBookToUser($status_id) {
	$query = "INSERT INTO user_reserved_book (user_id, status_id) VALUES (?, ?)";
	$stmt = $GLOBALS['db']->prepare($query);
	$stmt->bind_param('ss', $GLOBALS['user'], $status_id);
	$stmt->execute();
	
	if($stmt->affected_rows < 1) {
		return false;
	} 
	$stmt->close();

	return true;
}

function setBookBackToAvailable($status_id) {
	$query = "UPDATE book_status SET reserved = 0 WHERE status_id = ?";
	$stmt = $db->prepare($query);
	$stmt->bind_param('s', $status_id);
	$stmt->execute(); 
	$stmt->close();
}

// end of reserve book section

/* ------ mybooks.php ------ */

function getMyBooks() {
	$query = "SELECT Books.book_id, Books.book_title, Books.ISBN, Authors.first_name, Authors.last_name, u.reserved_id FROM Books
					JOIN book_author ON Books.book_id = book_author.book_id
					JOIN Authors ON Authors.author_id = book_author.author_id
					JOIN book_status s ON s.book_id = Books.book_id
					JOIN user_reserved_book u ON u.status_id = s.status_id
					WHERE s.reserved=1 AND u.user_id = ?
					ORDER BY Books.book_id";

	$stmt = $GLOBALS['db']->prepare($query);
	$stmt->bind_param('s', $GLOBALS['user']);
	$stmt->bind_result($bookid, $title, $isbn, $author_first, $author_last, $reserved_id);
	$stmt->execute();

	$books = [];
	while ($stmt->fetch()) {
		array_push($books, [
			'bookid' => $bookid, 
			'title' => $title, 
			'isbn' => $isbn,
			'author_first' => $author_first,
			'author_last' => $author_last,
			'reservedid' => $reserved_id
		]);
	}
	$stmt->close();

	return $books;
}

function returnBook($reserved_id) {
	$status_id = getStatusId($reserved_id);
	if(!$status_id) {
		return ['message' => "Ooops! Couldn't find your book."];
	}
	$successDelete = deleteFromReservedBook($reserved_id);
	if(!$successDelete) {
		return ['message' => "Ooops! Seems like your book doesn't want to be returned right now."];
	}
	$resetSuccess = resetReservedStatus($status_id);
	if(!$resetSuccess) {
		return ['message' => "Ooops! Seems like your book doesn't want to be returned right now."];
	}
	return [];
}

function getStatusId($reserved_id) {
	$query = "SELECT status_id FROM user_reserved_book WHERE reserved_id = ? ";
	$stmt = $GLOBALS['db']->prepare($query);
	$stmt->bind_param('s', $reserved_id); 
	$stmt->bind_result($status_id);
	$stmt->execute();
	
	$stmt->store_result();
	if($stmt->num_rows < 1) {
		return false;
	}

	$status_id = '';
	while ($stmt->fetch()) {
		$status_id = $status_id;
	}
	$stmt->close();
	
	return $status_id;
}

function deleteFromReservedBook($reserved_id) {
	$query = "DELETE FROM user_reserved_book WHERE reserved_id = ?";
	$stmt = $GLOBALS['db']->prepare($query);
	$stmt->bind_param('s', $reserved_id);
	$stmt->execute();

	if($stmt->affected_rows < 1) {
		return false;
	} 
	$stmt->close();
	
	return true;
}

function resetReservedStatus($status_id) {
	$query = "UPDATE book_status SET reserved = 0 WHERE status_id = ?";
	$stmt = $GLOBALS['db']->prepare($query);
	$stmt->bind_param('s', $status_id);
	$stmt->execute();

	if($stmt->affected_rows < 1) {
		return false;
	}
	$stmt->close();

	return true;
}

/* ------ gallery.php ------ */

function getImages() {
	$query = "SELECT imgFile FROM Gallery";
	$stmt = $GLOBALS['db']->prepare($query);
	$stmt->bind_result($imgFile);
	$stmt->execute();
	
	$stmt->store_result();
	if($stmt->num_rows < 1) {
		return false;
	}
	$images = [];
	while ($stmt->fetch()) {
		array_push($images, $imgFile);
	}

	$stmt->close();
	
	return $images;
}
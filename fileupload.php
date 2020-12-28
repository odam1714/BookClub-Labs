<?php 

	include("config.php");

	@ $db = new mysqli($dbserver, $dbuser, $dbpass, $dbname);

		//Check if can connect
		if($db->connect_error){
		  echo "Connection error: " . $db->connect_error;
		  exit();
		}

	if(isset($_POST['upload'])){ //is someone clicks the upload button, do the following

		$maxsize = 2000000;
		$allowed = array('jpg', 'png', 'jpeg', 'gif');
		$fileExt = strtolower(substr($_FILES['fileupload']['name'], strpos($_FILES['fileupload']['name'], ".")+1));
		
		// new file name  $imgName = $_FILES['fileupload']['name'];
		
		$imgName = 'gallery'; //substr($_FILES['fileupload']['name'], strstr($_FILES['fileupload']['name'], "."));
		$fullFileName = $imgName . "." . uniqid("", true) . "." . $fileExt;

		$errors = array(); //places errors if upload doesn't work
	

		if(in_array($fileExt, $allowed) === false){ //checks if those extension exist in the array and are allowed
			$errors[] = "This extension is not supported."; //if the extension doesn't exist - choose position [0]

		}

		if($_FILES['fileupload']['size']>$maxsize){ //'size' is taken automatically from the image, no need to specify it anywhere
			$errors[] = "This file is too big!";
		}

		if(empty($error)){
			
			$sql = "SELECT * FROM Gallery;";
			  $stmt = mysqli_stmt_init($db);
			  if (!mysqli_stmt_prepare($stmt, $sql)) {
				echo "SQL statement failed!";
			  } else {
				mysqli_stmt_execute($stmt);
				$result = mysqli_stmt_get_result($stmt);
				$rowCount = mysqli_num_rows($result);
				$setImageOrder = $rowCount + 1;

				$sql = "INSERT INTO gallery (imgFile, galleryOrder) VALUES (?, ?);";
				if (!mysqli_stmt_prepare($stmt, $sql)) {
				  echo "SQL statement failed!";
				} else {
				  mysqli_stmt_bind_param($stmt, "ss", $fullFileName, $setImageOrder);
				  mysqli_stmt_execute($stmt);
					
				move_uploaded_file($_FILES['fileupload']['tmp_name'], "gallery/{$fullFileName}");
					//moves uploaded file to a location - saves the files in "images" folder with the "name" --- _FILES['fileupload']['name']
					
				}
			}
		}
	}
	 include 'header.php';
	 
?>

	<div class="centerBlock">
		
		<?php
	if(isset($errors)){
		if(empty($errors)){
			echo '<p class="danger-text">'. "Files uploaded!" .'</p><br>';
		} else {
			foreach ($errors as $err){ //if many errors, echoes out all the errors
			echo '<p class="danger-text">'. $err .'</p><br>'; 
			}
		}
	}
	
		?> 
		<h2>Select an image to upload</h2>
		<br/>
		<form action="fileupload.php" method="post" enctype="multipart/form-data">
			<input type="file" name="fileupload">
			<input type="submit" value="Upload Image" name="upload">
		</form>
		<p class="danger-text"><strong>Note: </strong>Only .jpg, .jpeg, .gif, .png files are allowed. Maximun size 2MB.</p>
		
	</div>

	<?php include 'footer.php';?>
	
</body>

</html>
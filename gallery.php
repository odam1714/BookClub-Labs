<?php 
	include 'functions.php'; 
	$images = getImages();

	$randomImage = '';
  if(isset($_GET['new_pic'])) {
    $randomImage = 'https://picsum.photos/550/400';
  } else {
    $randomImage = '';
  }
 
	include 'header.php';
	?>
	
	<h2 id="galleryintro">Images uploaded to the gallery will be displayed below!</h2>
	<div id="gallery">
		<?php
			foreach($images as $image) {
				echo '<div class="gallery-upload">
				<img src="gallery/'.$image.'" height="200">
				</div>';
			} 
		?>
	</div>

  <div class="infoblock">
    <h2>
    <form method="GET" action="gallery.php">
      <button name="new_pic" value="1">Random Image</button>
    </from>
    </h2>
    <p>
      <img src="<?php echo $randomImage; ?>" />
    </p>
    
	</div>

	<?php include 'footer.php';?>
	
</body>

</html>
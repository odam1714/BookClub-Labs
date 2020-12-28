<?php 
	include 'functions.php'; 
	$images = getImages();

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

	<?php include 'footer.php';?>
	
</body>

</html>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>index
	</title>
	<link rel="stylesheet" type="text/css" href="assets/index.css">
</head>
<body>

	<div id="search_section">
		<div id="search_container">
			<input type="text" id="searchBar" placeholder="serie name">
			<button id="search_button"><strong>search</strong></button>
		</div>
	</div>
	<div id="series_section">
			<?php 
    
				$conn = new mysqli('localhost','root','','only_series');
				mysqli_set_charset($conn,"utf8");
				
				$q="SELECT * FROM series";
				$result=mysqli_query($conn, $q);
				$i=1;
				while($row=mysqli_fetch_array($result)){
					if($i==1){

			?> 
			<div id="series_container">
			<?php 
				}
			?>

			<div class="serie">
				<div id="image_holder">
					<img src="images/<?php echo $row['title']?>/<?php echo $row['image']?>" class="image">
				</div>
				<h3><a href="display_page.php?id=<?php echo $row['id']?>"><?php echo $row['title']?></a></h3>
			</div>
			

		<?php 
			if($i==5){
				$i=1;
		?>	
		
		<?php 

			} 
			$i=$i+1;
		?>
		<?php } ?>
	</div><br>
	</div>


</body>
</html>
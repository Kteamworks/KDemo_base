

<!DOCTYPE html>

<html>
	<head>
		
		<title>Selectize.js Demo</title>
		
		
		<link rel="stylesheet" href="css/normalize.css">
		<link rel="stylesheet" href="css/stylesheet.css">
		<script src="js/jquery.js"></script>
		<script src="../dist/js/standalone/selectize.js"></script>
		<script src="js/index.js"></script>
	</head>
    <body>
		<div>
			<h1>Selectize.js</h1>
			<div class="demo">
				<h2>Dynamic Options</h2>
				<p>The options are created straight from an array.</p>
				<div class="control-group">
					<label for="select-tools">Tools:</label>
					<select id="select-tools" placeholder="Pick a tool..."></select>
				</div>
				<?php 
				$conn = mysqli_connect('localhost', 'root','','greencity');
			    $qry = "SELECT Medicine_ID, Medicine_Name FROM medicine_master"; 
                 $result = mysqli_query($conn, $qry);

				?>
				
				
				<script>
				// <select id="select-tools"></select>

				$('#select-tools').selectize({
					maxItems: 1,
					valueField: 'id',
					labelField: 'title',
					searchField: 'title',
					options: 
[
						<?php  while ($jarray = mysqli_fetch_array($result)) { 
						$id=$jarray['Medicine_ID'];
						$id1=str_replace("'", "", $id);
						$title=$jarray['Medicine_Name'];
						$title1=str_replace("'", "", $title);
						
						?>
						
						 
						{id: '<?php echo $id1; ?>', title: '<?php echo $title1; ?>'},
					<?php } ?>
					],
					create: false
				});
				</script>
			</div>
		</div>
	</body>
</html>
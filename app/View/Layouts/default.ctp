<!DOCTYPE html>
<html>
<head>
	<title>
		<?php  
			echo $this->fetch('title');
		?>
	</title>
	<link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
	<?php 
		echo $this->Html->css('home');
		echo $this->Html->script('jquery-3.3.1.min');
		echo $this->Html->css('bootstrap.min');
		echo $this->Html->script('bootstrap.min');
	?>	
</head>
<body>
<?php 
	echo $this->fetch('content'); 
?>
</body>
</html>



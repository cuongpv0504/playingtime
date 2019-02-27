<?php  
	echo $this->assign('title','Login');
?>
<!DOCTYPE html>
<html>
<head>
</head>
<body>
<?php 
	echo $this->Html->image('tmh.jpg',array(
		'class' => 'mx-auto d-block w-50 m-5'
	)); 
?>
<div class="mx-auto d-block w-25">
	<a class="btn btn-lg btn-danger w-100" href="<?php echo $login_url ?>">Login with chatwork</a>
</div>
</body>
</html>

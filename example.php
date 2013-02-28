<?php

require_once 'Dropbox.class.php';

$dropbox = new Dropbox();

$userArray = $dropbox->call('https://api.dropbox.com/1/account/info');

?>


<html>
	<head>
		<title>Dropbox API</title>
	</head>
	<body>
	<h1>Dropbox API</h1>
	
	<?php 
	if($dropbox->hasAccess())
	{
		echo "<pre>";
		print_r($userArray);

		$files = $dropbox->filesGet('sandbox','test/test.html');
		print_r($files);

		$data = $dropbox->metadata('sandbox','');
		print_r($data);

		$shares = $dropbox->shares('sandbox','test/test.html');
		print_r($shares);
		$media = $dropbox->media('sandbox','test/test.html');
		print_r($media);



		echo '
		<h2>User Info</h2>
			<ul>	
				<li>'.$userArray->display_name.'</li>
				<li>'.$userArray->email.'</li>
			</ul>';
	}
	else
	{
		echo '
		<h2>Login</h2>
			<a href="'.$dropbox->getAccessURL().'">Login to Dropbox</a>';
	}
	?>
	
	</body>
</html>
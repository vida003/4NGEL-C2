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
	<link rel="shortcut icon" href="../assets/4ngel.jpg">
	<title>4NGEL</title>
</head>
<body>
	<nav>
		<a class="logo" href="/">4NGEL</a>
		<ul class="nav-list">
			<li><a href="/user/profile.php">Options</a></li>
			<li><a href="../about.php">About</a></li>
			<li><a href="../logout.php">Exit</a></li>
		<ul>
	</nav>
	<center>
		<hr width="75%" noshade>
	</center><br>
	<a href="<?php echo $_SERVER['HTTP_REFERER']; ?>" class="back"><< back</a>

	<?php
	require_once("conn.php");

	$id = strip_tags(addslashes($_SESSION['id']));
	$user = strip_tags(addslashes($_SESSION['user']));

	$chd = new Query();
	$chd->autoDel($user, $id);

	class Query{
		public function add_user($user, $pass, $role, $plan){
			global $pdo;

			$date_ini = date("d/m/Y");
			$date_venc = date("d/m/Y", strtotime("+$plan days"));

			$sql = "INSERT INTO users (user, pass, role, plan, ini, venc) VALUES (:user, :pass, :role, :plan, :ini, :venc)";
			$sql = $pdo->prepare($sql);
			$sql->bindValue("user", $user);
			$sql->bindValue("pass", $pass);
			$sql->bindValue("role", $role);
			$sql->bindValue("plan", $plan);
			$sql->bindValue("ini", $date_ini);
			$sql->bindValue("venc", $date_venc);
			$sql->execute();

			if($sql->rowCount() > 0){
				return true;
			} else {
				return false;
			}
		}
		public function del($id, $user){
			global $pdo;
			$sql = "DELETE FROM users WHERE id = :id";
			$sql = $pdo->prepare($sql);
			$sql->bindValue("id", $id);
			$sql->execute();

			if($sql->rowCount() > 0){
				return true;
			} else {
				return false;
			}
		}
		public function searchAll(){
			global $pdo;
			$sql = "SELECT * FROM users ORDER BY id ASC";
			$sql = $pdo->prepare($sql);
			$sql->execute();

			if($sql->rowCount() > 0){
				while($row_data = $sql->fetch(PDO::FETCH_ASSOC)){
					$i = $row_data['id'];
					$u = $row_data['user'];
					$r = $row_data['role'];
					$p = $row_data['plan'];
					$d_a = $row_data['ini'];
					$d_v = $row_data['venc'];

					echo "
					<tr>
						<td>$i</td>
						<td>$u</td>
						<td>$r</td>
						<td>$p</td>
						<td>$d_a - $d_v</td>
						<td><a href='profile.php?type=manage&action=remove&id=$i&user=$u'>Remove</a> | <a href='profile.php?type=manage&action=edit&id=$i&user=$u&plan=$p'>Edit</a></td>
					</tr>
					";
				}
			}
		}
		public function autoDel($user, $id){
			global $pdo;

			$sql = "SELECT * FROM users WHERE user = :user AND id = :id";
			$sql = $pdo->prepare($sql);
			$sql->bindValue("user", $user);
			$sql->bindValue("id", $id);
			$sql->execute();

			if($sql->rowCount() > 0){
				$data = $sql->fetch();
				
				$venc_d = strtotime($data['venc']);
				$date_actual = strtotime(date("d/m/Y"));

				if($date_actual > $venc_d){
					$sql = "DELETE FROM users WHERE user = :user AND id = :id";
					$sql = $pdo->prepare($sql);
					$sql->bindValue("user", $user);
					$sql->bindValue("id", $id);
					$sql->execute();
				}
			}
		}
		public function check($user){
			global $pdo;

			$sql = "SELECT * FROM users WHERE user = :user";
			$sql = $pdo->prepare($sql);
			$sql->bindValue("user", $user);
			$sql->execute();

			if($sql->rowCount() > 0){
				return true;
			} else {
				return false;
			}
		}

		public function edit($user, $id, $new_plan){
			global $pdo;

			$ini_d = date("d/m/Y");
			$venc_d = date("d/m/Y", strtotime("+$new_plan days"));

			$sql = "UPDATE users SET plan = :plan, ini = :ini, venc = :venc_d WHERE id = :id AND user = :user";
			$sql = $pdo->prepare($sql);
			$sql->bindValue("plan", $new_plan);
			$sql->bindValue("user", $user);
			$sql->bindValue("id", $id);
			$sql->bindValue("ini", $ini_d);
			$sql->bindValue("venc_d", $venc_d);
			$sql->execute();

			if($sql->rowCount() > 0){
				return true;
			} else {
				return false;
			}
		}

		public function changeUser($new_user, $user, $id){
			global $pdo;
			$sql = "UPDATE users SET user = :new_user WHERE user = :user AND id = :id";
			$sql = $pdo->prepare($sql);
			$sql->bindValue("new_user", $new_user);
			$sql->bindValue("user", $user);
			$sql->bindValue("id", $id);
			$sql->execute();

			if($sql->rowCount() > 0){
				return true;
			} else {
				return false;
			}
		}

		public function changePass($new_pass, $user, $id){
			global $pdo;

			$sql = "UPDATE users SET pass = :new_pass WHERE user = :user AND id = :id";
			$sql = $pdo->prepare($sql);
			$sql->bindValue("new_pass", $new_pass);
			$sql->bindValue("user", $user);
			$sql->bindValue("id", $id);
			$sql->execute();

			if($sql->rowCount() > 0){
				return true;
			} else {
				return false;
			}
		}

		public function upImg($img, $user, $id){
			global $pdo;

			$sql = "UPDATE users SET img = :img WHERE user = :user AND id = :id";
			$sql = $pdo->prepare($sql);
			$sql->bindValue("img", $img);
			$sql->bindValue("user", $user);
			$sql->bindValue("id", $id);
			$sql->execute();

			if($sql->rowCount() > 0){
				$_SESSION['img'] = $img;
				return true;
			} else {
				return false;
			}
		}
	}

	if(isset($_FILES['file']) && isset($_POST['submit_pic'])){

		$path_to_upload = "profile_pic".DIRECTORY_SEPARATOR;

		if(!file_exists($path_to_upload))
			mkdir($path_to_upload);

		$profile_img = strtolower($_FILES['file']['name']);
		
		$allow_ext = array("png", "jpg", "ico", "jpeg");
		$file_ext = pathinfo($profile_img, PATHINFO_EXTENSION);

		$name_to_upload = md5(time()).".".$file_ext;

		$path_to_upload = $path_to_upload.$name_to_upload;

		if(in_array($file_ext, $allow_ext)){
			$file_to_comp = explode(".", $profile_img);
			$comp = $file_to_comp[sizeof($file_to_comp) - 1];

			if($comp == "png" || $comp == "jpg" || $comp == "ico" || $comp == "jpeg"){
				move_uploaded_file($_FILES['file']['tmp_name'], $path_to_upload);
				$ui = new Query();

				if($ui->upImg($path_to_upload, $user, $id)){
					header("Refresh: 0");
					echo "<script>alert('Uploaded Image');</script>";
				} else {
					header("Refresh: 0");
					echo "<script>alert('Could not upload image');</script>";
				}
			}
		} else {
			header("Refresh: 0");
			die("<script>alert('Only image bro');</script>");
		}
	}
	if(isset($_POST['del_pic'])){
		
		$di = new Query();
		if($di->upImg("/assets/profile_pic.jpg", $user, $id)){
			echo "<script>alert('Image Removed');</script>";
		} else {
			echo "<script>alert('Could not remove image');</script>";
		}
	}
	?>
	
	<div align="center" class="config_users">
		<form method="POST" enctype="multipart/form-data">
			<img src="<?php echo $_SESSION['img'];?>" class="profile_pic" id="img_pic">
			<input type="file" accept="image/*" name="file" id="input_pic">
			<br><br>
			<?php 
				if($_SESSION['img'] != "/assets/profile_pic.jpg"){
					echo "<input type='submit' value='Remove' name='del_pic'><br><br>";
				}
			?>
			<input type="submit" value="Save" name="submit_pic" id="save_pic">
		</form>
	</div>

	<?php
	if(isset($_POST['change_password'])){

		$new_password = md5(strip_tags(addslashes($_POST['new_password'])));
		$confirm_new_password = md5(strip_tags(addslashes($_POST['confirm_new_password'])));

		$chp = new Query();

		if(!empty($new_password) && !empty($confirm_new_password)){
			if($new_password == $confirm_new_password){
				if($chp->changePass($new_password, $user, $id)){
					$_SESSION['pass'] = $new_password;
					die("<script>alert('Password Changed');
						window.location.replace('profile.php');
						</script>");
				} else {
					header("Refresh: 0");
					die("<script>alert('Could not change password');</script>");
				}
			} else {
				header("Refresh: 0");
				die("<script>alert('Passwords not match');</script>");
			}
		}
		die();
	} else if(isset($_POST['change_username'])){

		$new_username = strip_tags(addslashes($_POST['username']));
		$pass = md5(strip_tags(addslashes($_POST['password'])));

		if(strlen($new_username) > 30){
			header("Refresh: 0");
			die("<script>alert('Max Lenght: 30');</script>");
		}
		
		$chu = new Query();


		if(!empty($new_username) && !empty($pass)){
			if($_SESSION['pass'] == $pass){
				if($chu->check($new_username)){
					header("Refresh: 0");
					die("<script>alert('Choose another username');</script>");
				}
				if($chu->changeUser($new_username, $user, $id)){
					$_SESSION['user'] = $new_username;
					die("<script>alert('Username Changed');
						window.location.replace('profile.php');
						</script>");
				} else {
					header("Refresh: 0");
					die("<script>alert('Could not change username from $user');</script>");
				}
			} else {
				header("Refresh: 0");
				die("<script>alert('Wrong Password');</script>");
			}
		}
		die();
	}
	if(isset($_SESSION['role']) && $_SESSION['role'] == "ADM"){ ?>
	<div align="center">
		<table>
			<tr>
				<td><a href="profile.php?type=register">Register User</a></td>
				<td><a href="profile.php?type=manage">Management</a></td>
				<td><a href="profile.php">Edit User</a></td>
			</tr>
		</table>
	</div>
	<?php

	if(isset($_GET['type']) && $_GET['type'] == "register"){ ?>

	<form method="POST"><br>
		<input type="text" name="new_user" size="15" placeholder="name" maxlength="30" required autofocus autocomplete="off"><br><br>
		<input type="text" name="new_pass" size="15" placeholder="pass" required autocomplete="off" ><br><br>
		<select name="plan">
			<option value="15">15 days</option>
			<option value="30">30 days</option>
			<option value="00">Forever</option>
		</select>
		<select name="role">
			<option value="user">USER</option>
			<option value="adm">ADM</option>
		</select><br><br>
		<input type="submit" name="cad" value="Register">
	</form>
	<?php
		if(isset($_POST['new_user']) && !empty($_POST['new_user']) && isset($_POST['new_pass']) && !empty($_POST['new_pass'])){

			$add = new Query();

			if(strlen($_POST['new_user']) > 30){
				header("Refresh: 0");
				die("<script>alert('Max Lenght: 30');</script>");
			}

			$user = strip_tags(addslashes($_POST['new_user']));
			$pass = md5(strip_tags(addslashes($_POST['new_pass'])));

			// more security xD
			$role = strip_tags(addslashes($_POST['role']));
			$plan = strip_tags(addslashes($_POST['plan']));

			if($add->check($user)){
				header("Refresh: 0");
				die("<script>alert('This user already exist');</script>");
			}

			if($role == "user"){
				$role = "USR";
			} elseif($role == "adm"){
				$role = "ADM";
			} else {
				header("Refresh: 0");
				die();
			}

			if($plan == "15"){
				$plan = "15";
			} elseif($plan == "30"){
				$plan = "30";
			} elseif($plan == "00"){
				$plan = "90";
			} else {
				header("Refresh: 0");
				die();
			}

			if($add->add_user($user, $pass, $role, $plan)){
				echo "<script>alert('Added user $user');</script>";
				header("Refresh: 0");
			} else {
				echo "<script>alert('Could not add user $user');</script>";
				header("Refresh: 0");
			}
		} die();
	}
	elseif(isset($_GET['type']) && $_GET['type'] == "manage"){ ?>
	<br><table class="user_manage" cellspacing="0">
		<tr>
			<th>ID</th>
			<th>User</th>
			<th>Role</th>
			<th>Plan</th>
			<th>Expire</th>
			<th>Action</th>
		</tr>
	<?php
		if(isset($_GET['action']) && $_GET['action'] == "remove" && isset($_GET['id']) && isset($_GET['user'])){

			$user = strip_tags(addslashes($_GET['user']));
			$id = strip_tags(addslashes($_GET['id']));

			$r = new Query();
			if($r->del($id, $user)){
				die("<script>alert('User $user deleted'); window.location.replace('profile.php?type=manage');</script>");
			} else {
				die("<script>alert('Could delete $user');window.location.replace('profile.php?type=manage');</script>");
			}
			die();
		} elseif(isset($_GET['action']) && $_GET['action'] == "edit" && isset($_GET['id']) && isset($_GET['user']) && isset($_GET['plan']) && !empty($_GET['plan'])){
			$user_edit = strip_tags(addslashes($_GET['user']));
			$plan_edit = strip_tags(addslashes($_GET['plan']));
			$id_edit = strip_tags(addslashes($_GET['id']));
		?>
			<div align="center">
				<form method="POST">
					<?php echo $user_edit; ?>
					<select name="edit_plan">
						<option value="15">15 days</option>
						<option value="30">30 days</option>
						<option value="00">Forever</option>
					</select>
					<input type="submit" value="Edit">
				</form>
			</div><br>

		<?php
			if(isset($_POST['edit_plan'])){
				if($_POST['edit_plan'] != $plan_edit){
					$edit = new Query();

					$plan = $_POST['edit_plan'];

					if($plan == "15"){
						$plan = "15";
					} elseif($plan == "30"){
						$plan = "30";
					} elseif($plan == "00"){
						$plan = "90";
					} else {
						header("Refresh: 0");
						die();
					}

					if($edit->edit($user_edit, $id_edit, $plan)){
						die("<script>alert('$user_edit - $plan_edit -> $plan');
							window.location.replace('profile.php?type=manage');
							</script>");
					} else {
						die("<script>alert('Nothing Changed');
							window.location.replace('profile.php?type=manage');
							</script>");
					}

				} else {
					header("Refresh: 0");
					die("<script>alert('Nothing Changed');</script>");
				}
			}
		}

		$s = new Query();
		$s->searchAll();
		die(); }
	}

	if(isset($_GET['change']) && $_GET['change'] == "user"){ ?>
		<div align="center" class="config_users">
			<form method="POST">
				<span>Username: </span><input type="text" name="username" size="19" maxlength="30" required autofocus>
				<br><br>
				<span>Password: </span><input type="password" name="password" size="20" required><br><br>
				<input type="submit" value="Change" name="change_username">
			</form>
		</div>
	<?php die(); }

	elseif(isset($_GET['change']) && $_GET['change'] == "pass"){ ?>
		<div align="center" class="config_users">
			<form method="POST">
				<span>New Password: </span><input type="password" name="new_password" size="24" required autofocus><br><br>
				<span>Confirm Password: </span><input type="password" name="confirm_new_password" size="21"><br><br>
				<input type="submit" value="Change" name="change_password" required>
			</form>
		</div>
	<?php die(); }?>

	<table>
		<tr>
			<td><a href='profile.php?change=user'>Change Username</a></td>
			<td><a href='profile.php?change=pass'>Change Password</a></td>
		</tr>
	</table>

</body>
</html>

<script type="text/javascript">
	
	'use strict'

	let inputPic = document.getElementById("input_pic");
	let imgPic = document.getElementById("img_pic");
	let savePic = document.getElementById("save_pic");

	imgPic.addEventListener("click", () => {
		inputPic.click();
	});

	inputPic.addEventListener('change', () => {

		if(inputPic.files.lenght = 0)
			return;

		let reader = new FileReader();

		reader.onload = () => {
			imgPic.src = reader.result;
		}

		reader.readAsDataURL(inputPic.files[0]);
		savePic.style.cssText = 
			"display: block;" +
			"margin-bottom: 1%;";
	});

</script>
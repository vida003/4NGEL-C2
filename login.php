<?php
	if(isset($_POST['login']) && !empty($_POST['login']) && isset($_POST['pass']) && !empty($_POST['pass'])){

		require_once("conn.php");

		class User{
			public function login($user, $pass){
				global $pdo;

				$sql = "SELECT * FROM users WHERE user = :user AND pass = :pass";
				$sql = $pdo->prepare($sql);
				$sql->bindValue("user", $user);
				$sql->bindValue("pass", $pass);
				$sql->execute();

				if($sql->rowCount() > 0){
					$data = $sql->fetch();

					$_SESSION['id'] = $data['id'];
					$_SESSION['role'] = $data['role'];
					$_SESSION['user'] = $data['user'];
					$_SESSION['pass'] = $data['pass'];

					if(empty($data['img']))
						$_SESSION['img'] = "/assets/profile_pic.jpg";
					else
						$_SESSION['img'] = $data['img'];

					$_SESSION['plan'] = $data['plan']; 

					return true;
				} else {
					return false;
				}
			}
		}

		$user = strip_tags(addslashes($_POST['login']));
		$pass = md5(strip_tags(addslashes($_POST['pass'])));

		$x = new User();
		
		if($x->login($user, $pass)){
			if(isset($_SESSION['id'])){
				header("Location: home.php");
			} else {
				header("Location: /");
			}
		} else {
			header("Location: /");
		}

	} else {
		header("Location: /");
	}
?>
<?php
	class IndexControl{
		//set main as the default page

		public function __construct(){

			$typelist = array('login','do_login');


			if(empty($_SESSION['admin'])){
				//
				if(@!in_array($_GET['a'],$typelist)){

		
					echo header('location:./index.php?m=index&a=login');
				}

			}

		}

		function  index(){

			include './view/index.html';
		}

		public	function login(){
		//var_dump($_SESSION);

		include './view/login/login.html';
	}

	public function do_login(){
		
			if(strtoupper($_POST['code']) != strtoupper($_SESSION['code'])){
				echo '<script>alert("Validation code entry error");location="./index.php?m=index&a=login"</script>';

				}
			$username=$_POST['username'];
			$pwd=md5($_POST['pwd']);
			
			//link to data
			try{
				$dsn='mysql:host=localhost;dbname=activity;charset=utf8';
				//
				$pdo=new PDO($dsn,'root','root');
				//
				$pdo->setAttribute(3,1);
				$sql="select * from users where username='{$username}'  AND password='{$pwd}' AND status='0' ";
				
				$stmt=$pdo->prepare($sql);

				$stmt->execute();

				if($stmt->rowCount()){
					//
					$user=$stmt->fetchAll(2);
				
				//var_dump($user);

				unset($user[0]['password']);
				//var_dump($user);
				$_SESSION['admin']=$user[0];
				echo '<script>alert("Hi, you are now logged in successfully. Welcome!");location="index.php?m=index&a=index"</script>';
			
			
				}else{
					echo '<script>alert("ERROR! Incorrect username or password. Please retry");location="index.php?m=index&a=login"</script>';
				}
		}catch(PDOException $e){
			echo $e->getMessage();
		}
	}


	public function outlogin(){
		//var_dump($_SESSION);
		unset($_SESSION['admin']);
		echo '<script>alert("Logout successful");location="index.php?m=index&a=login"</script>';
	}

}
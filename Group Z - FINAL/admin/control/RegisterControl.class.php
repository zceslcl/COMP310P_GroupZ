<?php
	class RegisterControl{
		public function register(){
			include './view/user/add.html';
		}

		
		protected function pattern_user($str){
			//define correct username
			$pattern = '/^[a-zA-Z0-9]{6,18}$/';
			return preg_match($pattern,$str);

		}

		protected function pattern_phone($str){
			//define correct phone number(UK_PHONE)
			
			$pattern = '/^(\+?44|0)7\d{9}$/';
			return preg_match($pattern,$str);
		}

		protected function pattern_email($str){
			//define correct email address
			$pattern='/^\w+@\w+\.[a-zA-Z]{2,5}$/';
			return preg_match($pattern,$str);
		}


		protected function pwd($pwd1,$pwd2){
			if($pwd1!=$pwd2){
				return false;
			}else{
				return true;
			}
		}

		function  do_add(){
			//username
			if(!$this->pattern_user($_POST['username'])){
				echo '<script>alert("Please enter 6-18 characters. Only digits or letters are allowed.");location="./index.php?m=register&a=register"</script>';
				exit;
			}
			//password
			if($this->pwd($_POST['pwd'],$_POST['repwd'])){
				//
				$_POST['password'] = md5($_POST['pwd']);
			}else{
				echo '<script>alert("The two passwords are not the same");location="./index.php?m=register&a=register"</script>';
				exit;
			}

			//user
            if($_POST['realname'] == ''){
                echo '<script>alert("Please enter your real name");location="./index.php?m=register&a=register"</script>';
            }

			//select sex
			if($_POST['sex'] == 'xz'){
				echo '<script>alert("Please select your gender");location="./index.php?m=register&a=register"</script>';
				exit;
			}

			if(!$this->pattern_phone($_POST['user_phone'])){
				echo '<script>alert("Please enter a UK phone number");location="./index.php?m=register&a=register"</script>';
				exit;
			}

			//email address
            if($_POST['user_email'] == ''){
                echo '<script>alert("Please enter an email");location="./index.php?m=register&a=register"</script>';
            }

			if(!$this->pattern_email($_POST['user_email'])){
				echo '<script>alert("Make sure your email is in the correct format!");location="./index.php?m=register&a=register"</script>';
				exit;
			}
			

			
			$_POST['login_time'] = time();
			$_POST['status'] = 0;
			//create

			$d = new MysqlModel('users');
			// $where['username']=$_POST['username'];
			$list=$d->where('username="'.$_POST['username'].'"')->find();
			if($list){
				echo '<script>alert("The username has already existed");location="./index.php?m=register&a=register"</script>';
				exit;

			}
			//insert into the database
			if($d->insert($_POST)){
				//echo $d->sql;
				echo '<script>alert("add ['.$_POST['username'].'] success");location="./index.php?m=index&a=login"</script>';
			}else{
				//echo $d->sql;exit;
				echo '<script>alert("add ['.$_POST['username'].'] error");location="./index.php?m=register&a=register"</script>';

			}
		}
	}
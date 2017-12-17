<?php
	//user
	class UserControl{

			function __construct(){
			$typelist=array('login','do_login');
			if(empty($_SESSION['admin'])){
				if(!in_array($_GET['a'],$typelist)){
					header('location:./index.php?m=index&a=login');
					
				}
			}
		}
		
		private function time_pattern($time1,$time2){
				
				$array = array();
				$pattern = '/:|T|-/';
				$shijian1 = preg_replace($pattern,'-',$time1);
				//var_dump($shijian);
				$shijian2 = preg_replace($pattern,'-',$time2);

				list($year1,$month1,$day1,$h1,$i1) = explode('-',$shijian1);
				

				list($year2,$month2,$day2,$h2,$i2) = explode('-',$shijian2);
					
				//time duration
				$start = mktime($h1,$i1,0,$month1,$day1,$year1);
				$end = mktime($h2,$i2,0,$month2,$day2,$year2);
				$array['start'] = $start;
				$array['end'] = $end;

				
				

				// echo date('Y-m-d H:i:s',$start);
				// echo date('Y-m-d H:i:s',$end);
				// start_time < end_time
				if($start > $end){
					echo '<script>alert("The start time must not be greater than the ending time");location="./index.php?m=user&a=show"</script>';
				}else{
					return $array;
				}
			}
		
		protected function searchUser(){
			$wherelist = array();
			if(!empty($_GET['search'])){
				//username search
				$wherelist[] = " username LIKE '%{$_GET['search']}%'";
			}
			//
			if(isset($_GET['level']) && $_GET['level'] != 'xz'){
				$wherelist[] = ' level = '.$_GET['level'];
			}
			//register time
			
			
			if(!empty($_GET['time1']) && !empty($_GET['time2'])){
				//time duration
				$arr = $this->time_pattern($_GET['time1'],$_GET['time2']);

				$wherelist[] = ' addtime BETWEEN '.$arr['start'].' AND '.$arr['end'];
				

			}

			//
		if(isset($_GET['sex'])){
			$wherelist[]="id IN(select uid from user_info  where sex={$_GET['sex']})";
			
			}

			//search by id or not
		if(isset($_GET['no1']) && isset($_GET['no2'])&& ! empty($_GET['no1'])&& !empty($_GET['no2'])){
			$wherelist[]="id between {$_GET['no1']} AND {$_GET['no2']}";
			
		}
		

			

			//
			if(count($wherelist) > 0){
				return implode(' AND ',$wherelist);
			}else{
				return '';
			}
		}
		//display
		function show(){

			// if($_SESSION['admin']['username']!='admin'){
			// 		echo '<script>alert("insufficient privilege");location="./index.php?m=index&a=index"</script>';
			// 		exit;
			// }
			
			//echo mktime(10,10,0,4,6,2017);
			//user use the search function or not
			if(isset($_GET['c']) && $_GET['c'] == 'search'){
				//use search function
				$search = $this->searchUser();
			}else{
				//no search
				$search = '';
			}
			//default level
			$xz = $pu = $vip = $jin = $chao = '';
			if(isset($_GET['level'])){
				switch($_GET['level']){
					case '0':
						$pu = 'selected';
						break;
					case '1':
						$vip = 'selected';
						break;
					case '2':
						$jin = 'selected';
						break;
					case '3':
						$chao = 'selected';
						break;
					default:
						$xz  = 'selected';
				}
			}else{
				$xz = 'selected';
			}

			//default sex choice
			
			/**********************************/
			//
			$d = new MysqlModel('user');
			//page
		
			$p = new Page($d->where($search)->total(),3);
			if(!empty($search)){
				$where=$d->where($search.' AND is_delete=0 ');
			}else{
				$where=$d->where($search.'is_delete=0');
			}
			$result = $d->order('id ASC')->limit($p->limit())->select();
			//echo $d->sql;
			//var_dump($result);
			//var_dump($result);
			include './view/user/index.html';
		}

		function add(){

			// if($_SESSION['admin']['username']!='admin'){
			// 		echo '<script>alert("insufficient privilege");location="./index.php?m=index&a=index"</script>';
			// }
			include './view/user/add.html';
		}

		protected function pattern_user($str){
			//define correct username
			$pattern = '/^[a-zA-Z0-9]{6,18}$/';
			return preg_match($pattern,$str);

		}

		protected function pattern_phone($str){
			//define correct phone number(only UK)
			
			$pattern = '/^(\+?44|0)7\d{9}$/';
			return preg_match($pattern,$str);
		}

		protected function pattern_email($str){
			//define email address
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

			if(!$this->pattern_user($_POST['username'])){
				echo '<script>alert("Please enter 6-18 digits or letters");location="./index.php?m=user&a=add"</script>';
			}

			if($this->pwd($_POST['pwd'],$_POST['repwd'])){

				$_POST['pwd'] = md5($_POST['pwd']);
			}else{
				echo '<script>alert("The two password is inconsistent");location="./index.php?m=user&a=add"</script>';

			}

			if($_POST['level'] == 'xz'){
				echo '<script>alert("Please select permissions");location="./index.php?m=user&a=add"</script>';

			}

			//add user

			//get time duration
			$_POST['addtime'] = time();
			//var_dump($_POST);exit;


			$d = new MysqlModel('user');
			//get insert function
			if($d->insert($_POST)){
				//echo $d->sql;
				echo '<script>alert("add ['.$_POST['username'].'] success");location="./index.php?m=user&a=show"</script>';
			}else{
				//echo $d->sql;exit;
				echo '<script>alert("add ['.$_POST['username'].'] error");location="./index.php?m=user&a=add"</script>';

			}
		}
	
		/***************view details*******************/

		//look up details
		 function select(){
			//var_dump($_GET);
		
			include './view/user/detail.html';
	}
		//add or edit
	function do_select(){

	
	switch($_GET['b']){
		case 'add':
			//add
			//var_dump($_POST);
			//var_dump($_FILES);

			if(!empty($_FILES['pic']['name'])){
				//pic upload
				$u=new Upload('pic','../public/uploads/user/');

			//use upload function
				$result=$u->do_upload();
			//var_dump($result);
				if(is_array($result)){
					//
					$_POST['pic']=$result['name'];
				}else{
					echo '<script>alert("upload error");location="index.php?m=user&a=select&id='.$_POST['uid'].'&username='.$_POST['username'].'"</script>';
				}
			}

			$hobby=implode(',',$_POST['hobby']);
			$_POST['hobby']=$hobby;

			//use add function
				$d= new MysqlModel('user_info');
				
				if($d->insert($_POST)){
				
				echo '<script>alert("add success ");location="index.php?m=user&a=show&id='.$_POST['uid'].'&username='.$_POST['username'].'"</script>';;
			}else{
				
				echo '<script>alert("add error");location="index.php?m=user&a=select&id='.$_POST['uid'].'&username='.$_POST['username'].'"</script>';

			}
		
				break;

		case'update':
			
			//edit pic
			if(!empty($_FILES['pic']['name'])){
				//pic upload
				$u=new Upload('pic','../public/uploads/user/');

				//use do_upload function
				$result=$u->do_upload();
				if(is_array($result)){
					//
					$_POST['pic']=$result['name'];
				}else{
					echo '<script>alert("upload error");location="./index.php?m=user&a=select&id='.$_POST['uid'].'&username='.$_POST['username'].'"</script>';
				}
			}
			//var_dump($_POST);
			//var_dump($_FILES);
			//define correct phone number
			
			if(!$this->pattern_phone($_POST['phone'])){
				echo '<script>alert("Please enter the correct cell phone number");location="./index.php?m=user&a=select"</script>';
			}

			//define correct email
			if(!$this->pattern_email($_POST['email'])){
				echo '<script>alert("Please enter the correct email address");location="./index.php?m=user&a=select&id='.$_POST['uid'].'&username='.$_POST['username'].'"</script>';
			}


			$hobby=implode(',',$_POST['hobby']);
			$_POST['hobby']=$hobby;
			//var_dump($_POST);
			

			
			//edit
			$d= new MysqlModel('user_info');
			if($d->update($_POST)){

				echo '<script>alert("update ['.$_POST['username'].'] success");location="./index.php?m=user&a=show"</script>';
			}else{
				
				echo '<script>alert("update ['.$_POST['username'].'] error");location="./index.php?m=user&a=select&id='.$_POST['uid'].'&username='.$_POST['username'].'"</script>';

			}
		
			break;

	}
		}
		//id
		function update(){
			//var_dump($_GET);
		$d = new MysqlModel('users');
		$result = $d->where('id='.$_SESSION['admin']['id'])->find();
		include './view/user/update1.html';
	}
		//change password
		function do_update(){
			if(empty($_POST['pwd']=$_POST['repwd'])){
				unset($_POST['pwd']);
				unset($_POST['repwd']);

				//define correct username
					if(!$this->pattern_user($_POST['username'])){
						echo '<script>alert("Please enter 6-18 digits or letters");location="./index.php?m=user&a=update&id='.$_POST['id'].'"</script>';
					}


					if(!$this->pattern_phone($_POST['user_phone'])){
						echo '<script>alert("Please enter the correct cell phone number");location="./index.php?m=user&a=update&id='.$_POST['id'].'&username='.$_POST['username'].'"</script>';
					}

					//define correct email
					if(!$this->pattern_email($_POST['user_email'])){
						echo '<script>alert("Please enter the correct email address");location="./index.php?m=user&a=update&id='.$_POST['id'].'&username='.$_POST['username'].'"</script>';
					}
					// var_dump($_POST);
						//
					$d = new MysqlModel('users');

						//
					if($d->update($_POST)){
						//echo $d->sql;
						echo '<script>alert("update ['.$_POST['username'].'] success");location="./index.php?m=user&a=update&id='.$_POST['id'].'&username='.$_POST['username'].'"</script>';
					}else{
						//echo $d->sql;exit;
						echo '<script>alert("add ['.$_POST['username'].'] error");location="./index.php?m=user&a=update&id='.$_POST['id'].'&username='.$_POST['username'].'"</script>';

					}

			}else{
				//define correct username
					if(!$this->pattern_user($_POST['username'])){
						echo '<script>alert("Please enter 6-18 digits or letters");location="./index.php?m=user&a=update&id='.$_POST['id'].'"</script>';
					}
					//define correct password
					if($this->pwd($_POST['pwd'],$_POST['repwd'])){
						//
						$_POST['password'] = md5($_POST['pwd']);
					}else{
						echo '<script>alert("The two password is inconsistent");location="./index.php?m=user&a=update&id='.$_POST['id'].'"</script>';

					}
						
					
					if(!$this->pattern_phone($_POST['user_phone'])){
						echo '<script>alert("Please enter the correct cell phone number");location="./index.php?m=user&a=update&id='.$_POST['id'].'&username='.$_POST['username'].'"</script>';
					}

					//define correct email
					if(!$this->pattern_email($_POST['user_email'])){
						echo '<script>alert("Please enter the correct email address");location="./index.php?m=user&a=update&id='.$_POST['id'].'&username='.$_POST['username'].'"</script>';
					}
					$d = new MysqlModel('users');
					if($d->update($_POST)){
						//echo $d->sql;
						echo '<script>alert("update ['.$_POST['username'].'] error");location="./index.php?m=user&a=update&id='.$_POST['id'].'&username='.$_POST['username'].'"</script>';
					}else{
						// echo $d->sql;exit;
						echo '<script>alert("update ['.$_POST['username'].'] error");location="./index.php?m=user&a=update&id='.$_POST['id'].'&username='.$_POST['username'].'"</script>';

					}
				}
		

		}

		/***************edit*******************/

		function delete(){
	//get ID in index.html do the delete function
    //get delete ID
    	$id=$_GET['id'];
		$d = new MysqlModel('user');

		
		if($d->where('id='.$id)->delete()){
				$c=new MysqlModel('user_info');
				if($c->where('uid='.$id)->delete()){
					echo '<script>alert("delete ['.$_GET['username'].'] success");location="./index.php?m=user&a=show"</script>';
				}else{
					echo '<script>alert("delete ['.$_GET['username'].'] error");location="./index.php?m=user&a=show"</script>';exit;
				}
			}else{
				//echo $d->sql;exit;
				echo '<script>alert("删除 ['.$_GET['username'].'] 失败");location="./index.php?m=user&a=show"</script>';

			}


		}



		function getuser(){
			
			$d = new MysqlModel('user');
			if($d-> trash()){
				echo '<script>alert("delete'.$_GET['username'].'error");location="./index.php?m=user&a=userstrash"</script>';
			}else{
				echo '<script>alert("delete'.$_GET['username'].'success");location="./index.php?m=user&a=show"</script>';
			}


		}

		function userstrash(){
			

			if($_SESSION['admin']['username']!='admin'){
					echo '<script>alert("insufficient privilege");location="./index.php?m=index&a=index"</script>';
			}
		//get user_id

			
		
			//define $result variable
			$d = new MysqlModel('user');
			//page
		
			$p = new Page($d->where('is_delete=1')->total(),3);
			
			$d->where('is_delete=1');
			
			$result1 = $d->order('id ASC')->limit($p->limit())->select();
			//echo $d->sql;
			//var_dump($result);
			//var_dump($result);
   			include './view/user/list.html';
 				

   
		}


	
}


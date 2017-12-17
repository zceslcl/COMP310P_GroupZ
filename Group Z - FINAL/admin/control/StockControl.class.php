<?php
	class StockControl{


		function __construct(){
			$typelist=array('login','do_login');
			if(empty($_SESSION['admin'])){
				if(!in_array($_GET['a'],$typelist)){
					header('location:./index.php?m=index&a=login');
					
				}
			}
		}

        

		protected function searchType(){
			$wherelist = array();
			if(!empty($_GET['search'])){
				//search name
				$wherelist[] = " stock.stock_id LIKE '%{$_GET['search']}%'";
			}

			//
			if(count($wherelist) > 0){
				return implode(' AND ',$wherelist);
			}else{
				return '';
			}


		}

		public function show(){

			// if(empty($search)){
			$where='enter.activity_id=activity.id and enter.user_id=users.id and activity.user_id='.$_SESSION['admin']['id'];
			// }else{
			// 	$where= 'stock.goods_id=goods.id  AND  '.$search;
			// }

			$stock = new MysqlModel('enter');

		    if(empty($search)){
				$p = new Page($stock->total(),5);
			}else{
				$p = new Page($stock->where($where)->total(),5);
			}
            
            $result = $stock ->field('enter.id as e_id','activity.id','activity.name','activity.title','activity.activity_pic','activity.descr','activity.start_time','activity.end_time','activity.location','activity.over','users.user_email','users.user_phone','users.username','enter.status')->where($where)->order('enter.id ASC')->limit($p->limit())->r_select('enter','activity','users');	
            
            // echo  $stock->sql;
			// var_dump($result);
			foreach ($result as $key => $value) {
				$result[$key]['start_time']=date('Y-m-d H:i:s',$value['start_time']);
				$result[$key]['end_time']=date('Y-m-d H:i:s',$value['end_time']);
			}
			$i = 0;
			include './view/stock/index.html';

		}

		public function add(){
			$goods=new MysqlModel('goods');
			$result=$goods->select();
			$user=new MysqlModel('user');
			$userinfo=$user->select();
			include './view/stock/add.html';
		}

		public function do_add(){

			// var_dump($_POST);
			
			if($_POST['out_date']==null){
				$_POST['out_date']='未指定归还日期';
			}
			foreach($_POST as $val){
				if($val==''){
					echo '<script>alert("Please add our information carefully");location="./index.php?m=stock&a=add"</script>';exit;
				}
			}

			//new the type of the picture
			$u=new Upload('pic','../public/uploads/stock/');

			//use do_upload function
			$result=$u->do_upload();
			//var_dump($result);

			//picture upload or not
			if(is_array($result)){
				$_POST['pic']=$result['name'];
			}else{
				echo 'File upload failed';exit;
			}
            //insert data into database
		    $goods = new MysqlModel('goods');
		    $user = new MysqlModel('user');
		    $stock_info=$goods->where('id='.$_POST['goods_id'])->find();
		    $username=$user->where('id='.$_POST['handled_staff'])->find();
		    // var_dump($username);
		    $_POST['uid']=$_POST['handled_staff'];
		    $_POST['stock_id']=$stock_info['stock_id'];
		    $_POST['goods_name']=$stock_info['name'];
		    $_POST['handled_staff']=$username['username'];
			$stock = new MysqlModel('stock');
			if($sid=$stock->insert($_POST)){
				$info['goods_id']=$_POST['goods_id'];
				$info['stock_id']=$_POST['stock_id'];
				$info['update_name']=$_SESSION['admin']['username'];
				$info['update_time']=time();
				$info['update_type']='add';
				$update_info=new MysqlModel('update_info');
				if($uid=$update_info->insert($info)){
					$arr['p_id']=$uid;
					$stock->where('id='.$sid)->update($arr);
				}
				echo '<script>alert("Add Success");location="./index.php?m=stock&a=show"</script>';
			}else{
				unlink('../public/uploads/stock/'.$_POST['pic']);
				echo '<script>alert("Add Failed");location="./index.php?m=stock&a=add"</script>';
		}

		}

		public function update(){
			
			//var_dump($_GET);
			$id=$_GET['id'];
			$stock=new MysqlModel('stock');
			$goods = new MysqlModel('goods');
			$result=$stock->where('id='.$id)->select();
			$user=new MysqlModel('user');
			$userinfo=$user->select();
			$result1=$goods->select();
			// var_dump($result1);
			if(!empty($result)){
				$goods_id=$result['0']['goods_id'];
				$goods_result=$goods->where('id='.$goods_id)->select();
				$result['0']['name']=$goods_result['0']['name'];
			}
			include './view/stock/update.html';

		}

		public function sendemail(){
			$id=$_GET['id'];
			$email=$_GET['email'];
			include './view/stock/sendemail.html';
		}

		public function do_sendemail(){
			$_POST['addtime']=time();
			$_POST['status']=0;
			$sendemail=new MysqlModel('sendemail');
			$id=$sendemail->insert($_POST);
			if($id){
				echo '<script>alert("Send Success");location="./index.php?m=stock&a=show"</script>';
			}else{
				echo '<script>alert("Send Failed);location="./index.php?m=stock&a=show"</script>';
			}
		} 
		
		public function do_update(){
			$id=$_POST['id'];

			if(!empty($_FILES['pic']['name'])){
			//the type of pic
			$u=new Upload('pic','../public/uploads/stock/');

			//use do_upload function
			$result=$u->do_upload();
			//var_dump($result);
			
			//upload successfully or not
			if(is_array($result)){
				$_POST['pic']=$result['name'];
			}else{

				echo '<script>alert("Upload Failed");location="./index.php?m=stock&a=show"</script>';
			}
		}

			//add data to database
			//
			$stock= new MysqlModel('stock');

			if($stock->update($_POST)){
				$info['goods_id']=$_POST['goods_id'];
				$info['stock_id']=$_POST['stock_id'];
				$info['update_name']=$_SESSION['admin']['username'];
				$info['update_time']=time();
				$info['update_type']='update';
				$update_info=new MysqlModel('update_info');
				if($uid=$update_info->insert($info)){
					$arr['p_id']=$uid;
					$stock->where('id='.$id)->update($arr);
				}
				echo '<script>alert("Update Success");location="./index.php?m=stock&a=show"</script>';

			}else{
				
				unlink('../public/uploads/stock/'.$_POST['pic']);
				// echo $stock->sql;exit;
				echo '<script>alert("Update Failed");location="./index.php?m=stock&a=update&id='.$id.'"</script>';

		}


		}

		public function del(){

			//var_dump($_GET);

			$id=$_GET['id'];
			$stock=new MysqlModel('stock');
			$stock_info=$stock->where('id='.$id)->select();
			$pic=$stock_info['pic'];
			if($stock->where('id='.$id)->delete()){
				//path
				$path = '../public/uploads/stock/'.$pic;
				//delete pic
				unlink($path);
				echo '<script>alert("Congratulations on your success!");location="./index.php?m=stock&a=show"</script>';
				//unlink('');
			}else{
				echo '<script>alert("Brother, no, oh, Failed");location="./index.php?m=stock&a=show"</script>';
			}
		}

		public function update_info(){
			$id=$_GET['id'];
			$update_info=new MysqlModel('update_info');
			$result=$update_info->where('id='.$id)->find();
			$update_name=$result['update_name'];
			$update_time=$result['update_time'];
			$update_type=$result['update_type'];
			$goonds_name=$_GET['goods_name'];
			$stock_id=$_GET['stock_id'];
			include './view/stock/update_info.html';
		}

					//change to display
		public function do_see(){
			$type = new MysqlModel('enter');
			$id=$_GET['id'];
			$map=array();
			$map['status']=0;
			if($type->where('id='.$id)->update($map)){
				
				header('location:index.php?m=stock&a=show');
			}else{
				
				header('location:index.php?m=stock&a=show');
			}
		}
		//change to none
		public function do_hide(){
			$type = new MysqlModel('enter');
			$id=$_GET['id'];
			$map=array();
			$map['status']=1;
			if($type->where('id='.$id)->update($map)){
				
				header('location:index.php?m=stock&a=show');
			}else{
				header('location:index.php?m=stock&a=show');
			}
		}
	}
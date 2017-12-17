<?php
	class GoodsControl{

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
			if(!empty($_GET['start_time']) && !empty($_GET['end_time'])){
				$start_time=strtotime($_GET['start_time']);
				$end_time=strtotime($_GET['end_time']);
				$wherelist[] = "{$end_time} >= activity.start_time and {$start_time} <= activity.end_time ";
			}
	


			if(isset($_GET['type_id']) && $_GET['type_id'] != 'xz'){
				$wherelist[] = 'activity.type_id = '.$_GET['type_id'];
			}
			
			//
			if(count($wherelist) > 0){
				return implode(' AND ',$wherelist);
			}else{
				return '';
			}

		}	

		//show event
		public function show(){
			if(isset($_GET['c']) && $_GET['c'] == 'search'){
				//use search function
				$search = $this->searchType();
			}else{
				//search input is none
				$search = '';
			}

			//default selected
			$xz = $xianshi = $yincan = '';
			if(isset($_GET['status'])){
				switch($_GET['status']){
					case '0':
						$xianshi = 'selected';
						break;
					case '1':
						$yincan = 'selected';
						break;
					default:
						$xz  = 'selected';
				}
			}else{
				$xz = 'selected';
			}
			$goods  = new MysqlModel('activity');
			$type = new MysqlModel('type');
			if(empty($search)){
				$where='activity.type_id=type.id';
			}else{
				$where= 'activity.type_id=type.id  AND  '.$search;
			}
			if(empty($search)){
				$p = new Page($goods->where($search)->total(),10);
			}else{
				$p = new Page($goods->where($search)->total(),10);
			}
			$result=$goods->field('activity.id','activity.name','activity.title','activity.activity_pic','activity.descr','activity.remark','activity.start_time','activity.end_time','activity.location','activity.over','type.name as tname','activity.price','activity.sale_time','activity.status')->where($where)->order('activity.start_time desc')->limit($p->limit())->r_select('activity','type');
			foreach ($result as $key => $value) {
				$result[$key]['start_time']=date('Y-m-d H:i:s',$value['start_time']);
				$result[$key]['end_time']=date('Y-m-d H:i:s',$value['end_time']);
			}
			$result1=$type->order('concat(path,id) ASC')->select();
			// var_dump($result);
			$i = 0;
			include './view/goods/index.html';

		}

		//add event
		public function add(){

			$type = new MysqlModel('type');
			$result=$type->order('concat(path,id) ASC')->select();
			include './view/goods/add.html';
		}

		//use do_add function
		public function do_add(){
			//var_dump($_POST);
			//var_dump($_FILES);
			foreach($_POST as $val){
				if($val==''){
					echo '<script>alert("Please make sure no fields are left blank!");location="./index.php?m=goods&a=add"</script>';exit;
				}
			}

			//the type of upload pic
			$u=new Upload('pic','../public/uploads/goods/');

			//use do_upload function
			$result=$u->do_upload();
			// var_dump($result);

			//upload success or not
			if(is_array($result)){
				$_POST['activity_pic']=$result['name'];
			}else{
				echo 'File upload failed';exit;
			}

			//add data to database
			$_POST['user_id']=$_SESSION['admin']['id'];
			$_POST['over']=$_POST['remark'];
			$_POST['create_time']=time();
			$_POST['start_time']=strtotime($_POST['start_time']);
            $_POST['end_time']=strtotime($_POST['end_time']);
			$goods = new MysqlModel('activity');
			if($goods->insert($_POST)){
				echo '<script>alert("Add Success");location="./index.php?m=goods&a=show"</script>';

			}else{
				unlink('../public/uploads/goods/'.$_POST['activity_pic']);
				echo '<script>alert("Add Failed");location="./index.php?m=goods&a=add"</script>';

		}

	}
		//change status to unavailable
		public 	function do_up(){


			//id
			//var_dump($_GET);
			//
			$goods = new MysqlModel('goods');
			$id=$_GET['id'];
			$map=array();
			$map['status']=1;
			if($goods->where('id='.$id)->update($map)){
				
				header('location:index.php?m=goods&a=show');
			}else{
				
				header('location:index.php?m=goods&a=show');
			}

		}

		public 	function do_down(){
			//id
			//var_dump($_GET);
			//
			$goods = new MysqlModel('goods');
			$id=$_GET['id'];
			$map=array();
			$map['status']=0;
			if($goods->where('id='.$id)->update($map)){
				
				header('location:index.php?m=goods&a=show');
			}else{
				
				header('location:index.php?m=goods&a=show');
			}

		}

		//delete
		public function del(){

			//id
			//var_dump($_GET);
			$id=$_GET['id'];
			$goods=new MysqlModel('activity');
			//search the table get the name of pic
			$goods_image=$goods->field('activity_pic')->where('id='.$id)->find();
			// var_dump($goods_image);
			if($goods->where('id='.$id)->delete()){
				//get the path of the pic
				$path = '../public/uploads/goods/'.$goods_image['activity_pic'];
				//delete pic
				unlink($path);
				echo '<script>alert("Congratulations on your success!");location="./index.php?m=goods&a=show"</script>';
				//unlink('');
			}else{
				echo '<script>alert("Brother, no, oh, Failed");</script>';
			}


		}

		//edit
		public function update(){
			$id=$_GET['id'];
			$goods=new MysqlModel('activity');
			$type = new MysqlModel('type');
			$result=$goods->where('id='.$id)->select();
			if(!empty($result)){
				foreach($result as $key=>$val){
					//var_dump($val['typeid']);
					//search the type name
					$typeName=$type->field('name')->where('id='.$val['typeid'])->find();
					//var_dump($typeName);
					//insert the new type name to the database
					$result[$key]['typename']=$typeName['name'];
				}
			}

			//var_dump($result);
			
			$type = new MysqlModel('type');

			$result1=$type->order('concat(path,id) ASC')->select();
			$start_time=date('Y-m-dH:i:s',$result[0]['start_time']);
			$end_time=date('Y-m-dH:i:s',$result[0]['end_time']);
			$result[0]['start_time']=substr_replace($start_time,substr($start_time,0,10).'T',0,10);
			$result[0]['end_time']=substr_replace($end_time,substr($end_time,0,10).'T',0,10);
			include './view/goods/update.html';

		}

		public function do_update(){
			$id=$_POST['id'];
			$activity= new MysqlModel('activity');
		    $remark=$activity->field('remark')->where('id='.$id)->find();
		    if($remark['remark']!=$_POST['remark']){
		    	$_POST['over']=$_POST['remark'];
		    	unset($_POST['remark']);
		    
		    }else{
		    	unset($_POST['remark']);
		    }

			$id=$_POST['id'];
			if(!empty($_FILES['activity_pic']['name'])){
			//the type of upload pic
			$u=new Upload('activity_pic','../public/uploads/goods/');

			//use do_upload function
			$result=$u->do_upload();
			//var_dump($result);
			
			//upload success or not
			if(is_array($result)){
				$_POST['activity_pic']=$result['name'];
			}else{
				echo '<script>alert("Upload Failed");location="./index.php?m=goods&a=show"</script>';
			}
		}

			//add data to database
			$_POST['start_time']=strtotime($_POST['start_time']);
            $_POST['end_time']=strtotime($_POST['end_time']);
			$goods = new MysqlModel('activity');
			if($goods->update($_POST)){
				echo '<script>alert("Update Success");location="./index.php?m=goods&a=update&id='.$id.'"</script>';

			}else{
				//echo $goods->sql;exit;
				unlink('../public/uploads/goods/'.$_POST['activity_pic']);

				echo '<script>alert("Update Failed");location="./index.php?m=goods&a=update&id='.$id.'"</script>';

		}
	}

	public function catename(){
		$cate = new MysqlModel('brand');
			$result = $cate->order('id ASC')->select();
			foreach ($result as $key => $value) {
				$list[] = array(
					    'id' => $value['id'],
					    'label' => $value['name']
					);
			}
		echo json_encode($list);
	}

	/** [signview description] */
	public function signview(){
		$id=$_GET['id'];
		include './view/goods/enter.html';

	}

	/**
	sign up
	 */
	public function sign(){
		$activity= new MysqlModel('activity');
		$enter=new MysqlModel('enter');
		$where['activity_id']=$_POST['aid'];
		$where['user_id']=$_SESSION['admin']['id'];
		$enter_list=$enter->where($where)->find();
		$list=$activity->field('user_id','location','name','type_id','remark','sale_time')->where('id='.$_POST['aid'])->find();
        $sale_time=strtotime($list['sale_time']);

        if($list['user_id']!=$_SESSION['admin']['id']){
				if(time() < $sale_time){
					if($list['remark'] > 0){
					 if(empty($enter_list)){
						$data['activity_id']=$_POST['aid'];
						$data['type_id']=$list['type_id'];
						$data['activity_name']=$list['name'];
						$data['activity_address']=$list['location'];
						$data['user_id']=$_SESSION['admin']['id'];
						$data['user_phone']=$_SESSION['admin']['user_phone'];
						$data['user_email']=$_SESSION['admin']['user_email'];
						$data['username']=$_SESSION['admin']['username'];
						$data['number']=$_POST['number'];
						$data['bank']=$_POST['bank'];
						$data['code']=uniqid();
						$data['status']=0;
						$data['create_time']=time();
						$enter=new MysqlModel('enter');
						if($enter->insert($data)){
							$data_val['remark']=$list['remark']-$_POST['number'];
							$activity->where('id='.$_POST['aid'])->update($data_val);
							echo '<script>alert("Sign up Success");location="./index.php?m=goods&a=show"</script>';

						}else{
							echo '<script>alert("Sign up Failed");location="./index.php?m=goods&a=show"</script>';
						}
					}else{
						echo '<script>alert("Non - enrolment");location="./index.php?m=goods&a=show"</script>';
					}
				}else{
					echo '<script>alert("Activity ticket insufficient");location="./index.php?m=goods&a=show"</script>';
				}
			}else{
				echo '<script>alert("Sale time has passed");location="./index.php?m=goods&a=show"</script>';
			}
		}else{
			echo '<script>alert("Do not sign up for your own activities");location="./index.php?m=goods&a=show"</script>';
		}
	}

	public function issue(){
			$goods  = new MysqlModel('activity');
			$type = new MysqlModel('type');
			$where='activity.type_id=type.id and activity.user_id='.$_SESSION['admin']['id'];
			$p = new Page($goods->where($where)->total(),10);
			$result=$goods->field('activity.id','activity.name','activity.title','activity.activity_pic','activity.descr','activity.remark','activity.start_time','activity.end_time','activity.location','activity.over','type.name as tname','activity.price','activity.sale_time','activity.status')->where($where)->order('activity.id desc')->limit($p->limit())->r_select('activity','type');
			// var_dump($result);
			// echo $goods->sql;
			foreach ($result as $key => $value) {
				$result[$key]['start_time']=date('Y-m-d H:i:s',$value['start_time']);
				$result[$key]['end_time']=date('Y-m-d H:i:s',$value['end_time']);
			}
			$result1=$type->order('concat(path,id) ASC')->select();
			$i = 0;
			include './view/goods/myactivity.html';
	}

	public function attend(){
		$goods  = new MysqlModel('activity');
	    $type = new MysqlModel('type');
	    $enter=new MysqlModel('enter');
	    $where='enter.activity_id=activity.id and activity.type_id=type.id and enter.user_id='.$_SESSION['admin']['id'];
	    $p = new Page($enter->where('user_id='.$_SESSION['admin']['id'])->total(),10);
	    $result=$enter->field('activity.id','activity.name','activity.title','activity.activity_pic','activity.descr','activity.remark','activity.start_time','activity.end_time','activity.location','activity.over','type.name as tname','activity.status')->where($where)->order('activity.id desc')->limit($p->limit())->r_select('activity','type','enter');

	    // var_dump($result);
		foreach ($result as $key => $value) {
				$result[$key]['start_time']=date('Y-m-d H:i:s',$value['start_time']);
				$result[$key]['end_time']=date('Y-m-d H:i:s',$value['end_time']);
			}
	    include './view/goods/attend.html';
	}

	public function feedlist(){
		$where = 'assess.status=0 and assess.activity_id=activity.id and assess.user_id=users.id and assess.activity_id='.$_GET['id'];
		$assess = new MysqlModel('assess');
		$p = new Page($assess->where($where)->total(), 5);
		$result = $assess->field('activity.name', 'users.username', 'users.user_phone', 'users.user_email', 'assess.title', 'assess.content', 'assess.score','assess.id')->where($where)->order('assess.id ASC')->limit($p->limit())->r_select('assess', 'activity', 'users');
		$i = 0;
		// var_dump($result);
		include './view/goods/feedlist.html';
	}

}
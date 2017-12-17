<?php
	class TypeControl{

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
				//search type name
				$wherelist[] = " name LIKE '%{$_GET['search']}%'";
			}
			//
			if(count($wherelist) > 0){
				return implode(' AND ',$wherelist);
			}else{
				return '';
			}
		}


		//display
		public function show(){
			
			if(isset($_GET['c']) && $_GET['c'] == 'search'){
				//use search function
				$search = $this->searchType();
			}else{
				//no input in search
				$search = '';
			}
			
		
				
				if(empty($_GET['pid'])){
					$pid=0;
				}else{
					$pid = $_GET['pid'];

				}

			$type  = new MysqlModel('type');
			$p = new Page($type->where($search)->total(),3);

			if(empty($search)){
				$type->where( 'pid='.$pid);
			}else{
				$type->where($search);
			}
			
			
			$result = $type->order('id ASC')->limit($p->limit())->select();
			//echo $type->sql;
			$i = 0;
			include './view/type/index.html';
		}

		//add category and son category
		public function add(){
			if(empty($_GET['id'])){
					$pid = 0;
					$path = '0,';

				}else{
					$pid = $_GET['id'];
					//add ',' to son category after main category;
					$type  = new MysqlModel('type');
					$result = $type->where("id=".$pid)->find();
					$path = $result['path'].$result['id'].',';

				}

			include './view/type/add.html';
		}


		public function do_add(){
			$where=array();
			$type  = new MysqlModel('type');
			if(empty($_POST['name'])){
				echo '<script>alert("Please do not leave the field blank");location="./index.php?m=type&a=add"</script>';
				exit;
			}
			$where['name']=$_POST['name'];
			$result = $type->where($where)->find();
			if(!$result){
				if($list=$type->insert($_POST)){
					echo '<script>alert("Add category success");location="./index.php?m=type&a=show"</script>';
				}else{
					echo '<script>alert("Added category failed");location="./index.php?m=type&a=add"</script>';

				}
			}else{
				echo '<script>alert("Name repeat");location="./index.php?m=type&a=add"</script>';

			}
				
		}

		//delete category
		public function del(){
			//var_dump($_GET);
			$id = $_GET['id'];
			$type = new MysqlModel('type');
			$result = $type->where("pid=".$id)->select();
			if($result){
					echo '<script>alert("Subcategory");location="./index.php?m=type&a=show"</script>';

				}else{

					if($type->where('id='.$id)->delete()){
						echo '<script>alert("Delete success");location="./index.php?m=type&a=show"</script>';

					}else{

						echo '<script>alert("Delete failed");location="./index.php?m=type&a=show"</script>';

					}

				}
			}

			public function update(){
				
				$id=$_GET['id'];
				$type  = new MysqlModel('type');
				$result = $type->where('id='.$id)->select();
				include './view/type/update.html';
			}



				public function do_update(){
				
					$id=$_POST['id'];
					$type  = new MysqlModel('type');
					$result=$type->where('id='.$id)->update($_POST);
					if($result){
						echo '<script>alert("Update success");location="./index.php?m=type&a=show"</script>';
					}else{
						echo '<script>alert("Update failed");location="./index.php?m=type&a=show"</script>';
					}

			}
			public function back(){
				//var_dump($_GET);
				$id=$_GET['pid'];
				$urlshang = $_SERVER['HTTP_REFERER']; //get last page address
				$urldan = $_SERVER['PHP_SELF']; //get current page address
				var_dump($urldan); 
				$url=$urlshang.'&'.'pid='.$id; 
				echo $url;
				
				if($id=='1'){
					echo '<script>location="'.$urldan  .'"</script>';
				}else{
					echo '<script>location="'.$url.'"</script>';
				}
				

			}

				//change to display
		public function do_see(){
			//var_dump($_GET);
			$type = new MysqlModel('type');
			$id=$_GET['id'];
			$map=array();
			$map['display']=1;
			if($type->where('id='.$id)->update($map)){
				
				header('location:index.php?m=type&a=show');
			}else{
				
				header('location:index.php?m=type&a=show');
			}
		}
		//change to none
		public function do_hide(){
			//var_dump($_GET);

			$type = new MysqlModel('type');
			$id=$_GET['id'];
			$map=array();
			$map['display']=0;
			if($type->where('id='.$id)->update($map)){
				
				header('location:index.php?m=type&a=show');
			}else{
				
				header('location:index.php?m=type&a=show');
			}
		}

		public function catename(){
			// $name=$_POST['name'];
			$cate = new MysqlModel('cate');
			$result = $cate->order('id ASC')->select();
			foreach ($result as $key => $value) {
				$list[] = array(
					    'id' => $value['id'],
					    'label' => $value['name']
					);
			}
			echo json_encode($list);
		}

}
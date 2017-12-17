<?php
class FeedControl
{
	function __construct()
	{
		$typelist = array('login', 'do_login');
		if (empty($_SESSION['admin'])) {
			if (!in_array($_GET['a'], $typelist)) {
				header('location:./index.php?m=index&a=login');

			}
		}
	}


	public function show()
	{
		$where = 'assess.status=0 and assess.activity_id=activity.id and assess.user_id=users.id';
		$assess = new MysqlModel('assess');
		$p = new Page($assess->where($where)->total(), 5);
		$result = $assess->field('activity.name', 'users.username', 'users.user_phone', 'users.user_email', 'assess.title', 'assess.content', 'assess.score','assess.id')->where($where)->order('assess.id ASC')->limit($p->limit())->r_select('assess', 'activity', 'users');
		$i = 0;
		include './view/feed/index.html';
	}

	public function add()
	{
		$where = 'enter.status=0 and enter.activity_id=activity.id and enter.user_id='.$_SESSION['admin']['id'];
		$assess = new MysqlModel('enter');
		$result = $assess->field('activity.name', 'activity.start_time','activity.id as aid','enter.id')->where($where)->order('enter.id ASC')->r_select('activity', 'enter');
		foreach ($result as $key => $value) {
			if(time() < strtotime($value['start_time'])){
				unset($value);
			}
		}
		include './view/feed/add.html';
	
	}

	public function do_add(){
		if(empty($_POST['enter_id']) || empty($_POST['content'])
//			|| empty($_POST['title'])
			|| empty($_POST['score'])){
			echo '<script>alert("Please fill in all fields");location="./index.php?m=feed&a=add"</script>';
			exit;
		}
		$enter_id=$_POST['enter_id'];
		$enter = new MysqlModel('enter');
		$list=$enter->where('id='.$enter_id)->find();
		$_POST['activity_id']=$list['activity_id'];
		$_POST['user_id']=$_SESSION['admin']['id'];
		$_POST['addtime']=time();
		$_POST['status']=0;
		$assess=new MysqlModel('assess');
		if($assess->insert($_POST)){
			echo '<script>alert("Add Success");location="./index.php?m=feed&a=show"</script>';

		}else{
			echo '<script>alert("Add Failed");location="./index.php?m=feed&a=add"</script>';
		}
	}

	public function del(){
		$id=$_GET['id'];
		$assess=new MysqlModel('assess');
		if($assess->where('id='.$id)->delete()){
			echo '<script>alert("Congratulations on your success!");location="./index.php?m=feed&a=show"</script>';
		}else{
			echo '<script>alert("Brother, no, oh, Failed !");location="./index.php?m=feed&a=show"</script>';
		}
	}
}
?>		
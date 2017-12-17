<?php

	//
	class MysqlModel{

		protected $host;
		//username
		protected $user;
		//password
		protected $pwd;
		//table_name
		protected $tabName;
		//table_prefix
		protected $prefix;
		//database_name
		protected $dbname;

		protected $charset;

		protected $conn = null;

		private $sql;
		//path
		public $cache;
		//
		protected $where;
		protected $order;
		protected $limit;
		protected $field;
		protected $method = array('where','order','limit','field');
		//
		//initial value
		function __construct($tabName = '',$cache = './cache/'){
			//
			$this->host = DB_HOST;
			$this->user = DB_USER;
			$this->pwd = DB_PWD;
			$this->charset = DB_CHARSET;
			$this->prefix = DB_PREFIX;
			$this->dbname = DB_NAME;
			$this->cache = $cache;
			//check the table_name input or get a table name
			if($tabName == ''){
				//get_class
				//1.get user
				//2.change to lower case
				$this->tabName = strtolower(substr(get_class($this),0,-5));
			}else{
				$this->tabName = $tabName;
			}
			//initial link
			$this->link = $this->connect();

		}
		//define link
		protected function connect(){

			$conn = @mysqli_connect($this->host,$this->user,$this->pwd,$this->dbname);

			if(!$conn){
				return false;
			}
			//charset
			mysqli_set_charset($conn,$this->charset);
			return $conn;
		}

		function getField(){
			//file path store
			$pathInfo = $this->cache.$this->tabName.'Cache.php';
			if(file_exists($pathInfo)){
				return include $pathInfo;
			}else{
				$sql = 'DESC '.$this->prefix.$this->tabName;
				$result = $this->query($sql);
				// echo 111;
				return $this->writeField($result);
			}


		}

		function writeField($data){
			//1.validate the path
			if(!file_exists($this->cache)){
				mkdir($this->cache);
			}
			//1.1the part behind the "/"
			$this->cache = rtrim($this->cache,'/').'/';
			//2.create the path
			$pathInfo = $this->cache.$this->tabName.'Cache.php';
			//echo $pathInfo;
			$fields = array();
			//3.get each primary key and auto increment
			foreach($data as $key=>$val){
				//primary key
				if($val['Key'] == 'PRI'){
					$fields['_pk'] = $val['Field'];
				}
				//get auto increment
				if($val['Extra'] == 'auto_increment'){
					$fields['_auto'] = $val['Field'];
				}
				//get name
				$fields[] = $val['Field'];
			}
			file_put_contents($pathInfo,"<?php\r\n return ".var_export($fields,true)."\r\n?>");
			//
			//if set second one, then will save the value
			return $fields;

		}
		//define sql
		private function query($sql){
			//delete all
			$this->clearWhere();
			//save sql
			$this->sql = $sql;
			$rows = array();
			$result = mysqli_query($this->link,$sql);
			if($result && mysqli_num_rows($result)>0){
				while($row = mysqli_fetch_assoc($result)){
					//insert search input into array
					$rows[] = $row;
				}
				return $rows;
			}else{
				return false;
			}
		}
		//add,delete,edit,send function
		private function exec($sql){
			//delete all
			$this->clearWhere();
			//save sql
			$this->sql = $sql;
			//send
			$result = mysqli_query($this->link,$sql);
			if($result && mysqli_affected_rows($this->link)>0){
				return mysqli_insert_id($this->link)?mysqli_insert_id($this->link):mysqli_affected_rows($this->link);
			}else{
				return false;
			}

		}

		public function insert(array $data){

			// var_dump($data);
			$key = '';
			$val = '';

			$field = $this->getField();
			foreach($data as $k=>$v){

				if(in_array($k,$field)){
					$key .= '`'.$k.'`,';
					$val .= '"'.$v.'",';
				}
			}
			$key = rtrim($key,',');
			$val = rtrim($val,',');
			// echo $key.'<br/>'.$val;
			$sql = "INSERT INTO {$this->prefix}{$this->tabName}({$key}) VALUES({$val})";
			// echo $sql;
			return $this->exec($sql);
		}
		//if use the function not in the code
		function  __call($methodName,$args){

			if(in_array($methodName,$this->method)){
				//user use the where function or not
				if($methodName == 'where'){
					$this->where = isset($args[0])?$args[0]:'';

				}elseif($methodName == 'field'){
					$this->field = $args;
				}elseif($methodName == 'limit'){
					if(count($args)>1){
						$this->limit = $args[0].','.$args[1];
					}else{
						$this->limit = $args[0];
					}
				}elseif($methodName == 'order'){
					$this->order = $args[0];
				}
			}
			return $this;
		}

		function __get($name){
			if($name == 'sql'){
				echo $this->$name;
			}
		}
		//delete function
		function delete(){
			//var_dump($this->where);
			if(!empty($this->where)){
				$where = ' WHERE '.$this->where;
			}else{
				$where = '';

				if(!empty($_GET)){
					//get the value of $field
					$field = $this->getField();

					//get primary key in the $field
					$id = $field['_pk'];

					foreach($_GET as $k=>$v){
						if($id == $k){
							$val = $v;
							//id = 2;
						}
					}
					$where = ' WHERE '.$id. '=' .$val;

				}
			}
			$sql  = "DELETE FROM {$this->prefix}{$this->tabName} {$where}";

			//echo $sql;
			//return the value
			return $this->exec($sql);
		}
		//edit
		public function update(array $data){

			$field = $this->getField();
			$update = '';
			foreach($data as $k=>$v){
				//illegal chart invalidation
				if(in_array($k,$field) && $k != $field['_pk']){
					$update .= '`'.$k.'`="'.$v.'",';
				}elseif($k == $field['_pk']){
					$con = '`'.$k.'`= "'.$v.'"';
				}

			}
			//
			$update = rtrim($update,',');

			if(!empty($this->where)){

				$where = ' WHERE '.$this->where;
			}else{

				$where = ' WHERE '.$con;
			}
			//echo $update;

			$sql = "UPDATE {$this->prefix}{$this->tabName} SET {$update} {$where}";
			// echo $sql;exit;
			return $this->exec($sql);
		}

		//search
		public function select(){
			$limit = $where = $order = '';
			//input limit or not
			if(!empty($this->limit)){
				$limit = ' LIMIT '.$this->limit;
			}
			//input where or not
			if(!empty($this->where)){
				$where = ' WHERE '.$this->where;
			}
			//input order or not
			if(!empty($this->order)){
				$order = ' ORDER BY '.$this->order;
			}
			//user search the $field
			if(!empty($this->field)){

				$field = $this->getField();

				$hefa = array_intersect($this->field,$field);

				$fields = implode(',',$hefa);

			}else{
				//no search input
				$fields = '*';
			}
			$sql = "SELECT {$fields} FROM {$this->prefix}{$this->tabName} {$where} {$order} {$limit}";
			//select * from user WHERE ORDER LIMIT
			//echo $sql;
			return $this->query($sql);
		}
		//multiple search
		public function r_select($tabName1,$tabName2){
			//1.get all table names for searching
			$args = func_get_args();
			//2.join the table name
			$tabNames = implode(',',$args);
			$fields = $where = $limit = $order = '';
			//3.get the search input
			if(!empty($this->field)){
				$fields = $this->field;
			}


			foreach($args as $k=>$v){

				$this->tabName = $v;

				$this->getField();
			}

			foreach($args as $k=>$v){
				$cache = './cache/'.$v.'Cache.php';
				$arr = include $cache;
				foreach($arr as $key=>$val){

					$num[] = $v.'.'.$val;
				}
			}

			//$keys = array();
			//var_dump($fields);exit;
			//user.username
			$keys = array();
			foreach($fields as $k=>$v){
				if(strchr($v,' as ',true) && in_array(strchr($v,' as ',true),$num)){

					$keys[] = $v;
				}elseif(in_array($v,$num)){
					$keys[] = $v;
				}
			}
			if(empty($keys)){
				//check the repeat chart,if have, add it automatically
				$keys[] = '*';
			}
			if(!empty($this->where)){
				$where = ' WHERE '.$this->where;
			}
			//5.check limit
			if(!empty($this->limit)){
				$limit = ' LIMIT '.$this->limit;
			}
			//6.check order
			if(!empty($this->order)){
				$order = ' ORDER BY '.$this->order;
			}

			$fieldsStr = implode(',',$keys);
			//echo $fieldsStr;

			//combine sql
			$sql = "SELECT {$fieldsStr} FROM {$tabNames} {$where} {$order} {$limit}";
			// echo $sql;

			return $this->query($sql);

		}
		//
		function total(){
			$where = '';
			if(!empty($this->where)){
				$where = ' WHERE '.$this->where;
			}
			//get primary key
			$field = $this->getField();
			if(isset($field['_pk'])){
				$pk = $field['_pk'];
			}else{
				$pk = '*';
			}
			//combine sql
			$sql = "SELECT COUNT({$pk}) as total FROM {$this->prefix}{$this->tabName} {$where}";
			return intval($this->query($sql)[0]['total']);
		}

		public function find(){
			$where = '';

			if(!empty($this->where)){
				$where = ' WHERE '.$this->where;
			}

			if(!empty($this->field)){

				$field = $this->getField();

				$hefa = array_intersect($this->field,$field);

				$fields = implode(',',$hefa);
				//var_dump($hefa);
			}else{

				$fields = '*';
			}
			$sql = "SELECT {$fields} FROM {$this->prefix}{$this->tabName} {$where} LIMIT 1";

			return $this->query($sql)[0];
		}

		function clearWhere(){
			$this->where = '';
			$this->limit = '';
			$this->order = '';
			$this->field = '';
		}

		function  __destruct(){
			if($this->link != null){
				mysqli_close($this->link);
			}
		}


}


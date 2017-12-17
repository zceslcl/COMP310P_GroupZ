<?php
	class Upload{
		//properties
		public $pic;
		public $path;
		public $size;
		public $type;
		public $newImg='';
		public $newPath = '';
		public $pathInfo = array();
		//
		//create file for uploading
		function __construct($pic,$path = '',$size = 500000000000000000,array $type = array('image/jpeg','image/png','image/gif')){
			$this->pic = $pic;
			$this->path = $path;
			$this->size = $size;
			$this->type = $type;
		}
		//upload function
		public function do_upload(){
			if($this->fileError() !== true){
				return $this->fileError();
			}elseif($this->patternType() !== true){
				return $this->patternType();
			}elseif($this->patternSize() !== true){
				return $this->patternSize();
			}elseif($this->renameImg() !== true){
				return $this->renameImg();
			}else{
				//move the pic
				return $this->moveImg();
			}

		}

		//check the error method in the uploading
		protected function fileError(){
			if($_FILES[$this->pic]['error'] > 0){
				switch($_FILES[$this->pic]['error']){
					case 1:
						return 'over th upload_max_filesize value in PHP.ini';
					case 2:
						return 'over the MAX_FILE_SIZE value in HTML';
					case 3:
						return 'only part of file upload';
					case 4:
						return 'no file upload';
					case 6:
						return 'cannot find the catalog';
					case 7:
						return 'upload fail';
				}
			}
			return true;
		}
		//check the validation of the pic in the uploading
		protected function patternType(){
			if(!in_array($_FILES[$this->pic]['type'],$this->type)){
				return 'Invalidate type of picture';
			}
			return true;
		}
		//check the size of the picture
		protected function patternSize(){
			if($_FILES[$this->pic]['size'] > $this->size){
				return 'The size of picture is too large';
			}
			return true;
		}
		//check and save the path of the pic and rename the pic
		protected function renameImg(){
			do{
				//1.get suffix of pic
				$suffix = strrchr($_FILES[$this->pic]['name'],'.');
				//2.combine pic name
				$this->newName = md5(time().mt_rand(1,999).uniqid()).$suffix;
				//the "/" in the path
				$this->path = rtrim($this->path,'/').'/';
				//3.check the path
				if(!file_exists($this->path)){
					mkdir($this->path);
				}
				//combine the complete path
				$this->newPath = $this->path.$this->newName;
			}while(file_exists($this->newPath));
			return true;
		}
		protected function moveImg(){
			//var_dump($_FILES);
			//echo $_FILES[$this->pic]['tmp_name'].'<br/>';
			//echo $this->newPath;exit;
			if(move_uploaded_file($_FILES[$this->pic]['tmp_name'],$this->newPath)){
				$this->pathInfo['pathInfo'] = $this->newPath;
				$this->pathInfo['path'] = $this->path;
				$this->pathInfo['name'] = $this->newName;
				return $this->pathInfo;
			}else{
				return false;
			}
		}
	}
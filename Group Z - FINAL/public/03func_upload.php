<?php

	
	//var_dump($result);
	function upload($pic,$path='./upload',$size = 10000000000000,array $type=array('image/jpeg','image/png','image/jpg','image/gif')){
		$file  = $_FILES[$pic];
		//errors
		if($file['error'] > 0){
			switch($file['error']){
				case 1:
					return 'exceed php max filesize';
				case 2:
					return 'exceed html max filesize';
				case 3:
					return 'partial upload';
				case 4:
					return 'no file';
				case 6:
					return 'no catalog';
				case 7:
					return 'upload failed';
			}
		}

		if(!in_array($file['type'],$type)){
			return 'Invalidate file type';
		}

		if($file['size'] > $size){
			return 'The file is too big';
		}

		if(!file_exists($path)){
			mkdir($path);
		}

		$path = rtrim($path,'/').'/';


		$suffix = strrchr($file['name'],'.');
		do{

			$newName = md5(uniqid().time().mt_rand(1,1000)).$suffix;
		}while(file_exists($path.$newName));


		$arr = array();
		if(move_uploaded_file($file['tmp_name'],$path.$newName)){
			$arr['path'] = $path;
			$arr['name'] = $newName;
			$arr['info'] = $path.$newName;
			return $arr;
		}else{
			return 'upload failed';
		}
	}
	
	
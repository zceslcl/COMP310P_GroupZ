<?php

	session_start();
	

	$class = !empty($_GET['m'])?ucfirst($_GET['m']):'Index';
	$method = !empty($_GET['a'])?$_GET['a']:'index';
	include '../public/dbconfig.php';
	

	function __autoload($className){

		if(file_exists('./control/'.$className.'.class.php')){
			require('./control/'.$className.'.class.php');
		}elseif(file_exists('./model/'.$className.'.class.php')){
			require './model/'.$className.'.class.php';
		}elseif(file_exists('./org/'.$className.'.class.php')){
			require './org/'.$className.'.class.php';
		}else{
			die('Error, no correspondence found'.$className.'class!');
		}
	}

	//combine
	$name = $class.'Control';
	$one = new $name;
	$one -> $method();
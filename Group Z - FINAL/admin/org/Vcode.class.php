<?php
	//Vcode

	class Vcode{
		//properties

		private $width;

		private $height;

		private $img;

		private $codeNum;

		private $fontFile;

		//initial value of the width and height
		function __construct($fontfile = '',$width = 100,$height = 40,$codeNum = 4){
			$this->width = $width;
			$this->height = $height;
			$this->codeNum = $codeNum;
			$this->fontFile = $fontfile;
		}

		public function show(){

			$this->createImage();

			$this->setpixel();

			$this->imgLine();

			$this->createFont();

			$this->showImage();
		}

		function __toString(){
			$this->show();
			return '';
		}

		private function createImage(){

			$this->img = imagecreatetruecolor($this->width,$this->height);

			$back = imagecolorallocate($this->img,mt_rand(220,255),mt_rand(220,255),mt_rand(200,255));
			//background color
			imagefill($this->img,0,0,$back);

			$borderColor = imagecolorallocate($this->img,255,0,0);

			imagerectangle($this->img,0,0,$this->width-1,$this->height-1,$borderColor);
		}

		private function showImage(){
			header("Content-type:image/jpeg");
			imagejpeg($this->img);
		}

		private function imgLine(){

			for($i=0;$i<10;$i++){

				$lineColor = imagecolorallocate($this->img,mt_rand(150,180),mt_rand(150,180),mt_rand(150,180));
				imageline($this->img,mt_rand(2,$this->width-2),mt_rand(2,$this->height-2),mt_rand(2,$this->width-2),mt_rand(2,$this->height-2),$lineColor);
			}
		}

		private function setpixel(){

			for($i=0;$i<300;$i++){

				$pixelColor = imagecolorallocate($this->img,mt_rand(120,150),mt_rand(120,150),mt_rand(120,150));

				imagesetpixel($this->img,mt_rand(2,$this->width-2),mt_rand(2,$this->height-2),$pixelColor);
			}
		}

		private function getFont(){

			$ascii = '';
			$str = '345678ABCDEFGHJKLMNPQRSTUVWXYabcdefhijkmnpqrstuvwxy';
			//random four character
			for($i=0;$i<$this->codeNum;$i++){

				$str = str_shuffle($str);

				$char = $str{mt_rand(0,strlen($str)-1)};

				$ascii .= $char;
			}

			$_SESSION['code'] = $ascii;
			//返回拼接好后的四个字符
			return $ascii;
		}

		private function createFont(){

			$ascii = $this->getFont();

			if($this->fontFile != ''){

				for($i=0;$i<$this->codeNum;$i++){
					//random color
					$fontColor = imagecolorallocate($this->img,mt_rand(10,90),mt_rand(10,90),mt_rand(10,90));

					$x = $i*($this->width/$this->codeNum)+mt_rand(5,8);

					$y = mt_rand($this->height/2,$this->height-3);

					imagettftext($this->img,mt_rand(15,20),mt_rand(0,40),$x,$y,$fontColor,$this->fontFile,$ascii{$i});
				}
			}else{

				for($i=0;$i<$this->codeNum;$i++){

					$fontColor = imagecolorallocate($this->img,mt_rand(10,90),mt_rand(10,90),mt_rand(10,90));
					$x = $i*($this->width/$this->codeNum)+mt_rand(3,8);
					$y = mt_rand(10,20);
					imagechar($this->img,mt_rand(3,5),$x,$y,$ascii{$i},$fontColor);
				}
			}
		}

		function __destruct(){

			imagedestroy($this->img);
		}
	}
	// $c = new Vcode('./msyh.ttf');
	// //$c->show();
	// echo $c;
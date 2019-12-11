<?php
define('COOKIE_TIMER', 6 );
define('SALTING', 'aeb1021e49282375948ac10004c2eb53ef9070e0');

$captcha_error = null;
function check_captcha($captcha,$timer = true){
	global $error_captcha;
	
	$captcha = htmlspecialchars(stripslashes(trim($captcha)));
	if(empty($captcha)){
		remove_cookie();
		return false;
	}elseif (isset($_COOKIE['Captcha']) ){
		list($Hash, $Time) = explode('.', $_COOKIE['Captcha']);
		if ( md5(SALTING.$captcha.$_SERVER['REMOTE_ADDR'].$Time) != $Hash ){
			remove_cookie();
			return false;
		}elseif( (time() - COOKIE_TIMER*60) > $Time && $timer){
			remove_cookie();
			return false;
		}
	}else{
		$error_captcha = 'ERROR_CAPTCHA_COOKIE';
		return false;
	}
	return true;
}

function remove_cookie(){
	$domain = $_SERVER['HTTP_HOST'];
	if ( strtolower( substr($domain, 0, 4) ) == 'www.' )
		$domain = substr($domain, 4);
	if ( substr($domain, 0, 1) != '.' )
		$domain = '.'.$domain;
	setcookie('Captcha', '', time() - 3600, '/',$domain);
	unset($_COOKIE['Captcha']); 
}

if(isset($_GET['img'])){
	$capt = new captcha;
	$capt->display();
	exit();
}

class captcha {
	private $UserString;
	private $transparent = true;
	private $bg_color = 'ffffff';

	private function font(){
		switch(rand(1,2)){
			case 1: return dirname(__FILE__).'/fonts/arial.ttf'; break;
			case 2: return dirname(__FILE__).'/fonts/verdana.ttf'; break;
			default : return dirname(__FILE__).'/fonts/arial.ttf'; break; 
		}
	}

	public function transparent_bg($var){$this->transparent = ($var != true ? false : true);}
	public function bg_color($var){$this->bg_color = $var;}

	private function LoadPNG(){
		$im = imagecreatetruecolor(130, 34);
		$rgb = $this->html2rgb($this->bg_color);
		$bgcolor = imagecolorallocate($im, $rgb[0], $rgb[1], $rgb[2]);
		imagefill($im, 0, 0, $bgcolor);
		if($this->transparent)imagecolortransparent($im, $bgcolor);
		return $im;
	}

	private function task_string(){
		$image = $this->LoadPNG(); 
		$string_a = array("a","b","c","d","e","f","g","h","j","k",
						  "m","n","p","r","s","t","u","v","w","x",
						  "y","z","2","3","4","5","6","7","8","9");
		$x = 0;
		for($i = 0; $i < 5; $i++){
			$x += rand(16,18);
			$temp = $string_a[rand(0,29)];
			$this->UserString .= $temp;
			imagettftext($image, $this->rand_fontsize(), $this->rand_angle(), $x, $this->rand_Y(), $this->rand_color($image), $this->font(), $temp);
		}
		return $image;
	}

	private function rand_color($im){return imagecolorallocate($im, rand(0,155), rand(0,155), rand(0,155));}
	private function rand_angle(){return rand(-15,15);}
	private function rand_Y(){return rand(25,30);}
	private function rand_fontsize(){return rand(14,20);}

	private function task_sum(){
		$image	= $this->LoadPNG(); 
		$x		= rand(10,20);
		$sum	= rand(1,3);
		$number1= $sum != 3 ? rand(10,99) : rand(1,9);
		$number2= rand(1,9);
		imagettftext($image, $this->rand_fontsize(), $this->rand_angle(), $x, $this->rand_Y(), $this->rand_color($image), $this->font(),($sum ==3?'':substr($number1, 0,1)));
		imagettftext($image, $this->rand_fontsize(), $this->rand_angle(), ($x += 15), $this->rand_Y(), $this->rand_color($image), $this->font(),($sum ==3?$number1:substr($number1, -1)) );
		imagettftext($image, $this->rand_fontsize(), 0, ($x += 20), $this->rand_Y(), $this->rand_color($image), $this->font(),($sum ==1?'+':($sum ==2?'-':'*')));
		imagettftext($image, $this->rand_fontsize(), $this->rand_angle(), ($x += 18), $this->rand_Y(), $this->rand_color($image), $this->font(), $number2);
		imagettftext($image, $this->rand_fontsize(), $this->rand_angle(), ($x += 18), $this->rand_Y(), $this->rand_color($image), $this->font(),'=');
		imagettftext($image, $this->rand_fontsize(), $this->rand_angle(), ($x += 18), $this->rand_Y(), $this->rand_color($image), $this->font(),'?');
		$this->UserString = ($sum ==1?$number1+$number2:($sum == 2?$number1-$number2:$number1*$number2));
		return $image; 
	}

	private function cookie(){
		$time = time();
		$domain = $_SERVER['HTTP_HOST'];
		if ( strtolower( substr($domain, 0, 4) ) == 'www.' )
			$domain = substr($domain, 4);
		if ( substr($domain, 0, 1) != '.' )
			$domain = '.'.$domain;
		setcookie('Captcha', md5(SALTING.$this->UserString.$_SERVER['REMOTE_ADDR'].$time).'.'.$time, null, '/',$domain);
	}

	public function display(){
		switch(rand(1,2)){
			case 1:	 $image = $this->task_string();	break;
			case 2:	 $image = $this->task_sum();	break;
			default: $image = $this->task_sum();	break;
		}
		$this->cookie();
		header("Content-type: image/png");
		imagepng($image);
	}

	private function html2rgb($color){
		if ($color[0] == '#')
			$color = substr($color, 1);

		if (strlen($color) == 6)
			list($r, $g, $b) = array($color[0].$color[1],$color[2].$color[3],$color[4].$color[5]);
		elseif (strlen($color) == 3)
			list($r, $g, $b) = array($color[0].$color[0], $color[1].$color[1], $color[2].$color[2]);
		else
			return false;

		$r = hexdec($r); $g = hexdec($g); $b = hexdec($b);

		return array($r, $g, $b);
	}
}
?>
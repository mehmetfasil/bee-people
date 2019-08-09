<?php
/**
 * @copyright Masters 2019
 * @author Masters
 * @abstract CAPTCHA
 * @version 1.0
 */

class captcha  {
	private $Length;
	private $CaptchaString;
	public  $fontpath;
	private $fonts;
	public  $errors;

	public function __construct ($length=6){
		header("Content-type: image/png");

		$this->Length   = $length;
		$this->fontpath = "objects/fonts/";
		$this->fonts    = $this->getFonts();

		if ($this->fonts == false){
			//$errormgr = new error;
			$this->addError("Font bulunmadı!");
			$this->displayError();
			die();
		}

		if (function_exists("imagettftext") == false){
			$this->addError("");
			$this->displayError();
			die();
		}

		$this->stringGen();

		$this->makeCaptcha();
	} //captcha

	private function getFonts (){
		$fonts = array();

		if ($handle = @opendir($this->fontpath)){
			while (($file = @readdir($handle)) !== false){
				$extension = strtolower(substr($file, strlen($file) - 3, 3));

				if ($extension == "ttf"){
					$fonts[] = $file;
				}
			}
			
			@closedir($handle);
		}else{
			return false;
		}

		if (count($fonts) == 0){
			return false;
		}else{
			return $fonts;
		}
	} //getFonts

	private function getRandFont (){
		return $this->fontpath . $this->fonts[mt_rand(0, count($this->fonts) - 1)];
	} //getRandFont

	private function stringGen (){
		//$uppercase  = range("A", "Z");
		$lowercase  = range("a", "z");
		$numeric    = range(0, 9);

		//$CharPool   = array_merge($uppercase, $lowercase, $numeric);
		$CharPool   = array_merge($lowercase, $numeric);
		$PoolLength = count($CharPool) - 1;

		for ($i = 0; $i < $this->Length; $i++){
			$this->CaptchaString .= $CharPool[mt_rand(0, $PoolLength)];
		}
	} //StringGen

	private function makeCaptcha (){
		$imagelength = $this->Length * 25 + 16;
		$imageheight = 75;

		$image       = imagecreate($imagelength, $imageheight);

		//$bgcolor     = imagecolorallocate($image, 222, 222, 222);
		$bgcolor     = imagecolorallocate($image, 255, 255, 255);

		$stringcolor = imagecolorallocate($image, 0, 0, 0);

		$this->signs($image, $this->getRandFont());

		for ($i = 0; $i < strlen($this->CaptchaString); $i++){

			imagettftext($image, 25, mt_rand(-15, 15), $i * 25 + 10,
			mt_rand(30, 70),
			$stringcolor,
			$this->getRandFont(),
			$this->CaptchaString{$i});
		}

		//$this->noise($image, 10);
		//$this->blur($image, 6);

		imagepng($image);

		imagedestroy($image);
	} //MakeCaptcha
	
	private function noise (&$image, $runs = 30)	{
		$w = imagesx($image);
		$h = imagesy($image);

		for ($n = 0; $n < $runs; $n++){
			for ($i = 1; $i <= $h; $i++){
				$randcolor = imagecolorallocate($image,
				mt_rand(0, 255),
				mt_rand(0, 255),
				mt_rand(0, 255));

				imagesetpixel($image,
				mt_rand(1, $w),
				mt_rand(1, $h),
				$randcolor);
			}
		}
	}//noise

	private function signs (&$image, $font, $cells = 3){
		$w = imagesx($image);
		$h = imagesy($image);

		for ($i = 0; $i < $cells; $i++)	{
			$centerX     = mt_rand(1, $w);
			$centerY     = mt_rand(1, $h);
			$amount      = mt_rand(1, 15);
			$stringcolor = imagecolorallocate($image, 175, 175, 175);

			for ($n = 0; $n < $amount; $n++){
				$signs = range("A", "Z");
				$sign  = $signs[mt_rand(0, count($signs) - 1)];

				imagettftext($image, 25,
				mt_rand(-15, 15),
				$centerX + mt_rand(-50, 50),
				$centerY + mt_rand(-50, 50),
				$stringcolor, $font, $sign);
			}
		}
	} //signs

	private function blur (&$image, $radius = 3){
		$radius  = round(max(0, min($radius, 50)) * 2);

		$w       = imagesx($image);
		$h       = imagesy($image);

		$imgBlur = imagecreate($w, $h);

		for ($i = 0; $i < $radius; $i++){
			imagecopy     ($imgBlur, $image,   0, 0, 1, 1, $w - 1, $h - 1);
			imagecopymerge($imgBlur, $image,   1, 1, 0, 0, $w,     $h,     50.0000);
			imagecopymerge($imgBlur, $image,   0, 1, 1, 0, $w - 1, $h,     33.3333);
			imagecopymerge($imgBlur, $image,   1, 0, 0, 1, $w,     $h - 1, 25.0000);
			imagecopymerge($imgBlur, $image,   0, 0, 1, 0, $w - 1, $h,     33.3333);
			imagecopymerge($imgBlur, $image,   1, 0, 0, 0, $w,     $h,     25.0000);
			imagecopymerge($imgBlur, $image,   0, 0, 0, 1, $w,     $h - 1, 20.0000);
			imagecopymerge($imgBlur, $image,   0, 1, 0, 0, $w,     $h,     16.6667);
			imagecopymerge($imgBlur, $image,   0, 0, 0, 0, $w,     $h,     50.0000);
			imagecopy     ($image  , $imgBlur, 0, 0, 0, 0, $w,     $h);
		}
		imagedestroy($imgBlur);
	} //blur

	public function getCaptchaString (){
		return $this->CaptchaString;
	} //GetCaptchaString

	private function error (){
		$this->errors = array();
	} //error

	private function addError ($errormsg){
		$this->errors[] = $errormsg;
	} //addError

	public function displayError (){
		$iheight     = count($this->errors) * 20 + 10;
		$iheight     = ($iheight < 130) ? 130 : $iheight;

		$image       = imagecreate(600, $iheight);

		$errorsign   = imagecreatefrompng("objects/sysicons/error.png");
		imagecopy($image, $errorsign, 1, 1, 1, 1, 180, 120);

		$bgcolor     = imagecolorallocate($image, 255, 255, 255);

		$stringcolor = imagecolorallocate($image, 0, 0, 0);

		for ($i = 0; $i < count($this->errors); $i++){
			$imx = ($i == 0) ? $i * 20 + 5 : $i * 20;

			$msg = "Hata[".$i."]: ". $this->errors[$i];

			imagestring($image, 5, 190, $imx, $msg, $stringcolor);
		}

		imagepng($image);

		imagedestroy($image);
	} //displayError

	private function isError (){
		if (count($this->errors) == 0){
			return false;
		}else{
			return true;
		}
	} //isError
} //class: captcha
?>
<?php
/**
 * @copyright Masters 2019
 * @author Masters
 * @abstract Yazı Resme
 * @version 1.0
 */

class text2image {
	private $image;
	private $width;
	private $height;
	private $font;
	private $text_color;
	
	/**
	 * Function to Initialise the image
	 *
	 * @param $width  int   - required width of the image
	 * @param $height  int - required height of the image
	 * @param $bgColor  string - Color (in hexadecimal / RGB / Color Name)
	 * @param $textColor  string - Color (in hexadecimal / RGB / Color Name)
	 *
	 */	
	
	public function __construct ($width, $height, $bgColor=array(255,255,255), $textColor=array(0,0,0), $font="arial.ttf") {
		if ($width <= 0 || $height <= 0 || !is_numeric($width) || !is_numeric($height))  {
			die("Hata: Resim oluşturulamadı!");			
		}
		
		$this->width = $width;
		$this->height = $height;
		$this->image = imagecreate($width, $height) or die("Hata: GD kütüphanesi aktif değil!");
		$this->font = $font;
		$bgColorArray = $bgColor;
		$textColorArray = $textColor;

		imagecolorallocate($this->image, $bgColorArray[0], $bgColorArray[1], $bgColorArray[2]);
		$this->text_color = imagecolorallocate($this->image, $textColorArray[0], $textColorArray[1], $textColorArray[2]);
	}
	
	/**
	 * Function to create a PNG Image of the provided text 
	 *
	 * @param $fontSize int - Size of the text
	 * @param $x int - x-Coordinate of the starting of the text (image)
	 * @param $y int - y-Coordinate of the starting of the text (image)
	 * @param $textContent string - string to br converted into PNG
	 *
	 */
	
	public function ShowTextAsPng ($fontSize, $x, $y, $textContent){
		header("Content-type: image/png");
		
		imagettftext($this->image, $fontSize, 0, $x, $y, $this->text_color, "objects/fonts/".$this->font, $textContent);
		imagepng($this->image);
	}
	
	/**
	 * Function to create a JPG Image of the provided text
	 *
	 * @param $fontSize int - Size of the text
	 * @param $x int - x-Coordinate of the starting of the text (image)
	 * @param $y int - y-Coordinate of the starting of the text (image)
	 * @param $textContent string - string to br converted into JPG
	 *
	 */
	
	function ShowTextAsJpg ($fontSize, $x, $y, $textContent){
		header("Content-type: image/jpg");
		
		imagettftext($this->image, $fontSize, 0, $x, $y, $this->text_color, "objects/fonts/".$this->font, $textContent);
		imagejpeg($this->image);
	}
	
	
	/**
	 * Function to create a GIF Image of the provided text 
	 *
	 * @param $fontSize int - Size of the text
	 * @param $x int - x-Coordinate of the starting of the text (image)
	 * @param $y int - y-Coordinate of the starting of the text (image)
	 * @param $textContent string - string to br converted into GIF
	 *
	 */
	
	function ShowTextAsGif ($fontSize, $x, $y, $textContent){
		header("Content-type: image/gif");
		
		imagettftext($this->image, $fontSize, 0, $x, $y, $this->text_color, "objects/fonts/".$this->font, $textContent);
		imagegif($this->image);
	}

	/**
	 * Function to save the created GIF Image of the provided text 
	 *
	 * @param $fontSize int - Size of the text
	 * @param $x int - x-Coordinate of the starting of the text (image)
	 * @param $y int - y-Coordinate of the starting of the text (image)
	 * @param $textContent string - string to br converted into GIF
 	 * @param [$fileName] - string optional name of the file (by default: image) 
	 * PS: Do NOT add Extention in the file name
	 *	   Image will be stored in the directory containing this class.
	 *
	 * @return boolean - true if image is created , false otherwise
 	 */
	
	function SaveTextAsGif ($fontSize, $x, $y, $textContent, $fileName="image"){
		imagettftext($this->image, $fontSize, 0, $x, $y, $this->text_color, "objects/fonts/".$this->font, $textContent);
		return imagegif($this->image, $fileName.".gif");
	}
	
	/**
	 * Function to save the created JPG Image of the provided text 
	 *
	 * @param $fontSize int - Size of the text
	 * @param $x int - x-Coordinate of the starting of the text (image)
	 * @param $y int - y-Coordinate of the starting of the text (image)
	 * @param $textContent string - string to br converted into GIF
 	 * @param [$fileName] - string optional name of the file (by default: image) 
	 * PS: Do NOT add Extention in the file name
	 *	   Image will be stored in the directory containing this class.
	 *
	 * @return boolean - true if image is created , false otherwise
 	 */
	
	function SaveTextAsJpg ($fontSize, $x, $y, $textContent, $fileName="image"){
		imagettftext($this->image, $fontSize, 0, $x, $y, $this->text_color, "objects/fonts/".$this->font, $textContent);
		return imagegif($this->image, $fileName.".jpg");
	}

	/**
	 * Function to save the created PNG Image of the provided text 
	 *
	 * @param $fontSize int - Size of the text
	 * @param $x int - x-Coordinate of the starting of the text (image)
	 * @param $y int - y-Coordinate of the starting of the text (image)
	 * @param $textContent string - string to br converted into GIF
 	 * @param [$fileName] - string optional name of the file (by default: image) 
	 * PS: Do NOT add Extention in the file name
	 *	   Image will be stored in the directory containing this class.
	 *
	 * @return boolean - true if image is created , false otherwise
 	 */
	
	function SaveTextAsPng ($fontSize, $x, $y, $textContent, $fileName="image"){
		imagettftext($this->image, $fontSize, 0, $x, $y, $this->text_color, "objects/fonts/".$this->font, $textContent);
		return imagegif($this->image, $fileName.".png");
	}
}
?>
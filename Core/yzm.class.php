<?php
ob_clean();
class yzm
{
    public $width   = 90;
    public $height  = 30;
    
    public function add($li=4)
    {	
        $asd = '';
        for($i=1;$i<=$li;$i++)
        {
            $d = mt_rand();
            if($d%3 == 0)
            {
                $a = chr(mt_rand(48,57));
            }
            else if($d%2 == 0)
            {
                $a = chr(mt_rand(65,90));	
            }
            else
            {
                $a = chr(mt_rand(97,122));		
            }
            $asd .= $a;
        }
       return $asd;		
    }
    public function out($register=false)
    {
        $img = imagecreate($this->width,$this->height);//新建图像
        $r = Array(225, 255, 255, 223);
        $g = Array(225, 236, 237, 255);
        $b = Array(225, 236, 166, 125);
        $key = mt_rand(0, 3);
        imagecolorallocate($img,$r[$key], $g[$key], $b[$key]);
        for($i=0;$i<2;$i++)
        {
            imagearc($img,mt_rand(0,$this->width),mt_rand(0,$this->height),mt_rand(0,$this->width),mt_rand(0,$this->height),mt_rand(0,360),mt_rand(0,360),imagecolorallocate($img,mt_rand(0,155),mt_rand(0,155),mt_rand(0,155)));
            imageline($img,mt_rand(0,$this->width),mt_rand(0,$this->height),mt_rand(0,$this->width),mt_rand(0,$this->height),imagecolorallocate($img,mt_rand(0,155),mt_rand(0,155),mt_rand(0,155)));
            for($j=1;$j<=5;$j++)
            {	  
                imagesetpixel($img,mt_rand(0,$this->width),mt_rand(0,$this->height),imagecolorallocate($img,mt_rand(0,255),mt_rand(0,255),mt_rand(0,255))); 
            }   
        }
        //$str = $this->add(4);
        $str = randStr(4);
        for($i=0;$i<=strlen($str);$i++)
        {
            imagettftext($img,24,360,$i*20+10,mt_rand(24,$this->height-5),imagecolorallocate($img,mt_rand(0, 200), mt_rand(0, 120), mt_rand(0, 120)),'font/simhei.ttf',substr($str,$i,1));
        }
        $_SESSION[$GLOBALS['code']] = md5(strtoupper( $str ));
        header("Content-Type:image/png");
        imagepng($img);
    } 
}
/*$a = new yzm;
$a->out();*/
?>
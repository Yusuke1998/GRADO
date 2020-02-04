<?php

trait Filtered
{
	// Filtros
    public function image2gray(){

        imagefilter($this->rs,IMG_FILTER_GRAYSCALE);
    }

    public function imageBorder(){

        imagefilter($this->rs,IMG_FILTER_EDGEDETECT);
    }

    public function imageGaussian(){

        imagefilter($this->rs,IMG_FILTER_GAUSSIAN_BLUR);
    }

    public function imageSelective(){

        imagefilter($this->rs,IMG_FILTER_SELECTIVE_BLUR);
    }

    public function imageSmooth($arg1=0){

        imagefilter($this->rs,IMG_FILTER_SMOOTH,$arg1);
    }

    public function imagePixelate($arg1=0){

        imagefilter($this->rs,IMG_FILTER_PIXELATE,$arg1);
    }

    public function imageBrightnss($arg1=0){

        imagefilter($this->rs,IMG_FILTER_BRIGHTNESS,$arg1);
    }

    public function imageContrast($arg1=0){

        imagefilter($this->rs,IMG_FILTER_CONTRAST,$arg1);
    }

    public function imageColorize($arg1=0,$arg2=0,$arg3=0,$arg4=0){

        imagefilter($this->rs,IMG_FILTER_COLORIZE,$arg1,$arg2,$arg3,$arg4);
    }

    public function imageMean(){

        imagefilter($this->rs,IMG_FILTER_MEAN_REMOVAL);
    }

    public function imageNegate(){

        imagefilter($this->rs,IMG_FILTER_NEGATE);
    }
    // Fin Filtros

}

trait Helpers
{
	public function showImage()
    {
        $a=null;
        if($this->determineProper()){
            for ($i = 0; $i < imagesy($this->rs); $i++){
                for ($j = 0; $j < imagesx($this->rs); $j++) {
                    $pixelxy = imagecolorat($this->rs, $j, $i);
                    $rgb = imagecolorsforindex($this->rs, $pixelxy);
                    
                    $r=dechex($rgb["red"]);
                    $g=dechex($rgb["green"]);
                    $b=dechex($rgb["blue"]);
                    if(strlen($r)==1){
                        $he="0".$r;
                    }else{
                        $he=$r;
                    }
                    if(strlen($g)==1){
                        $he.="0".$g;
                    }else{
                        $he.=$g;
                    }
                    if(strlen($b)==1){
                        $he.="0".$b;
                    }else{
                        $he.=$b;
                    }

                    $a.= "<div style='position:static;float:left;width:".$this->zoom."px;height:".$this->zoom."px;background:#".$he.";top:".($j*$this->span*$this->zoom)."px;left:".($i*$this->span*$this->zoom)."px;margin-right:";
                    if($this->span>1){$a.=$this->span;}else{$a.= "0";}
                    $a.="px;margin-bottom:";
                    if($this->span>1){$a.=$this->span;}else{$a.= "0";}
                    $a.="px' title='(X=$j - Y=$i - #$he)'></div>";
                    $he="";
                }
            }
            if($this->zoom>1){
                if ($this->span>1){
                    echo "<div class='imgPixpic' style='height:".imagesy($this->rs)*($this->zoom+$this->span)."px;width:".imagesx($this->rs)*($this->zoom+$this->span)."px;'>".$a."</div>";
                }else{
                    echo "<div class='imgPixpic' style='height:".imagesy($this->rs)*($this->zoom)."px;width:".imagesx($this->rs)*($this->zoom)."px;'>".$a."</div>";
                }
            }else{
                if ($this->span>1){
                    echo "<div class='imgPixpic' style='height:".imagesy($this->rs)*($this->span+1)."px;width:".imagesx($this->rs)*($this->span+1)."px;'>".$a."</div>";
                }else{
                    echo "<div class='imgPixpic' style='height:".imagesy($this->rs)."px;width:".imagesx($this->rs)."px;'>".$a."</div>";
                }
            }
        }else{
            return $this->error();
        }
    }

    public function hexPixel($x,$y)
    {
        $pixelxy = imagecolorat($this->rs, $x, $y);
        $rgb = imagecolorsforindex($this->rs, $pixelxy);
        $r=dechex($rgb["red"]);
        $g=dechex($rgb["green"]);
        $b=dechex($rgb["blue"]);

        if(strlen($r)==1){
            $he="0".$r;
        }else{
            $he=$r;
        }
        if(strlen($g)==1){
            $he.="0".$g;
        }else{
            $he.=$g;
        }
        if(strlen($b)==1){
            $he.="0".$b;
        }else{
            $he.=$b;
        }
        return $he;
    }

    public function intPixel($x,$y)
    {
        return imagecolorat($this->rs, $x, $y);
    }

    public function imageArrPixel()
    {
    	if($this->determineProper()){
            for ($y = 0; $y < imagesy($this->rs); $y++){
                for ($x = 0; $x < imagesx($this->rs); $x++) {
                    $hex = $this->hexPixel($x,$y);
		            $pixel[]=array('x'=>$x,'y'=>$y,'h'=>$hex);
                }
            }
            return $pixel;
        }else{
            return $this->error();
        }
    }

    public function colBin($p)
    {
        $b = ($this->hexPixel($p['x'],$p['y']) === 'ffffff')?1:0;
        return $b;
    }

    public function positive($p)
    {
        if ($p['x'] > 0 && $p['y'] > 0) return true;
        return false;
    }

    public function iterSquelettisation($re)
    {
        for ($y = 0; $y < imagesy($this->rs)-1; $y++){
            for ($x = 0; $x < imagesx($this->rs)-1; $x++) {

                $p1 = ["x"=>$x, "y"=>$y];
                $p2 = ["x"=>$x - 1, "y"=>$y];
                $p3 = ["x"=>$x - 1, "y"=>$y + 1];
                $p4 = ["x"=>$x, "y"=>$y + 1];
                $p5 = ["x"=>$x + 1, "y"=>$y + 1];
                $p6 = ["x"=>$x + 1, "y"=>$y];
                $p7 = ["x"=>$x + 1, "y"=>$y - 1];
                $p8 = ["x"=>$x, "y"=>$y - 1];
                $p9 = ["x"=>$x - 1, "y"=>$y - 1];

                // Si tiene vecino positivo
                if ($this->positive($p1)&&$this->positive($p2)&&$this->positive($p3)&&
                    $this->positive($p4)&&$this->positive($p5)&&$this->positive($p6)&&
                    $this->positive($p7)&&$this->positive($p8))
                {
                    $A = ($this->colBin($p2) == 0 && $this->colBin($p3) == 1) + ($this->colBin($p3) == 0 && $this->colBin($p4) == 1) +
                     ($this->colBin($p4) == 0 && $this->colBin($p5) == 1) + ($this->colBin($p5) == 0 && $this->colBin($p6) == 1) +
                     ($this->colBin($p6) == 0 && $this->colBin($p7) == 1) + ($this->colBin($p7) == 0 && $this->colBin($p8) == 1) +
                     ($this->colBin($p8) == 0 && $this->colBin($p9) == 1) + ($this->colBin($p9) == 0 && $this->colBin($p2) == 1);
                    $B = $this->colBin($p2) + $this->colBin($p3) + $this->colBin($p4) + $this->colBin($p5) + $this->colBin($p6) + $this->colBin($p7) + $this->colBin($p8) + $this->colBin($p9);
                    $m1 = $re == 0 ? ($this->colBin($p2) * $this->colBin($p4) * $this->colBin($p6)) : ($this->colBin($p2) * $this->colBin($p4) * $this->colBin($p8));
                    $m2 = $re == 0 ? ($this->colBin($p4) * $this->colBin($p6) * $this->colBin($p8)) : ($this->colBin($p2) * $this->colBin($p6) * $this->colBin($p8));
                    if ($A == 1 && ($B >= 2 && $B <= 6) && $m1 == 0 && $m2 == 0) {
                        imagesetpixel($this->rs,$x,$y,0);
                    }
                }
            }
        }
    }

    public function squelettisation()
    {
        $this->iterSquelettisation(0);
        $this->iterSquelettisation(1);
    }

    public function image2Info()
    {
        $a=array();
        $x=imagesx($this->rs);$y=imagesy($this->rs);
        $to=$x*$y;
        for ($i = 0; $i < $x; $i++){
            for ($j = 0; $j < $y; $j++) {
                $pixelxy = imagecolorat($this->rs, $j, $i);
                $rgb = imagecolorsforindex($this->rs, $pixelxy);
                $r=dechex($rgb["red"]);
                $g=dechex($rgb["green"]);
                $b=dechex($rgb["blue"]);
                if(strlen($r)==1){
                    $he="0".$r;
                }else{
                    $he=$r;
                }
                if(strlen($g)==1){
                    $he.="0".$g;
                }else{
                    $he.=$g;
                }
                if(strlen($b)==1){
                    $he.="0".$b;
                }else{
                    $he.=$b;
                }
                if(!array_key_exists($he,$a)){              
                    $a[$he]=array("c"=>$he,"n"=>1,"p"=>0);
                }else{
                    $a[$he]["n"]+=1;
                    $a[$he]["p"]=round($a[$he]["n"]/$to,2);
                }
            }
        }
        return $a;
    }

    public function minMax()
    {
        $imgInf = $this->image2Info();
        $a = array_filter($imgInf,function($a){
            if($a['p'] > 0){
                return $a;
            }
        });
        return array('min' => min($a),'max' => max($a));
    }

    public function paintPixel($objeto='16777215',$fondo='0',$linea='255')
    {
        $mM = $this->minMax();
        for ($y = 0; $y < imagesy($this->rs); $y++){
            for ($x = 0; $x < imagesx($this->rs); $x++) {
                $hex = $this->hexPixel($x,$y);
                if ($hex === $mM['min']['c']) {
                    imagesetpixel($this->rs,$x,$y,$objeto);
                }elseif ($hex === $mM['max']['c']) {
                    imagesetpixel($this->rs,$x,$y,$fondo);
                }else{
                    imagesetpixel($this->rs,$x,$y,$linea);
                }
            }
        }
    }

    public function saveImage($name='img',$path='img\\')
    {
        $FileName = '.\\'.$path.'\\'.$name;
        return imagepng($this->rs,$FileName);
    }

    public function clearCache()
    {
        return imagedestroy($this->rs);
    }
}
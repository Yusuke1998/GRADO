<?php
require_once 'pixpic.class.php';
require_once 'Tools.php';

class PixReader extends Pixpic {

    use Filtered, Helpers;

    public function lineSpace()
    {
        $candidatos=[]; #candidatos a ser la linea de tiempo
        $cLine=0; #cantidad de lineas
        $cColor=0; #cantidad de lineas
        $xLine = 0; #cantidad de pixeles de la linea
        $equals=[]; #lineas similares
        $lines=[]; #lineas validas
        $xLength = imagesx($this->rs);
        $okLines = []; #lineas con un tamaño mayor al 80 del ancho de la imagen
        define('xMax', ($xLength/100)*80); #porcentaje valido para linea (80%)
        for ($y = 0; $y < imagesy($this->rs)-1; $y++){
            for ($x = 0; $x < imagesx($this->rs)-1; $x++) {
                if ($x>0 && $y>0)
                {
                    $current    = ["x"=>$x, "y"=>$y, "h"=>$this->hexPixel($x,$y)]; #center
                    $top        = ["x"=>$x, "y"=>$y-1, "h"=>$this->hexPixel($x,$y-1)]; #top
                    $right      = ["x"=>$x+1, "y"=>$y, "h"=>$this->hexPixel($x+1,$y)]; #right
                    $bottom     = ["x"=>$x, "y"=>$y+1, "h"=>$this->hexPixel($x,$y+1)]; #bottom
                    $left       = ["x"=>$x-1, "y"=>$y, "h"=>$this->hexPixel($x-1,$y)]; #left
                    
                    if ($current['h'] != '000000') #Si el px actual es blanco
                    {
                        #Se crea una linea
                        if ($top['h'] === '000000' && $left['h'] === '000000') #nueva linea
                        {
                            $cLine+=1;
                            array_push($lines, [
                                'x'=>$x,
                                'y'=>$y,
                                'l'=>$cLine,
                                'c'=>1
                            ]);
                        }else{
                            #Se pertenece a alguna linea
                            if ($left['h'] === 'ffffff' && $right['h'] === '000000') {
                                if ($this->searchLeft($lines,$left)!=0) {
                                    array_push($equals, [
                                        'x'=>$x,
                                        'y'=>$y,
                                        'l'=>$this->searchLeft($lines,$left)
                                    ]);
                                }else{
                                    array_push($equals, [
                                        'x'=>$x,
                                        'y'=>$y,
                                        'l'=>$this->searchLeft($equals,$left)
                                    ]);
                                }
                            #Es parte de una linea
                            }elseif ($left['h'] === 'ffffff' && $right['h'] === 'ffffff')
                            {
                                if ($this->searchLeft($lines,$left)!=0) {
                                    array_push($equals, [
                                        'x'=>$x,
                                        'y'=>$y,
                                        'l'=>$this->searchLeft($lines,$left)
                                    ]);
                                }else{
                                    array_push($equals, [
                                        'x'=>$x,
                                        'y'=>$y,
                                        'l'=>$this->searchLeft($equals,$left)
                                    ]);
                                }
                            }
                        }
                    }
                }
            }
        }
        #contamos los pixeles por linea
        for ($i=0; $i < sizeof($lines); $i++)
        {
            for ($j=0; $j < sizeof($equals); $j++)
            {
                if ($equals[$j]['l'] === $lines[$i]['l']) {
                    $lines[$i]['c']+=1;
                }
            }
        }
        #iteramos y eliminamos las lineas menores
        for ($k=0; $k < sizeof($lines); $k++)
        {
            if ($lines[$k]['c'] > xMax)
            {
                array_push($okLines,$lines[$k]);
            }
        }
        $candidatos_of=[];
        $cLine = 0;
        #busqueda de posibles lineas candidatas
        for ($y = 0; $y < imagesy($this->rs)-1; $y++){
            for ($x = 0; $x < imagesx($this->rs)-1; $x++) {
                if ($x>0 && $y>=$okLines[0]['y'])
                {
                    $current    = ["x"=>$x, "y"=>$y, "h"=>$this->hexPixel($x,$y)]; #center
                    $top        = ["x"=>$x, "y"=>$y-1, "h"=>$this->hexPixel($x,$y-1)]; #top
                    $right      = ["x"=>$x+1, "y"=>$y, "h"=>$this->hexPixel($x+1,$y)]; #right
                    $bottom     = ["x"=>$x, "y"=>$y+1, "h"=>$this->hexPixel($x,$y+1)]; #bottom
                    $left       = ["x"=>$x-1, "y"=>$y, "h"=>$this->hexPixel($x-1,$y)]; #left
                    if ($current['h'] === 'ffffff') {

                        if ($left['h'] === '000000')
                        {
                            $cLine+=1;
                            array_push($candidatos, [
                                "x"=>$x, "y"=>$y, "l"=>$cLine, 'c'=>1
                            ]);

                        }else{
                            if ($this->searchY($candidatos,$current)!==null&&$this->searchY($candidatos,$current)>0) {
                                array_push($candidatos_of, [
                                    'x'=>$x,
                                    'y'=>$y,
                                    'l'=>$this->searchY($candidatos,$current)
                                ]);
                            }
                        }
                    }
                }
            }
        }
        // se determinan los candidatos verdaderos
        for ($i=0; $i < sizeof($candidatos); $i++)
        {
            for ($j=0; $j < sizeof($candidatos_of); $j++)
            {
                if ($candidatos_of[$j]['l'] === $candidatos[$i]['l']) {
                    $candidatos[$i]['c']+=1;
                }
            }
        }
        // se obtienen las lineas validas, mayores a un 80% del ancho de la imagen
        $okLines=[];
        for ($k=0; $k < sizeof($candidatos); $k++)
        {
            if ($candidatos[$k]['c'] > xMax)
            {
                array_push($okLines,$candidatos[$k]);
            }
        }
        // se agrupan las lineas
        $okLines = $this->groupConsecutiveNumbers($okLines);
        for ($l=0; $l < sizeof($okLines); $l++) { 
            #se elmina la primera y la ultima linea de pixeles identificada
            $firstLine  = 0;
            $lastLine   = sizeof($okLines[$l])-1;
            for ($m=0; $m < $okLines[$l][$firstLine]['c']; $m++) { 
                imagesetpixel($this->rs,$okLines[$l][$firstLine]['x']+$m,$okLines[$l][$firstLine]['y'],000);
            }
            for ($m=0; $m < $okLines[$l][$lastLine]['c']; $m++) { 
                imagesetpixel($this->rs,$okLines[$l][$lastLine]['x']+$m,$okLines[$l][$lastLine]['y'],000);
            }
        }
    }

    public function Clustering()
    {
        $count_clusters=0;$labels=[];$equals=[];
        for ($y = 0; $y < imagesy($this->rs)-1; $y++){
            for ($x = 0; $x < imagesx($this->rs)-1; $x++) {
                if ($x>0 && $y>0)
                {
                    $current    = ["x"=>$x, "y"=>$y, "h"=>$this->hexPixel($x,$y)]; #center
                    $top        = ["x"=>$x, "y"=>$y-1, "h"=>$this->hexPixel($x,$y-1)]; #top
                    $right      = ["x"=>$x+1, "y"=>$y, "h"=>$this->hexPixel($x+1,$y)]; #right
                    $bottom     = ["x"=>$x, "y"=>$y+1, "h"=>$this->hexPixel($x,$y+1)]; #bottom
                    $left       = ["x"=>$x-1, "y"=>$y, "h"=>$this->hexPixel($x-1,$y)]; #left
                    if ($current['h'] != '000000') {
                        if ($top['h']==='000000' && $left['h'] === '000000') {
                            $count_clusters+=1;
                            array_push($labels,  ['x'=>$x,'y'=>$y,'c'=>$count_clusters]);
                        }else{
                            if ($left['h'] === 'ffffff' && $top['h'] != 'ffffff') {
                                array_push($labels, ['x'=>$x,'y'=>$y,'c'=>$this->find($left,$labels)]);
                            }elseif ($top['h'] === 'ffffff' && $left['h'] != 'ffffff') {
                                array_push($labels, ['x'=>$x,'y'=>$y,'c'=>$this->find($top,$labels)]);
                            }elseif($top['h'] === 'ffffff' && $left['h'] === 'ffffff') {
                                if($this->find($top,$labels) !== $this->find($left,$labels)){
                                    array_push($equals, ['x'=>$x,'y'=>$y,'c'=>$this->union($left,$top,$labels),'ct'=>$this->find($top,$labels),'cl'=>$this->find($left,$labels)]);
                                    array_push($labels, ['x'=>$x,'y'=>$y,'c'=>$this->union($left,$top,$labels)]);
                                }else{
                                    array_push($labels, ['x'=>$x,'y'=>$y,'c'=>$this->find($left,$labels)]);
                                }
                            }
                        }
                    }
                }
            }
        }

        for ($i=0; $i < sizeof($labels); $i++) {
            foreach ($equals as $equal) {
                if ($labels[$i]['c'] !== $equal['c']) {
                    if ($labels[$i]['c'] === $equal['ct']) {
                        $labels[$i]['c']=$equal['c'];
                    }elseif ($labels[$i]['c'] === $equal['cl']) {
                        $labels[$i]['c']=$equal['c'];
                    }
                }
            }
        }
        $this->paintClusters($labels);
    }

    public function groupConsecutiveNumbers(array $input): array{
        // se agrupan los numeros consecutivos, es decir los valores de y, si son consecutivos es una misma linea
        $result = [];
        $previous = array_shift($input);
        $currentGroup = [$previous];
        foreach ($input as $current) {
            if($current['l'] == $previous['l']+1)
                $currentGroup[] = $current;
            else{
                $result[] = $currentGroup;
                $currentGroup = [$current];
            }
            $previous = $current;
        }
        $result[] = $currentGroup;
        return $result;
    }

    public function searchY($labels,$current)
    {
        foreach ($labels as $key => $label) {
            if ($label['y']===$current['y']) {
                return $label['l'];
            }
        }
        return false;
    }

    public function searchLeft($labels,$current)
    {
        foreach ($labels as $label) {
            if ($label['y'] === $current['y'] && $label['x'] === $current['x']) {
                return $label['l'];
            }
        }
        return false;
    }

    public function find($position,$labels)
    {
        foreach ($labels as $label) {
            if ($position['x'] == $label['x'] && $position['y'] == $label['y']) {
                return $label['c'];
            }
        }
    }

    public function union($left,$top,$labels)
    {
        $cLeft=0;$cTop=0;$clLeft=0;$clTop=0;
        foreach ($labels as $label) {
            if ($label['x'] == $left['x'] && $label['y'] == $left['y']) {
                $clLeft = $this->find($left,$labels);
            }
            if ($label['x'] == $top['x'] && $label['y'] == $top['y']) {
                $clTop  = $this->find($top,$labels);
            }
        }
        return min($clLeft,$clTop);
    }

    public function showClusters($labels)
    {
        foreach ($labels as $label) {
            echo "x-".$label['x']." y-".$label['y']." c-".$label['c']."<br>";
        }
    }
    
    public function labelColor($labels,$c)
    {
        foreach ($labels as $label) {
            if ($label['c'] == $c)
            {
                return $label['h'];
            }
        }
        return false;
    }


    public function paintClusters($labels)
    {
        $colores=[];
        array_push($colores,['c'=>'1','h'=>255]);
        foreach ($labels as $label)
        {
            if ($color = $this->labelColor($colores,$label['c'])) {
                imagesetpixel($this->rs,$label['x'],$label['y'],$color);
            }else{
                $newColor = rand(255, 16777215);
                array_push($colores,['c'=>$label['c'],'h'=>$newColor]);
                imagesetpixel($this->rs,$label['x'],$label['y'],$newColor);
            }
        }
    }
}
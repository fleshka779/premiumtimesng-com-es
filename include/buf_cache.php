<?php
function cb($buff){    
//    $buff=str_replace('','',$buff);

    $buff=preg_replace_callback("/\<img([^>]+?)\>/sim",function ($matches){
        $chunk=$matches[1];
        $src=false;
        preg_match('/src="([^"]*)"/sim', $chunk, $srmatch);
        //return var_export($src,true);
        if($srmatch[1] && (strpos($chunk,'no_lozad')===false)){
            $src=$srmatch[1];        
            $chunk = str_replace("src=","data-srcset=",$chunk);        
            $chunk = ' src="'.$src.'" '.$chunk;
            $chunk = str_replace(" src="," srcset='data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7' src=",$chunk);
            return "<img ".$chunk." />";
        }else{ return "<img ".$matches[1]." />";}
    }, $buff);/**/
    return $buff;
}
ob_start('cb',0,0);
?>
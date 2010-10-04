<?php
$__postfilters = array("codehighlight");

function cms_add_postfilter($fn)
{
	global $__postfilters;
	$__postfilters[] = $fn;
}

/**
 *
 *
 */
function afterparse($parsed,$config)
{
    global $snippets, $__postfilters;
    $parsed =  callfunctions($parsed,$config);

    $parsed = str_replace(array_keys($snippets),
                          array_values($snippets),
                          $parsed);

    $parsed =  callfunctions($parsed,$config);
    
    foreach($__postfilters as $filter)
        $parsed = call_user_func($filter, $parsed);

    return $parsed;
}

function callfunctions($parsed,$config){
    #$a = array();
    #preg_match_all('/\[(\w+?)\](.*?)\[\/(\w+?)\]/ims', $parsed, $a);
    #print_r($a);

    #$parsed = preg_replace_callback('/\[(.+?)\](.*?)\[\/(.+?)\]/ims',"bodyfncall",$parsed);
    $parsed = preg_replace_callback('/[?]"(.+?)"/ims',"callreplacefn",$parsed);
    $parsed = preg_replace_callback('/\[(.+?)\]/ims',"callreplacefn",$parsed);
    return $parsed;
}

/**
 *
 */
function _bodyfncall($matches)
{
    if($matches[1] != array_pop($matches))
        return $matches[0];

    $list = explode(" ", $matches[1]);
    $fn = $list[0]; 
    $body = $matches[2];
    $list[0] = $body; 
    if(function_exists($fn))
        return call_user_func_array($fn, $list);
    else
        return $matches[0];
}


/**
 *
 */

function callreplacefn($matches)
{   
    $list = explode(" ", $matches[1]);
    $fn = $list[0]; unset($list[0]); 
    if(function_exists($fn))
        return call_user_func_array($fn, $list);
    else
        return $matches[0];
}



###############################################################################
## user func replacements
function raphael($body, $name)
{

    $s = "<div id='$name'>";
    $s.= "<script language='javascript'>$body</script>";
    $s.="</div>";
    return $s;
}

/**
 * hightlight program code into <code> tags.
 */
function codehighlight($content)
{  
 $m = array();
 $r = '/<code lang="(.*?)">(.*?)<\/code>/ims';
 $s = '/<code>\s*?\[lang:(.*?)\]\s*?(.*?)<\/code>/ims';

 #preg_match_all($r,  $content , $m );

 $parsed =   preg_replace_callback($r,
                    "highlightcllbck" , $content);
                    
 $parsed =   preg_replace_callback($s,
                    "highlightcllbck" , $parsed);
 return $parsed;
}

function highlightcllbck($matches)
{
    $language = $matches[1];
    $geshi = new GeSHi( html_entity_decode( $matches[2] ), $language);
    //echo $geshi->parse_code();
    return $geshi->parse_code();
}


function import($file)
{
    $f = DATA_DIR."/$file";
    $config = cms_get_config();
    $content = cms_render_file(cms_get_file($f,$config),$config);
    return $content;
}

function lnk($target , $label)
{
    global $map;
    if(!isset($map))
        $map = parse_ini_file(URLMAP);

    return "<a href='$map[$target]'>$label</a>";
}

function sourceBox($name)
{
    return '<div class="source">
                <h3>License</h3>
                    <p class="center">
                        _ccImage_
                    </p>
                    <h3>Download</h3>
                    <p class="center">'.lnkico("http://github.com/areku/$name",$name).'</p>
            </div>';
}

function mathdef($number = "")
{
    global $math_def_number;
    if(!isset($math_def_number)) 
        $math_def_number = 1;

    if($number = "")
        $number = $math_def_number;

    $test =  "<div class='right math-def-number'>($math_def_number)</div>";
    $math_def_number++;
    return $test;
}


function applet( $code, $archives, $width, $height)
{
    return "<p class=\"center\">
          <script src=\"http://www.java.com/js/deployJava.js\"></script>
          <script> 
               var attributes = {
                     codebase: '%wcwd%/jars/',
                     code:'$code',  
                     width:$width,
                     height:$height,
                     archive:'$archives'
                 } ; 
                 var parameters = {} ; 
                 deployJava.runApplet(attributes, parameters, '1.6'); 
             </script>
             </p>";
}

function lnkprof($name)
{
    return lnkico("http://fh-trier.de/?id=$name",'Prof.&nbsp;'.ucfirst($name));
}

function lnkwiki($title, $text)
{
    return lnkico("http://en.wikipedia.org/wiki/$title", $text);
}

function lnkico($url,$text)
{
    #$host = parse_url($url, PHP_URL_HOST);
    $info = parse_url($url);
    $host = $info["host"];

    return '<img src="http://'.$host.'/favicon.ico" height="12" style="vertical-align:middle"/>&nbsp;<a class="fh-link" href="'.$url.'">'.$text.'</a>';
}




?>

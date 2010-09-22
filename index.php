<?php
# Alexander Weigl <alexweigl@gmail.com>
# Date: 2009-10-23
#
# Licence under Creative Commons 3.0 - by-sa
#
# Small content management.
#

###############################################################################
## Include Section
require_once("lib/compability.php");
require_once("lib/functions.php");
require_once("config.php");
require_once('lib/classTextile.php');
require_once('lib/markdown.php');
require_once('lib/simple_html_dom.php');
require_once("lib/geshi/geshi.php");
require_once("lib/render.php");
require_once("lib/postfilter.php");
###############################################################################

###############################################################################
## Functions 

/**
 * Find the content file from the given path
 */
function retrieve_file($path)
{ 
    # empty path or no direct call of index.php use DATA_DIR
    if( empty($path) or basename($path) == basename($_SERVER['SCRIPT_NAME'])  )
    {
	$path = DATA_DIR;
    }
    
    $self =$_SERVER['PHP_SELF'];
       


  #if the $path is an directory search for the index file
  if( is_dir($path) )
  { 
     $candidates = _glob($path.'/'.INDEX_PAGE.'.*') ;
    
      rsort($candidates, SORT_STRING );
      if(count($candidates)>0)
	$path = array_pop( $candidates );
      else
	 #$path=false;  
	return $path.".dir";//use dirhandler
  }

  $base = basename($path);
  $name = substr($base,0,strpos($base,'.'));
  
  #if path not exists return 404 error!
  if( ! file_exists( $path ) ) 
  {
    $path = ERROR_404;
    header("HTTP/1.1 404 Not Found");
  }   
  return $path;
}


function findFileTypeIcon($file)
{
  $m = explode('.',$file);  
  $ext = strtolower($m[count($m)-1]);   
  $path = "static/images/$ext.png";  
  if(!file_exists($path))
    $path="static/images/application.png";
  return "<img src='_root_/$path' />";
}

function print_dir($path)
{
	$descpath = "$path/.description";	
	if(file_exists($descpath))
		$description= parse_ini_file("$path/.description");
	else
		$description = array();
		

	$files = scandir($path);
	
	sort($files);
  $html="<h1>".str_replace(DATA_DIR,'', $path) . 
	  "</h1><table class='borders autoindex'>";
  
  if($description) $html.="<p class='autoindex-description'>$description[dir]</p>";

  $html .="<tr><th>Name</th><th>Größe</th><th>Datum</th></tr>";
  $class = array('even','odd');
  $i=0;

  foreach($files as $file) 
  {
    if(fnmatch('.*',$file))  continue;
    if(     $description 
        and isset($description["default_type"]) 
        and !strrpos($file, '.'))
            $tp="?type=$description[default_type]";
    else 
        $tp='';;
   
    $html .= "<tr class=".$class[$i%2].">
	    <td><a href='${file}${tp}'";
	
    if($description and isset($description[$file]))
      $html.=" title='$description[$file]' ";
 
    $html .='>'.findFileTypeIcon($file)." $file</a>";

    if($description and isset($description[$file]))
      $html.=" <span style='background:#fad;font-size:small'>$description[$file]</span>";
  
    $html.= "</td><td style='text-align:right'>"
	    .min( round(filesize("$path/$file")/1024), 1)
	    ." kiB</td><td style='text-align:right'>".date("Y-m-d",filemtime("$path/$file"))."</td></tr>";
  }
  return "$html</table>";
}

##############################################################################
## main - section

if((isset($_GET['site'])))
{
    $map = parse_ini_file(URLMAP);
    $originpath = $requested_file = $map[ $_GET['site'] ];
}
else
{
    $request_url = $_SERVER['PHP_SELF'];
    $scrp_nam    = basename( $_SERVER['SCRIPT_NAME'] );

    if( $pos = strpos( $request_url, $scrp_nam ) )
    $requested_file = substr($request_url , 1 + strlen($scrp_nam) + $pos );
    else
    $requested_file = DEFAULT_PAGE;

    $originpath = $requested_file;
}
$path = DATA_DIR.'/'.$requested_file;

$requested_file = retrieve_file($path);

## weigla: add an dyn. snippet for cwd
$base = dirname($originpath);

$snippets['%cwd%'] = SITE_INDEX."/$base";
$snippets['_cwd_'] = SITE_INDEX."/$base";

$snippets['%wcwd%'] = ROOT_URL.DATA_DIR."/$base";
$snippets['_wcwd_'] = ROOT_URL.DATA_DIR."/$base";

$base = $request_url; 
if( is_dir($path) and preg_match("/.*[^\/]$/",$base))
{
    $base .='/';
}
    
 ############################################

$content = render_file($requested_file);

@header("content-type: text/html; charset=utf-8");
@header('Expires: '.date('r', time() + 7*24*60*60));
@header('Last-Modified:'.date('r',filemtime($requested_file)));

$style =  "light";
if($config['layout.decorator'])
    require("decorator.php");
else
    echo $content;
?>

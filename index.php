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

require_once("functions.php");
require_once("config.php");
require_once('lib/classTextile.php');
require_once('lib/markdown.php');
require_once('lib/simple_html_dom.php');
require_once("lib/geshi/geshi.php");


###############################################################################
## Actions evented by parameter

/**
 * print server variables
 */
if(isset($_REQUEST['debug'])) { 
  echo "<pre>";
  var_dump($_SERVER);
  echo "</pre>";
}
  
/**
 * clear all cache files
 */
if(isset($_REQUEST['cclear']))
  foreach(glob(DATA_DIR.'/*_cache') as $value)
    echo $value, unlink($value);

/** 
 * explizit use of gzip output handler
 */
if(!isset($_GET['gz']))
  ob_start("ob_gzhandler");

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
	$path = DATA_DIR;
  
  #if the $path is an directory search for the index file
  if( is_dir($path) )
  {    
     $candidates = _glob($path.'/'.INDEX_PAGE.'.*') ;
    # print_r( $candidates );
    
      rsort($candidates, SORT_STRING );
      if(count($candidates)>0)
	$path = array_pop( $candidates );
      else
	 #$path=false;  
	return $path.".dir";//use dirhandler
  }

  $base = basename($path);
  $name = substr($base,0,strpos($base,'.'));
  

  #if( file_exists(dirname($path)."/$name"



  #if path not exists return 404 error!
  if( ! file_exists( $path ) ) 
  {
    $path = ERROR_404;
    header("HTTP/1.1 404 Not Found");
  }   
  
  return $path;
}


/**
* Wrap $log_text at the given $limit and $divider
 */
function text_wrap($log_text, $limit, $divider=" ") {
  $words = explode($divider, $log_text);
  $word_count = count($words);
  $char_counter = 0;
  $block = "";
  foreach ($words as $value) {
    $chars = strlen($value);
    $block .= $value;
    $char_counter = $char_counter + $chars;
    if ($char_counter >= $limit) {
            $block .= " \n ";
            $char_counter = 0;
    } else {
      $block .= " ";
    }
  }
    return rtrim($block);
}

/**
 * return filename of the cache file
 */ 
function cachename($file) {   
  return CACHE_DIR.str_replace('/','_',$file);
}

/**
 * checks if the $requested_file has an cache file.
 * If the request_file is newer, do not return cache, else return cache
 */
function from_cache($requested_file) {
  $cache_file = cachename($requested_file);
  if( file_exists($cache_file) )
    {
      echo "FROM CACHE";
      $modtime = filemtime($requested_file);
      $createcache = filectime($cache_file);
      
      if($modtime < $createcache)
	return file_get_contents($cache_file);
      else
	return false;
    }
  return false;
}

/**
 * cache the path with the content, error will not happen
 */
function cache_file($path, $content) {
  echo "Cached<br>";
  file_put_contents(cachename($path) , $content );
}

/**
 * hightlight program code into <code> tags.
 */
function hightlight_code(&$dom)
{   
	$xpath = $dom->xpath_new_context();
	$obj = $xpath->xpath_eval('//code');
	$nodeset = $obj->nodeset;

  foreach($nodeset as $codeblock)
  {    
    $text = $codeblock->get_content();      
    $start_line=False;
    $matches = array();
  
    if(preg_match('/^@(.+)(:(\d+))?@/',$text, $matches))
    {
      #print_r($matches);
      $language = $matches[0];
      if($matches[1])
		$start_line = $matches[1];
    }
    
    $geshi = new GeSHi( html_entity_decode( $text ), $language);

    if($start_line)
    {
      $geshi->enable_line_numbers(GESHI_FANCY_LINE_NUMBERS);
      $geshi->start_line_numbers_at($start_line);
    }   
    $codeblock->set_content($geshi->parse_code());
  }
}


function include_file(&$parsedtext)
{  
	if(preg_match("/\[import (.*)\]/",$parsedtext,$matches))
	{
		//print_r($matches);
	
		for($i =0; $i<count($matches);$i+=2)
		{    		
			$file = $matches[$i+1];
			$f = DATA_DIR."/$file";
			$content = render_file(retrieve_file($f));
			$parsedtext = str_replace($matches[$i], 
					$content, $parsedtext);
		}
	}
	return $parsedtext;
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
    if(fnmatch('.*',$file))continue;
		if(                 $description 
							and isset($description["default_type"]) 
                    and !strrpos($file, '.'))
					$tp="?type=$description[default_type]";
else $tp='';;
   
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

/**
 *
 */
function render_file($path) {
  $p = basename($path);    
  
  if(isset($_GET['type']) or strpos($p,'.') < 0)
    $ext=$_GET['type'];
  else
     $ext = substr($path, strrpos( $path , '.') + 1);
	 
  if($ext=="dir")
  {
    global $requested_file;
    $requested_file = substr($path,0,strlen($path)-4);
    $parsed=print_dir($requested_file);
    $requested_file .= "/&lt;dir&gt;";
  }
  else
  {
	  $fn = "parse_$ext";
	  if(!function_exists($fn) and defined("DEFAULT_HANDLER"))
		  $fn = "parse_".DEFAULT_HANDLER;
	  $parsed = call_user_func($fn, file_get_contents($path) );
  }
	
  if(! is_a($parsed , "PHPContent"))
  {
	$search = array(
		'_w/o_', '§w/o§','%w/o%','_site_','§site§','_root_','§root§', '_static_', '§static§');
	$replacement = array(
		ROOT_URL.DATA_DIR.'/' , ROOT_URL.DATA_DIR.'/',ROOT_URL.DATA_DIR.'/',
		ROOT_URL.'index.php', ROOT_URL.'index.php',
		ROOT_URL, ROOT_URL, 
		ROOT_URL.'static/', ROOT_URL.'static/'
		);
		
		
	$parsed = str_replace($search, $replacement, $parsed);
		
   /*
    $parsed = str_replace('_w/o_', ROOT_URL.DATA_DIR.'/', $parsed);
    $parsed = str_replace('_site_', ROOT_URL.'index.php', $parsed);
    $parsed = str_replace('_root_', ROOT_URL, $parsed);
	
	$parsed = str_replace('§w/o§', ROOT_URL.DATA_DIR.'/', $parsed);
    $parsed = str_replace('§site§', ROOT_URL.'index.php', $parsed);
    $parsed = str_replace('§root§', ROOT_URL, $parsed);
	*/

	
    #dom nachbearbeitung  
	//header("content-type: text/plain");
	//echo "<pre>$parsed";	
	
	//$dom = domxml_open_mem("<div>$parsed</div>");
    //if($dom) {
	//	hightlight_code($dom);       
	//	include_file($dom);  
	//	return html_entity_decode($dom->html_dump_mem());
	  //}	   

	return include_file($parsed);
//	return $parsed;
  } 
  else return $parsed;
}
###############################################################################


###############################################################################
## parsing functions
## call parse_<suffix>(content of file) to get the parsed content pack


function parse_source($content,$language)
{
    global $requested_file;	
    $geshi = new GeSHi($content , $language);
    $geshi->enable_line_numbers(GESHI_FANCY_LINE_NUMBERS);
	define("NO_FORMULA",true);
	define("NO_INDENT",true);
    //return "<div style='text-align:right;'><a href='".ROOT_URL."$requested_file'>view plain</a></div>".$geshi->parse_code();
    return "<div style='text-align:right;'><a href='$_SERVER[PHP_SELF]?type=plain'>view plain</a></div>".$geshi->parse_code();
}

function parse_py  ($content) { return parse_source($content, "python"); }
function parse_c   ($content) { return parse_source($content, "c");      }
function parse_cpp ($content) { return parse_source($content, "cpp");    }
function parse_h   ($content) { return parse_source($content, "h");      }
function parse_boo ($content) { return parse_source($content, "boo");    }
function parse_java($content) { return parse_source($content, "java");   }
function parse_sh  ($content) { return parse_source($content, "bash");   }
function parse_pas ($content) { return parse_source($content, "pascal"); }
function parse_dpr ($content) { return parse_source($content, "pascal"); }
function parse_sql ($content) { return parse_source($content, "sql");    }

function parse_phps ($content) { return parse_source($content, "php");   }
function parse_php ($content) { return parse_source($content, "php");   }
function parse_conf ($content) { return parse_plain($content);   }


/**
 * html content need no special treetment
 */ 
function parse_html($content) 
{
  return $content;
}


/**
 * Wrapper object for embbedding php execution on output
 */
class PHPContent
{
  var $content = "";
   
  function PHPContent($content)
  { 
    $this->content=$content; 
  }

  function __toString() {	
    $this->content = preg_replace  ( '/(<\?(php)?|\?>)/', "" , $this->content);
    eval($this->content);
    return "";
  }
}

function parse_phpx($content) {  return new PHPContent($content); }

/**
 * Text will be wrapped and has <pre> enviroment
 */ 
function parse_txt($content) {
  return "<div class='simpleText'>".text_wrap($content,72)."</div>";
}

function parse_plain($content) {
	header("Content-Type: text/plain");
	echo $content; exit;
}

/**
 * call textile
 */ 
function parse_text($content) { $textile = new Textile(); return $textile->TextileThis($content);  }

function parse_mrk($content) {  return Markdown($content);             }

###############################################################################
define('PREFERED_LANG',  substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2) );

$request_url = $_SERVER['PHP_SELF'];
$scrp_nam    = basename( $_SERVER['SCRIPT_NAME'] );


if( $pos = strpos( $request_url, $scrp_nam ) )
  $requested_file = substr($request_url , 1 + strlen($scrp_nam) + $pos );
 else
   $requested_file = DEFAULT_PAGE;

$path = DATA_DIR.'/'.$requested_file;
$requested_file = retrieve_file($path);

#disable cache
#$content = (isset($_REQUEST['nocache']))
#	      ?    false
#	      :    from_cache($requested_file);
$content = false;
if(! $content ) {
  $content = render_file($requested_file);
#  if(! isset($_REQUEST['nocache']))
#    cache_file($requested_file, $content);  
}

@header("content-type: text/html; charset=utf-8");
@header('Expires: '.date('r', time() + 7*24*60*60));
@header('Last-Modified:'.date('r',filemtime($requested_file)));

include("head.php");
echo $content;
include("foot.php");
?>

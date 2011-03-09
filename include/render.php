<?php
require("lib/markdown.php");
require("lib/geshi/geshi.php");

function cms_render_file($path, $config) {
	$base = basename( $path );

	#if(  $config->is('page.type') ) 
	#	$ext = $config->page->type;
	#else 
	$ext = file_suffix($path);

	$config->set('page.type', $ext);

	// access on a directory, call autoindex
	$fn = "parse_$ext";
	if(!function_exists($fn) and defined("DEFAULT_HANDLER"))
		$fn = "parse_".DEFAULT_HANDLER;

	if($ext != "dir") 
		$content =  meta_split( file_get_contents($path) );
	else
		$content = $path;


	switch(count($content))
	{
		case 3:
			$config->override(meta_parse($content[1]));
			$parsed = call_user_func_array($fn, array($content[2], $config));
			break;
		case 2:
			$parsed = call_user_func_array($fn, array($content[1], $config));
			break;
		case 1:
			$parsed = call_user_func_array($fn, array($content, $config));
			break;
 		default: $parsed="";
	}
	return afterparse($parsed,$config);
}

function meta_split($content) {
	$matches = array();
  	
	$co = strpos( $content, ':' );
	$nl = strpos( $content, "\n");

	if( !$co || $co>$nl) # first line contains an colon
	{
		return array("",$content);
	}
	preg_match("/(.+?)\n\n(.*)/ism", $content, $matches);
	return $matches;
}

function file_suffix($file) {
	return substr($file, strrpos($file,'.')+1);
}

function meta_parse($string)
{
	if(empty($string)) 
		return array();
	$lines = explode("\n", $string);
	$cfg = array();
	foreach($lines as $line)
	{
		if(empty($line)) continue;
		if(substr($line,0,1)=='#')continue;
		@list($key,$value) = explode(':', $line,2);
		$cfg[trim($key)]=meta_eval(trim($value));
	}
	return $cfg;
}

function meta_eval($value)
{
	if(preg_match("/<.*>/ims",$value))
		return eval( "return ".trim($value,'<>').';' );
	return $value;
}

###############################################################################
## parsing functions
## call parse_<suffix>(content of file) to get the parsed content pack


function parse_source($content,$language)
{
	global $requested_file;	
	$geshi = new GeSHi($content , $language);
	$geshi->enable_line_numbers(GESHI_FANCY_LINE_NUMBERS);

	#define("NO_FORMULA",true);
	$config->page->noformula = true;
	#define("NO_INDENT",true);
	$config->page->noindent = true;
	return "<div style='text-align:right;'><a href='$_SERVER[PHP_SELF]?type=plain'>view plain</a></div>".$geshi->parse_code();
}

function parse_py  ($content,$c=null) { return parse_source($content, "python"); }
function parse_c   ($content,$c=null) { return parse_source($content, "c");      }
function parse_cpp ($content,$c=null) { return parse_source($content, "cpp");    }
function parse_h   ($content,$c=null) { return parse_source($content, "h");      }
function parse_boo ($content,$c=null) { return parse_source($content, "boo");    }
function parse_java($content,$c=null) { return parse_source($content, "java");   }
function parse_sh  ($content,$c=null) { return parse_source($content, "bash");   }
function parse_pas ($content,$c=null) { return parse_source($content, "pascal"); }
function parse_dpr ($content,$c=null) { return parse_source($content, "pascal"); }
function parse_sql ($content,$c=null) { return parse_source($content, "sql");    }
function parse_phps ($content,$c=null) { return parse_source($content, "php");   }
function parse_php ($content,$c=null) { return parse_source($content, "php");   }
function parse_conf ($content,$c=null) { return parse_plain($content);   }


/**
 * html content need no special treetment
 */ 
function parse_html($content,$c=null) 
{
	return $content;
}


/**
 * DEFAULT-HANDLER
 */
function parse_mime($content,$c=null)
{
	header("content-type: ". get_mime_type($c->page->path)); 
	return  $content;
}


/**
 * Wrapper object for embbedding php execution on output
 *
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
 */
//function parse_phpx($content,$c=null) {  return new PHPContent($content); }
function parse_phpx($content,$c=null) {  
    $content = preg_replace( 
        '/(<\?(php)?|\?>)/', "" , $content);
		return cms_capteval_output($content);
}

/**
 * Text will be wrapped and has <pre> enviroment
 */ 
function parse_txt($content,$c=null) {
	return "<div class='simpleText'>".text_wrap($content,72)."</div>";
}

function parse_plain($content,$c=null) {
	header("Content-Type: text/plain");
	echo $content; exit;
}

/**
 * call textile
 */ 
function parse_mkd($content,$config) {  return Markdown($content);  }

function parse_ml($content,$cfg) {
	
	if( $cfg->is("layout") )
		$layout = cms_render_file(
				cms_get_layout($cfg->layout), $cfg);
	else
		$layout = $content;

	$replace = array();
	foreach($cfg->ml->as_array() as $key => $var)
	{
		$c = $cfg->copy();
		$path = cms_get_file(dirname($cfg->page->path)."/$var", $c) ;
	#	echo $path;
		$replace[ "%".substr($key,3)."%" ] = cms_render_file( $path, $c );
	}
	$layout = str_replace(array_keys($replace), array_values($replace), $layout);
	return $layout;
}

###############################################################################


function findFileTypeIcon($file)
{
	$m = explode('.',$file);  
	$ext = strtolower($m[count($m)-1]);   
	$path = "static/images/$ext.png";  
	if(!file_exists($path))
		$path="static/images/application.png";
	return "<img src='_root_/$path' />";
}


function parse_dir( $content , $config = array() )
{
	$path = substr($content, 0, strlen($content)-4);
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

?>

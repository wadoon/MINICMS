<?php

function cms_render_file($path, $config) {
	$base = basename( $path );

	if(  $ext =  $config->get('page.type') ) ;
	else $ext = file_suffix($path);

	$config->set('page.type', $ext);

	// access on a directory, call autoindex
	$fn = "parse_$ext";
	if(!function_exists($fn) and defined("DEFAULT_HANDLER"))
		$fn = "parse_".DEFAULT_HANDLER;

	$content =  meta_split( file_get_contents($path) );
	$config->override(meta_parse($content[0]));
	$parsed = call_user_func_array($fn, array($content[1], $config));
	return $parsed;
}

function meta_split($content) {
	$matches = array();

	if( strpos($content, ':') > strpos($content,"\n"))
		return array("",$content);
	preg_match("/(.+?)\n\n(.*)/ism", $content, $matches);
	return $matches;
}

function file_suffix($file) {
	return substr($file, strrpos($file,'.'));
}

function meta_parse($string)
{
	$lines = explode("\n", $string);
	$cfg = array();
	foreach($lines as $line)
	{
		if(empty($line)) continue;
		if(substr($line,0,1)=='#')continue;
		list($key,$value) = explode(':', $line);
		$cfg[trim($key)]=trim($value);
	}
	return $cfg;
}

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
 * DEFAULT-HANDLER
 */
function parse_mime($content)
{
	global $path;
	header("content-type: ". get_mime_type($path)); 
	return  $content;
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
function parse_md($content) {  return Markdown($content);  }

function parse_ml($content) {
	global $request_file;
	$matches = array();
	preg_match("/(.+?)\n\n(.*)/ism", $content, $matches);

	$cfg = meta($matches[1]);
	trigger_page_action();

	if( isset($cfg['layout']) )
		$layout = render_file($cfg['layout']);
	else
		$layout = $matches[2];

	$replace = array();
	foreach($cfg as $key => $var)
	{
		if( preg_match("/ml\\./", $key) )
		{
			$replace[ substr($key,3) ] = 
				render_file( DATA_DIR.'/'.dirname($request_file)."/$var");
		}
	}


	config_melt($cfg);
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

<?php

function render_file($path) {
  $p = basename($path);    

  //examine a type
  if(isset($_GET['type']) or strpos($p,'.') < 0)
    $ext=$_GET['type'];
  else
    $ext = substr($path, strrpos( $path , '.') + 1);
 
  // access on a directory, call autoindex
  if($ext=="dir")
  {
    global $requested_file;
    $requested_file = substr($path,0,strlen($path)-4);
    $parsed         = print_dir($requested_file);
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
    return afterparse($parsed);
  } 
  else return $parsed;
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
 * DEFAULT-HANDLER
 */
function parse_mime($content)
{
    global $path;
    header("content-type: ". get_mime_type($path)); 
    echo $content;
    exit();
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
function parse_mkd($content) {  return Markdown($content);  }


function parse_mm($content) {
    $matches = array();
    preg_match("/(.+?)\n\n(.*)/ism", $content, $matches);
    
    config_melt(meta($matches[1]));
    trigger_page_action();
    return parse_mkd($matches[2]);
}


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
?>

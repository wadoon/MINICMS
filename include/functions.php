<?php

function cms_path()
{
	$chunks = func_get_args();
	return ROOT_DIR.'/'.implode('/', $chunks);
}


function cms_get_layout($name)
{
	return cms_path("_l",$name);
}



function cms_get_map($name)
{
	$map = cms_get_sitemap();
	return $map[$name];
}


function cms_get_dir($requested_path)
{
	//all index files
	$candidates = _glob($requested_path.'/'.INDEX_PAGE.'.*') ;

	if(count($candidates)>0)
	{
		rsort($candidates, SORT_STRING );
		return $path = array_shift( $candidates );
	}
	else
	{
		return $requested_path.".dir";
	}
}

function cms_get_file($requested_path,$config)
{
	$path = $requested_path;
	// empty path or no direct call of index.php use DATA_DIR
	if( empty($path) or basename($path) == basename($_SERVER['SCRIPT_NAME'])  )
	{
		$path = DATA_DIR;
	}

	//if the $path is an directory search for the index file
	if( is_dir($path) )
	{
		$path = cms_get_dir($path);
	}
	else
	{	//if path not exists return 404 error!
		if( ! file_exists( $path ) ) 
			$path = cms_error(404);
	}
	$config->set("page.path", $path);
	$config->set("page.base", cms_get_base() );

	return $path;
}

function cms_get_base()
{
	return dirname($_SERVER['PHP_SELF']);
}

function cms_get_config()
{
	global $defaults;
	return new config($defaults);
}

function cms_error($code=404)
{
	$path = ERROR_404;
	header("HTTP/1.1 $code Not Found");
	return $path;
}


function cms_get_sitemap()
{
	global $__sitemap;
	return ($__sitemap) 
		?  $__sitemap 
		:  $__sitemap = parse_ini_file(
                        cms_path(CONFIG_DIR, URLMAP));
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


function cms_extract_ctrl($path)
{
    if(empty($path)) return false;
    $chunks = explode("/", $path);
    if(count($chunks)==0) 
        return false;
    return array_shift($chunks);
}


function cms_get_ctrl_file($name)
{
  return cms_path("_c", "$name.ctrl.php");
}


function cms_exists_ctrl($name)
{
  $file = cms_get_ctrl_file($name);
  return file_exists($file);
}


function cms_call_ctrl($reqfile)
{
  $chunks = explode("/", $reqfile);

  $name = array_shift($chunks);
  require(cms_get_ctrl_file($name));
  $clazz = ucfirst($name)."Controller";

  $method = $chunks ? array_shift($chunks):"index";

  ob_start();
  $o = new $clazz;
  call_user_func_array(array($o, $method), $chunks);
  $c = ob_get_contents();
  ob_end_clean();
  return $c;
}


function cms_captincl_output($file)
{
	ob_start();
	require($file);
	$content = ob_get_contents();
	ob_end_clean();
  return $content;
}

function cms_capteval_output($code)
{
	ob_start();
	eval($code);
	$content = ob_get_contents();
	ob_end_clean();
  return $content;
}


?>

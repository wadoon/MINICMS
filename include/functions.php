<?php

function cms_path()
{
	$chunks = func_get_args();
	return ROOT_DIR.'/'.implode('/', $chunks);
}


function cms_get_map($name)
{
	$map = parse_ini_file(
		cms_path(CONFIG_DIR, URLMAP));
	return $map[$name];

}


function cms_get_dir($requested_path)
{
	//all index files
	$candidates = _glob($requested_path.'/'.INDEX_PAGE.'.*') ;

	if(count($candidates)>0)
	{
			rsort($candidates, SORT_STRING );
			$path = array_shift( $candidates );
	}
	else
	{
		if(AUTO_INDEX)
			return $path.".dir";//use dirhandler
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
		$path = cms_get_dir($path);
	
	//if path not exists return 404 error!
	if( ! file_exists( $path ) ) 
		$path = cms_error(404);

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
?>

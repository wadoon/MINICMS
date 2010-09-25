<?
###############################################################################
# Functions for compability for public.fh-trier.de


if(!function_exists('scandir')) {
	function scandir($dir = './', $sort = 0) {
		$dir_open = @opendir($dir);

		if (! $dir_open) 
			return false;

		while ( $dir_content = readdir($dir_open) )
			$files[] = $dir_content;


		//if ($sort)  rsort($files, SORT_STRING);
		//else      	sort($files, SORT_STRING);	   
		return $files;
	}
}

if (!function_exists('fnmatch')) {
	function fnmatch($pattern, $string) {
		return @preg_match(
				'/^' . strtr(addcslashes($pattern, '/\\.+^$(){}=!<>|'),
					array('*' => '.*', '?' => '.?')) . '$/i', $string
				);
	}
}

if (!function_exists('file_put_contents')) {
	function file_put_contents($filename, $data) {
		if( $f = fopen($filename, 'w') )
		{	      
			$bytes = fwrite($f, $data);
			fclose($f);
			return $bytes;
		}
		return False;
	}
}

if (!function_exists('file_put_contents')) {
	function get_file_contents($filename) {
		if($fhandle = fopen($filename, "r"))
		{
			$fcontents = fread($fhandle, filesize($filename));
			fclose($fhandle);
			return $fcontents;
		}
		return False;
	}
}

if(!function_exists('_glob')) {
	function _glob($i, $flags=0)
	{
		$input = explode('/',$i);
		$dir="./";

		foreach($input as $chunk)
			if(is_dir($dir.$chunk))
				$dir .= $chunk."/";

		$pattern = $input[count($input)-1];

		$db = opendir($dir);
		$output = array();
		while(($file = readdir($db))!= false)
		{
			if($file == '.' or $file == '..') continue;
			if(fnmatch( $pattern ,$file))
				$output[] = $dir.$file; 
		}
		@closedir($db);
		return $output;
	}
}

if(!function_exists('get_mime_type')) {
	function get_mime_type($filename, $mimePath = './') 
	{
		require_once("mime.php");
		global $mime;
		$ext = substr($filename, strrpos($filename,'.')+1);
		return $mime[$ext];
	} 
}


# end compability fh-trier


?>

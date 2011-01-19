<?php
# Alexander Weigl <alexweigl@gmail.com>
# Date: 2010-01-13
#
# Licence under Creative Commons 3.0 - by-sa
#
# Small Blog System
#

$articles = array();

require_once("lib/jsmin.php");

class JsController {
	function index($page=0) 
	{ 
		header("content-type: text/javascript");
		foreach(_glob("static/js/*.js") as $file)
		{
			$this->write($file);
		}
		exit();
	}

	
	function __call($name, $args)
	{
		header("content-type: text/javascript");
		$this->write("static/js/$name.js");
		exit();
	}



	function write ($file)
	{
		$minfile = "$file.min";
		if(file_exists($minfile))
		{ echo file_get_contents($minfile); return;}
			
		$content = JSMin::minify(file_get_contents($file));
		file_put_contents($minfile, $content);
		echo $content;
	}
}
?>

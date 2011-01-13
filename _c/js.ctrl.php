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
		foreach(_glob("static/js/*.js") as $file)
		{
			$this->write($file);
		}
	}

	
	function __call($name, $args)
	{
		$this->write("static/js/$name.js");
	}



	function write ($file)
	{
		echo JSMin::minify(file_get_contents($file));
		exit();
	}
}
?>

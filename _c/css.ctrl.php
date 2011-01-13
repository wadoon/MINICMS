<?php
# Alexander Weigl <alexweigl@gmail.com>
# Date: 2010-01-13
#
# Licence under Creative Commons 3.0 - by-sa
#
# Small Blog System
#

$articles = array();

require_once("lib/cssmin.php");

class CssController {
	function index($page=0) 
	{ 
		$this->default();
	}

	
	function __call($name, $args)
	{
		$file = "static/css/$name.css";
		echo cssmin::minify(file_get_contents($file));
		exit();
	}
}
?>

<?php
# Alexander Weigl <alexweigl@gmail.com>
# Date: 2009-10-23
#
# Licence under Creative Commons 3.0 - by-sa
#
# Small Blog System
#

$articles = array();

class BlogController {
	function dflt(&$a,$d)
	{
		return isset($a) and $a ?$a:$d;
	}

	function extr_title($file)
	{
		$start = max( strpos($file,"/"), strpos($file,"_"))+1;
		$end  = strpos($file,'.');

		return substr($file,$start, $end-$start); 
	}

	function extr_date($file)
	{
		return filemtime($file); 
	}

	function get_article($file)
	{
		global $config;
		$article = array();
		$article['title']   = $this->extr_title($file);
		$article['date']    = $this->extr_date($file);
		$article['content'] = cms_render_file($file,$config);
		$article['file']    = $file;
		return $article; 
	}

	function usort_articles($i,$j)
	{
		return $j['date'] - $i['date'];
	}

	function retrieveall($count = false, $page = 0)
	{
		$files = glob(BLOG_DIR."/*");
		$articles = array();


		if($count)
		{
			$all   = array_chunk($files, $count);
			$files = $all[$page]; 
			define("LAST_PAGE", count($all));
		}


		foreach($files as $file)
			$articles[]=$this->get_article($file);
		usort($articles,array($this,"usort_articles"));
		return $articles;
	}

	function index($page=0) 
	{ 
		define("PAGING",1);
		$page = $this->dflt($page,0);
		$articles = $this->retrieveall(11, $page);
		$page = $page;
		require(cms_path("include","blog.view.php"));

	}

	function all() { 
		return retrieveall(); 
	}

	function bydate() {

		$year = $_GET['year'];
		$month = $_GET['month'];
		$day = $_GET['day'];

		$after = mktime(0,0,0, $month, $day, $year);
		$before = mktime(0,0,0, $month, $day+1, $year);

		$files = glob("bata/*");
		$articles = array();

		foreach($files as $file)
		{
			$time = filemtime($file);
			if($time >= $after && $time < $before)
				$articles[]=$this->get_article($file);
		}
		usort($articles,"usort_articles");
		return $articles;
	}

	function render()
	{
		if(isset($_GET['rss']))
			require("include/rss.view.php");
		else
			require("include/blog.view.php");
	}
}
?>

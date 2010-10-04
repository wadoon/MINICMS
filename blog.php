<?php
# Alexander Weigl <alexweigl@gmail.com>
# Date: 2009-10-23
#
# Licence under Creative Commons 3.0 - by-sa
#
# Small Blog System
#

###############################################################################
## Include Section
require("include/compability.php");
require("include/functions.php");
require("include/config.php");
require("include/mime.php");
require("include/postfilter.php");
require("include/render.php");

require("config/config.php");
require("config/defaults.php");
require("config/snippets.php");
###############################################################################
## Functions 
function dflt(&$a,$d)
{
    return isset($a)?$a:$d;
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

$config = cms_get_config();
function get_article($file)
{
	global $config;
        $article = array();
        $article['title']   = extr_title($file);
        $article['date']    = extr_date($file);
        $article['content'] = cms_render_file($file,$config);
        $article['file']    = $file;
        return $article; 
}

function usort_articles($i,$j)
{
    return $j['date'] - $i['date'];
}

function retrieveall($count = false, $page = false)
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
       $articles[]=get_article($file);
    usort($articles,"usort_articles");
    return $articles;
}

function index() { define("PAGING",1); return retrieveall(10, dflt($_GET['page'],0) ); }
function all(){ return retrieveall(); }

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
            $articles[]=get_article($file);
    }
    usort($articles,"usort_articles");
    return $articles;
}

$request_url = $_SERVER['PHP_SELF'];
$scrp_nam    = basename( $_SERVER['SCRIPT_NAME'] );

if( $pos = strpos( $request_url, $scrp_nam ) )
    $requested_file = substr($request_url , 1 + strlen($scrp_nam) + $pos );

if( !isset($requested_file) or 
    $requested_file == "/" or 
    $requested_file == "")
    $requested_file = "index";
    
$articles = call_user_func($requested_file,array());

## weigla: add an dyn. snippet for cwd
############################################

@header("content-type: text/html; charset=utf-8");
#@header('Last-Modified:'.date('r',filemtime($requested_file)));

if(isset($_GET['rss']))
    require("include/rss.view.php");
else
    require("include/blog.view.php");
?>

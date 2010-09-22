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
require_once("lib/compability.php");
require_once("lib/functions.php");
require_once("config.php");
require_once('lib/classTextile.php');
require_once('lib/markdown.php');
require_once('lib/simple_html_dom.php');
require_once("lib/geshi/geshi.php");

require_once("lib/render.php");
require_once("lib/postfilter.php");
###############################################################################
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

function get_article($file)
{
        $article = array();
        $article['title']   = extr_title($file);
        $article['date']    = extr_date($file);
        $article['content'] = render_file($file);
        $article['file']    = $file;
        return $article; 
}

function usort_articles($i,$j)
{
    return $j['date'] - $i['date'];
}

function retrieveall($count = false, $page = false)
{
    $files = glob("bata/*");
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
$base = "";

$snippets['%cwd%'] = SITE_INDEX."/$base";
$snippets['_cwd_'] = SITE_INDEX."/$base";

$snippets['%wcwd%'] = ROOT_URL.DATA_DIR."/$base";
$snippets['_wcwd_'] = ROOT_URL.DATA_DIR."/$base";

############################################
@header("content-type: text/html; charset=utf-8");
@header('Last-Modified:'.date('r',filemtime($requested_file)));

$style =  "light";
if(isset($_GET['rss']))
    require("rss_decorator.php");
else
    require("blog_decorator.php");
?>

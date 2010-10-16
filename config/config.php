<?
/*****************************************************************************/
/* Configuration                                                             */ 
/*****************************************************************************/

/* Constants                                                                 */ 

define('ROOT_DIR', dirname(__FILE__).'/../');
define('CONFIG_DIR', dirname(__FILE__));

//root url from the site!
define('ROOT_URL','/~alex/MINICMS/');

// path to the index.php 
define('SITE_INDEX',ROOT_URL.'index.php');

// basename without extension for default sites in directories
define('INDEX_PAGE','index');

//not yet used 
define('DEFAULT_LANG', 'de');

// folder with the content
define('DATA_DIR','../fht/d');

// blog folder
define('BLOG_DIR','_b');

//path to the content of error404 
define('ERROR_404',DATA_DIR.'/error404.html');

//use auto mime as default handler, suffix only
define('DEFAULT_HANDLER', 'mime');

//a mapping of key and url
define("URLMAP", "config/map.ini");

//show every error
error_reporting(E_ALL);
?>

<?php
# Alexander Weigl <alexweigl@gmail.com>
# Date: 2010-09-23
#
# Licence under Creative Commons 3.0 - by-sa
#
# Small content management.
#

###############################################################################
## Include Section
require("include/compability.php");
require("include/functions.php");

cms_load("config/*.php");
include("include/compability.php");
include("include/config.php");
include("include/mime.php");
include("include/postfilter.php");
include("include/render.php");

##############################################################################
## main - section

if(isset($_GET['site']))
{
	$requested_file = cms_get_map($_GET['site']);
}
else
{
	$request_url = $_SERVER['PHP_SELF'];
	$scrp_nam    = basename( $_SERVER['SCRIPT_NAME'] );

	if( $pos = strpos( $request_url, $scrp_nam ) )
		$requested_file = substr($request_url , 1 + strlen($scrp_nam) + $pos );
	else
		$requested_file = DEFAULT_PAGE;
}
############################################

$config = new config($defaults);


$path    = cms_get_file   (DATA_DIR."/$requested_file", $config);
$content = cms_render_file($path, $config);

@header("content-type: text/html; charset=utf-8");
#@header('Expires: '.date('r', time() + 7*24*60*60));
@header('Last-Modified:'.date('r',filemtime($requested_file)));

require("include/std.view.php");
?>


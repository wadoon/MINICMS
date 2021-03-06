<html>
<head><meta http-equiv="content-type" content="text/html; charset=UTF-8">
<title>PhpMathPublisher : Demo Page Maker</title>
<meta name="keywords" content="mathematic,math,mathematics,mathématique,math renderer,php,formula,latex,mathml,publishing,pascal brachet,brachet">
<link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
<p align="center"><IMG src="header.gif" border="0"></p>
<div id="header">Demo : online creation of a mathematic web page</div>
<div id="contentlabel">Description</div>
<div id="contentbox"><p>This demo shows you how to use PhpMathPublisher to create dynamically a mathematic web page. The header and footer html code are defined in the "header.inc" et "footer.inc" files (in the "files/" directory). The body is created by PhpMathPublisher starting from the text below. The page created by the script is displayed in a pop-up window.<br>
<i>Remark :</i> for this demo, you can't change the file name ("tmp.html") <i>(it's only a demo not a real cms system)</i>.</p></div>
<div id="contentlabel">Input</div>
<div id="contentbox"><p>Type your text and click on "Create".<br>You can also fix the size of the font used for the text and the formulas.</p>
<p>Click <A href="../doc/help.html" target="_blank">here</A> to see the mathematical syntax to respect.</p></div>
<div align="left">
<form name="forme1" method="GET" action="<? echo $_SERVER['PHP_SELF'];?>">
<TEXTAREA NAME="message" COLS="80" ROWS="10">
<? echo stripslashes($HTTP_GET_VARS['message']); ?>
</TEXTAREA>
<p>
<input type="button" name="efface" value="Delete" onclick="document.forme1.message.value='';">
&nbsp;&nbsp;
Size :&nbsp;<input type="text" name="size" size="2" value="<? echo $HTTP_GET_VARS['size']; ?>">
&nbsp;&nbsp;
File name :&nbsp;<input type="text" name="filename" size="20" value="<? echo $HTTP_GET_VARS['filename']; ?>">
&nbsp;&nbsp;<input type="submit" name="bouton" value="Create">
</p>
</form>
<?
include("../mathpublisher.php") ;
$message=$HTTP_GET_VARS['message'];
$size=$HTTP_GET_VARS['size'];
//$filename=$HTTP_GET_VARS['filename'];
$filename="tmp.html";
$pathtofiles=$_SERVER["DOCUMENT_ROOT"]."/phpmathpublisher/files/";
$pathtoimg="../img/";
if ((!isset($size)) || $size<10) $size=14;
if ( isset($message) && $message!='' && isset($filename) && $filename!='' ) 
	{
	$headerfile=$pathtofiles."header.inc";
	$p=fopen($headerfile,"r");
	$header=fread($p,filesize($headerfile));
	fclose($p);
	$header=str_replace('font-size :;','font-size :'.$size.'pt;',$header);
	$footerfile=$pathtofiles."footer.inc";
	$p=fopen($footerfile,"r");
	$footer=fread($p,filesize($footerfile));
	fclose($p);
	$content=$header."<div>".mathfilter($message,$size,$pathtoimg)."</div>".$footer;
	$file=$pathtofiles.$filename;
	$p=fopen($file,"w");
	fputs($p,$content);
	fclose($p);
	echo ("<script>window.open('../files/".$filename."','','width=800,height=500,toolbar=yes,scrollbars=yes,resizeable=yes');</script>");
	}
?>
</div>
<div id="footer">
<p><A href="http://www.xm1math.net/phpmathpublisher/">PhpMathPublisher</A> - Copyright 2005 <b>Pascal Brachet - France</b> <br>The author is a teacher of mathematics in a French secondary school (Lycée Bernard Palissy - Agen).<br>
This program is licensed to you under the terms of the GNU General Public License Version 2 as published by the Free Software Foundation.</p>
</div>
</body>
</html>

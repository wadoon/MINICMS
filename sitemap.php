<?
require_once("config.php");
require_once("functions.php");

$path = '/';
$requested_file = __FILE__;


$startpath = isset($_GET['path'])? $_GET['path'] : DATA_DIR;

$a =  strpos($startpath, DATA_DIR);


if($a===false or $a != 0)
	exit("path error!");

$totalfiles = 0;
$totalsize  = 0;

function strip($p)
{
	return substr($p, strlen(DATA_DIR));
}

function ext($p)
{
	return substr($p, strrpos($p,'.')+1);
}

function printdir($path)
{
	global $totalsize, $totalfiles;
	$files = scandir($path);
	foreach($files as $file)
	{
		if($file == "." or $file == "..") continue;
		$p = "$path/$file";
		if( is_dir($p ))
		{
			echo "<li class='folder'>$file/<ul>";
			printdir($p);
			echo "</ul></li>";
		}
		else
		{
			$size = filesize($p);
			echo "<li class='file ".ext($file)."'><a href='".SITE_INDEX.'/'.strip($p)."'>$file</a> ($size Byte)</li>";
			$totalsize+=$size;
			$totalfiles++;
		}
	}


}

include "head.php";
echo "<h1>Sitemap: $startpath</h1>"
?>
<ul><?printdir($startpath);?></ul>

<p><b>insgesamt <?=$totalfiles?> Dateien mit <?=round($totalsize/1024)?> kB</b></p>

<?include "foot.php";?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
                      "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
 <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
 <title>areku - <?=preg_replace("/[\/]+/",'/',$requested_file)?></title>
  <?=defined("NO_CSS")     ?"":'<link type="text/css" rel="stylesheet" href="'.ROOT_URL.'static/style.css"></link>';?> 
  <?=defined("NO_FORMULA") ?"":'<script language="javascript" src="'.ROOT_URL.'static/ASCIIMathML.js"></script>';?>
  <?=defined("NO_INDENT")  ? "<style> .content   {width:inherit; margin:auto; }</style>": ""; ?>
</head>
<body>
<div class="body">
<div class="header">
<div class="navi">
Navigation:
<ul class="hierarchie">
<?
	echo "<li><a href='".SITE_INDEX."/'>Home</a></li>";
	foreach(_glob(DATA_DIR.'/*') as $f)
	    if(is_dir($f))
	    {
	      $f = basename($f);
	      echo "<li><a href='".SITE_INDEX."/$f/'>".ucfirst( $f )."</a></li>";
	    }
?>
	<li> <a href="<?=ROOT_URL?>/sitemap.php">Sitemap</a></li>
</ul>
</div>
<hr>
<div class="hierarchie">
Hierarchie:
<ul class="hierarchie">
<?
	# split path into chunks, remove data
	$path = substr($path,strlen(DATA_DIR."/") );	

	$pfragments =  explode('/',$path) ;	
	$route = '';
	foreach($pfragments as $fragment)
	{
		
		$route .= $fragment;
		echo "<li><a href='".SITE_INDEX."/${route}'>$fragment</a><ul>";
		
		$d = DATA_DIR.'/'.dirname($route);	
		if( $dir = opendir(DATA_DIR.'/'.dirname($route)))
		{
#		    echo "route: $route";
		    if(strpos($route,'/')>0)
		      $urld=dirname($route).'/';
		    else
		      $urld='';

		    #echo "a", strpos('/',$route) ,"d: $urld";
		    
		    
		    while($file=readdir($dir))
		    {
		      if(fnmatch('*_cache',$file))continue;
		      if(fnmatch('.*',$file))continue;
		      if(fnmatch('*~',$file))continue;
		      if(fnmatch($route,$file))continue;
		      
		      
		      echo "<li><a href='".SITE_INDEX."/$urld$file'>$file</a></li>";
		    }
		}
		closedir($dir);
		echo "</ul></li>";
		$route .= '/';
	}
?>
</ul>
</div>

<?
#K&uuml;rzlich besucht:
#	$visited =@( explode(';', $_COOKIE['recently_visited']) );
#	while($v = array_pop($visited))
#	{
#		echo "<a href='".ROOT_URL."index.php/$v'>".basename($v).'</a> &nbsp;';
#	}
?>
</div>
<div class="content"> 

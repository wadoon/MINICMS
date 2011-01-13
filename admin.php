<?php
require_once("config/config.php");
require_once("include/functions.php");
require_once("include/compability.php");

function strip($p) { return substr($p, strlen(DATA_DIR)); }
function ext($p) { return substr($p, strrpos($p,'.')+1); }

function printdir($path)
{       
	global $totalsize, $totalfiles;
	$files = scandir($path);
	foreach($files as $file)
	{
		if($file == "." or $file == "..") continue;
		if(substr($file,0,1) == "." ) continue;
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
			echo "<li class='file ".ext($file)."'>
				<a href='?action=show&file=$p'>$file</a></li>";
			$totalsize+=$size;
			$totalfiles++;
		}
	}
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" >
<head>
	<script language="Javascript" type="text/javascript" src="lib/edit_area/edit_area_compressor.php?plugins"></script>
	<script language="Javascript" type="text/javascript">
		// initialisation
		editAreaLoader.init({
			id: "example_1"	// id of the textarea to transform		
			,start_highlight: true	// if start with highlight
			,allow_resize: "no"
			,allow_toggle: false
			,word_wrap: true
			,language: "de"
			,toolbar: "new_document, save, load, |, charmap, |, search, go_to_line, |, syntax_selection, |, undo, redo, |, select_font, |, change_smooth_selection, highlight, reset_highlight, |, help"
			,syntax: "<?=ext($_GET['file'])?>"
			,load_callback: "my_load"
			,save_callback: "my_save"
			,new_callback: "my_save"
			,plugins: "charmap"
			,replace_tab_by_spaces: 4
			,min_height: 20000
			,charmap_default: "arrows"
		});
		
		
		function my_save(id, content){
		alert("Here is the content of the EditArea '"+ id +"' as received by the save callback function:\n"+content);
		}


		function my_load(id){
			editAreaLoader.setValue(id, "The content is loaded from the load_callback function into EditArea");
		}
		</script>
</head>
		<body>
			<div style="">Edit | Restore | Backup </div>
			<div style="float:left; width:10em;">
			<ul><? printdir("_b")?></ul>
			<ul><? printdir("_d")?></ul>
			</div>
			<div style="margin-left:12em;">
				<?
					if(isset($_GET['view']))
					{
						call_user_func($_GET['view']);
					}
				?>
			</div>
		</body>

<?php if($action="show"): ?>
<form action='' method='post'>
	<fieldset>
	<legend><?=$_GET['file']?></legend>
		<textarea id="example_1" style="height: 350px; width: 100%;" name="test_1"><?=file_get_contents($_GET['file'])?></textarea>
	</fieldset>
</form>
<? endif; ?>
</body>
</html>

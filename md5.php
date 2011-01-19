<?
function allfiles($directory=".", $filter=FALSE)
{
	if(substr($directory,-1) == '/')
	{
		$directory = substr($directory,0,-1);
	}
	if(!file_exists($directory) || !is_dir($directory))
	{
		return FALSE;
	}
	elseif(is_readable($directory))
	{
		$directory_list = opendir($directory);
		$directory_tree = array();
		while($file = readdir($directory_list))
		{
			if($file != '.' && $file != '..')
			{
				$path = $directory.'/'.$file;
				if(is_readable($path))
				{
					$subdirectories = explode('/',$path);
					if(is_dir($path))
					{
						$directory_tree[] = $path;
						$directory_tree = array_merge(
							$directory_tree, allfiles($path, $filter));
					}elseif(is_file($path))
					{
						$extension = end(explode('.',end($subdirectories)));
						if($filter === FALSE || $filter == $extension)
						{
							$directory_tree[] = $path;
						}
					}
				}
			}
		}
		closedir($directory_list); 
		return $directory_tree;
	}else{
		return FALSE;	
	}
}


function create() {
	header("content-type: text/plain");
	$files = allfiles();
	foreach($files as $file)
	{
		echo sha1_file($file) , "\t$file\n";
	}
}


function check() {
	$oldfiles = explode("\n", $_POST['input']);

	$prev=array();
	foreach($oldfiles as $ofile)
	{
		if(!$ofile) continue;
		$a = explode("\t", $ofile);
		$prev[ trim($a[1]) ] = $a[0];
	}
	echo "<style>.ok {background: green;} .err {background:#CC0000;} .warn { background: yellow}</style> ";


	$files = allfiles(); 
	foreach($files as $file)
	{
		$a =  sha1_file($file) ;
		if(array_key_exists($file, $prev))
		{
			if($prev[$file] == $a)
			{
				echo "<div class='ok'>OK:&nbsp; &nbsp;$file   $prev[$file] $a </div>";
			}
			else
			{
				echo "<div class='err'>ERR: &nbsp;$file checksum mismatch</div>";
			}
			unset($prev[$file]);
		}
		else
		{
			echo "<div class='warn'>NEW: $file</div>";
		}
	}

	foreach($prev as $key => $file)
		echo "<div class='warn'>DEL: $key  ($file)</div>";
}

function show() {
	echo "<form action='md5.php?check=1' method='post'>
		<textarea cols='80' rows='25' name='input' wrap='off'></textarea>
		<input type='submit' value='check' >
	      </form>";
}

if(isset($_GET['create']))
	create();

if(isset($_GET['check']))
	check();

if(isset($_GET['show']))
	show();
?>

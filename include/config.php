<?

$config_handlers = array();

function config_set($name, $value)
{
	global $config;
	$config->root[$name]=$value;
}

function str_startswith($needle, $haystack)
{
	if(empty($haystack))return false;
	if(empty($needle)) return true;
	#echo "strncmp($haystack, $needle, strlen($needle)) ",strncmp($haystack, $needle, strlen($needle)) ,"<br>";
	return strncmp($haystack, $needle, strlen($needle)) == 0;
	#return preg_match("/^$needle(.*)/ims", $haystack);
}

function array_melt()
{
	$arrays = func_get_args() ;
	$a = array_shift($arrays);
	foreach($arrays as $array)
	{
		foreach($array as $k=>$v)
		{
			$a[]=$v;
		}	
	}
	return $a;
}


class config 
{
	var $prefix = "";
	var $root   = array();
	var $overlay   = array();

	function Config(&$init=array())
	{
		$this->root = $init;
	}

	function set($name, $value) {
		$this->root[$name] = $value;
	}

	function get($name)
	{
		if(!array_key_exists( $this->prefix . $name,$this->root))
			return false;
		return $this->root[ $this->prefix . $name];
	}

	function __set($name,$key) {
		$path = $this->prefix . $name;
		$this->set($path,$key);
	}

	function __get($name)
	{
		$path = $this->prefix . $name;

		if(array_key_exists($path, $this->overlay))
		{
			if(gettype($this->overlay[$path]) =="array")
				return array_melt( $this->root[$path],$this->overlay[$path] );
			return $this->overlay[$path];
		}
		elseif(array_key_exists($path , $this->root))
			return $this->root[$path];
		else
		{
			$config =   new Config();
			$config->root   = &$this->root; 
			$config->overlay   = &$this->overlay; 
			$config->prefix = $this->prefix.$name.".";
			return $config;
		}
	}

	function as_array()
	{
		$a = array();
		foreach($this->root as $key=>$value) 
			if( str_startswith($this->prefix, $key))
				$a[$key]=$value;

		foreach($this->overlay as $key=>$value) 
			if( str_startswith($this->prefix, $key))
				$a[$key]=$value;

		return $a;
	}

	function is($key)
	{
		$a = $this->prefix . $key;
		return array_key_exists($a,$this->overlay) or 
			array_key_exists($a,$this->root);
	}


	function __toString()
	{
		$k = substr($this->prefix,0,-1);
		if(array_key_exists($k, $this->root))
			return (string) $this->root[$k];
		else	return "";
	}

	function copy()
	{
		$c = new config($this->as_array());
		return $c;
	}


	function override(&$map = array() )
	{
		$this->overlay = $map;
	}

}
?>

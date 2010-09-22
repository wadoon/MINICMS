<?php

function def($constant, $yes = "", $no = "" )
{
    echo defined($constant) ? $yes
                            : $no;
}

function notdef($constant, $no = "", $yes = "")
{
    echo def($constant, $yes, $no);
}


/**
* Wrap $log_text at the given $limit and $divider
 */
function text_wrap($log_text, $limit, $divider=" ") {
  $words = explode($divider, $log_text);
  $word_count = count($words);
  $char_counter = 0;
  $block = "";
  foreach ($words as $value) {
    $chars = strlen($value);
    $block .= $value;
    $char_counter = $char_counter + $chars;
    if ($char_counter >= $limit) {
            $block .= " \n ";
            $char_counter = 0;
    } else {
      $block .= " ";
    }
  }
    return rtrim($block);
}

/**
 * process the metadata as string
 *
 */
function meta($string)
{
    $lines = explode("\n", $string);
    $cfg = array();
    foreach($lines as $line)
    {
        list($key,$value) = explode(':', $line);
        $cfg[trim($key)]=trim($value);
    }
    return ($cfg);
}

function config_melt($usercfg)
{
    global $config;
    foreach($usercfg as $key => $value) 
    { 
        if( !array_key_exists($key,$config) or gettype( $config[$key]) != 'array' )
            $config[$key]=$value;
        else
            $config[$key][] = $value;
    }
}


function trigger_page_action()
{
    global $config, $request_file;
    if($config['page.action'])
    {
        echo $request_file;
        require(DATA_DIR.'/'.dirname($request_file).$config['page.action']);
    }
}

?>

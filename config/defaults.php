<?
/*****************************************************************************/
/* Configuration                                                             */ 
/*****************************************************************************/
/* This configuration can be overwritten and extend with mm               */ 
$defaults = array 
(

    'page.title'                =>  "default",
    'page.author'               =>  "Alexander Weigl, INF-BA",
    'page.date'                 =>  null,
    'page.action'               =>  null,
    'page.color1'               =>  '#000',
    'page.color2'               =>  '#000',
    'page.color3'               =>  '#000',
    'layout.decorator'          =>  true,
    'layout.nocss'              =>  false,
    'layout.nojavascript'       =>  false,
    'layout.css'                =>  array(),
    'layout.javascript'         =>  array(),
    'layout.noindent'           =>  false,
    'navi.special'              =>  array( 
                                        array("Home", "/"),
                                        array("Blog", "blog") ), 
    'navi.disabled'             =>  false
);


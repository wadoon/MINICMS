<?
/*****************************************************************************/
/* Configuration                                                             */ 
/*   (1) Constants and (2) modifiable Config                                 */ 
/*****************************************************************************/

/* Constants                                                                 */ 
  #root url from the site!
  define('ROOT_URL','/~alex/public_html/');
  
  # path to the index.php 
  define('SITE_INDEX',ROOT_URL.'index.php');

  # basename without extension for default sites in directories
  define('INDEX_PAGE','index');

  #not yet used 
  define('DEFAULT_LANG', 'de');
  
  # folder with the content
  define('DATA_DIR','_d');

  # blog folder
  define('BLOG_DIR','_b');

  #path to the content of error404 
  define('ERROR_404',DATA_DIR.'/error404.text');

  #use auto mime as default handler, suffix only
  define('DEFAULT_HANDLER', 'mime');

  #a mapping of key and url
  define("URLMAP", "map");
  
  #show every error
  error_reporting(E_ALL);

/* Config                                                                 */ 
/* This configuration can be overwritten and extend with mm               */ 
$config = array 
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
                                        array("Home", "index.php"),
                                        array("Blog", "blog.php") ), 
    'navi.disabled'             =>  false
);

  #replacements 
  $snippets = array(
                '_w/o_'              => ROOT_URL.DATA_DIR.'/', 
                '%w/o%'              => ROOT_URL.DATA_DIR.'/',
                '_site_'             => ROOT_URL.'index.php',
                '%site%'             => ROOT_URL.'index.php',
                '_root_'             => ROOT_URL,
                '%root%'             => ROOT_URL, 
                '_static_'           => ROOT_URL.'static/',
                '%static%'           => ROOT_URL.'static/',
                "_ghImage_"          =>  
                                '<img  src="http://github.com/favicon.png" alt="" width="16" height="16"/>',
                "_ccImage_"          => 
                                '<a rel="license" href="http://creativecommons.org/licenses/by-nc-sa/3.0/de/"><img
                                    alt="Creative Commons License"
                                    src="http://i.creativecommons.org/l/by-nc-sa/3.0/de/88x31.png" /></a>',
                "_gradle_"         => '<a href="http://www.gradle.org">Gradle</a>',
                "/graph" => '/></div>',
                'dmath/'  => "<div class='math math-definition'>[mathdef] <p
                        class='math-inner'>\n amath",
                "/math" => "\nendamath\n</p></div>",
                'math/'  => "<div class='math math-block'> <p
                        class='math-inner'> \namath",
                "graph/"  => "<div class='graph'> <embed ",
                "&&&"     => '<span style="font-family: cursive;">&amp;</span>'
    );
?>

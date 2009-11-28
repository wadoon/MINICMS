<?

  #root url from the site!
  #define('ROOT_URL','/~alex/07_fh-space');
  define('ROOT_URL','/~weigla/');
  
  # path to the index.php 
  define('SITE_INDEX',ROOT_URL.'index.php');

  # basename without extension for default sites in directories
  define('INDEX_PAGE','index');

  #not yet used 
  define('DEFAULT_LANG', 'de');
  
  #define('DEFAULT_PAGE', 'default.text');
  
  # folder with the content
  define('DATA_DIR','data');

  #cache folder
  define('CACHE_DIR',dirname(__FILE__)."/cache/");

  #path to the content of error404 
  define('ERROR_404',DATA_DIR.'/error404.text');

  #use plain as default handler
  define('DEFAULT_HANDLER', 'txt');

  #show every error
  error_reporting(E_ALL); 
?>

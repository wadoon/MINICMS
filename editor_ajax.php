<?
error_reporting(E_ALL);
require_once("lib/geshi/geshi.php");
require_once("lib/markdown.php");

$geshi = new GeSHI($_REQUEST['data'],"markdown");
$html = Markdown($_REQUEST['data']);

$highlight = $geshi->parse_code();

echo json_encode(array(
	"src" => $highlight,
       	"html"=> $html));
?>

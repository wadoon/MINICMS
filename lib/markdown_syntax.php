<?php
function MarkdownSynHigh($text) {
	$parser = new MarkdownSyntax();
	return $parser->transform($text);
}

class Markdown_ParserSyntax extends MarkdownExtra_Parser 
{
	//function setup() {}
	//	function teardown() {}
	//	function transform($text) {}
		function stripLinkDefinitions($text) { echo $text;}
		function _stripLinkDefinitions_callback($matches) {}
		function hashHTMLBlocks($text) {}
		function _hashHTMLBlocks_callback($matches) {}
		function hashPart($text, $boundary = 'X') {}
		function hashBlock($text) {}
		function runBlockGamut($text) {}
		function runBasicBlockGamut($text) {}
		function doHorizontalRules($text) {}
		function runSpanGamut($text) {}
		function doHardBreaks($text) {}
		function _doHardBreaks_callback($matches) {}
		function doAnchors($text) {}
		function _doAnchors_reference_callback($matches) {}
		function _doAnchors_inline_callback($matches) {}
		function doImages($text) {}
		function _doImages_reference_callback($matches) {}
		function _doImages_inline_callback($matches) {}
		function doHeaders($text) {}
		function _doHeaders_callback_setext($matches) {}
		function _doHeaders_callback_atx($matches) {}
		function doLists($text) {}
		function _doLists_callback($matches) {}
		function processListItems($list_str, $marker_any_re) {}
		function _processListItems_callback($matches) {}
		function doCodeBlocks($text) {}
		function _doCodeBlocks_callback($matches) {}
		function makeCodeSpan($code) {}
		function prepareItalicsAndBold() {}
		function doItalicsAndBold($text) {}
		function doBlockQuotes($text) {}
		function _doBlockQuotes_callback($matches) {}
		function _doBlockQuotes_callback2($matches) {}
		function formParagraphs($text) {}
		function encodeAttribute($text) {}
		function encodeAmpsAndAngles($text) {}
		function doAutoLinks($text) {}
		function _doAutoLinks_url_callback($matches) {}
		function _doAutoLinks_email_callback($matches) {}
		function encodeEmailAddress($addr) {}
		function parseSpan($str) {}
		function handleSpanToken($token, &$str) {}
		function outdent($text) {}
		function detab($text) {}
		function _detab_callback($matches) {}
		function _initDetab() {}
		function unhash($text) {}
		function _unhash_callback($matches) {}
		function MarkdownExtra_Parser() {}
		function setup() {}
		function teardown() {}
		function hashHTMLBlocks($text) {}
		function _hashHTMLBlocks_inMarkdown($text, $indent = 0,) {}
		function _hashHTMLBlocks_inHTML($text, $hash_method, $md_attr) {}
		function hashClean($text) {}
		function doHeaders($text) {}
		function _doHeaders_attr($attr) {}
		function _doHeaders_callback_setext($matches) {}
		function _doHeaders_callback_atx($matches) {}
		function doTables($text) {}
		function _doTable_leadingPipe_callback($matches) {}
		function _doTable_callback($matches) {}
		function doDefLists($text) {}
		function _doDefLists_callback($matches) {}
		function processDefListItems($list_str) {}
		function _processDefListItems_callback_dt($matches) {}
		function _processDefListItems_callback_dd($matches) {}
		function doFencedCodeBlocks($text) {}
		function _doFencedCodeBlocks_callback($matches) {}
		function _doFencedCodeBlocks_newlines($matches) {}
		function formParagraphs($text) {}
		function stripFootnotes($text) {}
		function _stripFootnotes_callback($matches) {}
		function doFootnotes($text) {}
		function appendFootnotes($text) {}
		function _appendFootnotes_callback($matches) {}
		function stripAbbreviations($text) {}
		function _stripAbbreviations_callback($matches) {}
		function doAbbreviations($text) {}
		function _doAbbreviations_callback($matches) {}
}

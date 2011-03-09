<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <title>alexander.weigl - <?=$config->page->title?></title>

        <?php if( !$config->layout->nocss): ?>
        <link type="text/css" rel="stylesheet"  media="screen" href="<?=ROOT_URL?>/index.php/css/screen"></link>
        <link type="text/css" rel="stylesheet"  media="print" href="<?=ROOT_URL?>/index.php/css/print"></link>
        <? endif;?>

        <?php if (! $config->layout->nojavascript): ?>
		<script language="javascript" src="<?=ROOT_URL?>index.php/js/ASCIIMathML"></script>
		<script language="javascript" src="<?=ROOT_URL?>index.php/js/ASCIIsvg"></script>
        <? endif;?>
        <?=$config->layout->noindent ?"<style> .content   {width:inherit; margin:auto; }</style>":""?>
        <base href="<?=$config->page->base?>" />
    </head>
    <body>
        <div class="body">
            <div class="header">
		    <div class="logo"><?=$config->page->title ?></div>
                <?php if(!$config->navi->disabled):?>
                <div class="navi">
                    <?
                    foreach($config->navi->special as $v )
		    {
			    list($title,$link) = $v;
			    echo "<a href='".SITE_INDEX."/$link'>$title</a> <span class='sep'>&#8226;</span> ";
		    }

                    foreach(_glob(DATA_DIR.'/*') as $f)
                    {
                        if(is_dir($f))
                        {
                            $f = basename($f);
                            echo "<a href='".SITE_INDEX."/$f/'>".ucfirst( $f )."</a>
                                        <span class='sep'>&#8226;</span> ";
                        }
                    }
                    ?>
                </div>
                <?endif;?>
            </div>
            <div class="content"> 
                <?=$content?>
            </div> 
            <!-- #content -->

            <div class="footer clear" >
                <p style="float:right">
                    <?=date("Y")?> <a href="mailto:weigla@fh-trier.de">Alexander Weigl (INF-I)</a>
		</p>
                <?=realpath($config->page->path)?> (<strong><?=@date('Y-M-d', filemtime($config->page->path)); ?>)
                </div>
                <script type="text/javascript">
                    var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
                    document.write(unescape("%3Cscript src='" + gaJsHost + "google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E"));
                </script>
                <script type="text/javascript">
                    try {
                    var pageTracker = _gat._getTracker("UA-64345-10");
                    pageTracker._trackPageview();
                    } catch(err) {}
                    </script>
                </div>
                <!-- #body-->
            </body>
        </html>

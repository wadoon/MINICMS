
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <title>alexander.weigl - <?=preg_replace("/[\/]+/",'/',$requested_file)?></title>
        <link href='http://fonts.googleapis.com/css?family=Reenie+Beanie' rel='stylesheet' type='text/css'>
        <link href='http://fonts.googleapis.com/css?family=Cardo' rel='stylesheet' type='text/css'>
        <link href='http://fonts.googleapis.com/css?family=Cantarell:regular,italic' rel='stylesheet' type='text/css'>
        <link href='http://fonts.googleapis.com/css?family=Cardo' rel='stylesheet' type='text/css'>
        <link href='http://fonts.googleapis.com/css?family=Cantarell:regular,italic' rel='stylesheet' type='text/css'>
        <?php
        if(! defined("NO_CSS") ) {
        echo '<link type="text/css" rel="stylesheet"  media="screen" href="'.ROOT_URL.'/static/screen.css"></link>';
        echo '<link type="text/css" rel="stylesheet"  media="screen" href="'.ROOT_URL.'/static/blog.css"></link>';
        echo '<link type="text/css" rel="stylesheet"  media="print" href="'.ROOT_URL.'/static/print.css"></link>';
        }
        notdef("NO_FORMULA", '<script language="javascript" src="'.ROOT_URL.'static/ASCIIMathML.js"></script>');
        notdef("NO_GRAPH", '<script language="javascript"   src="'.ROOT_URL.'static/ASCIIsvg.js"></script>');
        #        notdef("NO_GRAPH", '<script language="javascript"
            #            src="'.ROOT_URL.'static/raphael-min.js"></script>');

        def("NO_INDENT", "<style> .content   {width:inherit; margin:auto; }</style>");
        ?>
    </head>
    <body>
        <div class="body">
            <div class="header">
                <div class="logo">alexander.weigl</div>
                <div class="navi">
                    <?
                    echo "<a href='".SITE_INDEX."/'>Home</a> 
                    <span class='sep'>&#8226;</span> ";
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
                    <a href="<?=ROOT_URL?>/sitemap.php">Sitemap</a>
                </div>
            </div>
            <div class="content"> 
                <? foreach($articles as $article): ?>
                <div class="article">
                    <div class="sub"><?=date("Y-m-d  H:m:s",$article['date'])?></div>
                    <h2 class="article"><?=$article['title']?></h2>
                    <div class="inner"><?=$article['content']?></div>
                </div>
                <?endforeach;?>    

                <? if(defined("PAGING")): ?>
                <div style="text-align:center">
                    <?if($_GET['page'] != 0):?> <a href="?page=<?=$_GET['page'] - 1?>">&lt;</a> <?endif;?>

                    <? for($i = 0; $i < LAST_PAGE; $i++): ?>
                    <a href="?page=<?=$i?>"><?=$i?></a>
                    <? endfor;?>


                    <?if($_GET['page']+1 < LAST_PAGE):?> <a href="?page=<?=$_GET['page'] + 1?>">&gt;</a> <?endif;?>
                    <?endif;?>
                </div> 
                <!-- #content -->

                <div class="footer clear" >
                    <p style="float:right">
                        2010 <a href="mailto:weigla@fh-trier.de">Alexander Weigl (INF-I)</a>
                    </p>
                    <?=realpath($requested_file)?> (<strong><?=@date('Y-M-d', filemtime($requested_file)); ?>)
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

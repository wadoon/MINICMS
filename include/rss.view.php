<?header("content-type: application/rss+xml");?>
<?echo '<?xml version="1.0" encoding="utf-8"?>';?>

<rss version="2.0">
    <channel>
        <title>myworld</title>
        <link><?=ROOT_URL?></link>
        <description></description>
        <language>de-de</language>
        <copyright>Alexander Weigl</copyright>
        <pubDate><?=date(DATE_RFC822)?></pubDate>
        <? foreach($articles as $article): ?>
        <item>
            <pubDate><?=date(DATE_RFC822,$article['date'])?></pubDate>
            <title><?=$article['title']?></title>
            <author>Alexander Weigl &lt;A.Weigl@fh-trier.de&gt;</author>
            <description> <?=htmlentities($article['content'])?> </description>
        </item>
        <?endforeach;?>    
</channel>
</rss>



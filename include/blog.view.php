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


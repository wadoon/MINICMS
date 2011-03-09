  <? foreach($articles as $article): ?>
    <div class="article">
        <div class="sub"><?=date("Y-m-d  H:m:s",$article['date'])?></div>
        <h2 class="article"><?=$article['title']?></h2>
        <div class="inner"><?=$article['content']?></div>
    </div>
  <?endforeach;?>    

  <div style="clear:both"></div>
  <? if(defined("PAGING")): ?>
    <div style="text-align:center">
          <?if($page > 0):?> 
              <a href="<?=$page - 1?>">&lt;</a> 
          <?endif;?>

          <? for($i = 0; $i < LAST_PAGE; $i++): ?>
              <a href="<?=$i?>"><?=$i?></a>
          <? endfor;?>

          <?if($page != LAST_PAGE):?>
               <a href="<?=$page + 1?>">&gt;</a> 
          <?endif;?>
      </div>
  <?endif;?>


<main>
    <div id="sub_menu">
        <div class="center">
            <ul>
                <li><a href="/"><?= $vars[0][$lng] ?></a></li>
                <li><img src="/assets/images/sageata.png"></li>
                <li><?=$vars[28][$lng]?></li>
            </ul>
            <div class="clear"></div>
            <h2><?=$vars[28][$lng]?> - "<?=$_GET['word']?>" ( <?=count($search_news)+count($search_products) ?> )</h2>
            <hr/>
        </div>
    </div>

    <div class="center">
        <div id="main_noutati">
            <div id="left_noutati">
                <ul>
                    <?php foreach ($search_news as $new) { ?>
                        <li>
                            <h2><a href="/<?= $lng ?>/news/<?= $new['url'] ?>"><?= $new['name_' . $lng] ?></a></h2>
                            <span><?= date("d.m.Y", strtotime($new['data'])) ?></span>
                            <?php if ($new['photo'] != "") { ?><div class="imagine"><img src="/admin<?= $new['photo'] ?>"></div> <?php } ?>
                            <?= read_more($new['text_' . $lng], 30) ?>
                        </li>
                    <?php } ?>
                        
                        <?php foreach ($search_products as $new1) { ?>
                        <li>
                            <h2><a href="/<?= $lng ?>/products/<?= $new1['url'] ?>"><?= $new1['name_' . $lng] ?></a></h2>                            
                            <?php if ($new1['photo'] != "") { ?><div class="imagine"><img src="/admin<?= $new1['photo'] ?>"></div> <?php } ?>
                            <?= read_more($new1['name2_' . $lng], 30) ?>
                        </li>
                    <?php } ?>
                </ul>
                
            </div>

            <div id="right_noutati">
                <div class="news">
                    <ul>
                        <ul>
                            <?php foreach ($arhiva as $item) { ?>
                                <li>
                                    <span><?= date("d.m.Y", strtotime($item['data'])) ?></span>
                                    <a href="/<?= $lng ?>/news/<?= $item['url'] ?>"><?= read_more($item['text_' . $lng], 15) ?></a>
                                </li>
                            <?php } ?>                       
                        </ul>
                    </ul>
                </div>

                <div id="produse_noi_noutati">
                    <h2><?= $vars[1][$lng] ?></h2>
                    <div class="new_produse">
                        <ul class="bxslider3">
                            <li>
                                <?php $i=1; $c=1; foreach ($last_products as $l_p) { ?>
                                <span>
                                    <a href="/<?=$lng?>/products/<?=$l_p['url']?>">
                                        <img src="/admin<?=$l_p['photo']?>">
                                        <strong><?=$l_p['name_'.$lng]?></strong>
                                        <p><?=$l_p['title_'.$lng]?></p>
                                        <b><?=$l_p['timp']?></b>
                                    </a>
                                </span> 
                                <?php if($i==3 && $c != count($last_products)) { echo "</li><li>"; $i=0; } ?>
                                <?php $i++; $c++;  } ?>
                                 
                            </li>
                        </ul>
                    </div>
                </div>        	        
            </div>
            <div class="clear"></div>
        </div>
    </div>





    <div class="clear"></div>
    <div class="center">
        <hr/>
        <div id="parteneri">
            <h2><?= $vars[2][$lng] ?></h2>

            <?php foreach ($parteneri as $partener) { ?>
                <a href="<?= $partener['link'] ?>"><img src="/admin<?= $partener['photo'] ?>"></a> 
            <?php } ?>            
        </div>
    </div>
</div>  
</main>
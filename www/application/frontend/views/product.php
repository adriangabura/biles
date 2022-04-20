<main>
    <div id="sub_menu">
        <div class="center">
            <ul>
                <li><a href="index.php"><?=$vars[0][$lng]?></a></li>
                <li><img src="/assets/images/sageata.png"></li>
                <li><strong><?=$page['name_'.$lng]?></strong></li>
            </ul>
            <div class="clear"></div>
            <h2><?=$page['name_'.$lng]?></h2>
            <hr/>
        </div>
    </div>
    <div class="clear"></div>

    <div class="center">
        <div id="main_categorii">
            <div class="left_categorii">
                <h2><?=$vars[15][$lng]?></h2>

                <ul>
                    <?php foreach ($produse_cats as $cat) { ?>
                    <?php $products = $this->mysql->get_All('catalog',array('parent'=>$cat['id'])); ?>
                    <li><span class="view_text <?=($produs_parent['id']==$cat['id']?" active":"")?>"><a href="javascript:void(0);"><?=$cat['name_'.$lng]?></a>
                            <ol>
                                <?php foreach ($products as $product) { ?>
                                <li><a href="/<?=$lng?>/products/<?=$product['url']?>" class="<?=($produs['id']==$product['id']?"active":"")?>"><?=$product['name_'.$lng]?></a></li>
                                <?php } ?>
                            </ol>
                        </span>
                    </li>
                    <?php } ?>                   
                </ul>

                <div id="categorii_news">
                    <span>
                        <h3><?=$vars[16][$lng]?></h3>

                        <ul>
                            <?php foreach ($arhiva_p as $arh_p) { ?>
                            <li>
                                
                                <b><?=date("d.m.Y", strtotime($arh_p['data']))?></b>
                                <a href="/<?=$lng?>/news/<?=$arh_p['url']?>"><?=  read_more($arh_p['text_'.$lng], 10)?></a>
                            </li>
                            <?php } ?>
                        </ul>
                        <strong><a href="/<?=$lng?>/news"><?=$vars[17][$lng]?></a></strong>


                    </span>





                </div>

            </div>



            <div class="right_categorii">
                <span><img src="/admin<?=$produs['photo']?>"></span>
                <h3><?=$produs['name_'.$lng]?></h3>
                <?=$produs['name2_'.$lng]?>
                <div class="clear"></div>
                <h2><?=$vars[18][$lng]?></h2>
                <?=$produs['text_'.$lng]?>
            </div>
            <div class="clear"></div>
        </div>
    </div>

     <div class="center">						
        <div id="produse_noi">
            <h2><?=$vars[1][$lng]?></h2>
            <div class="new_produse">
                <ul class="bxslider2">
                    <?php foreach ($last_products as $l_p) { ?>
                    <li>
                        <span>
                            <a href="/<?=$lng?>/products/<?=$l_p['url']?>">
                                <img src="/admin<?=$l_p['photo']?>">
                                <strong><?=$l_p['name_'.$lng]?></strong>
                                <p><?=$l_p['title_'.$lng]?></p>
                                <b><?=$l_p['timp']?></b>
                            </a>
                        </span>   
                    </li>
                    <?php } ?>                    
                </ul>
            </div>
        </div>        	                               
    </div>                      

    <div class="center">
        <hr/>
        <div id="parteneri">
            <h2><?= $vars[2][$lng] ?></h2>

            <?php foreach ($parteneri as $partener) { ?>
                <a href="<?= $partener['link'] ?>"><img src="/admin<?= $partener['photo'] ?>"></a> 
            <?php } ?>            
        </div>
    </div>
</main>
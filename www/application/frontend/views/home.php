<main>
    <div id="bg_main">
        <div class="center">
            <div id="slider1">
                <ul class="bxslider">
                    <?php foreach ($slider as $slide) { ?>
                    <li><img src="/admin<?=$slide['photo']?>"></li>
                    <?php } ?>
                </ul>
            </div>

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
    </div>

    <div class="center">
        <div id="categorii">
            <h2><?= $vars[15][$lng] ?></h2>
            <ul>
                <?php foreach ($categorii as $categorie) { ?>
                    <li><a href="/<?=$lng?>/catalog"><img src="/admin<?=$categorie['photo']?>"><b><?=$categorie['name_'.$lng]?></b><span></span></a></li>
                <?php } ?>                
            </ul>
            <a href="/<?=$lng?>/catalog"><?= $vars[20][$lng] ?></a>
        </div>
    </div>

    <div class="center">
        <div id="noutati">

            <div class="noutati_left">
                <img src="/assets/images/img_noutati.png">
                <?=$snippet[2][$lng]?>
                <a href="/<?=$lng?>/contacts"><?= $vars[19][$lng] ?></a>
            </div>

            <div class="noutati_right">
                <h2><?= $vars[16][$lng] ?></h2>
                <ul class="slider_noutati">
                    <?php foreach ($arhiva as $new) { ?>
                    <li>
                        <span><?= date("d.m.Y", strtotime($new['data'])) ?></span>
                        <?php if ($new['photo'] != "") { ?><img src="/admin<?= $new['photo'] ?>"> <?php } ?>
                        <a href="/<?= $lng ?>/news/<?= $new['url'] ?>"><?= read_more($new['text_' . $lng], 10) ?></a>
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

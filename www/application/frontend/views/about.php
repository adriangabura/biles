
<main>
    <div id="sub_menu">
        <div class="center">
            <ul>
                <li><a href="/"><?= $vars[0][$lng] ?></a></li>
                <li><img src="/assets/images/sageata.png"></li>
                <li><?= $page['name_' . $lng] ?></li>
            </ul>
            <div class="clear"></div>
            <h2><?= $page['name_' . $lng] ?></h2>
            <hr/>
        </div>
    </div>

    <div class="center">
        <div id="main_despre_noi">
            <?= $page['text_' . $lng] ?>
        </div>
    </div>




    <div class="center">
        <div class="center">
            <div id="produse_noi">
                <h2><?= $vars[1][$lng] ?></h2>
                <div class="new_produse">
                    <ul class="bxslider2">
                        <?php foreach ($last_products as $l_p) { ?>
                            <li>
                                <span>
                                    <a href="/<?= $lng ?>/products/<?= $l_p['url'] ?>">
                                        <img src="/admin<?= $l_p['photo'] ?>">
                                        <strong><?= $l_p['name_' . $lng] ?></strong>
                                        <p><?= $l_p['title_' . $lng] ?></p>
                                        <b><?= $l_p['timp'] ?></b>
                                    </a>
                                </span>   
                            </li>
                        <?php } ?>                    
                    </ul>
                </div>
            </div>        	        
        </div>
        <hr/>
        <div id="parteneri">
            <h2><?= $vars[2][$lng] ?></h2>

            <?php foreach ($parteneri as $partener) { ?>
                <a href="<?= $partener['link'] ?>"><img src="/admin<?= $partener['photo'] ?>"></a> 
            <?php } ?>            
        </div>
    </div>
</main>
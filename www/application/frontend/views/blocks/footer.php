
<div class="clear"></div>
<!--******************************************FOOTER*******************************************-->
<footer>

    <div class="center">
        <div id="up_footer">
            <div id="adresa">
                <img src="/assets/images/logo_mic.png">
                <?=$menu_f?>
                <span>
                    <p>+373 <?=$vars[8][$lng]?></p>
                    <p>+373 <?=$vars[9][$lng]?></p>
                    <strong><?=$vars[5][$lng]?></strong>
                </span>

            </div>

            <div id="categorii_productie">
                <h2><?=$vars[12][$lng]?></h2>
                <ul style="-webkit-column-count: 2;-moz-column-count: 2;column-count: 2;">
                    <?php foreach ($categorii as $cat) { ?>
                    <li><a href="/<?=$lng?>/catalog"><?=$cat['name_'.$lng]?></a></li>
                    <?php } ?>                   
                </ul>
            </div>

            <div id="noutati_actii">
                <h2><?=$vars[10][$lng]?></h2>
                <ul>
                    <?php foreach ($arhiva_f as $item_f) {?>
                    <li>
                        <span><?= date("d.m.Y", strtotime($item_f['data'])) ?></span>
                        <a href="/<?= $lng ?>/news/<?= $item_f['url'] ?>"><?= read_more($item_f['text_' . $lng], 10) ?></a>
                    </li>                    
                    <?php } ?>                       
                </ul>
            </div>

            <div id="formular">
                <h2><?=$vars[11][$lng]?></h2>
               <form method="post">
                    <input type="text" name="nume" placeholder="<?=$vars[22][$lng]?>"/>
                    <input type="email" name="email" placeholder="<?=$vars[23][$lng]?>"/>
                    <textarea name="msg" placeholder="<?=$vars[24][$lng]?>"></textarea>
                    <input type="submit"  value="<?=$vars[25][$lng]?>"/>
                    <input type="hidden" name="send1" value="send">
                    <?php if($mesaj1) { ?> <p style="color: green;margin-top: 15px;"><?=$vars[26][$lng]?></p> <?php } ?>
                </form>
            </div>

        </div>
    </div>

    <div class="clear"></div>
    <div class="center">
        <div id="down_footer">
            <p>Copyright (c) 2015. Все права защищены</p>
            <span>Created by<a href="#"><img src="/assets/images/logo_bizon.png"></a></span>
        </div>
    </div>
</footer>

</body>
</html>

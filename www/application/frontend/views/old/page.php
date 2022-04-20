<main>
    <div class="center">
        <div id="submenu">
            <ul>
                <li><a href="/"><?=$pages[0]['name_'.$lng]?></a></li>
                <li><?=$page['name_'.$lng]?></li>
            </ul>
        </div>
        <div id="left_content" >
            <div class="punct">
                <h1><?=$page['name_'.$lng]?></h1>
                <?=$page['text_'.$lng]?>
            </div>                 

            <div id="subleft_content">
                <ul>
                    <li><?=$snippet[0][$lng]?></li>

                    <li><?=$snippet[1][$lng]?></li>

                    <li><?=$snippet[2][$lng]?></li>

                    <li><?=$snippet[3][$lng]?></li>

                    <span class="clear"></span>
                </ul>

            </div>

            <div id="contacte">
            
            <ul class="posta">
                <li><?=$vars[37][$lng]?></li>
                <li><?=$vars[38][$lng]?></li>
                <li><?=$vars[39][$lng]?></li>
                <li><a href="mailto:<?=$vars[40][$lng]?>"><?=$vars[40][$lng]?></a></li>
       	    </ul>
            <ul class="tel">
                <li><?=$vars[41][$lng]?></li>
                <li><?=$vars[42][$lng]?></li>
                <li><?=$vars[43][$lng]?></li>
                <li><?=$vars[44][$lng]?></li>
       	    </ul>
            <span><p><?=$vars[45][$lng]?></p>
                <a href="<?=$setting['facebook']?>"><img src="/assets/images/facebook.png" width="28" height="28" alt="facebook"></a>
                <a href="skype:<?=$setting['twiter']?>?chat"><img src="/assets/images/skype.png" width="28" height="28" alt="skype"></a>
                <a href="<?=$setting['google']?>"><img src="/assets/images/ok.png" width="28" height="28" alt="odnoklassniki"></a>
            </span>
        </div>
        </div>


        <div id="right_content">
            <div id="parteneri">
                <h2><?=$vars[46][$lng]?></h2>
                <?php foreach ($parteneri_l as $pal) { ?>
                <img src="/admin<?=$pal['photo']?>" width="216" height="49" alt="<?=$pal['name_'.$lng]?>">
                <?php } ?>
                <a href="/<?=$lng?>/info/<?=$infos[1]['url']?>"><?=$vars[47][$lng]?></a> 
            </div>
            <div id="noutati">            	
                <strong><?=$vars[35][$lng]?></strong>

                <ul>
                    <?php foreach ($arhiva as $arhiv) { ?>
                    <li>
                        <span><?=$arhiv['data']?></span>
                        <a href="/<?=$lng?>/noutati/<?=$arhiv['url']?>"><?=  strip_tags(read_more($arhiv['text_'.$lng], 15))?></a>
                    </li>
                    <?php } ?>
                    
                </ul> 
            </div>
            <div id="teh_ajutor">
                <span style="text-transform: uppercase"><?=$vars[0][$lng]?></span>
                <form method="post">
                    <input name="nume"  type="text" placeholder="<?=$vars[1][$lng]?>" >
                    <input name="tel"  type="tel" placeholder="<?=$vars[2][$lng]?>" required>
                    <textarea name="mesaj"  placeholder="<?=$vars[4][$lng]?>" required></textarea>
                    <input name="submit"  type="submit" value="<?=$vars[5][$lng]?>">
                    <input type="hidden" name="send" value="send">
                    <?php if($mesaj == true) { ?> <p style="color: green;text-align: center;margin-top: 11px;"><?=$vars[48][$lng]?></p> <?php } ?>
                </form>
            </div>               
        </div>

        <div class="clear"></div>
    </div>
</main>
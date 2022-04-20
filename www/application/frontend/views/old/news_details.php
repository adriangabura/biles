<main>
    <div class="center">
        <div id="submenu">
            <ul>
                <li><a href="/"><?= $pages[0]['name_' . $lng] ?></a></li>
                <li><?= $page['name_' . $lng] ?></li>
            </ul>
        </div>

        <div id="noutati_left">
            <div id="noutati_detalii">  
                <h2><?=$new['name_'.$lng]?></h2>
                <span><?=$new['data']?></span>
                
                <?php if($new['photo'] !="") { ?><img src="/admin<?=$new['photo']?>" alt="<?=$new['name_'.$lng]?>"> <?php } ?>
                <?=$new['text_'.$lng]?>
                
            </div>
        </div>
    </div>
    <div class="center">
        <div id="noutati_right">
            <div id="noutati">            	
                <strong><?=$vars[50][$lng]?></strong>

                <ul>
                    <?php foreach ($arhiva as $item) { ?>
                    <li>
                        <span><?=$item['data']?></span>
                        <a href="/<?=$lng?>/noutati/<?=$item['url']?>"><?=  read_more($item['name_'.$lng], 10)?></a>
                    </li>
                    <?php } ?>                    
                </ul> 
            </div>   
        </div>


    </div>


    <div class="clear"></div>	
    <div class="center">
        <div id="contacte">
            <a href="/" id="logo2">
                <img src="/assets/images/logo-mic.png" width="202" height="41" alt="Fertilitatea">
            </a>
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
</main>
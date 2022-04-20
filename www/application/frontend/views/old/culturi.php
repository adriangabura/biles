<main>
    <div class="center">
        <div id="submenu">
            <ul>
               <li><a href="/"><?=$pages[0]['name_'.$lng]?></a></li>
                <li><?=$page['name_'.$lng]?></li>
            </ul>
        </div>
    </div>
    <?php if($mesaj == true) { ?><p style="
    width: 995px;
    margin: 0 auto;
    text-align: center;
    color: green;
    background-color: rgb(223, 223, 223);
    border: 1px solid green;
    padding: 5px;
    margin-bottom: 10px;
"><?=$vars[49][$lng]?>
</p><?php } ?>
    <div class="center">
        <div id="left_culturi">
            <strong><?=$page['name_'.$lng]?></strong>
            <ul>
                <?php foreach ($culturi as $cultura) { ?>
                <li><a href="#"><?=$cultura['name_'.$lng]?></a>
                    <ol>
                        <span></span>
                        <?php $subculturi = $this->mysql->get_All('catalog',array('parent'=>$cultura['id']),'','','ord','asc'); ?>
                        <?php foreach ($subculturi as $subcultura) { ?> 
                        <li><a href="/<?=$lng?>/culturi_detalii/<?=$subcultura['url']?>"><?=$subcultura['name_'.$lng]?></a></li>
                        <?php } ?>
                    </ol>
                </li>
                <?php } ?>                
            </ul>
            <a href="#inline2" class="fancybox culturi_contact" id="send_mesage"><?=$vars[8][$lng]?></a>
        </div>

        <div id="right_culturi">
            <h2><?=$page_cultura['name_'.$lng]?></h2>
            <hr/>
            <div class="punct">
                <?=$page_cultura['text_'.$lng]?>
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
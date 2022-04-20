<!--******************************************Content*******************************************-->

<main>
    <div class="center">
        <div id="slider">
            <ul class="bxslider">
                <?php foreach ($slider as $slide) { ?>
                <li>
                    <span>
                        <strong><?=$slide['name_'.$lng]?></strong>
                        <p><?=read_more($slide['text_'.$lng],15)?>
                        </p>
                        <a href="/<?=$lng?>/noutati/<?=$slide['url']?>"><?=$vars[30][$lng]?></a>
                    </span>
                </li>
                <?php } ?>

            </ul>
        </div>
        <div id="noi">
            <h3><?=$vars[31][$lng]?></h3>
            <ul>
                <li><?=$snippet[0][$lng]?></li>
                <li><?=$snippet[1][$lng]?></li>
                <li><?=$snippet[2][$lng]?></li>
                <li><?=$snippet[3][$lng]?></li>
            </ul>
        </div>
    </div>

    <div class="center">
        <div id="desprenoi">
            <h2><?=$vars[32][$lng]?></h2>
            <hr/>
            <div>
                <div id="datenoi" 
                     <div class="punct">
                        <?=$pages[1]['text2_'.$lng]?>
                         <a href="/<?=$lng?>/<?=$pages[1]['url']?>"><?=$vars[33][$lng]?></a>
                    </div>
                </div>              
                <div id="parteneri">
                    <h2><?=$vars[46][$lng]?></h2>
                    <?php foreach ($parteneri_l as $pal) { ?>
                <img src="/admin<?=$pal['photo']?>" width="216" height="49" alt="<?=$pal['name_'.$lng]?>">
                <?php } ?>
                     <a href="/<?=$lng?>/info/<?=$infos[1]['url']?>"><?=$vars[47][$lng]?></a> 
                </div>
            </div>

        </div>
    </div>
    <div class="center">
        <div id="produse">        
            <h2><?=$vars[34][$lng]?></h2>
            <hr/>       
            <ul class="slider1">
                <?php foreach ($prod_cat as $prc) { ?>
                <li><img src="/admin<?=$prc['photo']?>" ><a href="/<?=$lng?>/produse"><?=$prc['name_'.$lng]?></a></li> 
                <?php } ?>                
            </ul>
        </div>
        <div id="recomandari">                
            <h2><?=$vars[52][$lng]?></h2>       		
            <hr/>               
            <ul class="slider2">
                <?php foreach ($recomandari as $recomandare) {?>
                <?php $foto = $this->mysql->get_row('photo_products',array('pid'=>$recomandare['id'])); ?>
                <li>
                    <a href="/<?=$lng?>/produse_detalii/<?=$recomandare['url']?>">
                        <span><?=$recomandare['name_'.$lng]?></span><?php if($recomandare['status'] == 2) { ?><span class="top_produs">TOP PRODUS</span> <?php } elseif($recomandare['status'] == 1) { ?><span class="nou_stoc">NOU ÎN STOC</span><?php } ?>
                        <img src="<?=$foto['path']?>">
                        <p><strong><?=$vars[51][$lng]?>:</strong><br/><?=$recomandare['company_'.$lng]?>"</p>
                    </a>
                </li>
                <?php } ?>
            </ul>
        </div> 

        <div id="noutati">            	
            <h2><?=$vars[35][$lng]?></h2>
            <hr/>
            <ul>
                <?php foreach ($arhiva as $arhiv) { ?>
                <li>
                    <span><?=$arhiv['data']?></span>
                    <a href="/<?=$lng?>/noutati/<?=$arhiv['url']?>"><?=  strip_tags(read_more($arhiv['text_'.$lng], 15))?></a>
                </li>
                <?php } ?>
                
            </ul> 
            <a href="/<?=$lng?>/noutati" class="more"><?=$vars[36][$lng]?></a>           
        </div> 
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
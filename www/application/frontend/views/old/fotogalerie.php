<main>
    <div class="center">
        <div id="submenu">
            <ul>
                <li><a href="/"><?=$pages[0]['name_'.$lng]?></a></li>
                <li><?=$page['name_'.$lng]?></li>
            </ul>
        </div>
    </div>

    <div class="center">
        <div id="galerie_left">
            <div id="produse">

                <ul>
                    <?php foreach ($galerie as $gal) { ?>
                    <?php $foto = $this->mysql->get_All('photo_products',array('pid'=>$gal['id'])); ?>
                    <li><img src="<?=$foto[0]['thumb']?>" ><a href="<?=$foto[0]['path']?>" class="fancybox" rel="group<?=$gal['id']?>"><?=$gal['name_'.$lng]?></a>
                        <ul style="visibility: hidden;">
                        <?php $i=1; foreach ($foto as $img) { if ($i!==1) {?>
                            <li><img src="<?=$img['thumb']?>" ><a href="<?=$img['path']?>"  class="fancybox" rel="group<?=$gal['id']?>"></a>
                        <?php  }$i++;} ?>
                        </ul>
                    </li>    
                    <?php } ?>
                    
                </ul>

            </div>
            <hr/>
            <?=$pagination?>   
        </div>
    </div>






    <div class="center">
        <div id="galerie_right">
            <div id="right_content">	
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
        </div>
        <div class="clear"></div>    
    </div></main>

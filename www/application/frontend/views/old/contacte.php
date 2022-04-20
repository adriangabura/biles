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
        <div id="contacte2">
            <h2><?=$page['name_'.$lng]?></h2>
            <hr/>

            <div id="left_contact">
                <h3><?=$vars[0][$lng]?></h3>
                <hr/>
                <div id="forma_contact">
                  
                    <div id="teh_ajutor">
                
                <form method="post">
                    <input name="nume"  type="text" placeholder="<?=$vars[1][$lng]?>" >
                    <input name="tel"  type="tel" placeholder="<?=$vars[2][$lng]?>" required>
                    <input name="email" type="mail" placeholder="<?=$vars[3][$lng]?>">
                    <textarea name="mesaj"  placeholder="<?=$vars[4][$lng]?>" required></textarea>
                    <input name="submit"  type="submit" value="<?=$vars[5][$lng]?>">
                    <input type="hidden" name="send" value="send">
                    <?php if($mesaj == true) { ?> <p style="color: green;text-align: center;margin-top: 11px;"><?=$vars[48][$lng]?></p> <?php } ?>
                </form>
            </div> 
                </div>  
            </div>
            <div id="right_contact">
                <div id="right_contact1">
                    <h3><?=$vars[6][$lng]?></h3>
                    <hr/>
                    <span class="mail2">
                        <?=$snippet[4][$lng]?>
                    </span>
                    <br/>
                    <span class="tel2">
                        <?=$snippet[5][$lng]?>
                        
                </div>
                <div id="right_contact2">
                    <h3><?=$vars[7][$lng]?></h3>
                    <hr/>
                    <?=$snippet[6][$lng]?>
                    

                </div>
                <div class="clear"></div>

                <a href="#inline2" class="fancybox specialist" id="send_mesage"><?=$vars[8][$lng]?></a>
                <p><?=$vars[9][$lng]?></p>
            </div>
            <div id="harta">
                <h3><?=$vars[10][$lng]?></h3>
                <hr/>
                <iframe width="1000" height="290" frameborder="0" src="http://point.md/ru/map/embed/#x%3D47.02968173467043%26y%3D28.836595416069027%26z%3D18%26m%3D%255B%255B47.029462344737176%252C28.83713722229004%252C%2522%2522%255D%255D"></iframe></div>
        </div>
        <div class="clear"></div>
    </div>
</main>  
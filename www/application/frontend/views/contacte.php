<main>
    <div id="sub_menu">
        <div class="center">
            <ul>
                <li><a href="/"><?=$vars[0][$lng]?></a></li>
                <li><img src="/assets/images/sageata.png"></li>
                <li><?=$page['name_'.$lng]?></li>
            </ul>
            <div class="clear"></div>
            <h2><?=$page['name_'.$lng]?></h2>
            <hr/>
        </div>
    </div>


    <div class="center">
        <div id="contacte_main">
            <div class="despre_noi">
                <?=$pages[0]['text2_'.$lng]?>
            </div>

            <div id="formular">
                <h2><?=$vars[3][$lng]?></h2>
                <form method="post">
                    <input type="text" name="nume" placeholder="<?=$vars[22][$lng]?>"/>
                    <input type="email" name="email" placeholder="<?=$vars[23][$lng]?>"/>
                    <textarea name="msg" placeholder="<?=$vars[24][$lng]?>"></textarea>
                    <input type="submit"  value="<?=$vars[25][$lng]?>"/>
                    <input type="hidden" name="send" value="send">
                    <?php if($mesaj) { ?> <p style="color: green;margin-top: 15px;"><?=$vars[22][$lng]?></p> <?php } ?>
                </form>
            </div>

            <div class="clear"></div>
            <hr/>

            <div class="date_contact">
                <h4><?=$vars[4][$lng]?></h4>
                <span><?=$vars[5][$lng]?></span>
                <?=$snippet[0][$lng]?>
            </div>

            <div class="orar_lucru">
                <h4><?=$vars[6][$lng]?></h4>
                <?=$snippet[1][$lng]?>
                
            </div>

            <div class="clear"></div>
            <hr/>

            <h4><?=$vars[7][$lng]?></h4>
            <iframe width="1150" height="436" frameborder="0" src="http://point.md/ru/map/embed/#x%3D47.04424720835162%26y%3D28.8007128238678%26z%3D17%26m%3D%255B%255B47.04494174757876%252C28.800562620162964%252C%2522%2522%255D%255D"></iframe>

            <div class="clear"></div>
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
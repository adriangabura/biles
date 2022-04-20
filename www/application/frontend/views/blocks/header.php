<!DOCTYPE HTML>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <title><?php if (isset($title_page) and $title_page != "") echo $title_page;
else echo $title_general; ?></title>
        <meta name="description" content="<?php if (isset($description) and $description != "") echo $description;
else echo $description_general; ?>" />
        <meta name="keywords" content="<?php if (isset($keywords) and $keywords != "") echo $keywords;
else echo $keywords_general; ?>" />
        <link href="/assets/css/style_main.css" rel="stylesheet" type="text/css">
        <link href="/assets/css/stylesheet.css" rel="stylesheet" type="text/css">

        <!-- jQuery library (served from Google) -->
        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
        <!-- bxSlider Javascript file -->
        <script src="/assets/js/jquery.bxslider.js"></script>
        <!-- bxSlider CSS file -->
        <link href="/assets/css/jquery.bxslider.css" rel="stylesheet" />

        <!-- Add fancyBox -->
        <link rel="stylesheet" href="/assets/css/jquery.fancybox.css" type="text/css" media="screen" />
        <script type="text/javascript" src="/assets/js/jquery.fancybox.js"></script>

        <script type="text/javascript">
            $(document).ready(function () {

                $('.bxslider').bxSlider({
                    auto: true
                });

                $('.bxslider2').bxSlider({
                    slideWidth: 5000,
                    minSlides: 4,
                    maxSlides: 4,
                    slideMargin: 16
                });

                $('.slider_noutati').bxSlider({
                    slideWidth: 5000,
                    minSlides: 2,
                    maxSlides: 2,
                    slideMargin: 20
                });

                $('.bxslider3').bxSlider({
                    slideWidth: 300,
                    minSlides: 1,
                    maxSlides: 1,
                    slideMargin: 10
                });

            });


            $(document).ready(function () {
                $(".fancybox").fancybox();
            });

            $(document).ready(function () {
                $(".view_text a").click(function () {

                    if ($(this).parent().hasClass("active")) {
                        $(this).parent().removeClass("active");

                    }
                    else {
                        $(this).parent().addClass("active");

                    }

                });
            });



        </script>
    </head>

    <body>
        <!--******************************************PARTEA DE SUS*******************************************-->
        <header>

            <div id="up_header">
                <div class="center">
                    <div class="header1">
                        
                        <ul>
                            <?php foreach ($infos as $info) { ?>
                                <li><a href="/<?=$lng?>/info/<?=$info['url']?>"><?=$info['name_'.$lng]?></a></li>
                            <?php } ?>                            
                        </ul>
                    </div>
                    <div class="limba">
                        <ul>
                            <span><?=$vars[21][$lng]?></span>
                            <li><a href="/limba/ro" class="<?=($lng=='ro'?"active":"")?>"></a></li>
                            <li><a href="/limba/ru" class="<?=($lng=='ru'?"active":"")?>"></a></li>
                            <li><a href="/limba/en" class="<?=($lng=='en'?"active":"")?>"></a></li>
                        </ul>            
                    </div>
                </div>
            </div>
            <div class="clear"></div>

            <div id="center_header">
                <div class="center">
                    <div class="logo">
                        <a href="/<?=$lng?>"></a>
                    </div>
                    <form action="<?=$lng?>/search" method="get"> 
                        <input type="search" name="word" placeholder="<?=$vars[27][$lng]?>"/>
                        <input type="submit" value=" "/>
                    </form>
                    <div class="tel">
                        <ul>
                            <li><span><?=$vars[13][$lng]?></span></li>
                            <li>+373 <strong><?=$vars[8][$lng]?></strong></li>
                            <li>+373 <strong><?=$vars[9][$lng]?></strong></li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="clear"></div>
            <div id="down_header">
                <div class="center">

                    <div  class="categorii"> 
                        <div class="view_text"><a href="javascript:void(0)"><?=$vars[14][$lng]?></a>

                            <ul>
                                <span></span>
                                <?php foreach ($produse_cats as $cat) { ?>
                                <?php $products = $this->mysql->get_All('catalog',array('parent'=>$cat['id'])); ?>
                                <li><a href="javascript:void(0);"><?=$cat['name_'.$lng]?></a>
                                    <ol>
                                        <?php foreach ($products as $product) { ?>
                                             <li><a href="/<?=$lng?>/products/<?=$product['url']?>"><?=$product['name_'.$lng]?></a></li>
                                        <?php } ?>
                                    </ol>

                                </li>
                                <?php } ?>
                                
                            </ul>

                        </div>   
                    </div>

                    <div class="menu">
                        <?=$menu?>
                        <div class="cos">
                            <table>
                                <tr>
                                    <td>Товаров</td>
                                    <td>0 шт.</td>
                                </tr>
                                <tr>
                                    <td>Сума</td>
                                    <td>0.00 лей</td>
                                </tr>
                            </table>

                        </div>
                    </div>


                </div>
            </div>

        </header>
        <!--******************************************Content*******************************************-->
        <div class="clear"></div>

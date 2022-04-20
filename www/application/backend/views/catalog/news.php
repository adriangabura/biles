<div class="box">
  <div class="left"></div>
  <div class="right"></div>
  <div class="heading">
    <h1 style="background-image: url('/admin/img/news.png');"><a href='/admin/news'><?=lang('news')?></a><?=$crumbs_news;?></h1>
<form action="/admin/del_news/<?=$parent?>" method="post" enctype="multipart/form-data" id="form">
    <div class="buttons">
        <span class="button" style="float:right" >
            <input type=submit value="<?=lang('del')?>" onclick="if (confirm('<?=lang('e_sigur_stergi')?>')) return true;else return false;" />
        </span>
        <a href="/admin/news_form/<?=$parent?>" class="button"><span><?=lang('add')?></span></a>
    </div>
  </div>
  <div class="content">

      <table class="list">
        <thead>
          <tr>
            <td width="1" style="text-align: center;"><input type="checkbox" onclick="$('input[name*=\'selected\']').attr('checked', this.checked);" /></td>
            <td class="left" width="5%"><?=lang('photo')?></td>
            <td class="left"><?=lang('name')?></td>
            <td class="center" width="10%">Data</td>
<!--            <td class="center" width="5%"><?=lang('ordonare')?></td>-->
            <td class="center" width="5%"><?=lang('actions')?></td>
          </tr>
        </thead>
        <tbody>
            
          <?php if ($news) { ?>
          <?php
          $nr_news = count($news);$i=0; 
          foreach ($news as $item) { 
              $i++;  ?>
          <tr>
            <td style="text-align: center;">         
              <input type="checkbox" name="selected[]" value="<?=$item['id']?>" />
            </td>
            <td class="left" valign="top">
                <a href="/admin/news_form/<?=$item['parent']?>/<?=$item['id']?>" class="none" >  
                    <?php
                    if($item['photo'] and file_exists($_SERVER['DOCUMENT_ROOT'].'/admin/'.$item['photo']))
                    {        
                    ?>
                        <img src="/admin/<?=$item['photo']?>" height="75" /> 
                    <?php
                    }
                    else
                    {    
                    ?>
                        <img src="/admin/img/no_image.jpg" width="100"  height="75" />                         
                    <?php
                    }
                    ?>
                </a>
            </td>             
            <td class="left" valign="top">
                <a href="/admin/news_form/<?=$item['parent']?>/<?=$item['id']?>" class="none" >
                    <?=$item['name_'.$base_lang]; ?>                   
                </a>
            </td>
            <td style="text-align: center;">
                <?=$item['data']?>
            </td>
<!--            <td class="center">
              <?php
              if($i==1)
              {    
              ?>
                <a href="/admin/news/<?=$item['parent']?>/<?=$item['id']?>?ac=down" style="margin-left: 20px;" ><img src="/admin/img/arr_down.png" title="Coboara cu o pozitie mai sus" /></a>&nbsp;   
              <?php
              }
              elseif($i==$nr_news)
              {    
              ?>
                <a href="/admin/news/<?=$item['parent']?>/<?=$item['id']?>?ac=up" style="margin-left: -20px;" ><img src="/admin/img/arr_up.png" title="Ridica cu o pozitie mai sus" /></a>
              <?php
              }
              else
              {    
              ?>
                <a href="/admin/news/<?=$item['parent']?>/<?=$item['id']?>?ac=up" ><img src="/admin/img/arr_up.png" title="Ridica cu o pozitie mai sus" /></a>&nbsp;   
                <a href="/admin/news/<?=$item['parent']?>/<?=$item['id']?>?ac=down" ><img src="/admin/img/arr_down.png" title="Coboara cu o pozitie mai sus" /></a>
              <?php
              }
              ?>
            </td>            -->
            <td class="center">
                [<a href="/admin/news_form/<?=$item['parent']?>/<?=$item['id']?>" ><?=lang('edit')?></a>]
                <!-- [<a href="/admin/add_terms/<?=$item['id']?>" >[<?=lang('add_terms')?></a>] -->
            </td>
          </tr>
          <?php } ?>
          <?php } else { ?>
          <tr>
            <td class="center" colspan="5"><?=lang('no_items')?></td>
          </tr>
          <?php } ?>
        </tbody>
      </table>
 
  </div>
</form>    
</div>

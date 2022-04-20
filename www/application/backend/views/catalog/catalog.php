<div class="box">
  <div class="left"></div>
  <div class="right"></div>
  <div class="heading">
    <h1 style="background-image: url('/admin/img/portfolio.png');"><a href='/admin/catalog'>Catalog</a><?=$crumbs_catalog;?></h1>
<form action="/admin/del_catalog/<?=$parent?>" method="post" enctype="multipart/form-data" id="form">
    <div class="buttons">
        <span class="button" style="float:right" >
            <input type=submit value="<?=lang('del')?>" onclick="if (confirm('<?=lang('e_sigur_stergi')?>')) return true;else return false;" />
        </span>
        <a href="/admin/catalog_form/<?=$parent?>" class="button"><span><?=lang('add')?></span></a>
    </div>
  </div>
  <div class="content">

      <table class="list">
        <thead>
          <tr>
            <td width="1" style="text-align: center;">
                <input type="checkbox" onclick="$('input[name*=\'selected\']').attr('checked', this.checked);" />
            </td>
            <?php if($nivele > 1) { ?>
<!--            <td class="left" width="5%">Foto</td>-->
            <?php } ?>
            <td class="left"><?=lang('name')?></td>
                
                
<!--            <td class="center" width="5%"><?=lang('ordonare')?></td>-->
              <?php if(($nivele ==0) && $parent !=37) { ?>
            <td class="center" width="10%">Subcategorii</td>
            <?php } ?>
            <td class="center" width="10%"><?=lang('actions')?></td>
          </tr>
        </thead>
        <tbody>

          <?php
          if ($catalog) { ?>
          <?php 
          $nr_catalog = count($catalog);$i=0;  
          foreach ($catalog as $item) {
          $i++;    
          if($item['sub_items']==0) $cl_item = 'sub_items_empty';else $cl_item = 'sub_items_full';
          ?>
          <tr>
            <td style="text-align: center;">         
              <input type="checkbox" name="selected[]" value="<?=$item['id']?>" />
            </td>
            <?php if($nivele > 1) { ?>
<!--                    <td class="left" valign="top">
                        <a href="/admin/catalog_form/<?=$item['parent']?>/<?=$item['id']?>" class="none" >  
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
                    </td>-->
            <?php } ?>
            <td class="left" valign="top"> 
              <?php if($nivele == 0 || $nivele == 1) { ?>               
<!--               <a href="/admin/catalog/<?=$item['id']?>"  class="none <?=$cl_item?>"  ><?=$item['name_'.$base_lang]; ?></a>-->
               <a href="/admin/catalog_form/<?=$item['parent']?>/<?=$item['id']?>"  class="none"  ><?=$item['name_'.$base_lang]; ?></a>
              <?php
              }
              else
              {    
              ?>
               <a href="/admin/catalog_form/<?=$item['parent']?>/<?=$item['id']?>"  class="none"  ><?=$item['name_'.$base_lang]; ?></a>
              <?php
              }
              ?>
            </td>
<!--            <td class="center">
              <?php
              if($i==1)
              {    
              ?>
                <a href="/admin/catalog/<?=$item['parent']?>/<?=$item['id']?>?ac=down" style="margin-left: 20px;" ><img src="/img/arr_down.png" title="Coboara cu o pozitie mai sus" /></a>&nbsp;   
              <?php
              }
              elseif($i==$nr_catalog)
              {    
              ?>
                <a href="/admin/catalog/<?=$item['parent']?>/<?=$item['id']?>?ac=up" style="margin-left: -20px;" ><img src="/img/arr_up.png" title="Ridica cu o pozitie mai sus" /></a>
              <?php
              }
              else
              {    
              ?>
                <a href="/admin/catalog/<?=$item['parent']?>/<?=$item['id']?>?ac=up" ><img src="/img/arr_up.png" title="Ridica cu o pozitie mai sus" /></a>&nbsp;   
                <a href="/admin/catalog/<?=$item['parent']?>/<?=$item['id']?>?ac=down" ><img src="/img/arr_down.png" title="Coboara cu o pozitie mai sus" /></a>
              <?php
              }
              ?>
            </td>-->
            <?php if(($nivele == 0) && $parent !=37) { ?>
            <td class="center">
                [ <a href="/admin/catalog/<?=$item['id']?>" >Subcategorii <?=$item['sub_items']; ?></a> ]
            </td> 
            <?php } ?>
             
            <td class="center">
                [<a href="/admin/catalog_form/<?=$item['parent']?>/<?=$item['id']?>" ><?=lang('edit')?></a>]
            </td>
            
          </tr>
          <?php } ?>
          <?php } else { ?>
          <tr>
            <td class="center" colspan="4"><?=lang('no_items')?></td>
          </tr>
          <?php } ?>
        </tbody>
      </table>
     <div class="pagination" style="margin-top: 0px;text-align: center" >
          <?=$pagination?>
    </div>
      
      
  </div>
</form>    
</div>

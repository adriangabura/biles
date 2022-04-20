<div class="box">
  <div class="left"></div>
  <div class="right"></div>
  <div class="heading">
    <h1 style="background-image: url('<?=base_url()?>admin/img/pages.png');"><a href='/admin/pages'><?=lang('menu')?></a><?=$crumbs_pages;?></h1>
<form action="/admin/del_pages/<?=$parent?>" method="post" enctype="multipart/form-data" id="form">
    <div class="buttons">
        <span class="button" style="float:right" >
            <input type=submit value="<?=lang('del')?>" onclick="if (confirm('<?=lang('e_sigur_stergi')?>')) return true;else return false;" />
        </span>
        <a href="/admin/pages_form/<?=$parent?>" class="button"><span><?=lang('add')?></span></a>
    </div>
  </div>
  <div class="content">

      <table class="list">
        <thead>
          <tr>
            <td width="1" style="text-align: center;">
                <input type="checkbox" onclick="$('input[name*=\'selected\']').attr('checked', this.checked);" />
            </td>
            <td class="left"><?=lang('name')?></td>
            <td class="center" width="5%"><?=lang('ordonare')?></td>
            <?php  if($nivele>3) { ?>    
            <td class="center" width="10%">Subitems</td>
            <?php } ?>
            <td class="center" width="10%"><?=lang('actions')?></td>
          </tr>
        </thead>
        <tbody>

          <?php
          if ($pages) { ?>
          <?php 
          $nr_pages = count($pages);$i=0;  
          foreach ($pages as $item) {
          $i++;    
          if($item['sub_items']==0) $cl_item = 'sub_items_empty';else $cl_item = 'sub_items_full';
          ?>
          <tr>
            <td style="text-align: center;">         
              <input type="checkbox" name="selected[]" value="<?=$item['id_pages']?>" />
            </td>
            <td class="left" valign="top">                 
             <?php
              if($nivele==0) 
              {    
              ?>               
               <a href="/admin/pages_form/<?=$item['parent']?>/<?=$item['id_pages']?>"  class="none"  ><?=$item['name_'.$base_lang]; ?></a>
              <?php
              }
              else
              {    
              ?>
                <a href="/admin/pages_form/<?=$item['parent']?>/<?=$item['id_pages']?>"  class="none"  ><?=$item['name_'.$base_lang]; ?></a>
              <?php
              }
              ?>                     
                   
                                       

            </td>
            <td class="center">
              <?php
              if($i==1)
              {    
              ?>
                <a href="/admin/pages/<?=$item['parent']?>/<?=$item['id_pages']?>?ac=down" style="margin-left: 20px;" ><img src="<?=base_url()?>admin/img/arr_down.png" title="Coboara cu o pozitie mai sus" /></a>&nbsp;   
              <?php
              }
              elseif($i==$nr_pages)
              {    
              ?>
                <a href="/admin/pages/<?=$item['parent']?>/<?=$item['id_pages']?>?ac=up" style="margin-left: -20px;" ><img src="<?=base_url()?>admin/img/arr_up.png" title="Ridica cu o pozitie mai sus" /></a>
              <?php
              }
              else
              {    
              ?>
                <a href="/admin/pages/<?=$item['parent']?>/<?=$item['id_pages']?>?ac=up" ><img src="<?=base_url()?>admin/img/arr_up.png" title="Ridica cu o pozitie mai sus" /></a>&nbsp;   
                <a href="/admin/pages/<?=$item['parent']?>/<?=$item['id_pages']?>?ac=down" ><img src="<?=base_url()?>admin/img/arr_down.png" title="Coboara cu o pozitie mai sus" /></a>
              <?php
              }
              ?>
            </td>   
            <?php  if($nivele>3) { ?>      
            <td class="center">
                <a href="/admin/pages/<?=$item['id_pages']?>">Submenu (<?=$item['sub_items']; ?>) </a>                                       
            </td>
            <?php } ?>
            <td class="center">      
                <a href="/admin/pages_form/<?=$item['parent']?>/<?=$item['id_pages']?>" ><?=lang('edit')?></a>                          
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
         
    </div>
      
      
  </div>
</form>    
</div>

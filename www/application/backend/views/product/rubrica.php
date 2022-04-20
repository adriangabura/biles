<div class="box">
  <div class="left"></div>
  <div class="right"></div>
  <div class="heading">
    <h1 style="background-image: url('/img/catalog.png');"><a href='/admin/rubrica'><?=lang('rubrici')?></a><?=$crumbs_rubrica_category;?></h1>
<form action="/admin/del_rubrica/<?=$parent?>" method="post" enctype="multipart/form-data" id="form">
    <div class="buttons">
        <span class="button" style="float:right" >
            <input type=submit value="<?=lang('del')?>" onclick="if (confirm('<?=lang('e_sigur_stergi')?>')) return true;else return false;" />
        </span>
        <a href="/admin/rubrica_form/<?=$parent?>" class="button"><span><?=lang('add')?> Rubrica</span></a>
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
            <td class="center" width="10%"><?=lang('categorii')?></td>
            <td class="center" width="10%"><?=lang('actions')?></td>
          </tr>
        </thead>
        <tbody>

          <?php
          if ($rubrica) { ?>
          <?php 
          $nr_rubrica = count($rubrica);$i=0;  
          foreach ($rubrica as $item) {
          $i++;    
          if($item['sub_items']==0) $cl_item = 'sub_items_empty';else $cl_item = 'sub_items_full';
          $item['parent'] = 0;
          ?>
          <tr>
            <td style="text-align: center;">         
              <input type="checkbox" name="selected[]" value="<?=$item['id']?>" />
            </td>
            <td class="left" valign="top"> 
                     <a href="/admin/categoria/<?=$item['id']?>"  class="none <?=$cl_item?>"  ><?=$item['title_ro']; ?></a>
            </td>
            <td class="center">
              <?php
              if($i==1)
              {    
              ?>
                <a href="/admin/rubrica/<?=$item['id']?>?ac=down" style="margin-left: 20px;" ><img src="/img/arr_down.png" title="Coboara cu o pozitie mai sus" /></a>&nbsp;   
              <?php
              }
              elseif($i==$nr_rubrica)
              {    
              ?>
                <a href="/admin/rubrica/<?=$item['id']?>?ac=up" style="margin-left: -20px;" ><img src="/img/arr_up.png" title="Ridica cu o pozitie mai sus" /></a>
              <?php
              }
              else
              {    
              ?>
                <a href="/admin/rubrica/<?=$item['id']?>?ac=up" ><img src="/img/arr_up.png" title="Ridica cu o pozitie mai sus" /></a>&nbsp;   
                <a href="/admin/rubrica/<?=$item['id']?>?ac=down" ><img src="/img/arr_down.png" title="Coboara cu o pozitie mai sus" /></a>
              <?php
              }
              ?>
            </td>            
            <td class="center">
                [ <a href="/admin/categoria/<?=$item['id']?>" class="none" ><?=lang('categorii')?> <?=$item['sub_items']?> </a> ]   
            </td>               
            <td class="center">
                [ <a href="/admin/rubrica_form/<?=$item['id']?>" > <?=lang('edit')?> </a> ]                
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
    
      
      
  </div>
</form>    
</div>

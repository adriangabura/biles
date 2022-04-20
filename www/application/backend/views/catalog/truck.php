<div class="box">
  <div class="left"></div>
  <div class="right"></div>
  <div class="heading">
    <h1 style="background-image: url('/img/trucks.png');"><a href='/admin/truck'><?=lang('truck_marci')?></a><?=$crumbs_truck;?></h1>
<form action="/admin/del_truck/<?=$parent?>" method="post" enctype="multipart/form-data" id="form">
    <div class="buttons">
        <span class="button" style="float:right" >
            <input type=submit value="<?=lang('del')?>" onclick="if (confirm('<?=lang('e_sigur_stergi')?>')) return true;else return false;" />
        </span>
        <a href="/admin/truck_form/<?=$parent?>" class="button"><span><?=lang('add')?></span></a>
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
            <td class="center" width="10%"><?=lang('truck_modele')?></td>
            <td class="center" width="10%"><?=lang('actions')?></td>
          </tr>
        </thead>
        <tbody>

          <?php
          if ($truck) { ?>
          <?php 
          $nr_truck = count($truck);$i=0;  
          foreach ($truck as $item) {
          $i++;    
          if($item['sub_items']==0) $cl_item = 'sub_items_empty';else $cl_item = 'sub_items_full';
          ?>
          <tr>
            <td style="text-align: center;">         
              <input type="checkbox" name="selected[]" value="<?=$item['id']?>" />
            </td>
            <td class="left" valign="top"> 
               <?php
              if($nivele!=1) 
              {    
              ?>               
               <a href="/admin/truck/<?=$item['id']?>"  class="none <?=$cl_item?>"  ><?=$item['title_'.$base_lang]; ?></a>
              <?php
              }
              else
              {    
              ?>
               <a href="/admin/truck_form/<?=$item['parent']?>/<?=$item['id']?>"  class="none"  ><?=$item['title_'.$base_lang]; ?></a>
              <?php
              }
              ?>
            </td>
            <td class="center">
              <?php
              if($i==1)
              {    
              ?>
                <a href="/admin/truck/<?=$item['parent']?>/<?=$item['id']?>?ac=down" style="margin-left: 20px;" ><img src="/img/arr_down.png" title="Coboara cu o pozitie mai sus" /></a>&nbsp;   
              <?php
              }
              elseif($i==$nr_truck)
              {    
              ?>
                <a href="/admin/truck/<?=$item['parent']?>/<?=$item['id']?>?ac=up" style="margin-left: -20px;" ><img src="/img/arr_up.png" title="Ridica cu o pozitie mai sus" /></a>
              <?php
              }
              else
              {    
              ?>
                <a href="/admin/truck/<?=$item['parent']?>/<?=$item['id']?>?ac=up" ><img src="/img/arr_up.png" title="Ridica cu o pozitie mai sus" /></a>&nbsp;   
                <a href="/admin/truck/<?=$item['parent']?>/<?=$item['id']?>?ac=down" ><img src="/img/arr_down.png" title="Coboara cu o pozitie mai sus" /></a>
              <?php
              }
              ?>
            </td>  
            <td class="center">
                [ <a href="/admin/truck/<?=$item['id']?>" ><?=lang('modele')?> <?=$item['sub_items']; ?></a> ]
            </td>            
            <td class="center">
              
                [<a href="/admin/truck_form/<?=$item['parent']?>/<?=$item['id']?>" ><?=lang('edit')?></a>]
              
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

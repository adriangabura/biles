<div class="box">
  <div class="left"></div>
  <div class="right"></div>
  <div class="heading">
    <h1 style="background-image: url('/img/services.png');"><a href='/admin/criteriu_cat'> Criterii = <?=$criteriu_cat['title_ro']?></a> 
    </h1>
<form action="/admin/del_criteriu/<?=$parent?>" method="post" enctype="multipart/form-data" id="form">
    <div class="buttons">
        <span class="button" style="float:right" >
            <input type=submit value="<?=lang('del')?>" onclick="if (confirm('<?=lang('e_sigur_stergi')?>')) return true;else return false;" />
        </span>
        <a href="/admin/criteriu_form/<?=$parent?>" class="button"><span><?=lang('add')?> <?=lang('criteriu')?></span></a>
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
            <td class="center" width="10%"><?=lang('type_crit')?></td>  
            <td class="center" width="10%"><?=lang('actions')?></td>
          </tr>
        </thead>
        <tbody>

          <?php
          if ($criteriu) { ?>
          <?php 
          $nr_criteriu = count($criteriu);$i=0;  
          foreach ($criteriu as $item) {
          $i++;    
          if($item['sub_items']==0) $cl_item = 'sub_items_empty';else $cl_item = 'sub_items_full';
          $item['parent'] = 0;
          ?>
          <tr>
            <td style="text-align: center;">         
              <input type="checkbox" name="selected[]" value="<?=$item['id']?>" />
            </td>
            <td class="left" valign="top"> 
                     <a href="/admin/criteriu_form/<?=$parent?>/<?=$item['id']?>"  class="none"  ><?=$item['title_ro']; ?></a>            
            </td>
           
            <td class="center">
              <?php
              if($i==1)
              {    
              ?>
                <a href="/admin/criteriu/<?=$parent?>/<?=$item['id']?>?ac=down" style="margin-left: 20px;" ><img src="/img/arr_down.png" title="Coboara cu o pozitie mai sus" /></a>&nbsp;   
              <?php
              }
              elseif($i==$nr_criteriu)
              {    
              ?>
                <a href="/admin/criteriu/<?=$parent?>/<?=$item['id']?>?ac=up" style="margin-left: -20px;" ><img src="/img/arr_up.png" title="Ridica cu o pozitie mai sus" /></a>
              <?php
              }
              else
              {    
              ?>
                <a href="/admin/criteriu/<?=$parent?>/<?=$item['id']?>?ac=up" ><img src="/img/arr_up.png" title="Ridica cu o pozitie mai sus" /></a>&nbsp;   
                <a href="/admin/criteriu/<?=$parent?>/<?=$item['id']?>?ac=down" ><img src="/img/arr_down.png" title="Coboara cu o pozitie mai sus" /></a>
              <?php
              }
              ?>
             </td>  
            
             <td class="center"><!-- <?=$item['ord']?> -->
                [ <?=type_crit($item['type'])?> ]              
             </td>             
             <td class="center"><!-- <?=$item['ord']?> -->
                [<a href="/admin/criteriu_form/<?=$parent?>/<?=$item['id']?>" ><?=lang('edit')?></a>]                
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

<div class="box">
  <div class="left"></div>
  <div class="right"></div>
  <div class="heading">
    <h1 style="background-image: url('/img/services.png');">
        <a href='/admin/criteriu_cat'><?=lang('criteriu_categ')?></a> 
    </h1>
<form action="/admin/del_criteriu_cat/<?=$parent?>" method="post" enctype="multipart/form-data" id="form">
    <div class="buttons">
        <span class="button" style="float:right" >
            <input type=submit value="<?=lang('del')?>" onclick="if (confirm('<?=lang('e_sigur_stergi')?>')) return true;else return false;" />
        </span>
        <a href="/admin/criteriu_cat_form/<?=$parent?>" class="button"><span><?=lang('add')?> <?=lang('criteriu_cat')?></span></a>
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
            <td class="center" width="18%"><?=lang('actions')?></td>
          </tr>
        </thead>
        <tbody>

          <?php
          if ($criteriu_cat) { ?>
          <?php 
          $nr_criteriu_cat = count($criteriu_cat);$i=0;  
          foreach ($criteriu_cat as $item) {
          $i++;    
          if($item['sub_items']==0) $cl_item = 'sub_items_empty';else $cl_item = 'sub_items_full';
          $item['parent'] = 0;
          ?>
          <tr>
            <td style="text-align: center;">         
              <input type="checkbox" name="selected[]" value="<?=$item['id']?>" />
            </td>
            <td class="left" valign="top"> 
                     <a href="/admin/criteriu/<?=$item['id']?>"  class="none <?=$cl_item?>"  ><?=$item['title_ro']; ?></a>            
            </td>
                   
             <td class="center"><!-- <?=$item['ord']?> -->
                [<a href="/admin/criteriu_cat_form/<?=$parent?>/<?=$item['id']?>" ><?=lang('edit')?></a>]      
                [<a href="/admin/criteriu/<?=$item['id']?>"    >
                             <?=lang('list_criterii')?>(<?=$item['sub_items']?>) 
                </a>]
                
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

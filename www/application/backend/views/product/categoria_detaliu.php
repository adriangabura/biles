<div class="box">
  <div class="left"></div>
  <div class="right"></div>
  <div class="heading">
    <h1 style="background-image: url('/img/pages.png');">
       <a href='/admin/rubrica/<?=$rubrica['id']?>'> Rubrica = <?=$rubrica['title_ro']?></a> 
        :: <a href='/admin/categoria/<?=$rubrica['id']?>'> Categoria = <?=$categoria['title_ro']?></a>
        <?php
        if($parent_parent)
        {    
        ?>
        :: <a href='/admin/categoria_detaliu/<?=$categoria['id']?>'> Detaliu = <?=$detaliu['title_ro']?></a>
        <?php
        }
        ?>
    </h1>
<form action="/admin/del_categoria_detaliu/<?=$parent?>/<?=$parent_parent?>" method="post" enctype="multipart/form-data" id="form">
    <div class="buttons">
        <span class="button" style="float:right" >
            <input type=submit value="<?=lang('del')?>" onclick="if (confirm('<?=lang('e_sigur_stergi')?>')) return true;else return false;" />
        </span>
        <?php        
        if($categoria['type']==1)
        {    
        ?>
        <a href="/admin/categoria_detaliu_form/<?=$parent?>/<?=$parent_parent?>" class="button"><span><?=lang('add')?> <?=lang('checkb')?></span></a>
        <?php
        }
        elseif($parent_parent and $categoria['type']!=1)
        {    
        ?>
        <a href="/admin/categoria_detaliu_form/<?=$parent?>/<?=$parent_parent?>" class="button"><span><?=lang('add')?> <?=lang('checkb')?></span></a>
        <?php
        }        
        else
        {    
        ?>
        <a href="/admin/categoria_detaliu_form/<?=$parent?>" class="button"><span><?=lang('add')?> <?=lang('checkb_cat')?></span></a>
        <?php
        }
        ?>
        
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
            
            <td class="center" width="10%"><?=lang('actions')?></td>
          </tr>
        </thead>
        <tbody>

          <?php
          // echo "parent=".$parent_parent;
          if ($categoria_detaliu) { ?>
          <?php 
          $nr_categoria_detaliu = count($categoria_detaliu);$i=0;            
          foreach ($categoria_detaliu as $item) {
          $i++;    
          if($item['sub_items']==0) $cl_item = 'sub_items_empty';else $cl_item = 'sub_items_full';
          ?>
          <tr>
            <td style="text-align: center;">         
              <input type="checkbox" name="selected[]" value="<?=$item['id']?>" />
            </td>
            <td class="left" valign="top">                 
               <?php  if($categoria['type']==1) { ?>   
                     <a href="/admin/categoria_detaliu_form/<?=$parent?>/<?=$parent_parent?>/<?=$item['id']?>"  class="none"  >
                       <?=$item['title_'.$base_lang]; ?>
                     </a>                     
               <?php }  
                     elseif($parent_parent==0 and $categoria['type']!=1) 
                     { ?>   
                     <a href="/admin/categoria_detaliu/<?=$categoria['id']?>/<?=$item['id']?>"  class="none <?=$cl_item?>"  ><?=$item['title_'.$base_lang]; ?></a>
               <?php } else { ?>
                     
                     <a href="/admin/categoria_detaliu_form/<?=$parent?>/<?=$parent_parent?>/<?=$item['id']?>"  class="none"  ><?=$item['title_'.$base_lang]; ?></a>                     
               <?php } ?>                      
            </td>
           
            <td class="center"><!-- <?=$item['ord']?> -->
               <?php  if($categoria['type']==1) 
                      {
                   ?>        
                       [<a href="/admin/categoria_detaliu_form/<?=$parent?>/<?=$parent_parent?>/<?=$item['id']?>" ><?=lang('edit')?></a>]               
                <?php } 
                      elseif($parent_parent==0 and $categoria['type']!=1) 
                      {
               ?>
                       [<a href="/admin/categoria_detaliu/<?=$categoria['id']?>/<?=$item['id']?>" ><?=lang('edit')?></a>]               
               <?php        
                      }    
                      else 
                      { ?>
                         [<a href="/admin/categoria_detaliu_form/<?=$parent?>/<?=$parent_parent?>/<?=$item['id']?>" ><?=lang('edit')?></a>]
                <?php } ?>                                
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

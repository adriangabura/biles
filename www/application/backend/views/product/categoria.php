<div class="box">
  <div class="left"></div>
  <div class="right"></div>
  <div class="heading">
    <h1 style="background-image: url('/img/catalog.png');"><a href='/admin/rubrica/<?=$rubrica['id']?>'> Rubrica = <?=$rubrica['title_ro']?></a></h1>
<form action="/admin/del_categoria/<?=$parent?>" method="post" enctype="multipart/form-data" id="form">
    <div class="buttons">
        <span class="button" style="float:right" >
            <input type=submit value="<?=lang('del')?>" onclick="if (confirm('<?=lang('e_sigur_stergi')?>')) return true;else return false;" />
        </span>
        <a href="/admin/categoria_form/<?=$parent?>" class="button"><span><?=lang('add')?> Categoria</span></a>
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
              <td class="center" width="14%"><?=lang('type_det_sup')?></td>
              <td class="center" width="18%"><?=lang('criterii')?></td>
              <td class="center" width="10%"><?=lang('actions')?></td>
          </tr>
        </thead>
        <tbody>

          <?php
          if ($categoria) { ?>
          <?php 
          $nr_categoria = count($categoria);$i=0;  
          foreach ($categoria as $item) {
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
            <td class="center">
              <?php
              if($i==1)
              {    
              ?>
                <a href="/admin/categoria/<?=$parent?>/<?=$item['id']?>?ac=down" style="margin-left: 20px;" ><img src="/img/arr_down.png" title="Coboara cu o pozitie mai sus" /></a>&nbsp;   
              <?php
              }
              elseif($i==$nr_categoria)
              {    
              ?>
                <a href="/admin/categoria/<?=$parent?>/<?=$item['id']?>?ac=up" style="margin-left: -20px;" ><img src="/img/arr_up.png" title="Ridica cu o pozitie mai sus" /></a>
              <?php
              }
              else
              {    
              ?>
                <a href="/admin/categoria/<?=$parent?>/<?=$item['id']?>?ac=up" ><img src="/img/arr_up.png" title="Ridica cu o pozitie mai sus" /></a>&nbsp;   
                <a href="/admin/categoria/<?=$parent?>/<?=$item['id']?>?ac=down" ><img src="/img/arr_down.png" title="Coboara cu o pozitie mai sus" /></a>
              <?php
              }
              ?>
             </td>
             <td class="center">                                
                [<a href="/admin/categoria_detaliu/<?=$item['id']?>"  class="none" > <?=type_detalii($item['type'])?>  <?=$item['detalii']?></a> ]
             </td>               
             <td class="center">  
                <?php
                if($item['sub_items'])
                {    
                ?>
                [<a href="/admin/criteriu/<?=$item['criteriu_cat_id']?>" target="_blank" class="none" > <?=lang('criterii')?> <?=$item['sub_items']?> </a>]
                <?php
                }
                else
                {    
                ?>
                [<a href="/admin/categoria_form/<?=$parent?>/<?=$item['id']?>" > Atribuie o categorie de criterii </a>]                
                <?php
                }
                ?>
             </td>             
             <td class="center">                 
                [<a href="/admin/categoria_form/<?=$parent?>/<?=$item['id']?>" ><?=lang('edit')?></a>]                
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

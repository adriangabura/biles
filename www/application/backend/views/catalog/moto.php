<div class="box">
  <div class="left"></div>
  <div class="right"></div>
  <div class="heading">
    <h1 style="background-image: url('/admin/img/abonati.png');"><a href='/admin/moto'>Staff</a><?=$crumbs_moto;?></h1>
<form action="/admin/del_moto/<?=$parent?>" method="post" enctype="multipart/form-data" id="form">
    <div class="buttons">
        <?php if ($nivele > 0) { ?>
        <span class="button" style="float:right" >
            <input type=submit value="<?=lang('del')?>" onclick="if (confirm('<?=lang('e_sigur_stergi')?>')) return true;else return false;" />
        </span>
        <a href="/admin/moto_form/<?=$parent?>" class="button"><span><?=lang('add')?></span></a>
        <?php } ?>
    </div>
  </div>
  <div class="content">

      <table class="list">
        <thead>
          <tr>
            <td width="1" style="text-align: center;">
                <input type="checkbox" onclick="$('input[name*=\'selected\']').attr('checked', this.checked);" />
            </td>
             <?php if($nivele !=0) { ?>
             <td class="left" width="5%"><?=lang('photo')?></td><?php } ?>
            <td class="left"><?=lang('name')?></td>
           <td class="center" width="10%">Order</td>
            <td class="center" width="10%"><?=lang('actions')?></td>
          </tr>
        </thead>
        <tbody>

          <?php
          if ($moto) { ?>
          <?php 
          $nr_moto = count($moto);$i=0;  
          foreach ($moto as $item) {
          $i++;    
          if($item['sub_items']==0) $cl_item = 'sub_items_empty';else $cl_item = 'sub_items_full';
          ?>
          <tr>
            <td style="text-align: center;">         
              <input type="checkbox" name="selected[]" value="<?=$item['id']?>" />
            </td>
            <?php if($nivele !=0) { ?>
             <td class="left" valign="top">
                <a href="/admin/moto_form/<?=$item['parent']?>/<?=$item['id']?>" class="none" >  
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
                        <img src="/img/no_image.jpg" width="100"  height="75" />                         
                    <?php
                    }
                    ?>
                </a>
            </td> 
           			
            <?php } ?>
            <td class="left" valign="top"> 
               <?php
              if($nivele!=1) 
              {    
              ?>               
               <a href="/admin/moto/<?=$item['id']?>"  class="none <?=$cl_item?>"  ><?=$item['title_'.$base_lang]; ?></a>
              <?php
              }
              else
              {    
              ?>
               <a href="/admin/moto_form/<?=$item['parent']?>/<?=$item['id']?>"  class="none"  ><?=$item['title_'.$base_lang]; ?></a>
              <?php
              }
              ?>
            </td>
              <td class="center">
              <?php
              if($i==1)
              {    
              ?>
                <a href="/admin/moto/<?=$item['parent']?>/<?=$item['id']?>?ac=down" style="margin-left: 20px;" ><img src="/admin/img/arr_down.png" title="Coboara cu o pozitie mai sus" /></a>&nbsp;   
              <?php
              }
              elseif($i==$nr_moto)
              {    
              ?>
                <a href="/admin/moto/<?=$item['parent']?>/<?=$item['id']?>?ac=up" style="margin-left: -20px;" ><img src="/admin/img/arr_up.png" title="Ridica cu o pozitie mai sus" /></a>
              <?php
              }
              else
              {    
              ?>
                <a href="/admin/moto/<?=$item['parent']?>/<?=$item['id']?>?ac=up" ><img src="/admin/img/arr_up.png" title="Ridica cu o pozitie mai sus" /></a>&nbsp;   
                <a href="/admin/moto/<?=$item['parent']?>/<?=$item['id']?>?ac=down" ><img src="/admin/img/arr_down.png" title="Coboara cu o pozitie mai sus" /></a>
              <?php
              }
              ?>
            </td>
            <td class="center">
              
                [<a href="/admin/moto_form/<?=$item['parent']?>/<?=$item['id']?>" ><?=lang('edit')?></a>]<br>
                <?php if ($nivele == 0) { ?>
                [ <a href="/admin/moto/<?=$item['id']?>" >Records <?=$item['sub_items']; ?></a> ]
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
          <?=$pagination?>
    </div>
      
      
  </div>
</form>    
</div>

<div class="box">
  <div class="left"></div>
  <div class="right"></div>
  <div class="heading">
    <h1 style="background-image: url('/admin/img/services.png');"><a href='/admin/text'>Info</a><?=$crumbs_text;?></h1>
<form action="/admin/del_text/<?=$parent?>" method="post" enctype="multipart/form-data" id="form">
    <div class="buttons">
      <span class="button" style="float:right" >
            <input type=submit value="<?=lang('del')?>" onclick="if (confirm('<?=lang('e_sigur_stergi')?>')) return true;else return false;" />
        </span>
        <a href="/admin/text_form/<?=$parent?>" class="button"><span><?=lang('add')?></span></a>
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
<!--            <td class="center" width="5%"><?=lang('ordonare')?></td>-->
            <td class="center" width="10%"><?=lang('actions')?></td>
          </tr>
        </thead>
        <tbody>

          <?php if ($text) { ?>
          <?php
          $nr_text = count($text);$i=0; 
          foreach ($text as $item) { 
		  
              $i++;  ?>
          <tr>  
               <td style="text-align: center;">         
              <input type="checkbox" name="selected[]" value="<?=$item['id']?>" />
            </td>
            <td class="left" valign="top">
                <a href="/admin/text_form/<?=$item['parent']?>/<?=$item['id']?>" class="none" >
                    <?=$item['name_'.$base_lang]; ?>                   
                </a>
            </td>
<!--            <td class="center">
              <?php
              if($i==1)
              {    
              ?>
                <a href="/admin/text/<?=$item['parent']?>/<?=$item['id']?>?ac=down" style="margin-left: 20px;" ><img src="/img/arr_down.png" title="Coboara cu o pozitie mai sus" /></a>&nbsp;   
              <?php
              }
              elseif($i==$nr_text)
              {    
              ?>
                <a href="/admin/text/<?=$item['parent']?>/<?=$item['id']?>?ac=up" style="margin-left: -20px;" ><img src="/img/arr_up.png" title="Ridica cu o pozitie mai sus" /></a>
              <?php
              }
              else
              {    
              ?>
                <a href="/admin/text/<?=$item['parent']?>/<?=$item['id']?>?ac=up" ><img src="/img/arr_up.png" title="Ridica cu o pozitie mai sus" /></a>&nbsp;   
                <a href="/admin/text/<?=$item['parent']?>/<?=$item['id']?>?ac=down" ><img src="/img/arr_down.png" title="Coboara cu o pozitie mai sus" /></a>
              <?php
              }
              ?>
            </td>            -->
            <td class="center">
                [<a href="/admin/text_form/<?=$item['parent']?>/<?=$item['id']?>" ><?=lang('edit')?></a>]
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

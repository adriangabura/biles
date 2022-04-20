<div class="box">
  <div class="left"></div>
  <div class="right"></div>
  <div class="heading">
    <h1 style="background-image: url('/img/events.png');"><a href='/admin/anunturi'><?=lang('anunturi')?></a></h1>
<form action="/admin/del_anunturi/<?=$parent?>" method="post" enctype="multipart/form-data" id="form">
    <div class="buttons">
    
      <span class="button" style="float:right" >
            <input type=submit value="<?=lang('del')?>" onclick="if (confirm('<?=lang('e_sigur_stergi')?>')) return true;else return false;" />
        </span>
    <!--
        <a href="/admin/anunturi_form/<?=$parent?>" class="button"><span><?=lang('add')?></span></a>
       --> 
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
            <td class="center" width="15%">Category</td>
            <td class="center" width="15%">User</td>
            <td class="center" width="10%"><?=lang('actions')?></td>
          </tr>
        </thead>
        <tbody>

          <?php        
          if ($anunturi) { ?>
          <?php 
          $nr_anunturi = count($anunturi);$i=0;  
          foreach ($anunturi as $item) {
          $i++;    
         $cl_item = 'sub_items_empty';
          ?>
          <tr>
            <td style="text-align: center;">         
              <input type="checkbox" name="selected[]" value="<?=$item['id']?>" />
            </td>
            <td class="left" valign="top">                 
                <a href="/admin/anunturi/<?=$parent?>/<?=$item['id']?>"   class="none"   ><?=$item['title']?></a>
            </td>            
            </td>
            <td class="center">
                <?php foreach ($categorii as $cat) {
                    if($item['categoria_id'] == $cat['criteriu_cat_id']) {
                        echo $cat['title_ro'];
                    } 
                } ?>
            </td>            
            <td class="center">
                [<?php foreach ($users as $user) {
                    if($item['user_id'] == $user['id']) {
                        echo $user['first_name'].' '.$user['last_name'].' ';
                    } 
                } ?> ]<br>
                [ <?php foreach ($users as $user) {
                    if($item['user_id'] == $user['id']) {
                        echo $user['email'];
                    } 
                } ?> ]
            </td>
            
            <td class="center">
                
                <?php $cat_url = $this->mysql->get_row('categoria',array('id' => $item['categoria_id'])); ?>
                <?php $rubrica_url = $this->mysql->get_row('rubrica',array('id' => $cat_url['rubrica_id'])); ?>
                <?php $link = "/$rubrica_url[url]/$cat_url[url]/$item[url]-$item[id].html";?>
<!--                [ <a href="/admin/anunturi_form/<?=$parent?>/<?=$item['id']?>" >Vizualizeaza</a> ]-->
                [ <a href="<?=$link?>" target="_blank" >View ad</a> ]
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

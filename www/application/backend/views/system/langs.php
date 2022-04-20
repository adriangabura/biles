<div class="box">
  <div class="left"></div>
  <div class="right"></div>
  <div class="heading">
    <h1 style="background-image: url('/img/language.png');"><?=lang('langs')?></h1>
<form action="/admin/del_langs" method="post" enctype="multipart/form-data" id="form">     
    <div class="buttons">
        <span class="button" style="float:right" >
            <input type=submit value="<?=lang('del')?>" onclick="if (confirm('<?=lang('e_sigur_stergi')?>')) return true;else return false;" />
        </span>              
        <a href="/admin/langs_form" class="button"><span><?=lang('add')?></span></a>
    </div>
  </div>
  <div class="content"> 
    
      <table class="list">
        <thead>
          <tr>
<!--            <td width="1" style="text-align: center;"><input type="checkbox" onclick="$('input[name*=\'selected\']').attr('checked', this.checked);" /></td>-->
            <td class="left"><?=lang('name')?></td>  
            <td class="left"><?=lang('code')?></td>  
            <td class="center" style="width:6%">Default</td>   
<!--            <td class="center" style="width:6%"><?=lang('actions')?></td>-->
          </tr>
        </thead>
        <tbody>
          <?php foreach ($langs as $lang) { ?>
          <tr>
<!--            <td style="text-align: center;">
              <input type="checkbox" <?php if($lang['id_langs']==1) echo "disabled";?> name="<?php if($lang['id_langs']>1) echo "selected[]";?>" value="<?php echo $lang['id_langs']; ?>"/>
            </td>-->
            <td class="left"><a class="none" <?php if($lang['id_langs']>1) {?> href="/admin/langs_form/<?=$lang['id_langs']?>" <?php }?> ><?php echo $lang['name']; ?></a></td>
            <td class="left"><?php echo $lang['ext']; ?></td> 
            <td class="center">
                <input type="radio" name="lang_change" <?=($lang['default']==1?'checked':'')?> onclick="window.location.href='/admin/change_lang/<?=$lang['id_langs']?>'">
            </td>   
<!--            <td class="right">
            <?php if($lang['id_langs']>1){ ?>
              [ <a href="/admin/langs_form/<?=$lang['id_langs']?>"><?=lang('edit')?></a> ]
              <?php } ?></td>-->
          </tr>
          <?php } ?>         
        </tbody>
      </table>
    
  </div>
</form>  
</div>
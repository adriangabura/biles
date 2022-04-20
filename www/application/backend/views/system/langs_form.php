
<div class="box">
  <div class="left"></div>
  <div class="right"></div>
  <div class="heading">
    <h1 style="background-image: url('/img/language.png');"><?php echo $heading_title; ?></h1>
<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">    
    <div class="buttons">
        <span class="button" style="float:left" ><input type=submit value="<?=lang('save')?>" /></span>
        <input type="hidden" name="id" value="<?=((isset($lang['id_langs'])=="")?"":"$lang[id_langs]")?>" />
        <a href="/admin/langs" style="float:left" class="button"><span><?php echo lang('cancel'); ?></span></a>
    </div>
  </div>
  <div class="content">
      <table class="form">
        <tr>
          <td><span class="required">*</span> <?php echo lang('name'); ?>:</td>
          <td>
              <input type="text" name="name" class="request" value="<?=((isset($lang['name'])=="")?set_value("name"):set_value("name",$lang["name"]))?>" />
              <?=form_error('name', '<span class="error">', '</span>');?>          </td>
        </tr>
        <tr>
          <td>
              <span class="required">*</span> <?php echo lang('ext'); ?>:
              <br /><span class="help"><?=lang('ext_help')?></span>
          </td>
          <td>
             <input type="text" name="ext"  class="request" value="<?=((isset($lang['ext'])=="")?set_value("ext"):set_value("ext",$lang["ext"]))?>" />
             <?=form_error('ext', '<span class="error">', '</span>');?>
          </td>
        </tr>
        <tr>
          <td><?php echo lang('status'); ?>:</td>
          <td><?=$status?></td>
        </tr>
      </table>
  </div>
</form>
  
</div>
<div class="box">
  <div class="left"></div>
  <div class="right"></div>
  <div class="heading">
    <h1 style="background-image: url('/img/user_group.png');"><?=$title_page?></h1>
<form action="<?php echo $action; ?>" onsubmit="return check_field('<?=lang('input_request')?>')"  method="post" enctype="multipart/form-data" id="form">    
    <div class="buttons">
        <span class="button" style="float:left" ><input type=submit value="<?=lang('save')?>" /></span>
        <input type="hidden" name="id" value="<?=((isset($user['id_auth_group'])=="")?"":"$user[id_auth_group]")?>" />
        <a href="/admin/users_group" style="float:left" class="button"><span><?php echo lang('cancel'); ?></span></a>
    </div>
  </div>  
  <div class="content">
      <table class="form">
        <tr>
          <td><span class="required">*</span> <?=lang('name_users_group')?></td>
          <td>
              <input type="text" name="name" class="request" value="<?=((isset($user['name'])=="")?"":$user['name'])?>" />
          </td>
        </tr>
        <tr>
          <td valign="top"><?=lang('permission')?> :</td>
          <td>
            <div class="scrollbox" id="scrollbox" style="height:300px">
              <?php $class = 'odd'; ?>
              <?php 
              if(isset($user['permission'])) $access = json_decode($user['permission']);          
              foreach ($permissions as $permission) { ?>
              <?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
              <div class="<?php echo $class; ?>">
                <?php { ?>
                <?php if(isset($access) and in_array($permission['func'], $access)) $check =' checked=checked ';else $check = '';?>  
                <input type="checkbox" name="permission[]" <?=$check?> value="<?php echo $permission['func']; ?>" />
                <?php echo $permission['name']; ?>
                <?php } ?>
              </div>
              <?php } ?>
            </div>
            <span>
                <a onclick="$('#scrollbox :checkbox').attr('checked', 'checked');"><u>Select All</u></a> /
                <a onclick="$('#scrollbox :checkbox').attr('checked', '');"><u>Unselect All</u></a>
            </span>              
          </td>
        </tr>
      </table>
    </form>
  </div>
</div>
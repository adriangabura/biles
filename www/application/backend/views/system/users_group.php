<div class="box">
  <div class="left"></div>
  <div class="right"></div>
  <div class="heading">
    <h1 style="background-image: url('/img/user_group.png');"><?=lang('users_group')?></h1>
<form action="/admin/del_users_group" method="post" enctype="multipart/form-data" id="form">      
    <div class="buttons">
        <span class="button" style="float:right" >
            <input type=submit value="<?=lang('del')?>" onclick="if (confirm('<?=lang('e_sigur_stergi_users_group')?>')) return true;else return false;" />
        </span>
        <a href="/admin/users_group_form" class="button"><span><?=lang('add_users_group')?></span></a>
    </div>
  </div>
  <div class="content"> 
      <table class="list">
        <thead>
          <tr>
            <td width="1" style="text-align: center;"><input type="checkbox" onclick="$('input[name*=\'selected\']').attr('checked', this.checked);" /></td>
            <td class="left"><?=lang('name_users_group')?></td>
            <td class="center" width="5%"><?=lang('actions')?></td>
          </tr>
        </thead>
        <tbody>
          <?php if ($users_group) {             
          ?>
          <?php foreach ($users_group as $user) { ?>
          <tr>
            <td style="text-align: center;"><input type="checkbox" <?php if($user['id_auth_group']==1 or $user['id_auth_group']==2 or $user['id_auth_group']==3) echo "disabled";?> name="<?php if($user['id_auth_group']>1) echo "selected[]";?>" value="<?=$user['id_auth_group']?>" /></td>
            <td class="left"><a href="/admin/users_group_form/<?=$user['id_auth_group']?>" class="none" ><?php echo $user['name']; ?></a></td>
            <td class="right">[<a href="/admin/users_group_form/<?=$user['id_auth_group']?>" ><?=lang('edit')?></a>]</td>
          </tr>
          <?php } ?>
          <?php } else { ?>
          <tr>
            <td class="center" colspan="5"><?=lang('no_users_group')?></td>
          </tr>
          <?php } ?>
        </tbody>
      </table>
    </form>
  </div>
</div>

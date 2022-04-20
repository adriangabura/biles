<div class="box">
  <div class="left"></div>
  <div class="right"></div>
  <div class="heading">
    <h1 style="background-image: url('/img/user.png');"><?=lang('users')?></h1>
<form action="/admin/del_users" method="post" enctype="multipart/form-data" id="form">      
    <div class="buttons">
        <span class="button" style="float:right" >
            <input type=submit value="<?=lang('del')?>" onclick="if (confirm('<?=lang('e_sigur_stergi')?>')) return true;else return false;" />
        </span>
        <a href="/admin/users_form" class="button"><span><?=lang('add_user')?></span></a>
    </div>
  </div>
  <div class="content"> 
      <table class="list">
        <thead>
          <tr>
            <td width="1" style="text-align: center;"><input type="checkbox" onclick="$('input[name*=\'selected\']').attr('checked', this.checked);" /></td>
            <td class="left"><?=lang('username')?></td>
            <td class="left"><?=lang('fname')?></td>
            <td class="left"><?=lang('lname')?></td>
            <td class="left"><?=lang('mail')?></td>
           
            <td class="left"><?=lang('users_group')?></td>
          
            <td class="left"><?=lang('date_add')?></td>
            <td class="center" width="5%"><?=lang('actions')?></td>
          </tr>
        </thead>
        <tbody>
          <?php if ($users) { ?>
          <?php foreach ($users as $user) { ?>
          <tr>
            <td style="text-align: center;"><input type="checkbox" <?php if($user['id']==1) echo "disabled";?> name="<?php if($user['id']>1) echo "selected[]";?>" name="selected[]" value="<?=$user['id']?>" /></td>
            <td class="left"><a href="/admin/users_form/<?=$user['id']?>" class="none" ><?php echo $user['username']; ?></a></td>
            <td class="left"><?php echo $user['first_name']; ?></td>
            <td class="left"><?php echo $user['last_name']; ?></td>
            <td class="left"><?php echo $user['email']; ?></td>            
            <td class="left"><?php echo $user['name']; ?></td>
          
            <td class="left"><?php echo $user['date_joined']; ?></td>
            <td class="right">[<a href="/admin/users_form/<?=$user['id']?>" ><?=lang('edit')?></a>]</td>
          </tr>
          <?php } ?>
          <?php } else { ?>
          <tr>
            <td class="center" colspan="5"><?=lang('no_users')?></td>
          </tr>
          <?php } ?>
        </tbody>
      </table>
    </form>
  </div>
</div>
<div class="pagination">
 <div class="results">Showing 3 to 4 of 18 (9 Pages)</div>    
</div>
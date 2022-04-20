<div class="box">
  <div class="left"></div>
  <div class="right"></div>
  <div class="heading">
    <h1 style="background-image: url('/img/user.png');"><?php echo $title_page; ?></h1>
<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">    
    <div class="buttons">
        <span class="button" style="float:left" ><input type=submit value="<?=lang('save')?>" /></span>
        <input type="hidden" name="id" value="<?=((isset($user['id'])=="")?"":"$user[id]")?>" />
        <a href="/admin/users_site" style="float:left" class="button"><span><?php echo lang('cancel'); ?></span></a>
        <input type="hidden" name="redir" value="<?=$redir?>" />
    </div>
  </div>
  <div class="content">
      <table class="form">
        <tr>
          <td><span class="required">*</span> <?=lang('username')?> :</td>
          <td>
            <input type="text" name="username" class="request" value="<?=((isset($user['username'])=="")?set_value("username"):set_value("username",$user["username"]))?>" />            
            <?=form_error('username', '<span class="error">', '</span>');?> 
          </td>
        </tr>
        <tr>
          <td><?=lang('fname')?> :</td>
          <td>
             <input type="text" name="fname" value="<?=((isset($user['first_name'])=="")?set_value("fname"):set_value("fname",$user["first_name"]))?>" />
             <?=form_error('fname', '<span class="error">', '</span>');?> 
          </td>
        </tr>
        <tr>
          <td><?=lang('lname')?> :</td>
          <td>
              <input type="text" name="lname"  value="<?=((isset($user['last_name'])=="")?set_value("lname"):set_value("lname",$user["last_name"]))?>" />
              <?=form_error('lname', '<span class="error">', '</span>');?> 
          </td>
        </tr>
        <tr>
          <td><span class="required">*</span> <?=lang('mail')?> :</td>
          <td>
              <input type="text" name="mail" class="request" value="<?=((isset($user['email'])=="")?set_value("mail"):set_value("mail",$user["email"]))?>" />
              <?=form_error('mail', '<span class="error">', '</span>');?> 
          </td>
        </tr>
        <?php
        if(isset($pwd) and $pwd=='no')
        {
        ?>
        <tr>
          <td>
             <?=lang('change_pwd')?> :
             <br /><span class="help"><?=lang('change_pwd_help')?></span> 
            
          </td>
          <td>
              <a href="/admin/users_site_form/<?=$user['id']?>/1/1"><?=lang('change_pwd_now')?></a>
          </td>
        </tr>        
        <?php
        }
        else
        {    
        ?>
        <tr>
          <td>
             <?=lang('pwd')?> :
             <br /><span class="help"><?=lang('pwd_help')?></span>             
          </td>
          <td>
             <input type="password" name="pwd" class="pwd" value="<?=set_value("pwd")?>" />
             <?=form_error('pwd', '<span class="error">', '</span>');?> 
              <input type="hidden" value ="1" name="change_pwd" />
          </td>
        </tr>
        <tr>
          <td>
              <?=lang('conf_pwd')?> :
              <br /><span class="help"><?=lang('conf_pwd_help')?></span>
          </td>
          <td>
             <input type="password" name="conf_pwd" class="conf_pwd" value="<?=set_value("conf_pwd")?>" />
             <?=form_error('conf_pwd', '<span class="error">', '</span>');?> 
          </td>
        </tr>
        <?php
        }
        ?>
        <!--
        <tr>
          <td>
              <?php echo lang('status'); ?> :
              <br /><span class="help"><?=lang('status_help')?></span>     
          </td>
          <td>
             <!-- 
             <select name="status">
                 <option value="1" <?=((isset($user['is_active'])=="")?set_select('status',1,TRUE):set_select('status',$user["is_active"]))?>  ><?=lang('enable')?></option>
                 <option value="2" <?=((isset($user['is_active'])=="")?set_select('status',2):set_select('status',$user["is_active"]))?> ><?=lang('disable')?></option>
             </select>              
             <input type="checkbox" name="is_active" value="1" <?php if(isset($user['is_active']) and $user['is_active']==1) echo set_checkbox('is_active',$user['is_active'],TRUE);else echo set_checkbox('is_active',1);?> />                          
          </td>
        </tr>
        <tr>
          <td>
              <?php echo lang('is_staff'); ?> :
              <br /><span class="help"><?=lang('is_staff_help')?></span>     
          </td>
          <td>            
              <input type="checkbox" name="is_staff" value="1" <?php if(isset($user['is_staff']) and $user['is_staff']==1) echo set_checkbox('is_staff',$user['is_staff'],TRUE);else echo set_checkbox('is_staff',1);?> />             
          </td>
        </tr>
        <tr>
          <td>
              <?php echo lang('is_superuser'); ?> :
              <br /><span class="help"><?=lang('is_superuser_help')?></span>     
          </td>
          <td>            
              <input type="checkbox" name="is_superuser" value="1"  <?php if(isset($user['is_superuser']) and $user['is_superuser']==1) echo set_checkbox('is_staff',$user['is_superuser'],TRUE);else echo set_checkbox('is_superuser',1);?> />             
          </td>
        </tr>
             -->

      </table>
    </form>
  </div>
</div>
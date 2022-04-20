<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title><?=lang('page_title_login')?></title>
    <?php
    $meta = array(
        array('name' => 'Content-type', 'content' => 'text/html; charset=utf-8', 'type' => 'equiv'),
        array('name' => 'description', 'content' => 'All by Spy'),
        array('name' => 'keywords', 'content' => 'Codeigniter ,Jquery ,Ajax')
    );
    echo meta($meta);
    echo link_tag('/admin/css/style.css');
    echo link_tag('favicon.ico','shortcut icon','img/favicon.ico');
    ?>
    <script type="text/javascript" src="<?=base_url()?>js/jquery/ui/themes/ui-lightness/ui.all.css"></script>
    <script type="text/javascript" src="<?=base_url()?>js/jquery/jquery-1.3.2.min.js"></script>
    <script type="text/javascript" src="<?=base_url()?>js/jquery/ui/ui.core.js"></script>
    <script type="text/javascript" src="<?=base_url()?>js/jquery/superfish/js/superfish.js"></script>
    <script type="text/javascript" src="<?=base_url()?>js/jquery/tab.js"></script>


</head>
<body>
<div id="container">
  <div id="header">
    <div class="div1">Admin</div>
  </div>
  <div id="content">
            <div class="box" style="width: 325px; min-height: 300px; margin-top: 40px; margin-left: auto; margin-right: auto;">
              <div class="left"></div>
              <div class="right"></div>
              <div class="heading">
                <h1 style="background-image: url('/admin/img/lockscreen.png');"><?=lang('login_form_title')?></h1>
              </div>
              
              <div class="content" style="min-height: 150px;">
                <?php if($this->session->flashdata('eroare')) { ?>
                <div class="warning" style="padding: 3px;padding-left:30px"><?php echo $this->session->flashdata('eroare'); ?></div>
                <?php } ?>
                <?=form_error('user_name', '<div class="warning" style="padding: 3px;padding-left:30px">', '</div>');?> 
                <?=form_error('pwd', '<div class="warning" style="padding: 3px;padding-left:30px">', '</div>');?> 
                <form action="/admin/login" method="post" enctype="multipart/form-data" id="form">
                  <table style="width: 100%;">
                    <tr>
                      <td style="text-align: center;" rowspan="4"><img src="/admin/img/login.png" alt="" /></td>
                    </tr>
                    <tr>
                      <td><?=lang('username')?>:<br />
                        <input type="text" name="user_name" value="" style="margin-top: 4px;" />
                        <br />
                        <br />
                        <?=lang('pwd')?>:<br />
                        <input type="password" name="pwd" value="" style="margin-top: 4px;" /></td>
                    </tr>
                    <tr>
                      <td>&nbsp;</td>
                    </tr>
                    <tr>
                      <td style="text-align: right;">
                        <span  class="button" >
                          <input type="submit" name="" class="button" value="<?=lang('auth_admin')?>" id="submit"/> 
                        </span>  
                      </td>   
                    </tr>
                  </table>

                </form>

              </div>
            </div>
   </div> 
</div>    

  <div id="footer">
		© 2011 All Rights Reserved.</div>

</body>
</html>
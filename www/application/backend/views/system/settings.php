<style>
    .ta_ra {
        width: 415px;
        float: left;
        margin-right: 27px;
        margin-top: 35px;
    }
</style>
<div class="box">
  <div class="left"></div>
  <div class="right"></div>
  <div class="heading">
    <h1 style="background-image: url('/img/setting.png')"><?=lang('settings')?></h1>
<form action="<?php echo $action; ?>" onsubmit="return check_field('<?=lang('input_request')?>','')" method="post" enctype="multipart/form-data" id="form">    
    <div class="buttons">
        <span class="button" style="float:left" ><input type=submit value="<?=lang('save')?>" /></span>
        <input type="hidden" name="id" value="1" />
        <a href="/admin/settings" style="float:left" class="button"><span><?php echo lang('cancel'); ?></span></a>
    </div>
  </div>
  <div class="content">
      <table class="form">
        <tr>
          <td><span class="required">*</span> <?php echo lang('site_name'); ?>:</td>
          <td>
              <input type="text" name="settings[][site_name]" class="request" value="<?=((isset($settings['site_name'])=="")?"":$settings["site_name"])?>" />
          </td>
        </tr>
        <tr>
          <td><span class="required">*</span> <?php echo lang('company'); ?>:</td>
          <td>
              <input type="text" name="settings[][company]" class="request" value="<?=((isset($settings['company'])=="")?"":$settings["company"])?>" />
          </td>
        </tr>
        <tr>
          <td><span class="required">*</span> <?php echo lang('mail'); ?>:</td>
          <td>
              <input type="text" name="settings[][mail]" class="request" value="<?=((isset($settings['mail'])=="")?"":$settings["mail"])?>" />
          </td>
        </tr>
        <tr>
          <td><span class="required">*</span> Facebook Link:</td>
          <td>
              <input type="text" name="settings[][facebook]" class="request" value="<?=((isset($settings['facebook'])=="")?"":$settings["facebook"])?>" />
          </td>
        </tr>
       -  <tr>
          <td><span class="required">*</span> Skype ac. name:</td>
          <td>
              <input type="text" name="settings[][twiter]" class="request" value="<?=((isset($settings['twiter'])=="")?"":$settings["twiter"])?>" />
          </td>
        </tr>
         <tr>
          <td><span class="required">*</span> OK Link:</td>
          <td>
              <input type="text" name="settings[][google]" class="request" value="<?=((isset($settings['google'])=="")?"":$settings["google"])?>" />
          </td>
        </tr>
        <!-- <tr>
          <td><span class="required">*</span> Linkedin Link:</td>
          <td>
              <input type="text" name="settings[][linkedin]" class="request" value="<?=((isset($settings['linkedin'])=="")?"":$settings["linkedin"])?>" />
          </td>
        </tr>-->
        <tr>
          <td><span class="required">*</span> <?php echo lang('per_page_admin'); ?>:</td>
          <td>
              <input type="text" name="settings[][per_page_admin]" class="request" value="<?=((isset($settings['per_page_admin'])=="")?"":$settings["per_page_admin"])?>" />
          </td>
        </tr>
        <tr>
          <td><span class="required">*</span> <?php echo lang('per_page_site'); ?>:</td>
          <td>
              <input type="text" name="settings[][per_page_site]" class="request" value="<?=((isset($settings['per_page_site'])=="")?"":$settings["per_page_site"])?>" />
          </td>
        </tr>
         <tr>
          <td> Editeaza Administrator:</td>
          <td>
         <a href="/admin/users_form/1">Editeaza</a>
          </td>
        </tr>
      </table>
      <div id="tab_general">
        <div id="languages" class="htabs">
            <ul>   
          <?php foreach ($langs as $lang) { ?>
                <li>  
                   <a href="#langs<?php echo $lang['id_langs']; ?>">
                    <img src="/admin/img/flags/<?php echo $lang['image']; ?>" /> <?php echo $lang['name']; ?>
                   </a>
                </li>    
	   <?php } ?>
            </ul>    
        </div>
        <div class="panes">    
        <?php foreach ($langs as $lang) { ?>        
         <div>
          <table class="form">
            <!-- meta taguri -->
             <tr>
              <td>Title Tag</td>
              <td>
                 <input name="settings[<?=$lang['id_langs']?>][title_<?=$lang['ext']?>]" value="<?=((isset($settings['title_'.$lang['ext']])=="")?"":$settings['title_'.$lang['ext']])?>"  class="request" size="100" value="" />                
              </td>
            </tr>            
             <tr>
              <td><?=lang('keywords')?></td>
              <td>
                 <input name="settings[<?=$lang['id_langs']?>][keywords_<?=$lang['ext']?>]" value="<?=((isset($settings['keywords_'.$lang['ext']])=="")?"":$settings['keywords_'.$lang['ext']])?>"  class="request" size="100" value="" />                
              </td>
            </tr>
             <tr>
              <td><?=lang('description')?></td>
              <td>
                 <input name="settings[<?=$lang['id_langs']?>][description_<?=$lang['ext']?>]" value="<?=((isset($settings['description_'.$lang['ext']])=="")?"":$settings['description_'.$lang['ext']])?>"  class="request" size="100" value="" />                
              </td>
            </tr>
            <!-- end meta taguri -->            
          </table>
         </div>            
	<?php } ?>
       </div><!-- end panes -->   
          
      </div>
      
     
  </div>
</form>
  
</div>
<script type="text/javascript">
    
$(document).ready(function() {    
   $("#languages ul").tabs("div.panes > div"); 
  
}); 

  
</script>

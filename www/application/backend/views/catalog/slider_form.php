  <link rel="stylesheet" href="<?=base_url()?>css/smoothness/jquery-ui-1.10.2.custom.css" />

<div class="box">
  <div class="left"></div>
  <div class="right"></div>
  <div class="heading">
    <h1 style="background-image: url('admin/img/news.png');"><?=lang('slider')?></h1>
<form action="<?=$action; ?>" onsubmit="return check_field('<?=lang('input_request')?>','<?=lang('pwd_request')?>')" method="post" enctype="multipart/form-data" id="form">    
    <div class="buttons">
        <span class="button" style="float:left" ><input type=submit value="<?=lang('save')?>" /></span>
        <input type="hidden" name="id" value="<?=((isset($page['id'])=="")?"":"$page[id]")?>" />
        <input type="hidden" name="parent" value="<?=((isset($parent)=="")?"":"$parent")?>" />
        <a href="/admin/slider" class="button"><span><?=lang('cancel')?></span></a>
    </div>
  </div>
  <div class="content">    

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
            <tr>
              <td><span class="required">*</span> <?=lang('name')?></td>
              <td>
                 <input name="page[<?=$lang['id_langs']?>][name_<?=$lang['ext']?>]" value="<?=((isset($page['name_'.$lang['ext']])=="")?"":$page['name_'.$lang['ext']])?>"  size="100" value="" />                
              </td>
            </tr>
            
<!--            <tr>
              <td>Slite text</td>
              <td><input name="page[<?=$lang['id_langs']?>][text_<?=$lang['ext']?>]" value="<?=((isset($page['text_'.$lang['ext']])=="")?"":$page['text_'.$lang['ext']])?>" size="100" /> </td>
            </tr>
            <tr>
              <td>Link</td>
              <td><input  name="page[<?=$lang['id_langs']?>][link_<?=$lang['ext']?>]" value="<?=((isset($page['link_'.$lang['ext']])=="")?"":$page['link_'.$lang['ext']])?>"  size="50" value="" />    </td>
            </tr>-->
          </table>
         </div>            
	<?php } ?>
       </div><!-- end panes -->   
       <table class="form">
            <tr>
              <td>Link</td>
              <td><input  name="page[1][link_ro]" value="<?=((isset($page['link_ro'])=="")?"":$page['link_ro'])?>"  size="50" value="" />    </td>
            </tr>
            <tr>
              <td>
                <?=lang('photo_general')?> :
              
                <span class="help" style='color:red' ></span>
              </td>
              <td>
                <input type="file" name="image" />
                <?php if(isset($page['photo']) and $page['photo'] and file_exists($_SERVER['DOCUMENT_ROOT'].'/admin/'.$page['photo'])) echo "<a href='/admin/$page[photo]' style='color:red' target='_blank' >".lang('view_photo')."</a><br /><input type=checkbox name=del_photo /><span style='color:red' >Check the box if you want to delete the photo</span>";?>
              </td>
            </tr>
          </table>
      </div>

    </form>
  </div>
</div>
<script type="text/javascript" src="<?=base_url()?>admin/js/ckeditor/ckeditor.js"></script>
<script type="text/javascript"><!--
<?php foreach ($langs as $lang) { ?>
 CKEDITOR.replace('description<?php echo $lang['id_langs']; ?>', {toolbar:
                    [
                    ['Source','-','Preview','Templates'],
                    ['Undo','Redo','PasteText','PasteFromWord'],
                    ['Bold','Italic','Underline','Strike','Subscript','Superscript'],
                    ['NumberedList','BulletedList','Outdent','Indent','Blockquote'],
                    ['JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock'],
                    ['Link','Unlink'],
                    ['Image','Flash','Table','HorizontalRule','RemoveFormat'],

                    ['Styles','Format','Font','FontSize'],
                    ['TextColor','BGColor']                 
                                    ],
                   filebrowserBrowseUrl: 'admin/js/filemanager/index.html'    
                });
<?php } ?>
//--></script>
<script type="text/javascript">
    
$(document).ready(function() {    
   $("#languages ul").tabs("div.panes > div"); 
  
}); 
</script>


<!-- <script src="<?=base_url()?>css/smoothness/jquery-1.9.1.js"></script>-->
  <script src="<?=base_url()?>css/smoothness/jquery-ui-1.10.2.custom.min.js"></script>  
  
  <script>
  $(function() {
      
    $( "#datepicker" ).datepicker({
         dateFormat : 'yy-mm-dd'
    });
      
  
    
  });
  </script>
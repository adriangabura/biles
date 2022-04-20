<!-- upload -->
<script src="<?=base_url()?>js/jquery/ajaxupload.js" type="text/javascript"></script>     
<script >
function delete_photo(obj,id)
{
  $.post("/admin/del_photo",{'id':id,'table': 'photo_pages'} , function(data) { obj.parent('div').remove();});                                                  
}
</script>
<div class="box">
  <div class="left"></div>
  <div class="right"></div>
  <div class="heading">
    <h1 style="background-image: url('/img/edit.png');"><?=lang('edit')?></h1>
<form action="<?=$action; ?>" method="post" enctype="multipart/form-data" id="form">    
    <div class="buttons">
        <span class="button" style="float:left" ><input type=submit value="<?=lang('save')?>" /></span>
        
        <input type="hidden" name="id" id="pid" value="<?=((isset($page['id'])=="")?"":"$page[id]")?>" />
        <input type="hidden" name="parent" value="<?=((isset($parent)=="")?"":"$parent")?>" />
        <input type="hidden" name="parent_parent" value="<?=((isset($parent_parent)=="")?"":"$parent_parent")?>" />
        <a href="/admin/pages" class="button"><span><?=lang('cancel')?></span></a>
    </div>
  </div>
  <div class="content">    

      <div id="tab_general">
        <div id="languages" class="htabs">
            <ul>   
          <?php foreach ($langs as $lang) { ?>
                <li>  
                   <a href="#langs<?php echo $lang['id_langs']; ?>">
                    <img src="/img/flags/<?php echo $lang['image']; ?>" /> <?php echo $lang['name']; ?>
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
                 <input name="page[<?=$lang['id_langs']?>][title_<?=$lang['ext']?>]" value="<?=((isset($page['title_'.$lang['ext']])=="")?"":$page['title_'.$lang['ext']])?>"  class="request" size="100" value="" />                
              </td>
            </tr>    

          </table>
         </div>            
	<?php } ?>
            

           
       </div><!-- end panes -->     
          
       <table class="form">
             
       </table>
      
          
       
       
      </div>

    </form>
  </div>
</div>
<script type="text/javascript" src="<?=base_url()?>js/ckeditor/ckeditor.js"></script>
<script type="text/javascript"><!--
<?php foreach ($langs as $lang) { ?>
 CKEDITOR.replace('description<?php echo $lang['id_langs']; ?>', {toolbar:
 [
                    ['Format','Source'],
                    ['-','Preview','Templates'],
                    ['Undo','Redo','PasteText','PasteFromWord'],
                    ['Bold','Italic','Underline','Strike','Subscript','Superscript'],
                    ['NumberedList','BulletedList','Outdent','Indent','Blockquote'],
                    ['JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock'],
                    ['Link','Unlink'],
                    
                    
                                
                                    ],
                   filebrowserBrowseUrl: '/js/filemanager/index.html'    
                });
<?php } ?>
//--></script>
<script type="text/javascript">
    
$(document).ready(function() {    
   $("#languages ul").tabs("div.panes > div"); 
  
}); 
</script>


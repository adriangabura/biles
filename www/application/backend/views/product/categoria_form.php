<style>
    .check_area div{
        padding-top: 2px;
    }
    .check_area div span{
        line-height: 25px;
    }
</style>
<!-- upload -->
<script src="<?=base_url()?>js/jquery/ajaxupload.js" type="text/javascript"></script>     
<script >
function delete_photo(obj,id)
{
  $.post("/admin/del_photo",{'id':id,'table': 'photo_products'} , function(data) { obj.parent('div').remove();});                                                  
}
</script>
<div class="box">
  <div class="left"></div>
  <div class="right"></div>
  <div class="heading">
    <h1 style="background-image: url('/img/product.png');"><?=lang('categoria')?></h1>
<form action="<?=$action; ?>" onsubmit="return check_field('<?=lang('input_request')?>','<?=lang('pwd_request')?>')" method="post" enctype="multipart/form-data" id="form">    
    <div class="buttons">
        <span class="button" style="float:left" ><input type=submit value="<?=lang('save')?>" /></span>
        <input type="hidden" name="id" value="<?=((isset($page['id'])=="")?"":"$page[id]")?>" />
        <input type="hidden" name="pid" id="pid" value="<?=((isset($page['id'])=="")?"":"$page[id]")?>" />
        <input type="hidden" name="parent"  value="<?=((isset($parent)=="")?"":"$parent")?>" />
        <a href="/admin/categoria/<?=$parent?>" class="button"><span><?=lang('cancel')?></span></a>
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
                 <input name="page[<?=$lang['id_langs']?>][title_<?=$lang['ext']?>]" value="<?=((isset($page['title_ro'])=="")?"":$page['title_ro'])?>"   size="100" value="" />                
              </td>
            </tr> 
          </table>
         </div>            
	<?php } ?>

         
            
            
       </div><!-- end panes -->   
           <table class="form">
            <tr >
              <td>
                    <?=lang('type_det_sup')?> :
                    <span class="help">
                        <b>Checkbox</b> - Apar numai checkboxuri pentru bifare <br />
                        <b>Checkbox Categorizat </b>- Posibilitatea de grupa checkboxurile pentru bifare 
                    </span>
              </td>
              <td>
                  <select name="page[1][type]" onchange="get_type($(this).val())" style="float:left">
                  <?php
                //  $type = array('1'=>'Checkbox','2'=>'Checkbox Categorizat');      
                    $type = array('2'=>'Checkbox Categorizat');  
                  foreach($type as $k=>$v)
                  {
                      echo "<option value=$k ";
                      if(isset($page) and $page['type']==$k) echo "selected=selected ";                      
                      echo " >$v</option>";
                  }
                  ?>
                  </select>
              </td>              
            </tr>  
            <tr >
              <td>
                    <?=lang('criteriu_categ')?> :
                    <span class="help">
                        <b>Alege o categorie de de criterii</b><br />
                        <b>Apasa pe lista de criterii pentru a vizauliza/edita/adauga</b><br />
                        <b>Pentru a vizauliza/adauga noi categorii apasa pe adauga categorie de criterii noua</b><br />
                    </span>
              </td>
              <td>
                  <select name="page[1][criteriu_cat_id]"  style="float:left" >
                  <option value="">-- selecteaza --</option>    
                  <?php                        
                  foreach($criteriu_cat as $item)
                  {
                      echo "<option value=$item[id] ";
                      if(isset($page) and $page['criteriu_cat_id']==$item['id']) echo "selected=selected ";                      
                      echo " >$item[title_ro]</option>";
                  }
                  ?>
                  </select>
                  <div style="clear:both" ><br /></div>
                  <div class="buttons">
                        <a href="/admin/criteriu_cat" target="_blank" class="add_crit button"><span>Adauga/Vezi Categorii de Criterii</span></a>
                  </div>
              </td>              
            </tr>            
            
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
                   filebrowserBrowseUrl: '/js/filemanager/index.html'    
                });
               
<?php } ?>
//--></script>
<script type="text/javascript">
    
$(document).ready(function() {    
   $("#languages ul").tabs("div.panes > div");  
}); 

</script>

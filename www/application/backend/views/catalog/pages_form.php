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
    <h1 style="background-image: url('<?=base_url()?>admin/img/edit.png');"><?=$title_page?></h1>
<form action="<?=$action; ?>" method="post" enctype="multipart/form-data" id="form">    
    <div class="buttons">
        <span class="button" style="float:left" ><input type=submit value="<?=lang('save')?>" /></span>
        <input type="hidden" name="id" value="<?=((isset($page['id_pages'])=="")?"":"$page[id_pages]")?>" />
        <input type="hidden" name="id" id="pid" value="<?=((isset($page['id_pages'])=="")?"":"$page[id_pages]")?>" />
        <input type="hidden" name="parent" value="<?=((isset($parent)=="")?"":"$parent")?>" />
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
                    <img src="<?=base_url()?>admin/img/flags/<?php echo $lang['image']; ?>" /> <?php echo $lang['name']; ?>
                   </a>
                </li>    
	   <?php } ?>
          <?php if($nivele == 3) { ?>
                        <li>  
                            <a href="#photos" onclick="$('#general').hide()" >
                                <img src="/admin/img/flags/gal.png" /><?= lang('gallery') ?> 
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
                 <input name="page[<?=$lang['id_langs']?>][name_<?=$lang['ext']?>]" value="<?=((isset($page['name_'.$lang['ext']])=="")?"":$page['name_'.$lang['ext']])?>"  class="request" size="100" value="" />                
              </td>
            </tr>  
            
<!--            <tr>
              <td>Subtitlu</td>
              <td>
                 <input name="page[<?=$lang['id_langs']?>][subtitle_<?=$lang['ext']?>]" value="<?=((isset($page['subtitle_'.$lang['ext']])=="")?"":$page['subtitle_'.$lang['ext']])?>"  class="request" size="100" value="" />                
              </td>
            </tr>  -->
            
            <tr>
              <td><?=lang('desctription')?></td>
              <td><textarea name="page[<?=$lang['id_langs']?>][text_<?=$lang['ext']?>]" id="description<?php echo $lang['id_langs']; ?>"><?=((isset($page['text_'.$lang['ext']])=="")?"":$page['text_'.$lang['ext']])?></textarea></td>
            </tr>
<!--           <tr>
              <td>Text Prima Pagina</td>
              <td><textarea name="page[<?=$lang['id_langs']?>][text2_<?=$lang['ext']?>]" id="description2<?php echo $lang['id_langs']; ?>"><?=((isset($page['text2_'.$lang['ext']])=="")?"":$page['text2_'.$lang['ext']])?></textarea></td>
            </tr>-->
          </table>
         </div>            
	<?php } ?>
            
      <!-- fotogalerie -->   
         <div>
             
          <div class="uload_file">
            <div id="upload-div">
                <a class="button" ><span id="upload-button" class="submit_field1" style="opacity:1" >Incarca Foto</span></a>                
                <img id="loader" style='display:none;width: 21px;margin-bottom: -7px;' src='/admin/img/uploading.gif' />
                <div class="clear" ></div>
            </div><br />
           <div id="image_list" > 
            <?php if(isset($gallery)) { foreach ($gallery as $item) { ?>  
               <div class="image" style='float:left;margin-left: 10px;border: 1px solid #CCCCCC;padding:4px;' >
                   <img widht="80" height="80" src="<?=$item['thumb']?>" />                   
                   <br />
                   <a href="#" onclick="if (confirm('Esti sigur ca vrei sa stergi')){delete_photo($(this),'<?=$item['phid']?>');return false}"  style="padding-left:13px;" >Sterge Foto</a>
               </div>
            <?php } }?>
               <div id="last_photo" ></div> 
               <div style='clear:both' ></div>  
           </div>              
            <script type="text/javascript">/*<![CDATA[*/
                $(document).ready(function(){
                        new AjaxUpload('upload-button', {
                                action: '/admin/upload/do_upload',
                                responseType: 'json',
                                allowedExtensions: ['jpg', 'jpeg', 'png', 'gif'], 
                                onSubmit : function(file , ext){
                                        // Allow only images. You should add security check on the server-side.
                                    /* Change status text */
                                                pid = $('#pid').val();
                                                this.setData({'pid': pid, 'table': 'app_photo_pages'});
                                                $('#upload-button').text("Uploading.." + file);
                                                //$('#loader_overlay').show();
                                                $('#loader').show();/*
                                                setTimeout(function(){
                                                   $('#loader').hide();
                                                   $('#upload-button').html('incarca alta');
                                                   $('#upload-div .text').text('succes');
                                                   //$(".foldere").html(ajax_load); 
                                                   $.post("/pmanager/foldere",{'id_proiecte':id_proiecte} , function(data) {$(".foldere").html(data).show();});                                                  
                                                 }, 2000);*/
                                             

                                },
                                onComplete: function(file, responseJSON){
                                    //console.log(responseJSON);
                                                   $('#upload-button').html('Incarca Alta');
                                                   $('#loader').hide();
                                                   $.post("/admin/get_last_photo",{'table': 'app_photo_pages'} , function(data) {$("#last_photo").append(data);});                                                  
                                  
                                 // console.debug("Here is the response: %o", responseJSON);
                                  /*
                                  if(responseJSON.status=='error') alert(responseJSON.issue);
                                 */  
                               }
                          });
                     });/*]]>*/
                </script>
             </div>
           
           </div> <!-- end fotogaleria -->  
           
       </div><!-- end panes -->     
          
       <table class="form">
           <!--
            <tr>
              <td>Link Youtube</td>
              <td>
                 <input name="page[<?=$lang['id_langs']?>][video]" value="<?=((isset($page['video'])=="")?"":$page['video'])?>"  class="" size="100" value="" />                
              </td>
            </tr>    
           -->
           <?php if ($nivele==3) { ?>
           <tr>
              <td>Cod produs</td>
              <td>
                 <input name="page[<?=$lang['id_langs']?>][cod]" value="<?=((isset($page['cod'])=="")?"":$page['cod'])?>"  class="" size="100" value="" />                
              </td>
            </tr> 
           <tr>
              <td>Price</td>
              <td>
                 <input name="page[<?=$lang['id_langs']?>][price]" value="<?=((isset($page['price'])=="")?"":$page['price'])?>"  class="" size="100" value="" />                
              </td>
            </tr> 
            <tr>
              <td>Recomandat</td>
              <td>
                  <input name="page[<?=$lang['id_langs']?>][recomandat]" type="checkbox" value="on" <?=(isset($page['recomandat']) && $page['recomandat'] == 1?'checked':'')?> />                
              </td>
            </tr> 
           <?php } ?>
            <tr>
              <td>
                
               <?=lang('photo_general')?> :
              
                <span class="help" style='color:red' ></span>
              </td>
              <td>
                <input type="file" name="photo_ro" />
                <?php if(isset($page['photo_ro']) and ($page['photo_ro']) and file_exists($_SERVER['DOCUMENT_ROOT'].$page['photo_ro'])) echo "<a href='$page[photo_ro]' style='color:red' target='_blank' >vizaulizeaza fisierul</a><br /><input type=checkbox name=del_photo /><span style='color:red' >Bifeaza daca doresti sa stergi fisierul</span>
";?>
              </td>
            </tr> 
             <!--
            <tr>
               
              <td>
                <?php
                echo 'Fisiere en';
                ?>:
                <span class="help" style='color:red' ></span>
              </td>
              <td>
                <input type="file" name="photo_en" />
                <?php if(isset($page['photo_en']) and ($page['photo_en']) and file_exists($_SERVER['DOCUMENT_ROOT'].$page['photo_en'])) echo "<a href='$page[photo_en]' style='color:red' target='_blank' >vizaulizeaza fisierul</a><br /><input type=checkbox name=del_photo2 /><span style='color:red' >Bifeaza daca doresti sa stergi fisierul</span>
";?>
              </td>
            </tr>           
           -->
             
          </table>
      
          
       
       
      </div>

    </form>
  </div>
</div>
<script type="text/javascript" src="<?=base_url()?>/admin/js/ckeditor/ckeditor.js"></script>
<script type="text/javascript"><!--
<?php foreach ($langs as $lang) { ?>
 CKEDITOR.replace('description<?php echo $lang['id_langs']; ?>', {
                   filebrowserBrowseUrl: '/admin/js/filemanager/index.html'    
                });
<?php } ?>
//--></script>
<script type="text/javascript"><!--
<?php foreach ($langs as $lang) { ?>
 CKEDITOR.replace('description3<?php echo $lang['id_langs']; ?>', {
                   filebrowserBrowseUrl: '/admin/js/filemanager/index.html'    
                });
<?php } ?>
//--></script>
<script type="text/javascript"><!--
<?php foreach ($langs as $lang) { ?>
 CKEDITOR.replace('description2<?php echo $lang['id_langs']; ?>', {
                   filebrowserBrowseUrl: '/admin/js/filemanager/index.html'    
                });
<?php } ?>
//--></script>


<script type="text/javascript">
    
$(document).ready(function() {    
   $("#languages ul").tabs("div.panes > div"); 
  
}); 
</script>


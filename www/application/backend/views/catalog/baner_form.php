<script>
  
       
       function get_categoria(id)
       {
                           
                            var p = {id:id};
                            $('#categoria').html('')
                            $.get('/admin/ajax_get_categorii',p,function(response){                                
                                    $('#categoria').append(response);                               
                            });    
       }                     
    
</script>    
<div class="box">
  <div class="left"></div>
  <div class="right"></div>
  <div class="heading">
    <h1 style="background-image: url('/img/banners.png');"><?=lang('baner')?></h1>
<form action="<?=$action; ?>" onsubmit="return check_field('<?=lang('input_request')?>','<?=lang('pwd_request')?>')" method="post" enctype="multipart/form-data" id="form">    
    <div class="buttons">
        <span class="button" style="float:left" ><input type=submit value="<?=lang('save')?>" /></span>
        <input type="hidden" name="id" value="<?=((isset($page['id'])=="")?"":"$page[id]")?>" />
        <input type="hidden" name="parent" value="<?=((isset($parent)=="")?"":"$parent")?>" />
        <a href="/admin/baner" class="button"><span><?=lang('cancel')?></span></a>
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
                 <input  class="request" name="page[<?=$lang['id_langs']?>][title_<?=$lang['ext']?>]" value="<?=((isset($page['title_'.$lang['ext']])=="")?"":$page['title_'.$lang['ext']])?>"  size="100" value="" />                
              </td>
            </tr>
           
            <tr>
              <td><?=lang('desctription')?></td>
              <td><textarea name="page[<?=$lang['id_langs']?>][text_<?=$lang['ext']?>]" id="description<?php echo $lang['id_langs']; ?>"><?=((isset($page['text_'.$lang['ext']])=="")?"":$page['text_'.$lang['ext']])?></textarea></td>
            </tr>
            
          </table>
         </div>            
	<?php } ?>
       </div><!-- end panes -->   
       <table class="form">
            <tr>
              <td>Rubrica</td>
              <td>
                <select id="rubrica" onchange="return get_categoria($(this).val())" name="page[1][rubrica_id]">                     
                    <?php                                
                    foreach($rubrici as $item)
                    {
                        if(isset($page) and $page['rubrica_id'] == $item['id']) $sel ='selected=selected';
                        else $sel = '';
                        echo "<option value=$item[id] $sel >$item[title_ro]</option>";
                    }
                    ?> 
                </select>                 
              </td>
            </tr>   
            <tr>
              <td>Categoria</td>
              <td>
                <select  id="categoria" name="page[1][categoria_id]">                     
                    <?php                                
                    foreach($categorii as $item)
                    {
                        if(isset($page) and $page['categoria_id'] == $item['id']) $sel ='selected=selected';
                        else $sel = '';
                        echo "<option value=$item[id] $sel >$item[title_ro]</option>";
                    }
                    ?> 
                </select>                 
              </td>
            </tr>            
            <tr>
              <td>Link</td>
              <td>
                 <input name="page[<?=$lang['id_langs']?>][link]" value="<?=((isset($page['link'])=="")?"":$page['link'])?>"  size="100" value="" />                
              </td>
            </tr>           
            <tr>
              <td><span class="required">*</span> Pozitia</td>
              <td>
                 <input class="request" name="page[<?=$lang['id_langs']?>][position]" value="<?=((isset($page['position'])=="")?"":$page['position'])?>"  size="100" value="" />                
              </td>
            </tr>
            <tr>
              <td>
                <?=lang('photo_general')?> :
              
                <span class="help" style='color:red' ></span>
              </td>
              <td>
                <input type="file" name="image" />
                <?php if(isset($page['photo']) and $page['photo'] and file_exists($_SERVER['DOCUMENT_ROOT'].'/admin/'.$page['photo'])) echo "<a href='/admin/$page[photo]' style='color:red' target='_blank' >".lang('view_photo')."</a><br /><input type=checkbox name=del_photo /><span style='color:red' >Bifeaza daca doresti sa stergi fotogafria</span>";?>
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

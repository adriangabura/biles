<style>
    .check_area div{
        padding-top: 2px;
    }
    .check_area div span{
        line-height: 25px;
    }
    .c_block{
        float:left;
        width: 200px;
        margin-bottom: 10px;
    }
    .c_block img{
        float:left;padding-top: 5px;margin-right: 2px;cursor: pointer        
    }
    .c_block input {
       width: 83%;
    }    
</style>
<!-- upload -->
<script src="<?=base_url()?>js/jquery/ajaxupload.js" type="text/javascript"></script>     
<script >
    
    $(document).ready(function(){
        $('.add_crit').click(function(){
            
            var id = $('#pid').val();
            $.post("/admin/add_crit_val",{'id':id} , function(data) { 
               $('#crit_td').append(data);
            });    
            
        });
    });
    
    function del_crit_val(obj,id)
    {
      $.post("/admin/del_crit_val",{'id':id} , function(data) { obj.parent('div').remove();});                                                  
    }
    
    function get_type(id)
    {
        
       if(id == 2) 
       {
           $('#view_btn').css('visibility', 'visible');
           $('#crit_tr').css('visibility', 'visible');
           $('#view_btn').show();
           $('#crit_tr').show();
           $('#view_btn_auto').hide();
       }
       else if(id == 3) 
       {
           $('#view_btn_auto').css('visibility', 'visible');           
           $('#view_btn_auto').show();
           
           $('#view_btn').hide();
           $('#crit_tr').hide();
       }       
       else
       {
           $('#view_btn').hide();
           $('#crit_tr').hide();
           $('#view_btn_auto').hide();
       }    
    }    

</script>
<form action="<?=$action; ?>" onsubmit="return check_field('<?=lang('input_request')?>','<?=lang('pwd_request')?>')" method="post" enctype="multipart/form-data" id="form">    
    
<div class="box">
  <div class="left"></div>
  <div class="right"></div>
  <div class="heading">
    <h1 style="background-image: url('/img/services.png');"><?=lang('criteriu_categ')?></h1>

    <div class="buttons">
        <span class="button" style="float:left" ><input type=submit value="<?=lang('save')?>" /></span>
       
       
        <input type="hidden" name="id" value="<?=((isset($page['id'])=="")?"":"$page[id]")?>" />
        <input type="hidden" name="pid" id="pid" value="<?=((isset($page['id'])=="")?"":"$page[id]")?>" />
        <input type="hidden" name="parent"  value="<?=((isset($parent)=="")?"":"$parent")?>" />
        <a href="/admin/criteriu_cat/<?=$parent?>" class="button"><span><?=lang('cancel')?></span></a>
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
            <tr>
              <td ><?=lang('short_desc')?></td>
              
                 <td>
                     <textarea name="page[<?=$lang['id_langs']?>][short_<?=$lang['ext']?>]" id="description<?php echo $lang['id_langs']; ?>"><?=((isset($page['short_'.$lang['ext']])=="")?"":$page['short_'.$lang['ext']])?></textarea>
                 </td>
            </tr>              
                 
          </table>
         </div>            
	<?php } ?>
            
       </div><!-- end panes -->   
           <table class="form">
          
          </table>     
      </div>

    
  </div>
</div>
</form>
<script type="text/javascript" src="<?=base_url()?>js/ckeditor/ckeditor.js"></script>

<script type="text/javascript"><!--
<?php foreach ($langs as $lang) { ?>
 CKEDITOR.replace('description<?php echo $lang['id_langs']; ?>', {
        height: '60px',
        toolbar:
                    [
                    ['Source','-','Preview','Templates'],
                    ['Undo','Redo','PasteText','PasteFromWord'],
                    ['Bold','Italic','Underline','Strike','Subscript','Superscript'],
                    ['NumberedList','BulletedList','Outdent','Indent','Blockquote'],
                    ['JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock'],                  
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

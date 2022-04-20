<div class="box">
  <div class="left"></div>
  <div class="right"></div>
  <div class="heading">
    <h1 style="background-image: url('/img/vars.png');">Edit Snippets</h1>
<form action="<?=$action; ?>" onsubmit="return check_field('<?=lang('input_request_lang')?>')" method="post" enctype="multipart/form-data" id="form">    
    <div class="buttons">
        <span class="button" style="float:left" ><input type=submit value="<?=lang('save')?>" /></span>
        <input type="hidden" name="id" value="<?=((isset($snippets['id_snippets'])=="")?"":"$snippets[id_snippets]")?>" />

        <a href="/admin/snippets" class="button"><span><?=lang('cancel')?></span></a>
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
              <td>Snippet name</td>
              <td>
                  <textarea name="snippet[<?=$lang['id_langs']?>][<?=$lang['ext']?>]" id="description<?php echo $lang['id_langs']; ?>"><?=((isset($snippets[''.$lang['ext']])=="")?"":$snippets[''.$lang['ext']])?></textarea>                    
              </td>
            </tr>
           </table>
         </div>            
	<?php } ?>
       </div><!-- end panes -->   
          
      </div>

    </form>
  </div>
</div>

<script type="text/javascript">
    
$(document).ready(function() {    
   $("#languages ul").tabs("div.panes > div");  
}); 

</script>
<script type="text/javascript" src="<?= base_url() ?>admin/js/ckeditor/ckeditor.js"></script>
<script type="text/javascript"><!--
<?php foreach ($langs as $lang) { ?>
                                CKEDITOR.replace('description<?php echo $lang['id_langs']; ?>', {toolbar:
                                            [
                                                ['Format', 'Source'],
                                                ['-', 'Preview', 'Templates'],
                                                ['Undo', 'Redo', 'PasteText', 'PasteFromWord'],
                                                ['Bold', 'Italic', 'Underline', 'Strike', 'Subscript', 'Superscript'],
                                                ['NumberedList', 'BulletedList', 'Outdent', 'Indent', 'Blockquote'],
                                                ['JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock'],
                                                ['Link', 'Unlink'],
                                                ['Image', 'Flash'], ['Table', 'HorizontalRule', 'RemoveFormat'],['ShowBlocks']
                                            ],
                                    filebrowserBrowseUrl: '/admin/js/filemanager/index.html',
                                });
                                 CKEDITOR.config.autoParagraph = false;
                                // CKEDITOR.config.extraPlugins = 'showblocks';
<?php } ?>
//--></script>
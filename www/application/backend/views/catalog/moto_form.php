<style>
    .check_area div{
        padding-top: 2px;
    }
    .check_area div span{
        line-height: 25px;
    }
</style>
<!-- upload -->
<script src="<?= base_url() ?>js/jquery/ajaxupload.js" type="text/javascript"></script>     
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
        <h1 style="background-image: url('/admin/img/abonati.png');">Staff</h1>
        <form action="<?= $action; ?>" onsubmit="return check_field('<?= lang('input_request') ?>','<?= lang('pwd_request') ?>')" method="post" enctype="multipart/form-data" id="form">    
            <div class="buttons">
                <span class="button" style="float:left" ><input type=submit value="<?= lang('save') ?>" /></span>
                <input type="hidden" name="id" value="<?= ((isset($page['id']) == "") ? "" : "$page[id]") ?>" />
                <input type="hidden" name="pid" id="pid" value="<?= ((isset($page['id']) == "") ? "" : "$page[id]") ?>" />
                <input type="hidden" name="parent" value="<?= ((isset($parent) == "") ? "" : "$parent") ?>" />
                <a href="/admin/moto" class="button"><span><?= lang('cancel') ?></span></a>
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
                                <td><span class="required">*</span> <?= lang('name') ?></td>
                                <td>
                                    <input name="page[<?= $lang['id_langs'] ?>][title_<?= $lang['ext'] ?>]" value="<?= ((isset($page['title_' . $lang['ext']]) == "") ? "" : $page['title_' . $lang['ext']]) ?>"   size="100" value="" />                
                                </td>
                            </tr>
                             <tr>
                                <td><span class="required">*</span> City</td>
                                <td>
                                    <input name="page[<?= $lang['id_langs'] ?>][city_<?= $lang['ext'] ?>]" value="<?= ((isset($page['city_' . $lang['ext']]) == "") ? "" : $page['city_' . $lang['ext']]) ?>"   size="100" value="" />                
                                </td>
                            </tr>
                             <tr>
                                <td><span class="required">*</span> Post</td>
                                <td>
                                    <input name="page[<?= $lang['id_langs'] ?>][post_<?= $lang['ext'] ?>]" value="<?= ((isset($page['post_' . $lang['ext']]) == "") ? "" : $page['post_' . $lang['ext']]) ?>"   size="100" value="" />                
                                </td>
                            </tr>
                            <tr>
                                <td><?= lang('desctription') ?></td>
                                <td><textarea name="page[<?= $lang['id_langs'] ?>][text_<?= $lang['ext'] ?>]" id="description<?php echo $lang['id_langs']; ?>"><?= ((isset($page['text_' . $lang['ext']]) == "") ? "" : $page['text_' . $lang['ext']]) ?></textarea></td>
                            </tr>   

                        </table>
                    </div>            
                <?php } ?>



            </div><!-- end panes -->   
            <table class="form">
                <tr>
                    <td>

                        <?= lang('photo_general') ?> :

                        <span class="help" style='color:red' ></span>
                    </td>
                    <td>
                        <input type="file" name="image" />
                        <?php if (isset($page['photo']) and ($page['photo']) and file_exists($_SERVER['DOCUMENT_ROOT'] . '/admin/' . $page['photo'])) echo "<img src='/admin$page[photo]' style='max-width: 120px;float: left;' target='_blank' /><br /><input type=checkbox name=del_photo /><span style='color:red' >Bifeaza daca doresti sa stergi fisierul</span>
"; ?>
                    </td>
                </tr> 
            </table>
        </div>

        </form>
    </div>
</div>

<script type="text/javascript" src="<?= base_url() ?>/admin/js/ckeditor/ckeditor.js"></script>

<script type="text/javascript"><!--
<?php foreach ($langs as $lang) { ?>
        CKEDITOR.replace('description<?php echo $lang['id_langs']; ?>', {toolbar:
                [
                ['Source','-','Templates'],
                    ['Undo','Redo','PasteText'],
                    ['Bold','Italic','Underline','Strike','Subscript','Superscript'],
                    ['FontSize'],
                    ['TextColor']            
            ],
            filebrowserBrowseUrl: '/admin/js/filemanager/index.html'    
        });
                   
<?php } ?>
    //--></script>
<script type="text/javascript">
    
    $(document).ready(function() {    
        $("#languages ul").tabs("div.panes > div");  
    }); 

</script>

<!-- upload -->
<link rel="stylesheet" href="<?= base_url() ?>/admin/css/smoothness/jquery-ui-1.10.2.custom.css" />
<script src="<?= base_url() ?>js/jquery/ajaxupload.js" type="text/javascript"></script>     
<script >
    function delete_photo(obj, id)
    {
        $.post("/admin/del_photos", {'id': id, 'table': 'app_photo_products'}, function (data) {
            obj.parent('div').remove();
        });
    }
</script>
<div class="box">
    <div class="left"></div>
    <div class="right"></div>
    <div class="heading">
        <h1 style="background-image: url('/admin/img/portfolio.png');">Catalog</h1>
        <script language="javascript">
            function Button(theButton) {
                var theForm = theButton.form;
                if (theButton.name == "Button1") {
                    theForm.action = "submit1.php"
                }

            }
        </script>
        <form action="<?= $action; ?>" method="post" enctype="multipart/form-data" id="form" name="forma">    
            <div class="buttons">
                <span class="button" style="float:left" ><input type=submit value="<?= lang('save') ?>" name="save" /></span>
                <input type="hidden" name="id" value="<?= ((isset($page['id']) == "") ? "" : "$page[id]") ?>" />
                <input type="hidden" name="id" id="pid" value="<?= ((isset($page['id']) == "") ? "" : "$page[id]") ?>" />
                <input type="hidden" name="id" id="anunt_id" value="<?= ((isset($page['id']) == "") ? "" : "$page[id]") ?>" />
                <input type="hidden" value="<?= $time ?>" name="time_add" id="time_add" />  
                <input type="hidden" name="parent" value="<?= ((isset($parent) == "") ? "" : "$parent") ?>" />
                <a href="/admin/pages" class="button"><span><?= lang('cancel') ?></span></a>
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
                    <?php if ($nivele == 1 || isset($page['parent']) && $page['parent'] == 37) { ?>
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
                                <td><span class="required">*</span> <?= lang('name') ?></td>
                                <td>
                                    <input name="page[<?= $lang['id_langs'] ?>][name_<?= $lang['ext'] ?>]" value="<?= ((isset($page['name_' . $lang['ext']]) == "") ? "" : $page['name_' . $lang['ext']]) ?>"  class="request" size="100" value="" />                
                                </td>
                            </tr> 
                            <?php  if($nivele == 1) { ?>
                            <tr>
                                <td> Descriere Scurta</td>
                                <td>
                                    <input name="page[<?= $lang['id_langs'] ?>][title_<?= $lang['ext'] ?>]" value="<?= ((isset($page['title_' . $lang['ext']]) == "") ? "" : $page['title_' . $lang['ext']]) ?>"  size="100" value="" />                
                                </td>
                            </tr> 
                            <?php } ?>
                            <?php if ($nivele == 1) { ?>
                            <tr>
                                <td>Description</td>
                                <td>
                                    <textarea name="page[<?= $lang['id_langs'] ?>][name2_<?= $lang['ext'] ?>]" id="description2<?php echo $lang['id_langs']; ?>" ><?= ((isset($page['name2_' . $lang['ext']]) == "") ? "" : $page['name2_' . $lang['ext']]) ?></textarea>               
                                </td>
                            </tr>
                            <?php } if ($nivele == 1) { ?>
                                <tr>
                                    <td>Технические данные</td>
                                    <td><textarea name="page[<?= $lang['id_langs'] ?>][text_<?= $lang['ext'] ?>]" id="description<?php echo $lang['id_langs']; ?>"><?= ((isset($page['text_' . $lang['ext']]) == "") ? "" : $page['text_' . $lang['ext']]) ?></textarea></td>
                                </tr>
                            <?php } ?>
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
                            <?php if (isset($gallery)) {
                                foreach ($gallery as $item) { ?>  
                                    <div class="image" style='float:left;margin-left: 10px;border: 1px solid #CCCCCC;padding:4px;' >
                                        <img widht="80" height="80" src="<?= $item['thumb'] ?>" />                   
                                        <br />
                                        <a href="#" onclick="if (confirm('Esti sigur ca vrei sa stergi')) {
                                                    delete_photo($(this), '<?= $item['phid'] ?>');
                                                    return false
                                                }"  style="padding-left:13px;" >Sterge Foto</a>
                                    </div>
    <?php }
} ?>
                            <div id="last_photo" ></div> 
                            <div style='clear:both' ></div>  
                        </div>              
                        <script type="text/javascript">/*<![CDATA[*/
                            $(document).ready(function () {
                                new AjaxUpload('upload-button', {
                                    action: '/admin/upload/do_upload',
                                    responseType: 'json',
                                    allowedExtensions: ['jpg', 'jpeg', 'png', 'gif'],
                                    onSubmit: function (file, ext) {
                                        // Allow only images. You should add security check on the server-side.
                                        /* Change status text */
                                        pid = $('#pid').val();
                                        this.setData({'pid': pid, 'table': 'app_photo_products'});
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
                                    onComplete: function (file, responseJSON) {
                                        $('#upload-button').html('Incarca Alta');
                                        $('#loader').hide();
                                        $.post("/admin/get_last_photo", {'table': 'app_photo_products'}, function (data) {
                                            $("#last_photo").append(data);
                                        });

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
                <?php  if(isset($parentparent) && $parentparent['parent'] == 2) { ?>
                <tr>
              <td>Tip Produs</td>
              <td>
                  <label><input type="radio" <?=((isset($page['tip']) && $page['tip'] =="0")?"checked":'')?> name="page[1][tip]" value="0"> Simplu</label>
                  <label><input type="radio" <?=((isset($page['tip']) && $page['tip'] =="1")?"checked":'')?> name="page[1][tip]" value="1"> Recomandat</label>
                 
              </td>
            </tr> 
                    <tr>
              <td>Categorie</td>
              <td>
                  <label><input type="radio" <?=((isset($page['status']) && $page['status'] =="0")?"checked":'')?> name="page[1][status]" value="0"> None</label>
                  <label><input type="radio" <?=((isset($page['status']) && $page['status'] =="1")?"checked":'')?> name="page[1][status]" value="1"> Produs Nou</label>
                  <label><input type="radio" <?=((isset($page['status']) && $page['status'] =="2")?"checked":'')?> name="page[1][status]" value="2"> Top Produs</label>
                 
              </td>
            </tr> 
                <?php } ?>

<!--                <tr>
                   <td>Area Oficiului</td>
                   <td>
                       <input name="page[1][client]" value="<?= ((isset($page['client']) == "") ? "" : $page['client']) ?>"  class="request" size="50" value="" />                
                   </td>
               </tr>
               -->
                   <!--                <tr>
                       <td>Categorie</td>
                       <td>
                           <select  name="page[1][timp]" id="">
                               <option value="1" <?= (isset($page['timp']) && $page['timp'] == 1 ? 'selected' : '') ?> >Invitatii</option>
                               <option value="2" <?= (isset($page['timp']) && $page['timp'] == 2 ? 'selected' : '') ?>>Felicitari</option>
                               <option value="3" <?= (isset($page['timp']) && $page['timp'] == 3 ? 'selected' : '') ?>>Accesorii</option>
                           </select>
                       </td>
                   </tr>-->
    <!--                <tr>
                       <td>Tip</td>
                       <td>
                           <select  name="page[1][tags]" id="typef">
                               <option value="1" <?= (isset($page['tags']) && $page['tags'] == 1 ? 'selected' : '') ?> >Patrat</option>
                               <option value="2" <?= (isset($page['tags']) && $page['tags'] == 2 ? 'selected' : '') ?>>Coloana verticala</option>
                               <option value="3" <?= (isset($page['tags']) && $page['tags'] == 3 ? 'selected' : '') ?>>Coloana orizontala</option>
                           </select>
                       </td>
                   </tr>-->
<?php if ($nivele == 1) { ?>

               <tr>
                   <td>Pretul</td>
                   <td>
                       <input   name="page[1][timp]" value="<?= ((isset($page['timp']) == "") ? "" : $page['timp']) ?>"  class="request" size="50" value="" />                
                   </td>
               </tr>
<?php }  ?>
               
                 <tr>
         <td>Foto :</td>
         <td>
             <input type="file" name="image" />
    <?php if (isset($page['photo']) and ( $page['photo']) and file_exists($_SERVER['DOCUMENT_ROOT'] . '/admin/' . $page['photo'])) echo "<a href='/admin$page[photo]' style='color:red' target='_blank' >vizaulizeaza fisierul</a><br /><input type=checkbox name=del_photo /><span style='color:red' >Bifeaza daca doresti sa stergi fisierul</span>
"; ?>
         </td>
     </tr> 


            </table>

            <script>
                $(document).ready(function () {

                    if ($('#typef').val() == 1) {
                        $('#marimi').html('231*231');
                    } else if ($('#typef').val() == 2) {
                        $('#marimi').html('231*466');
                    } else if ($('#typef').val() == 3) {
                        $('#marimi').html('466*231');
                    }

                    $('#typef').change(function () {
                        if ($(this).val() == 1) {
                            $('#marimi').html('231*231');
                        } else if ($(this).val() == 2) {
                            $('#marimi').html('231*466');
                        } else if ($(this).val() == 3) {
                            $('#marimi').html('466*231');
                        }
                    });

                });
            </script>


        </div>

        </form>
    </div>
</div>
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
                                    ['Image', 'Flash'], ['Table', 'HorizontalRule', 'RemoveFormat'],
                                ],
                        filebrowserBrowseUrl: '/admin/js/filemanager/index.html'
                    });
<?php } ?>
//--></script>
<script type="text/javascript"><!--
<?php foreach ($langs as $lang) { ?>
                    CKEDITOR.replace('description2<?php echo $lang['id_langs']; ?>', {toolbar:
                                [
                                    ['Format', 'Source'],
                                    ['-', 'Preview', 'Templates'],
                                    ['Undo', 'Redo', 'PasteText', 'PasteFromWord'],
                                    ['Bold', 'Italic', 'Underline', 'Strike', 'Subscript', 'Superscript'],
                                    ['NumberedList', 'BulletedList', 'Outdent', 'Indent', 'Blockquote'],
                                    ['JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock'],
                                    ['Link', 'Unlink'],
                                    ['Image', 'Flash'], ['Table', 'HorizontalRule', 'RemoveFormat'],
                                ],
                        filebrowserBrowseUrl: '/admin/js/filemanager/index.html'
                    });
<?php } ?>
//--></script>
<script type="text/javascript">

                $(document).ready(function () {
                    $("#languages ul").tabs("div.panes > div");

                });
</script>
<script src="<?= base_url() ?>admin/css/smoothness/jquery-ui-1.10.2.custom.min.js"></script>  
<script>
                $(function () {
                    $("#datepicker").datepicker({
                        dateFormat: 'yy-mm-dd'
                    });
                });
</script>


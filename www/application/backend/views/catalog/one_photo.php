               <div class="image" style='float:left;margin-left: 10px;border: 1px solid #CCCCCC;padding:4px;' >
                   <img widht="80" height="80" src="<?=$photo['path']?>" />                   
                   <br />
                   <a href="#" onclick="if (confirm('Esti sigur ca doresti sa stergi')){delete_photo($(this),'<?=$photo['phid']?>');return false}"  style="padding-left:13px;" >Sterge Foto</a>
               </div>

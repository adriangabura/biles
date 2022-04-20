                            <script >
                            function delete_photo(id)
                            {
                              var time = $('#time_add').val();  
                              var anunt_id = $('#anunt_id').val();
                              $.post("/admin/del_photo",{'anunt_id':anunt_id,'id':id,'time': time} , function(data) { 
                                  if(data) 
                                  {
                                      $('#photo_area').html(data);
                                     $('#js_add_photo').html('<script src="/admin/js/upload_photo.js" type="text/javascript"><\/script>');                                          
                                  }
                              });                                                  
                            }
                            function move_photo(id,type)
                            {
                              var time     = $('#time_add').val();
                              var type     = type;
                              var anunt_id = $('#anunt_id').val();
                              $.post("/admin/move_photo",{'anunt_id':anunt_id,'id':id,'type': type,'time' : time} , function(data) { 
                                  if(data) {$('#photo_area').html(data);
                                  $('#js_add_photo').html('<script src="/admin/js/upload_photo.js" type="text/javascript"><\/script>');
                                    }
                              });                                                  
                            }                                
                            </script> 
                          <?php
                          //pr($photo);
                          $i = 0;$k = 1;$nr = 0;
                          if(isset($photo) and $photo)
                          {    
                            $nr = count($photo);                         
                            foreach($photo as $item)
                            { 
                                $i++;
                                if($i ==1)
                                {
                                  $prev = 'left_photo_disable';
                                  $next = 'right_photo_enable';
                                }
                                elseif($i == $nr)
                                {
                                  $prev = 'left_photo_enable';
                                  $next = 'right_photo_disable';
                                }
                                else
                                {
                                  $prev = 'left_photo_enable';
                                  $next = 'right_photo_enable';
                                }                              
                            ?>
                              <div class="upload_foto">
                                  <div class="nr_photo" > <?=$i?></div>
                                  <img src="<?=$item['thumb']?>" class="images_upload" />  
                                  <div class="action_photo"> 
                                      <div class="<?=$prev?>" onclick="move_photo('<?=$item['id']?>','up')"> </div>
                                      <div class="delete_photo" onclick="delete_photo('<?=$item['id']?>')" > </div>
                                      <div class="<?=$next?>" onclick="move_photo('<?=$item['id']?>','down')" > </div>
                                  </div>    
                              </div>
                             <?php
                            }
                          }
                          $k = $i;$nr ++; 
                          if($k<10)
                          { 
                            $k++;  
                            for ($i = $k; $i <= 10; $i++)
                            {                                                  
                            ?>
                             <div class="upload_foto">
                                 <div class="nr_photo" > <?=$i?></div>
                                 <?php
                                 if($i ==$nr)
                                 {    
                                 ?>
                                 <div class="uplaod_button_wrapper">
                                     <span class="inner_text_button">Browse</span>
                                     <input type="file" id="upload-button" class="" name="f1" />
                                     
                                 </div>
                                 <?php
                                 }
                                 ?>                               
                                 <img src="/admin/img/poza_bg.jpg" class="images_upload" /> 
                                 <div class="action_photo"> 
                                     <div class="left_photo_disable"> </div>
                                     <div class="delete_photo" style="cursor: default" > </div>
                                     <div class="right_photo_disable"> </div>
                                 </div>    
                             </div>
                             <?php
                             }
                           }
                           ?>
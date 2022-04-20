                                   $(document).ready(function(){
                                           new AjaxUpload('upload-button', {
                                                   action: '/admin/do_upload',
                                                   responseType: 'json',
                                                   allowedExtensions: ['jpg', 'jpeg', 'png', 'gif'], 
                                                   onSubmit : function(file , ext){
                                                                    
                                                                   time     = $('#time_add').val();
                                                                   anunt_id = $('#anunt_id').val();
                                                                   this.setData({'time': time,'anunt_id': anunt_id});
                                                                  
                                                                  
                                                   },
                                                   onComplete: function(file, responseJSON){  
                                                                       
                                                                      var time = $('#time_add').val();
                                                                      var anunt_id = $('#anunt_id').val();                                                                  
                                                                      $.post("/admin/get_photo_add",{'anunt_id' : anunt_id,'time':time} ,
                                                                      function(data)                                                                       
                                                                      {     
                                                                          if(data) 
                                                                          {    
                                                                            $('#photo_area').html(data);
                                                                            $('#js_add_photo').html('<script src="/admin/js/upload_photo.js" type="text/javascript"></script>');
                                                                          }    
                                                                      });                                                  
                                                  }
                                             });
                                        });/*]]>*/

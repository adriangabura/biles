<style>
    .check_area div{
        padding-top: 2px;
    }
    .check_area div span{
        line-height: 25px;
    }
</style>

        <script src="/js/jquery.min.js"></script>
        <script type="text/javascript"
            src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAneydhZQRYvGx_3egeJZPH2mw1fePYkQk&language=ro&region=ro&sensor=false">
        </script>
       
          <!-- end header account -->
        <script src="/js/gmap.js"></script>          
        <script>
            $(function(){
                        <?php
                        if(isset($page) and $page['map_x'] and $page['map_y'])
                        {    
                        ?>
                        gmap.init(({lat: "<?=$page['map_x']?>",lng: "<?=$page['map_y']?>"})); 
                        <?php
                        }
                        else 
                        {
                       ?>
                          gmap.init();     
                       <?php        
                        }
                        ?>
                        $('#gmap_street_go').click(function(){
                            var txt=$("#gmap_stree_input").val() + ' ' + (parseInt($("#localitatea").val())>0?$("#localitatea option:selected").text():'') + ' ' + ($("#judet").val()!=''?$("#judet option:selected").text():'');
                            gmap.searchAdress(txt);
                            return false;
                        })
                        
                        $('#rubrica').change(function(){
                            var controller = $('#controller').val();
                            load_categorii($(this).val(),controller)
                        });

                        $('#judet').change(function(){
                            var $this = $(this);
                            var controller = $('#controller').val();
                            load_localitatea($(this).val(),controller)
                            gmap.searchAdress($('#judet option:selected').text());
                        })
                        
                        $('#localitatea').change(function(){
                            gmap.searchAdress($('#judet option:selected').text() + ", " + $('#localitatea option:selected').text() );
                        
                        })
                        
                        /*Load Localitatea*/
                        function load_localitatea(id,controller)
                        {
                            var p = {id:id};
                            $('#localitatea').html('')
                            $.get('/'+controller+'/ajax_get_localitatea',p,function(response){
                                if(response.success )
                                {
                                    $('#localitatea').append('<option value="0"> -- selecteaza -- </option>');
                                    $.each(response.data.categoria, function(i,item){
                                        $('#localitatea').append('<option value="'+item.id+'">'+item.title+'</option>');
                                    })
                                    $.uniform.update('#localitatea');
                                }
                                else
                                {
                                    $('#localitatea').html('')
                                   // alert(response.errors);
                                }
                            },'JSON')
                        }

                        /*Load Categorii*/
                        function load_categorii(id,controller)
                        {
                            var p = {id:id};
                            $('#categoria').html('')
                            $.get('/'+controller+'/ajax_get_categorii',p,function(response){
                                if ( response.success )
                                {
                                    $('#categoria').append('<option value="0"> -- selecteaza -- </option>');
                                    $.each(response.data.categoria, function(i,item){
                                        $('#categoria').append('<option value="'+item.id+'">'+item.title+'</option>');
                                    })
                                    $.uniform.update('#categoria');
                                }
                                else
                                {
                                   // alert(response.errors);
                                }
                            },'JSON')
                        }

               
               
            });
        </script>
<div class="box">
  <div class="left"></div>
  <div class="right"></div>
  <div class="heading">
    <h1 style="background-image: url('/img/product.png');"><?=lang('obiective')?></h1>
<form action="<?=$action; ?>" onsubmit="return check_field('<?=lang('input_request')?>','<?=lang('pwd_request')?>')" method="post" enctype="multipart/form-data" id="form">    
    <div class="buttons">
        <span class="button" style="float:left" ><input type=submit value="<?=lang('save')?>" /></span>
        <input type="hidden" name="id" value="<?=((isset($page['id'])=="")?"":"$page[id]")?>" />
        <input type="hidden" name="pid" id="pid" value="<?=((isset($page['id'])=="")?"":"$page[id]")?>" />
        <input type="hidden" name="parent" value="<?=((isset($parent)=="")?"":"$parent")?>" />
        <a href="/admin/obiective" class="button"><span><?=lang('cancel')?></span></a>
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
                 <input name="page[<?=$lang['id_langs']?>][title_<?=$lang['ext']?>]" value="<?=((isset($page['title_'.$lang['ext']])=="")?"":$page['title_'.$lang['ext']])?>"   size="100" value="" />                
              </td>
            </tr>
            <?php
            if($parent>0):
            ?>
            <tr>
                <td colspan="2" >
                    <div style="clear:both" ><br /></div> 
                    <input type="hidden" value="account" id="controller"  /> 
                    <input type="hidden" id="lat" name="lat" value="">
                    <input type="hidden" id="lng" name="lng" value="">
                    
                       <div class="input_adauga_anunt" style="float:left;" >
                            <div class="den_optiune clearfix">
                                
                                Judetul
                                <span class="required">*</span>
                            </div>
                            <select class="select_normal" name="page[1][judet_id]" style="width: 150px;" id="judet" >
                                <option value='' >----</option>
                                <?php                                
                                foreach($judet as $item)
                                {
                                    if(isset($page) and $page['judet_id']==$item['id']) $sel = 'selected=selected';
                                    else  $sel = '';                                    
                                    echo "<option value=$item[id] $sel >$item[title]</option>";
                                }
                                ?>  
                            </select>                           
                        </div>
                       <div class="input_adauga_anunt" style="float:left;margin-left: 10px;" >
                            <div class="den_optiune">Localitatea</div>
                            <select class="select_normal" name="page[1][localitate_id]" style="width: 150px;" id="localitatea" >
                                <option value='' >----</option>
                                <?php
                                if(isset($localitatea))
                                {    
                                    foreach($localitatea as $item)
                                    {
                                        if(isset($page) and $page['localitate_id']==$item['id']) $sel = 'selected=selected';
                                        else  $sel = '';                                    
                                        echo "<option value=$item[id] $sel >$item[title]</option>";
                                    }
                                }
                                ?>                                  
                            </select>
                        </div> 
                    
                    <div class="open_map clearfix" style='display: block;' >
                        <div class="clearfix"  style="float:left;margin-left: 10px;" >
                            <div class="txt_den_str">Strada</div>                             
                            <input type="text" name="page[1][strada]" value="<?=((isset($page['strada'])=="")?"":"$page[strada]")?>" class="input1 input_special" style="height: 14px" id="gmap_stree_input">
                            <input type="button" name="" value="Cauta" class="submit_search_in_map" id="gmap_street_go">                                                
                        </div>
                        <div style="clear:both" ><br /></div> 
                       <div class="container_google_maps" id="map-canvas" style="display: block;width:100%;height: 500px;"></div>
                       <div class="clearfix"><br />
                           <div class=" icons_style_localizare_harta icon_localizare_harta"> </div>
                           <div>Pentru o localizare mai exacta, puteti trage cursorul in pozitia dorita cu ajutorul mouse-ului.</div>
                       </div>  
                     </div>                    
                </td>
            </tr>
            <?php endif;?>     
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

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
         $('.add_critr').click(function(){
            
            var id = $('#pid').val();
            $.post("/admin/add_crit_val",{'id':id} , function(data) { 
               $('#crit_tdr').append(data);
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
           $('#view_btn_zona').hide();
       }
       else if(id == 3) 
       {
           $('#view_btn_auto').css('visibility', 'visible');           
           $('#view_btn_auto').show();
           $('#view_btn_zona').hide();
            $('#view_btn_moto').hide();
           $('#view_btn_truck').hide();
           $('#view_btn').hide();
           $('#crit_tr').hide();
       }  
        else if(id == 5) 
       {
           $('#view_btn_truck').css('visibility', 'visible');           
           $('#view_btn_truck').show();
           $('#view_btn_zona').hide();
            $('#view_btn_auto').hide();
           $('#view_btn_moto').hide();
           $('#view_btn').hide();
           $('#crit_tr').hide();
       } 
       else if(id == 6) 
       {
           $('#view_btn_moto').css('visibility', 'visible');           
           $('#view_btn_moto').show();
           $('#view_btn_zona').hide();
           $('#view_btn_auto').hide();
           $('#view_btn_truck').hide();
           $('#view_btn').hide();
           $('#crit_tr').hide();
       }  
       else if(id == 4) 
       {
           $('#view_btn_zona').css('visibility', 'visible');           
           $('#view_btn_zona').show();
            $('#view_btn_moto').hide();
           $('#view_btn_truck').hide();
           $('#view_btn').hide();
           $('#crit_tr').hide();
           $('#view_btn_auto').hide();
       }        
       else
       {
           $('#view_btn').hide();
           $('#crit_tr').hide();
           $('#view_btn_auto').hide();
           $('#view_btn_zona').hide();
            $('#view_btn_moto').hide();
           $('#view_btn_truck').hide();
       }    
    }    

function get_typee(id)
    {
        
       if(id == 2) 
       {
           $('#view_btnr').css( 'display', 'table-row');
           $('#crit_trr').css( 'display', 'table-row');
        
       }
        else
       {
           
           $('#view_btnr').css( 'display', 'none');
           $('#crit_trr').css( 'display', 'none');
       }  
    }

</script>
<form action="<?=$action; ?>" onsubmit="return check_field('<?=lang('input_request')?>','<?=lang('pwd_request')?>')" method="post" enctype="multipart/form-data" id="form">    
    
<div class="box">
  <div class="left"></div>
  <div class="right"></div>
  <div class="heading">
    <h1 style="background-image: url('/img/product.png');"><?=lang('criteriu')?></h1>

    <div class="buttons">
        <span class="button" style="float:left" ><input type=submit value="<?=lang('save')?>" /></span>
        <span class="button" style="float:left" ><input type=submit onclick="$('#form').attr('action','/admin/edit_criteriu/1');" value="<?=lang('save_finish')?>" /></span>
       
        <input type="hidden" name="id" value="<?=((isset($page['id'])=="")?"":"$page[id]")?>" />
        <input type="hidden" name="pid" id="pid" value="<?=((isset($page['id'])=="")?"":"$page[id]")?>" />
        <input type="hidden" name="parent"  value="<?=((isset($parent)=="")?"":"$parent")?>" />
        <a href="/admin/criteriu/<?=$parent?>" class="button"><span><?=lang('cancel')?></span></a>
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
              <td><?=lang('name_after')?>
                  <span class="help">Ex: km,m2,cmc,-- selecteaza --, -- din --..</span>
              </td>
              <td>
                 <input name="page[<?=$lang['id_langs']?>][after_<?=$lang['ext']?>]" value="<?=((isset($page['after_ro'])=="")?"":$page['after_ro'])?>"   size="100" value="" />                
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
            <tr >
              <td>
                <?=lang('type_crit')?> :
               <span class="help">1. Input - for text only<br />2. Select - drop down<br />3. Auto - for Cars<br />4. Truck - for Trucks<br />5. Moto - for Motorcycle</span>
              </td>
              <td>
                  <select name="page[1][type]" onchange="get_type($(this).val())" style="float:left" >
                  <?php
                  $type = array('1'=>'Input','2'=>'Select','3'=>'Auto','4'=>'Zona','5'=>'Truck','6'=>'Moto');                  
                  foreach($type as $k=>$v)
                  {
                      echo "<option value=$k ";
                      if(isset($page) and $page['type']==$k) echo "selected=selected ";                      
                      echo " >$v</option>";
                  }
                  ?>
                  </select>
                  <?php
                  $dspl = 'display:none';      
                  $dspl2 = 'display:none'; 
                  $dspl4 = 'display:none'; 
                  $dspl5 = 'display:none'; 
                  $dspl6 = 'display:none'; 
                  if(isset($page) and $page['type']==2) $dspl = 'visibility:visible';
                  elseif(isset($page) and $page['type']==3) $dspl2 = 'visibility:visible';
                  elseif(isset($page) and $page['type']==5) $dspl5 = 'visibility:visible';
                  elseif(isset($page) and $page['type']==6) $dspl6 = 'visibility:visible';
                  elseif(isset($page) and $page['type']==4) $dspl4 = 'visibility:visible';
                  else $dspl = 'visibility:hidden';                  
                  if(isset($page))
                  { 
                  ?>
                  <div id="view_btn" style="margin-left:10px;float:left;<?=$dspl?>" >
                    <div class="buttons">
                        <a href="javascript:void(0)" class="add_crit button"><span><?=lang('add_criteriu_val')?></span></a>
                    </div>    
                 </div> 
                  <div id="view_btn_auto" style="margin-left:10px;float:left;<?=$dspl2?>" >
                    <div class="buttons">
                        <a href="/admin/auto" target="_blank" class="add_crit button"><span><?=lang('add_view_criteriu_val_auto')?></span></a>
                    </div>    
                 </div>
                  <div id="view_btn_truck" style="margin-left:10px;float:left;<?=$dspl5?>" >
                    <div class="buttons">
                        <a href="/admin/truck" target="_blank" class="add_crit button"><span><?=lang('add_view_criteriu_val_truck')?></span></a>
                    </div>    
                 </div>
                  <div id="view_btn_moto" style="margin-left:10px;float:left;<?=$dspl6?>" >
                    <div class="buttons">
                        <a href="/admin/moto" target="_blank" class="add_crit button"><span><?=lang('add_view_criteriu_val_moto')?></span></a>
                    </div>    
                 </div>
                  <div id="view_btn_zona" style="margin-left:10px;float:left;<?=$dspl4?>" >
                    <div class="buttons">
                        <a href="/admin/zona" target="_blank" class="add_crit button"><span>Adauga/Vezi Criterii Zona</span></a>
                    </div>    
                 </div>                  
                 <?php
                 }
                 ?>
                  
              </td>
            </tr>  
            <tr id="crit_tr" style="<?=$dspl?>;width:100%" >
              <td colspan="2" id="crit_td">
              <?php
              if(isset($criterii_val_list))
              {
                  foreach($criterii_val_list as $val)
                  {
              ?>
                  <div class="c_block" >
                     <img onclick="if (confirm('Esti sigur ca vrei sa stergi')){del_crit_val($(this),'<?=$val['id']?>');return false;}" src="/img/delete.png"  /> 
                     <input name="page_val[<?=$val['id']?>][title_ro]" placeholder="Valoare Criteriu"  value="<?=$val['title_ro']?>" />                
                  </div>                     
              <?php    
                  }    
              }    
              ?>
              </td>              
            </tr> 
            <tr>
              <td>Latime Input/Select(Adaugare/Editare)
                  <span class="help">ex 200 = 200px<br/><b>Daca nu specificam are 360px</b></span>
              </td>
              <td>
                 <input name="page[1][width_input]" value="<?=((isset($page['width_input'])=="")?"":$page['width_input'])?>"   size="20" value="" />px                
              </td>
            </tr>            
            <tr >
              <td>
                Obligatoriu/Ne obligatoriu(Adaugare/Editare) :               
              </td>                
              <td>
                <select name="page[1][request]"  style="float:left">
                  <option value="0">Ne obligatoriu</option>  
                  <option value="1" <?php if(isset($page) and $page['request']==1) echo "selected=selected";?> >Obligatoriu</option>
                  
              </td>              
            </tr>
            <tr >
              <td>
                Apare/Ne Apare(in lista anunturi , extra info) :               
              </td>                
              <td>
                <select name="page[1][on_list]"  style="float:left">
                  <option value="0">Nu Apare(in lista anunturi)</option>  
                  <option value="1" <?php if(isset($page) and $page['on_list']==1) echo "selected=selected";?> >Apare (in lista anunturi)</option>
                  
              </td>              
            </tr>            
            <tr>
              <td>Titlu care apare in lista cautare/anunturi (extra info):
              </td>
              <td>
                 <input name="page[1][title_on_list]" value="<?=((isset($page['title_on_list'])=="")?"":$page['title_on_list'])?>"   size="20" value="" />             
              </td>
            </tr>  
             <tr >
              <td>
                Apare/Ne Apare(in TITLU in lista anunturi , extra info) :               
              </td>                
              <td>
                <select name="page[1][on_title_list]"  style="float:left">
                  <option value="0">Nu Apare(in TITLU din lista anunturi)</option>  
                  <option value="1" <?php if(isset($page) and $page['on_title_list']==1) echo "selected=selected";?> >Apare (in TITLU din lista anunturi)</option>
                  
              </td>              
            </tr>   
            <tr>
              <td>Titlu care apare in filtru
                  <span class="help">daca nu este setat ramine pe cel standart</span>
              </td>
              <td>
                 <input name="page[1][title_filtru]" value="<?=((isset($page['title_filtru'])=="")?"":$page['title_filtru'])?>"   size="20" value="" />               
              </td>
            </tr>             
            <tr>
              <td>
                Apare sau nu in filtru catuare:               
              </td>                
              <td>
                <select name="page[1][on_search]"  style="float:left">
                  <option value="0">Nu apare in filtru</option>  
                  <option value="1" <?php if(isset($page) and $page['on_search']==1) echo "selected=selected";?> >Aparea in filtru</option>                  
                </select>  
              </td>              
            </tr> 
            <tr>
              <td>
                Tip aparitie in filtru search: 
                <span class="help">standart input/select apare numai un input sau select</br>
                    Min/Max apar 2 inputuri sau selecturi max si min</br>
                    De la/Pina apar 2 inputuri sau selecturi max si min</br>
                </span>
              </td>                
              <td>
                <select name="page[1][type_search]"  style="float:left" onchange="get_typee($(this).val())">
                  <option value="1" <?php if(isset($page) and $page['type_search']==1) echo "selected=selected";?> >Input Standart</option>  
                  <option value="2" <?php if(isset($page) and $page['type_search']==2) {echo "selected=selected"; $dspl55 = 'display:block'; }?> >Select Standart </option>                    
                  <option value="3" <?php if(isset($page) and $page['type_search']==3) echo "selected=selected";?> >Input Min/Max</option>                                    
                  <option value="4" <?php if(isset($page) and $page['type_search']==4) {echo "selected=selected";}?> >Select Min/Max</option>                  
                  <option value="5" <?php if(isset($page) and $page['type_search']==5) echo "selected=selected";?> >Input De la/Pina</option>                  
                  <option value="6" <?php if(isset($page) and $page['type_search']==6) echo "selected=selected";?> >Select De la/Pina</option>      
                  <option value="7" <?php if(isset($page) and $page['type_search']==7) echo "selected=selected";?> >Customizate dupa ID</option>                  
                </select>  
              </td>              
            </tr> 
            <?php  if(isset($page) and $page['type_search']==2) $dspl55 = 'display:table-row'; else $dspl55 = 'display:none'; 
           ?>
             <tr id="crit_trr" style="<?=$dspl55?>;width:100%" >
                 
                 <td> <div id="view_btnr" style="margin-left:10px;float:left;<?=$dspl55?>" >
                    <div class="buttons">
                        <a href="javascript:void(0)" class="add_critr button"><span><?=lang('add_criteriu_val')?></span></a>
                    </div>    
                 </div></td>
              <td  id="crit_tdr">
                 
              <?php
              if(isset($criterii_val_list))
              {
                  foreach($criterii_val_list as $val)
                  {
              ?>
                  <div class="c_block" >
                     <img onclick="if (confirm('Esti sigur ca vrei sa stergi')){del_crit_val($(this),'<?=$val['id']?>');return false;}" src="/img/delete.png"  /> 
                     <input name="page_val[<?=$val['id']?>][title_ro]" placeholder="Valoare Criteriu"  value="<?=$val['title_ro']?>" />                
                  </div>                     
              <?php    
                  }    
              }    
              ?>
              </td>              
            </tr> 
            <tr>
              <td>
               Nr de orinde in filtru catuare:               
              </td>                
              <td>
               <input name="page[1][ord_serach]" value="<?=((isset($page['ord_serach'])=="")?"":$page['ord_serach'])?>"   size="20" value="" />               
              </td>              
            </tr> 
            
                  
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

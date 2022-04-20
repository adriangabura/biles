<!-- jquery ui 
<script type='text/javascript' src='<?=base_url()?>js/frontend/jquery-ui-1.8.16.custom.min.js'></script>
<link href="<?=base_url()?>css/smoothness/jquery-ui-1.8.16.custom.css" rel="stylesheet" type="text/css" />
<!--end  jquery ui -->
<script>
$(document).ready(function() {  /*  
    $('#anul').datepicker({
       beforeShow: function(input, inst) {
                                    $("#ui-datepicker-div").hide();
                                },
        buttonImage: '/img/calendar.gif',        
        buttonImageOnly: true,
        changeYear: true,
        showButtonPanel: true,
        dateFormat: 'yy',        
        onClose: function(dateText, inst) {            
            var year = $("#ui-datepicker-div .ui-datepicker-year :selected").val();
            $(this).datepicker('setDate', new Date(year, 1));
        }
    });
  $('#luna').datepicker({                      
        buttonImage: '/img/calendar.gif',
        changeMonth: true,        
        buttonImageOnly: true,
        showButtonPanel: true,
        dateFormat: 'MM',
        
        onClose: function(dateText, inst) { 
            var month = $("#ui-datepicker-div .ui-datepicker-month :selected").val();           
            $(this).datepicker('setDate', new Date(month, 1));
        }
    });    
    */
}); 


</script>
<style>
.ui-datepicker-calendar {
    display: none;
    }
button.ui-datepicker-current { display: none; }

</style>

<div class="box">
    
<form action="/admin/orders" method="post" enctype="multipart/form-data" >      
 <table class="list" style="margin-bottom: 5px;display:none" id="search">    
         <tr class="filter">          
            <td style="width:50px;"><?=lang('order_id')?></td>
            <td style="width:50px;"><input type="text" value="" name="filtru[order_id]" style="width:46px;" /></td>
            <td style="width:50px;" align="right" >Anul</td>
            <td style="width:50px;">
                <select name="filtru[anul]" >
                <option value="">-----------</option>    
                 <?php
                  $curent = date('Y');
                  for ($i = 2011;$i<$curent+10;$i++)
                  {    
                    echo "<option  value=$i  ";
                     //if($key==$order["order_status"]) echo " selected=selected ";
                    echo " >$i</option>";    
                   }
                 ?>
               </select>                
            </td>
            <td style="width:50px;" align="right" >Luna</td>
            <td style="width:50px;">
              <select name="filtru[luna]" >  
               <?php
                $lunile = lunile();
                foreach($lunile as $key=>$value)
                {
                   echo "<option  value=$key  ";
                     //if($key==$order["order_status"]) echo " selected=selected ";
                    echo " >$value</option>";    
                }    
               ?> 
              </select>    
            </td>
            <td align="right" style="width:100px; " ><?=lang('statut_order')?></td>
            <td>
              <select name="filtru[status]" >
                <option value="">-----------</option>      
                 <?php
                  foreach ($statut as $key=>$value)
                  {    
                    echo "<option  value=$key  ";
                     //if($key==$order["order_status"]) echo " selected=selected ";
                    echo " >$value</option>";    
                   }
                 ?>
              </select>
            </td>                        
            <td align="right"  style="width:190px;">
               <div class="buttons">
                <span style="float:right" class="button">
                   <input type="submit" value="<?=lang('filtru')?>">
                </span> 
                <a class="button" id="cancel_filtru" href="#"><span><?=lang('cancel_filtru')?></span></a>        
               </div>
            </td>            
          </tr>            
 </table> 
</form>     
          
  <div class="left"></div>
  <div class="right"></div>
  <div class="heading">
    <h1 style="background-image: url('/img/order.png');"><?=lang('orders')?></h1>
<form action="/admin/del_orders" method="post" enctype="multipart/form-data" id="form">      
    <div class="buttons">
        <span class="button" style="float:right" >
            <input type=submit value="<?=lang('del')?>" onclick="if (confirm('<?=lang('e_sigur_stergi')?>')) return true;else return false;" />
        </span> 
        <a class="button" id="filtru" href="#"><span><?=lang('filtru')?></span></a>        
    </div>
  </div>
  <div class="content">

      <table class="list">
        <thead>
          <tr>
            <td width="1" style="text-align: center;"><input type="checkbox" onclick="$('input[name*=\'selected\']').attr('checked', this.checked);" /></td>
            <td class="center" width="5%"><?=lang('order_id')?></td>
            <td class="center"><?=lang('user')?></td>
            <td class="center"><?=lang('mail')?></td>
            <td class="center"><?=lang('phone')?></td>
            <td class="center"><?=lang('data_order')?></td>
            <td class="center"><?=lang('statut_order')?></td>
            <td class="center"><?=lang('reserv')?></td>
            <td class="center"><?=lang('qt')?></td>
            <td class="center"><?=lang('discount')?></td>
            <td class="center"><?=lang('total')?></td>
            <td class="center"><?=lang('subtotal')?></td>
            <td class="center" width="10%"><?=lang('actions')?></td>
          </tr>
        </thead>
        <tbody> 
          <?php          
          if ($orders) { ?> 
          <?php foreach ($orders as $item) { ?> 
          <tr>
            <td style="text-align: center;">         
              <input type="checkbox" name="selected[]" value="<?=$item['order_id']?>" />
            </td>
            <td class="center"><?=$item['order_id']?></td>   
            <td class="center"><a href="/admin/orders_detail/<?=$item['order_id']?>" class="none" ><?=$item['username']; ?></a></td>           
            <td class="center"><?=$item['email']?></td>  
            <td class="center"><?=$item['phone']?></td>
            <td class="center"><?=data_md($item['created'])?></td>   
            <td class="center"><?php if(array_key_exists($item['order_status'],$statut)) echo $statut[$item['order_status']];?></td>    
            <td class="center"><?=data_db($item['start_data'])?>=<?=data_db($item['end_data'])?></td>
           
            <td class="center"><?=$item['product_count']?></td>
            <td class="center"><?=$item['discount']?> %</td>
            <td class="center"><?=$item['order_total']?></td>
            <td class="center"><?=($item['order_total']-(($item['order_total']*abs($item['discount']))/100))?></td>
            <td class="center">                       
                [<a href="/admin/orders_detail/<?=$item['order_id']?>" ><?=lang('detail')?></a>]  
                [<a href="/admin/orders_form/<?=$item['order_id']?>" ><?=lang('edit')?></a>]
            </td>
          </tr>
          <?php } ?>
          <?php } else { ?>
          <tr>
            <td class="center" colspan="13"><?=lang('no_orders')?></td>
          </tr>
          <?php } ?>
        </tbody>
      </table>
 
  </div>
</form>    
</div>
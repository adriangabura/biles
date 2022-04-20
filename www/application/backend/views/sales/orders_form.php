<script type="text/javascript" >
function show_txt(status)               
{       
    if(status =='cancel') { $('#cancel').show(); }
    else $('#cancel').hide();
}              
</script>
<div class="box">
  <div class="left"></div>
  <div class="right"></div>
  <div class="heading">
    <h1 style="background-image: url('/img/order.png');"><?=lang('edit_orders')?></h1>
<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">    
    <div class="buttons">
        <span class="button" style="float:left" ><input type=submit value="<?=lang('save')?>" /></span>
        <input type="hidden" name="id" value="<?=((isset($order['order_id'])=="")?"":"$order[order_id]")?>" />
        <a href="/admin/orders" style="float:left" class="button"><span><?php echo lang('cancel'); ?></span></a>
    </div>
  </div>
  <div class="content">
      <table class="form">
        <tr>
          <td><?=lang('statut_order')?> :</td>
          <td>            
            <select name="order_status" id="status" onchange="show_txt($(this).val())"  >
             <?php
              foreach ($statut as $key=>$value)
              {    
                echo "<option  value=$key  ";
                 if($key==$order["order_status"]) echo " selected=selected ";
                echo " >$value</option>";    
              }
             ?>
            </select>            
          </td>
        </tr>
         <tr>
          <td><?=lang('discount')?> :</td>
          <td>
            <input type="text" name="discount" class="request" value="<?=((isset($order['discount'])=="")?"":$order["discount"])?>" />                        
          </td>
        </tr>
        <tr id="cancel" <?php if($order["order_status"]!='cancel') echo "style='display:none'";?>  >
          <td><?=lang('canceled_reason')?> :<br /><span class="help"><?=lang('canceled_reason_help')?></span></td>
          <td><textarea  style="height: 150px"  name="canceled_reason" id="canceled_reason" ><?=((isset($order['canceled_reason'])=="")?"":$order['canceled_reason'])?></textarea></td>
        </tr>
        
      </table>
    </form>
  </div>
</div>
<script type="text/javascript" src="<?=base_url()?>js/ckeditor/ckeditor.js"></script>
<script type="text/javascript"><!--
 CKEDITOR.replace('canceled_reason', {});
//--></script>
<div class="box">
  <div class="left"></div>
  <div class="right"></div>
  <div class="heading">
    <h1 style="background-image: url('/img/vars.png');"><?=lang('vars')?></h1>
<form action="<?=$action; ?>" onsubmit="return check_field('<?=lang('input_request_lang')?>')" method="post" enctype="multipart/form-data" id="form">    
    <div class="buttons">
        <span class="button" style="float:left" ><input type=submit value="<?=lang('save')?>" /></span>
        <input type="hidden" name="id" value="<?=((isset($var['id_vars'])=="")?"":"$var[id_vars]")?>" />

        <a href="/admin/vars" class="button"><span><?=lang('cancel')?></span></a>
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
              <td><?=lang('vars_name')?></td>
              <td>
                 <input name="var[<?=$lang['id_langs']?>][<?=$lang['ext']?>]" value="<?=((isset($var[''.$lang['ext']])=="")?"":$var[''.$lang['ext']])?>" size="100" value="" />                     
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

<div class="box">
  <div class="left"></div>
  <div class="right"></div>
  <div class="heading">
    <h1 style="background-image: url('/img/vars.png');"><?=lang('vars')?></h1>
<form action="/admin/del_vars" method="post" enctype="multipart/form-data" id="form">      
    <div class="buttons">
        <span class="button" style="float:right" >
            <input type=submit value="<?=lang('del')?>" onclick="if (confirm('<?=lang('e_sigur_stergi_categ')?>')) return true;else return false;" />
        </span>
        <a href="/admin/vars_form" class="button"><span><?=lang('add_vars')?></span></a>
    </div>
  </div>
  <div class="content">

      <table class="list">
        <thead>
          <tr>      
            <td class="center" width="3%" ><?=lang('id')?></td>
            <td class="left"><?=lang('name')?></td>
            <td class="center" width="6%"><?=lang('actions')?></td>
          </tr>
        </thead>
        <tbody>
          <?php if ($vars) { ?>
          <?php foreach ($vars as $item) { ?>
          <tr>
            <td style="text-align: center;">         
              <?=$item['id_vars']-1?>
            </td>
            <td class="left"><a href="/admin/vars_form/<?=$item['id_vars']?>" class="none" ><?=$item[''.$base_lang]; ?></a></td>           
            <td class="center">
                [<a href="/admin/vars_form/<?=$item['id_vars']?>" ><?=lang('edit')?></a>]                
            </td>
          </tr>
          <?php } ?>
          <?php } else { ?>
          <tr>
            <td class="center" colspan="4"><?=lang('no_vars')?></td>
          </tr>
          <?php } ?>
        </tbody>
      </table>
 
  </div>
</form>    
</div>
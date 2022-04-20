<div class="box">
  <div class="left"></div>
  <div class="right"></div>
  <div class="heading">
    <h1 style="background-image: url('/img/banners.png');"><a href='/admin/baner'><?=lang('baner')?></a><?=$crumbs_baner;?></h1>
<form action="/admin/del_baner/<?=$parent?>" method="post" enctype="multipart/form-data" id="form">
    <div class="buttons">
          <span class="button" style="float:right" >
            <input type=submit value="<?=lang('del')?>" onclick="if (confirm('<?=lang('e_sigur_stergi')?>')) return true;else return false;" />
        </span>
        <a href="/admin/baner_form/<?=$parent?>" class="button"><span><?=lang('add')?></span></a>
    </div>
  </div>
  <div class="content">

      <table class="list">
        <thead>
          <tr>
            <td width="1" style="text-align: center;"><input type="checkbox" onclick="$('input[name*=\'selected\']').attr('checked', this.checked);" /></td>
            <td class="left" width="5%"><?=lang('photo')?></td>
            <td class="left"><?=lang('name')?></td>
            
            <td class="center" width="10%"><?=lang('rubrica')?></td>
            <td class="center" width="10%"><?=lang('categoria')?></td>            
            <td class="center" width="2%">Pozitia pe site</td>
            <td class="center" width="5%"><?=lang('actions')?></td>
          </tr>
        </thead>
        <tbody>

          <?php if ($baner) { ?>
          <?php
          $nr_baner = count($baner);$i=0; 
          $ci = &get_instance();
          foreach ($baner as $item) { 
              $i++;  ?>
          <tr>
            <td style="text-align: center;">         
              <input type="checkbox" name="selected[]" value="<?=$item['id']?>" />
            </td>
            <td class="left" valign="top">
                <a href="/admin/baner_form/<?=$item['parent']?>/<?=$item['id']?>" class="none" >  
                    <?php
                    if($item['photo'] and file_exists($_SERVER['DOCUMENT_ROOT'].'/admin/'.$item['photo']))
                    {        
                    ?>
                        <img src="/admin/<?=$item['photo']?>" height="75" /> 
                    <?php
                    }
                    else
                    {    
                    ?>
                        <img src="/img/no_image.jpg" width="100"  height="75" />                         
                    <?php
                    }
                    ?>
                </a>
            </td>             
            <td class="left" valign="top">
                <a href="/admin/baner_form/<?=$item['parent']?>/<?=$item['id']?>" class="none" >
                    <?=$item['title_'.$base_lang]; ?>                   
                </a>
            </td>
            <td class="center">
                <?php
                    $cat = $this->mysql->get_row('rubrica',array('id' => $item['rubrica_id']));
                    echo $cat['title_ro']
                ?>                
            </td>    
            <td class="center">
                <?php
                    $cat = $this->mysql->get_row('categoria',array('id' => $item['categoria_id']));
                    echo $cat['title_ro']
                ?>
            </td> 
            <td class="center">
                <?=$item['position']?>
            </td>             
            <td class="center">
                [<a href="/admin/baner_form/<?=$item['parent']?>/<?=$item['id']?>" ><?=lang('edit')?></a>]
                <!-- [<a href="/admin/add_terms/<?=$item['id']?>" >[<?=lang('add_terms')?></a>] -->
            </td>
          </tr>
          <?php } ?>
          <?php } else { ?>
          <tr>
            <td class="center" colspan="5"><?=lang('no_items')?></td>
          </tr>
          <?php } ?>
        </tbody>
      </table>
     <div class="pagination" style="margin-top: 0px;text-align: center" >
          <?=$pagination?>
    </div>      
 
  </div>
</form>    
</div>

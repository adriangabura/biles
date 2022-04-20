<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title><?=((isset($title_page)=="")?"Admin":"$title_page")?></title>
    <?php
    $meta = array(
        array('name' => 'Content-type', 'content' => 'text/html; charset=utf-8', 'type' => 'equiv'),
        array('name' => 'description', 'content' => 'All by WEBSTYLE'),
        array('name' => 'keywords', 'content' => 'Codeigniter ,Jquery ,Ajax')
    );
    echo meta($meta);
    echo link_tag('admin/css/style.css');
    echo link_tag('favicon.ico','shortcut icon','img/favicon.ico');
    ?>

    <script type="text/javascript" src="<?=base_url()?>admin/js/jquery/jquery.js"></script>
    <script type="text/javascript" src="<?=base_url()?>admin/js/jquery/custom_admin.js"></script>
    <!-- meniul-->
    <script type="text/javascript" src="<?=base_url()?>admin/js/jquery/superfish/js/superfish.js"></script>
    <!-- tabs --> 
    <script type="text/javascript" src="<?=base_url()?>admin/js/jquery/jquery.tools.min.js"></script>
    <!--
    <script type="text/javascript" src="<?=base_url()?>js/jquery/tab.js"></script>
    <script type="text/javascript" src="<?=base_url()?>js/jquery/tab.js"></script>
    --> 



</head>
<body> 
<div id="container">
  <div id="header">
    <div class="div1"><?=$company?></div>
    <div class="div2"><img src="<?=base_url()?>admin/img/lock.png" alt="" style="position: relative; top: 3px;" />&nbsp;<?=((isset($user_name)=="")?"":"$user_name")?></div>
  </div>
  <div id="menu">
   <?=$this->load->view('menu_top')?>
  </div>    
  <div id="content" >
    <?php if ($breadcrumbs) { ?>
    <div class="breadcrumb">
      <?php foreach ($breadcrumbs as $breadcrumb) { ?>
      <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
      <?php } ?>
    </div>
    <?php } ?> 
      
<?php if (isset($errors) and ($errors)) { ?>
<div class="warning"><?php echo $errors; ?></div>
<?php } ?>

<?php if(isset($succes) and ($succes)) { ?>
<div class="succes"><?php echo $succes; ?></div>
<?php } ?>

<div id="msg"></div>
      
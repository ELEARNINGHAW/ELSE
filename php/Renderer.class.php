<?php

require_once '../smarty/libs/Smarty.class.php';

class Renderer
{

var $smarty;
var $Util;

function   __construct( $CONFIG, $Util  )
{
  $this->smarty = new Smarty;
  $this->Util = $Util;
  $conf = $CONFIG->getConf();
  $this->smarty->compile_dir   = $conf['templates_compile_dir'];
  $this->smarty->template_dir  = "../templates";
  $this->smarty->config_dir    = "../configs";
  $this->smarty->compile_check = true;
  $this->smarty->addTemplateDir('./templates');
}

function smarty_init()
{
}

function doRedirect( $url = "index.php?category=X" )
{
  $this->smarty->assign("url", $url);
  header("Location: $url");
	$this->smarty->display('header.tpl');
//	$this->smarty->display('redirect.tpl');
//	$this->smarty->display('footer.tpl');
	exit(0);
}

function do_template( $template, $kw, $HuF = true )
{
  foreach ($kw as $k => $v)   {  $this->smarty->assign($k, $v); }

  if ($HuF) { $this->smarty->display('header.tpl'); }

  $this->smarty->display($template);

  if ($HuF) {  $this->smarty->display('footer.tpl');}

  $_SESSION['last_page'] = $_SERVER['REQUEST_URI'];
}

}

?>

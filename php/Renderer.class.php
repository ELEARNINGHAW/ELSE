<?php
require_once '../smarty/libs/Smarty.class.php';

class Renderer
{

var $smarty;
var $Util;

function   __construct(  $Util  )
{
  $conf_cwd = $_SESSION[ 'CFG' ][ 'SERVER' ];

  $this->smarty = new Smarty;
  $this->Util = $Util;
  $this->smarty->compile_dir   = $conf_cwd['templates_compile_dir'];
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

  
  
  if ($HuF) {
header("Expires: ".gmdate("D, d M Y H:i:s")." GMT");
header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Pragma: no-cache");
header("Cache-Control: post-check=0, pre-check=0", false);
    $this->smarty->display('header.tpl');
  }

  $this->smarty->display($template);

  if ($HuF) {  $this->smarty->display('footer.tpl');}

  $_SESSION['last_page'] = $_SERVER['REQUEST_URI'];
}

}

?>

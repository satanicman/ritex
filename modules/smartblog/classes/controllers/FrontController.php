<?php

class smartblogModuleFrontController extends ModuleFrontController
{
  public $ssl = false;
	public function initContent()
	{
	    parent::initContent();
$colums = Context::getContext()->theme->hasColumns(Context::getContext()->controller->page_name);
if($colums)
{
$hide_column_left = isset($colums['left_column']) && !empty($colums['left_column'])?0:1;
$hide_column_right = isset($colums['right_column']) && !empty($colums['right_column'])?0:1;
}
            if($id_category = Tools::getvalue('id_category') && Tools::getvalue('id_category') != Null){
                 $this->context->smarty->assign(BlogCategory::GetMetaByCategory(Tools::getvalue('id_category')));
            }
            if($id_post = Tools::getvalue('id_post')  && Tools::getvalue('id_post') != Null){
                 $this->context->smarty->assign(SmartBlogPost::GetPostMetaByPost(Tools::getvalue('id_post')));
            }
            if(Tools::getvalue('id_category') == Null  && Tools::getvalue('id_post') == Null){
              $meta['meta_title'] = Configuration::get('smartblogmetatitle');
              $meta['meta_description'] = Configuration::get('smartblogmetadescrip');
              $meta['meta_keywords'] = Configuration::get('smartblogmetakeyword');
              $this->context->smarty->assign($meta);
            }
              if(Configuration::get('smartshowcolumn') == 0){
                  $this->context->smarty->assign(array(
          'HOOK_LEFT_COLUMN' => Hook::exec('displaySmartBlogLeft'),
          'hide_right_column' => '',
			    'hide_left_column' => '',
			    'HOOK_RIGHT_COLUMN' => Hook::exec('displaySmartBlogRight')
			));
              }elseif(Configuration::get('smartshowcolumn') == 1){
                   $this->context->smarty->assign(array(
          'HOOK_LEFT_COLUMN' => Hook::exec('displaySmartBlogLeft'),
          'hide_right_column' => '1',
          'hide_left_column' => '',
          'HOOK_RIGHT_COLUMN' => ''
      )); 
              }elseif(Configuration::get('smartshowcolumn') == 2){

                    $this->context->smarty->assign(array(
          'HOOK_LEFT_COLUMN' => '',
          'hide_right_column' => '',
          'hide_left_column' => '1',
          'HOOK_RIGHT_COLUMN' => Hook::exec('displaySmartBlogRight')
      ));
              }elseif(Configuration::get('smartshowcolumn') == 3){

                  $this->context->smarty->assign(array(
          'hide_right_column' => $hide_column_right,
          'hide_left_column' => $hide_column_left,
      ));
              }else{
                  $this->context->smarty->assign(array(
			    'HOOK_LEFT_COLUMN' => Hook::exec('displaySmartBlogLeft'),
          'hide_right_column' => '',
          'hide_left_column' => '',
			    'HOOK_RIGHT_COLUMN' => Hook::exec('displaySmartBlogRight')
			));   
              } 
        }
}
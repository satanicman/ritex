<?php

require_once(dirname(__FILE__).'/../../config/config.inc.php');
require_once(dirname(__FILE__).'/../../init.php');
include_once(dirname(__FILE__).'/modelfilter.php');

$modelfilter = new Modelfilter();
$parentId = Tools::getValue('categoryId');

$errors = array();
if (!$parentId)
    $errors[] = $modelfilter->l('You must choose category', 'ajax');
if (!(Category::categoryExists($parentId)))
    $errors[] = $modelfilter->l('Category not found', 'ajax');

if (!empty($errors))
    die(Tools::jsonEncode(array('hasError' => true, 'errors' => $errors)));
else {
    $childrenCategoryes = Category::getChildren((int)$parentId, (int)$context->language->id);
    foreach($childrenCategoryes as $key => $childrenCategorye) {
        $childrenCategoryes[$key]['has_children'] = Category::hasChildren((int)$childrenCategorye['id_category'], (int)$context->language->id);
    }
    die(Tools::jsonEncode($childrenCategoryes));
}
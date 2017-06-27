<?php

if (!defined('_PS_VERSION_'))

    exit;

class Homepagefilter extends Module

{
    protected $spacer_size = '5';
    
    public function __construct()
    {
        $this->name = 'homepagefilter';
        $this->tab = 'other';
        $this->version = '0.1';
        $this->author = 'vk.com/id24260100';
        $this->need_instance = 0;
        $this->ps_versions_compliancy = array('min' => '1.6', 'max' => _PS_VERSION_);
        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->l('Home page filter');
        $this->description = $this->l('Filter by category and attribute on home page');

        $this->confirmUninstall = $this->l('Are you sure you want to uninstall?');

        if (!Configuration::get('MUMODULE_NAME'))
            $this->warning = $this->l('No name provided');
    }

    public function registerHooks()
    {

        foreach ($this->hooks as $hook) {
            if (!$this->registerHook($hook)) {
                $this->_errors[] = "Failed to install hook '$hook'<br />\n";
                return false;
            }
        }

        return true;
    }

    public function unregisterHooks()
    {

        foreach ($this->hooks as $hook) {
            if (!$this->unregisterHook($hook)) {
                $this->_errors[] = "Failed to uninstall hook '$hook'<br />\n";
                return false;
            }
        }

        return true;
    }

    public function install()
    {
        $this->_clearCache('homepagefilter.tpl');

        if (!parent::install()
            || !$this->registerHook('header')
            || !$this->registerHook('displayHomeTabContent')
            || !Configuration::updateValue('HOMEPAGEFILTER_CATEGORY', '')
            || !Configuration::updateValue('HOMEPAGEFILTER_FEATURES', '')
        )
            return false;

        return true;
    }

    public function uninstall()
    {
        $this->_clearCache('homepagefilter.tpl');

        if (!parent::uninstall()
            || !$this->unregisterHook('header')
            || !$this->unregisterHook('displayHomeTabContent')
            || !Configuration::deleteByName('HOMEPAGEFILTER_CATEGORY')
            || !Configuration::deleteByName('HOMEPAGEFILTER_FEATURES')
        )
            return false;

        return true;

    }

    public function renderForm()
    {
        $fields_form = array(
            'form' => array(
                'legend' => array(
                    'title' => $this->l('Home page filter links'),
                    'icon' => 'icon-link'
                ),
                'input' => array(
                    array(
                        'type' => 'category_choice',
                        'label' => $this->l('Category'),
                        'name' => 'Category',
                        'lang' => true,
                    ),
                    array(
                        'type' => 'feature_choice',
                        'label' => false,
                        'name' => 'Features',
                        'lang' => true,
                    )
                ),
                'submit' => array(
                    'name' => 'submit_'.$this->name,
                    'title' => $this->l('Save')
                )
            ),
        );

        $helper = new HelperForm();
        $helper->show_toolbar = false;
        $helper->table = $this->table;
        $lang = new Language((int)Configuration::get('PS_LANG_DEFAULT'));
        $helper->default_form_language = $lang->id;
        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') ? Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') : 0;
        $this->fields_form = array();
        $helper->module = $this;
        $helper->identifier = $this->identifier;
        $helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false).
            '&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->tpl_vars = array(
            'fields_value' => array(
                'HOMEPAGEFILTER_CATEGORY' => Configuration::get('HOMEPAGEFILTER_CATEGORY')
            ),
            'languages' => $this->context->controller->getLanguages(),
            'id_language' => $this->context->language->id,
            'choices_category' => $this->renderChoicesSelect('category'),
            'choices_feature' => $this->renderChoicesSelect('features'),
            'selected_links_feature' => $this->makeMenuOption('features'),
        );
        return $helper->generateForm(array($fields_form));
    }

    public function renderChoicesSelect($type)
    {
        $spacer = str_repeat('&nbsp;', $this->spacer_size);
        $html = '';
        switch ($type) {
            case 'category' :
                // BEGIN Categories
                $html .= '<select class="' . $type . '" name ="' . $type . '">';
                $item = Configuration::get('HOMEPAGEFILTER_CATEGORY');
                $html .= $this->generateCategoriesOption($this->customGetNestedCategories($this->context->shop->id, null, (int)$this->context->language->id, false), $item);
                break;
            case 'features' :
                // BEGIN features
                $html .= '<select multiple="multiple" class="availableItems" style="width: 300px; height: 160px;">';
                $items = explode('|', Configuration::get('HOMEPAGEFILTER_FEATURES'));
                $html .= '<optgroup label="' . $this->l('Features') . '">';
                foreach (Feature::getFeatures($this->context->language->id) as $feature) {
                    if(in_array($feature['id_feature'], $items))
                        continue;
                    $html .= '<option value="' . $feature['id_feature'] . '">' . $feature['name'] .'</option>';
                }
                $html .= '</optgroup>';
                break;
        }

        $html .= '</select>';
        return $html;
    }


    protected function makeMenuOption($type)
    {
        $html = '<select multiple="multiple" name="' . $type . '[]" class="items" style="width: 300px; height: 160px;">';
        switch ($type) {
            case 'categories' :
                foreach (explode('|', Configuration::get('HOMEPAGEFILTER_CATEGORY')) as $id) {
                    if (!$id) {
                        continue;
                    }

                    $category = new Category((int)$id, (int)$this->context->language->id);
                    if (Validate::isLoadedObject($category)) {
                        $html .= '<option selected="selected" value="'.$id.'">'.$category->name.'</option>'.PHP_EOL;
                    }
                }
                break;
            case 'features' :
                foreach (explode('|', Configuration::get('HOMEPAGEFILTER_FEATURES')) as $id) {
                    if (!$id) {
                        continue;
                    }

                    $feature = new Feature((int)$id, (int)$this->context->language->id);
                    if (Validate::isLoadedObject($feature)) {
                        $html .= '<option selected="selected" value="'.$id.'">'.$feature->name.'</option>'.PHP_EOL;
                    }
                }
                break;
        }

        return $html.'</select>';
    }


    protected function generateCategoriesOption($categories, $selected = null)
    {
        $html = '';

        foreach ($categories as $key => $category) {
            $html .= '<option value="'.(int)$category['id_category'].'"' . ((int)$category['id_category'] == (int)$selected ? 'selected="selected"' : '') . '>'
                    .str_repeat('&nbsp;', $this->spacer_size * (int)$category['level_depth']).$category['name'].'</option>';

            if (isset($category['children']) && !empty($category['children'])) {
                $html .= $this->generateCategoriesOption($category['children'], $selected);
            }
        }
        return $html;
    }


    public function customGetNestedCategories($shop_id, $root_category = null, $id_lang = false, $active = false, $groups = null, $use_shop_restriction = true, $sql_filter = '', $sql_sort = '', $sql_limit = '')
    {
        if (isset($root_category) && !Validate::isInt($root_category)) {
            die(Tools::displayError());
        }

        if (!Validate::isBool($active)) {
            die(Tools::displayError());
        }

        if (isset($groups) && Group::isFeatureActive() && !is_array($groups)) {
            $groups = (array)$groups;
        }

        $cache_id = 'Category::getNestedCategories_'.md5((int)$shop_id.(int)$root_category.(int)$id_lang.(int)$active.(int)$active
            .(isset($groups) && Group::isFeatureActive() ? implode('', $groups) : ''));

        if (!Cache::isStored($cache_id)) {
            $result = Db::getInstance()->executeS('
                            SELECT c.*, cl.*
                FROM `'._DB_PREFIX_.'category` c
                INNER JOIN `'._DB_PREFIX_.'category_shop` category_shop ON (category_shop.`id_category` = c.`id_category` AND category_shop.`id_shop` = "'.(int)$shop_id.'")
                LEFT JOIN `'._DB_PREFIX_.'category_lang` cl ON (c.`id_category` = cl.`id_category` AND cl.`id_shop` = "'.(int)$shop_id.'")
                WHERE 1 '.$sql_filter.' '.($id_lang ? 'AND cl.`id_lang` = '.(int)$id_lang : '').'
                '.($active ? ' AND (c.`active` = 1 OR c.`is_root_category` = 1)' : '').'
                '.(isset($groups) && Group::isFeatureActive() ? ' AND cg.`id_group` IN ('.implode(',', $groups).')' : '').'
                '.(!$id_lang || (isset($groups) && Group::isFeatureActive()) ? ' GROUP BY c.`id_category`' : '').'
                '.($sql_sort != '' ? $sql_sort : ' ORDER BY c.`level_depth` ASC').'
                '.($sql_sort == '' && $use_shop_restriction ? ', category_shop.`position` ASC' : '').'
                '.($sql_limit != '' ? $sql_limit : '')
            );

            $categories = array();
            $buff = array();

            foreach ($result as $row) {
                $current = &$buff[$row['id_category']];
                $current = $row;

                if ($row['id_parent'] == 0) {
                    $categories[$row['id_category']] = &$current;
                } else {
                    $buff[$row['id_parent']]['children'][$row['id_category']] = &$current;
                }
            }

            Cache::store($cache_id, $categories);
        }

        return Cache::retrieve($cache_id);
    }

    public function getContent()
    {
        $message = '';

        if (Tools::isSubmit('submit_'.$this->name))
            $message = $this->_saveContent();

        return $message.$this->renderForm();
    }

    private function _saveContent()
    {
        $message = '';
        if(Configuration::updateValue('HOMEPAGEFILTER_CATEGORY', Tools::getValue('category')) &&
            Configuration::updateValue('HOMEPAGEFILTER_FEATURES', implode("|", Tools::getValue('features'))))
            $message = $this->displayConfirmation($this->l('Settings saved successfully.'));
        else
            $message = $this->displayWarning($this->l('Something went wrong!'));

        return $message;
    }

    public function hookHeader($params)
    {
        $this->context->controller->addCSS($this->_path.'modelfilter.css', 'all');
        $this->context->controller->addJS($this->_path.'modelfilter.js');
    }
    public function hookdisplayHomeTabContent($params)
    {
        $features = array();

        $category = new Category(Configuration::get('HOMEPAGEFILTER_CATEGORY'), $this->context->language->id);
        $categories[] = $category->recurseLiteCategTree();

        foreach(explode('|', Configuration::get('HOMEPAGEFILTER_FEATURES')) as $id) {
            $feature = new Feature($id, $this->context->language->id);
            $features[$id]['name'] = $feature->name;
            $values = $this->getUrlFeatureValue(FeatureValue::getFeatureValuesWithLang($this->context->language->id, $id));
            foreach ($values as &$value) {
                $f = Feature::getFeature($this->context->language->id, $value['id_feature']);
                $value['feature_url'] = str_replace('-', '_', Tools::link_rewrite($f['name']));
            }
            $features[$id]['values'] = $values;
        }

        if (file_exists(_PS_THEME_DIR_.'modules/homepagefilter/homepagefilter.tpl'))
            $this->smarty->assign('branche_tpl_path', _PS_THEME_DIR_.'modules/homepagefilter/homepagefilter-category-branch.tpl');
        else
            $this->smarty->assign('branche_tpl_path', _PS_MODULE_DIR_.'homepagefilter/homepagefilter-category-branch.tpl');

        $result = array(
            'categories' => $categories,
            'features' => $features
        );

        $this->context->smarty->assign($result);
        return $this->display(__FILE__, 'homepagefilter.tpl');
    }

    protected function getUrlFeatureValue($data){
        if (!$anchor = Configuration::get('PS_ATTRIBUTE_ANCHOR_SEPARATOR'))
            $anchor = '-';

        foreach ($data as &$part){
            $part['url_name'] = str_replace($anchor, '_', Tools::link_rewrite($part['value']));
        }
        return $data;
    }
}
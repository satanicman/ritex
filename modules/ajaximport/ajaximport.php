<?php

if (!defined('_PS_VERSION_'))
    exit;

include_once(dirname(__FILE__) . "/tools/phpExcel/PHPExcel.php");
include_once(dirname(__FILE__) . "/tools/phpExcel/PHPExcel/IOFactory.php");
include_once(dirname(__FILE__) . '/AjaxImportModel.php');
include_once(dirname(__FILE__) . '/ImportXml.php');
include_once(dirname(__FILE__) . '/ImportExcel.php');

class ajaximport extends Module

{
    protected $xml;
    protected $excel;
    protected $messages = '';
    protected $count = 0;
    protected $total = 0;
    protected $step = 0;
    protected $step_count = 0;
    protected $override = array(
        'AttributeGroup',
        'Category',
        'Feature',
        'Product',
    );

    public function __construct()
    {
        $this->name = 'ajaximport';
        $this->tab = 'other';
        $this->version = '0.1.4';
        $this->author = 'http://vk.com/id24260100';
        $this->need_instance = 0;
        $this->ps_versions_compliancy = array('min' => '1.6', 'max' => _PS_VERSION_);
        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->l('ajaximport');
        $this->description = $this->l('ajax import products from xml and excel file');

        $this->confirmUninstall = $this->l('Are you sure you want to uninstall?');

        if (!Configuration::get('MUMODULE_NAME'))
            $this->warning = $this->l('No name provided');
    }

    public function install()
    {
        foreach ($this->override as $fileName) {
            if (file_exists(_PS_ROOT_DIR_ . "/override/classes/".$fileName.".php")) {
                $this->_errors[] = Tools::displayError(sprintf($this->l('Please delete your files %s.php from override folder.'), $fileName));
            }
        }

        if(count($this->_errors)) {
            $this->_errors[] = Tools::displayError($this->l('Please delete class_index.php in cache folder.'));
            return false;
        }

        if (!parent::install() ||
            !Configuration::updateValue('AJAXIMPORT_CATEGORY', Configuration::get('PS_HOME_CATEGORY')) ||
            !Configuration::updateValue('AJAXIMPORT_NBR_PRODUCTS', 1) ||
            !AjaxImportModel::addFields())
            return false;

        return true;
    }

    public function uninstall()
    {
        if (!parent::uninstall() ||
            !AjaxImportModel::rmFields())
            return false;

        return true;

    }

    public function renderForm()
    {
        $fields_form = array(
            'form' => array(
                'legend' => array(
                    'title' => $this->l('Ajax import from xml and excel'),
                    'icon' => 'icon-link'
                ),
                'input' => array(
                    array(
                        'type' => 'progress',
                        'label' => $this->l('Progress'),
                        'name' => 'progress'
                    ),
                    array(
                        'type' => 'file',
                        'label' => $this->l('Products (import.xml)'),
                        'id' => 'products',
                        'name' => 'products',
                        'value' => true
                    ),
                    array(
                        'type' => 'file',
                        'label' => $this->l('Prices and quantity (offers.xml)'),
                        'id' => 'prices',
                        'name' => 'prices',
                        'value' => true
                    ),
                    array(
                        'type' => 'file',
                        'label' => $this->l('Excel properties (привязка.xlsx)'),
                        'id' => 'excel',
                        'name' => 'excel',
                        'value' => true
                    ),
                    array(
                        'type' => 'text',
                        'label' => $this->l('Id home category'),
                        'name' => 'AJAXIMPORT_CATEGORY',
                        'class' => 'fixed-width-xs',
                        'desc' => sprintf($this->l('Id category where another category was sets (default: %d).'), Configuration::get('PS_HOME_CATEGORY')),
                    ),
//                    array(
//                        'type' => 'text',
//                        'label' => $this->l('Number products'),
//                        'name' => 'AJAXIMPORT_NBR_PRODUCTS',
//                        'class' => 'fixed-width-xs',
//                        'desc' => $this->l('Number of products which set in one time (default: 10).'),
//                    )
                ),
                'submit' => array(
                    'name' => 'submit' . $this->name,
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
            'fields_value' => $this->getConfigFieldsValues(),
            'languages' => $this->context->controller->getLanguages(),
            'id_language' => $this->context->language->id
        );
        return $helper->generateForm(array($fields_form));
    }

    public function getConfigFieldsValues()
    {
        return array(
            'AJAXIMPORT_CATEGORY' => Tools::getValue('AJAXIMPORT_CATEGORY', (string)Configuration::get('AJAXIMPORT_CATEGORY')),
            'AJAXIMPORT_NBR_PRODUCTS' => Tools::getValue('AJAXIMPORT_NBR_PRODUCTS', (string)Configuration::get('AJAXIMPORT_NBR_PRODUCTS')),
        );
    }

    public function getContent()
    {
        $this->context->controller->addCSS(($this->_path).'css/admin.css');
        $message = '';

        if (Tools::isSubmit('submit' . $this->name))
            $message = $this->_saveContent();

        return $message.$this->renderForm();
    }

    private function _saveContent()
    {

        Configuration::updateValue('AJAXIMPORT_CATEGORY', Tools::getValue('AJAXIMPORT_CATEGORY', Configuration::get('PS_HOME_CATEGORY')));
        Configuration::updateValue('AJAXIMPORT_NBR_PRODUCTS', Tools::getValue('AJAXIMPORT_NBR_PRODUCTS', 1));

        $message = $this->displayConfirmation($this->l("Success"));

        return $message;
    }

    public function ajaxCall() {
        $this->step_count = Tools::getValue('step_count', 0);
        $this->step = Tools::getValue('step', 0);
        $this->xml = new ImportXml();
        $this->excel = new ImportExcel();

        set_time_limit(10000000000);
        error_reporting(1);

        if(isset($_FILES['products']) && !$_FILES['products']['error']) {
            $this->importProducts($_FILES['products']);
        }
        if(isset($_FILES['prices']) && !$_FILES['prices']['error']) {
            $this->importPrices($_FILES['prices']);
        }
        if(isset($_FILES['excel']) && !$_FILES['excel']['error']) {
            $this->importExcel($_FILES['excel']);
        }


        $result = array(
            'answer' => $this->messages,
            'count' => $this->xml->counter + $this->excel->counter,
            'total' => $this->total,
            'step_count' => $this->step_count,
            'step' => $this->step,
        );

        die(Tools::jsonEncode($result));
    }

    private function importProducts($file) {
        $xml = simplexml_load_file($file['tmp_name']);
        $this->setStep(1, $xml->{'Классификатор'}->{'Группы'}->{'Группа'});
        $this->setStep(2, $xml->{'Классификатор'}->{'Свойства'}->{'СвойствоНоменклатуры'});
        $this->setStep(3, $xml->{'Каталог'}->{'Товары'}->{'Товар'});
    }

    private function importPrices($file) {
        $xml = simplexml_load_file($file['tmp_name']);
        $this->setStep(4, $xml->{'ПакетПредложений'}->{'Предложения'}->{'Предложение'});
    }

    private function importExcel($file) {
        $sheet = $this->createObjectReader($file);
        $this->setStep(5, $sheet, true);
    }

    private function setStep($nbr, $array, $excel = false) {
        if($excel)
            $count = $array->getHighestRow();
        else
            $count = count($array);
        $this->total += $count;
        if($count && $this->step_count < $count && $this->xml->counter < Configuration::get('AJAXIMPORT_NBR_PRODUCTS') && $this->step <= $nbr) {
            $this->step = $nbr;

            switch ($nbr) {
                case 1:
                    $this->messages .= $this->xml->setCategories($array, $this->step_count);
                    break;
                case 2:
                    $this->messages .= $this->xml->setFeatures($array, $this->step_count);
                    break;
                case 3:
                    $this->messages .= $this->xml->setProducts($array, $this->step_count);
                    break;
                case 4:
                    $this->messages .= $this->xml->updateProductPrice($array, $this->step_count);
                    break;
                case 5:
                    $this->messages .= $this->excel->parseExcel($array, $this->step_count);
                    break;
                default:
                    die('!Error');
                    break;
            }

            $this->step_count += Configuration::get('AJAXIMPORT_NBR_PRODUCTS');

            if($this->step_count >= $count) {
                $this->step++;
                $this->step_count = 0;
            }
        }
    }

    private function createObjectReader($file = Null)
    {
        if (is_null($file))
            return array('error' => 'File not initialize');

        if ($file['type'] == 'application/vnd.ms-excel')
            $objReader = PHPExcel_IOFactory::createReader('Excel5');
        else
            $objReader = PHPExcel_IOFactory::createReader('Excel2007');

        $objReader->setReadDataOnly(true);
        // Открываем файл
        $objPHPExcel = $objReader->load($file['tmp_name']);
        // Устанавливаем индекс активного листа
        $objPHPExcel->setActiveSheetIndex(0);
        // Получаем активный лист
        $sheet = $objPHPExcel->getActiveSheet();

        return $sheet;
    }
}
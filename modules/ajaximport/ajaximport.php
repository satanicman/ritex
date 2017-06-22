<?php

if (!defined('_PS_VERSION_'))
    exit;

include_once(dirname(__FILE__) . "/tools/phpExcel/PHPExcel.php");
include_once(dirname(__FILE__) . "/tools/phpExcel/PHPExcel/IOFactory.php");
include_once(dirname(__FILE__) . '/AjaxImportModel.php');
include_once(dirname(__FILE__) . '/ImportExcel.php');

class ajaximport extends Module

{
    protected $excel;
    protected $messages = '';
    protected $reader = array();
    protected $count = 0;
    protected $total = 0;
    protected $step_total = array();
    protected $step = 0;
    protected $from = 0;
    protected $lines = array();
    protected $override = array(
        'Product',
    );

    public function __construct()
    {
        $this->name = 'ajaximport';
        $this->tab = 'other';
        $this->version = '0.0.4';
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
                        'label' => $this->l('Omega'),
                        'id' => 'omega',
                        'name' => 'omega',
                        'value' => true
                    ),
                    array(
                        'type' => 'file',
                        'label' => $this->l('Continental'),
                        'id' => 'continental',
                        'name' => 'continental',
                        'value' => true
                    )
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
        $this->context->controller->addJS($this->_path.'ajaximport_admin.js');
        $this->context->controller->addCSS(($this->_path).'css/admin.css');
        $message = '';

        if (Tools::isSubmit('submit' . $this->name))
            $message = $this->_saveContent();

        return $message.$this->renderForm();
    }

    private function _saveContent()
    {
        $message = $this->displayConfirmation($this->l("Success"));

        return $message;
    }

    public function ajaxCall() {
        $total = 0;
        $this->from = Tools::getValue('step_count', 0);
        $this->step = Tools::getValue('step', 0);
        $values = Tools::getValue('values', 0);
        $this->excel = new ImportExcel();

        set_time_limit(10000000000);
        error_reporting(1);

        if(isset($_FILES['omega']) && !$_FILES['omega']['error'] && !$values) {
//            $this->importOmega($_FILES['omega']);
            $this->parseFiles($_FILES['omega'], 'omega');
        }
        if(isset($_FILES['continental']) && !$_FILES['continental']['error'] && !$values) {
//            $this->importContinental($_FILES['continental']);
            $this->parseFiles($_FILES['continental'], 'continental');
        }

//        $this->excel->parseExcel($this->reader[$this->step], $this->from);

        $result = array(
            'message' => $this->messages,
            'from' => $this->from
        );



        die(Tools::jsonEncode($result));
    }

    private function importOmega($file) {
        $step = 1;
        $this->reader[$step] = $this->createObjectReader($file);

        if($this->step == 0) {
            $this->total += $this->step_total[$step] = $this->getTotal($this->reader[$step]);

            if($this->step_total[$step] > $this->from)
                $this->step = $step;
            else
                $this->step = $step + 1;
        }
    }

    private function importContinental($file) {
        $step = 2;
        $this->reader[$step] = $this->createObjectReader($file);

        if($this->step == 0) {
            $this->total += $this->step_total[$step] = $this->getTotal($this->reader[$step]);

            if($this->step_total[$step] > $this->from)
                $this->step = $step;
            else
                $this->step = $step + 1;
        }
    }

    private function setStep($nbr, $reader) {
        if($this->from >= $this->step_total[$nbr] || $this->excel->counter >= Configuration::get('AJAXIMPORT_NBR_PRODUCTS'))
            return;

        if($this->excel->counter)
        if($count && $this->from) {
            $this->step = $nbr;

            switch ($nbr) {
                case 1:
                    $this->messages .= $this->xml->setCategories($reader, $this->from);
                    break;
                case 2:
                    $this->messages .= $this->xml->setFeatures($reader, $this->from);
                    break;
                case 3:
                    $this->messages .= $this->xml->setProducts($reader, $this->from);
                    break;
                case 4:
                    $this->messages .= $this->xml->updateProductPrice($reader, $this->from);
                    break;
                case 5:
                    $this->messages .= $this->excel->parseExcel($reader, $this->from);
                    break;
                default:
                    die('!Error');
                    break;
            }

            $this->from += Configuration::get('AJAXIMPORT_NBR_PRODUCTS');

            if($this->from >= $count) {
                $this->step++;
                $this->from = 0;
            }
        }
    }

    private function createObjectReader($file = Null)
    {
        if ($file['type'] == 'application/vnd.ms-excel')
            $objReader = PHPExcel_IOFactory::createReader('Excel5');
        else
            $objReader = PHPExcel_IOFactory::createReader('Excel2007');

        $objReader->setReadDataOnly(true);
        // Открываем файл
        $objPHPExcel = $objReader->load($file['tmp_name']);

        return $objPHPExcel;
    }

    private function getTotal($reader)
    {
        $total = 0;

        for ($i = 0; $i < $reader->getSheetCount(); $i++) {
            $reader->setActiveSheetIndex($i);
            $sheet =  $reader->getActiveSheet();
            $total += $sheet->getHighestRow();
        }

        return $total;

    }

    protected function parseFiles($file, $name) {
        $reader = $this->createObjectReader($file);

        for($i = 0; $i < $reader->getSheetCount(); $i++) {
            $reader->setActiveSheetIndex($i);
            $sheet = $reader->getActiveSheet();
            for($j = $this->excel->excelColumns[$name]['line']; $j < $sheet->getHighestRow(); $j++) {
                $result = array(
                    'sheet' => (string)$sheet->getTitle(),
                    'name' => (string)$sheet->getCellByColumnAndRow($this->excel->excelColumns[$name]['fields']['name'], $j)->getValue(),
                    'reference' => (string)$sheet->getCellByColumnAndRow($this->excel->excelColumns[$name]['fields']['reference'], $j)->getValue(),
                    'price' => (float)$sheet->getCellByColumnAndRow($this->excel->excelColumns[$name]['fields']['price'], $j)->getValue()
                );

                if(!$result['name'] || !$result['reference'] || !$result['price'])
                    continue;

                if(isset($this->excel->excelColumns[$name]['fields']['features'])) {
                    foreach ($this->excel->excelColumns[$name]['fields']['features'] as $feature) {
                        $result['features'][] = $sheet->getCellByColumnAndRow($feature, $j)->getValue();
                    }
                }

                $this->lines[] = $result;
            }
        }
    }
}
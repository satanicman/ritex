<?php

/**
 * @property Feature $object
 */
class AdminFeaturesController extends AdminFeaturesControllerCore
{
    /**
     * AdminController::renderForm() override
     * @see AdminController::renderForm()
     */
    public function initFormFeatureValue()
    {
        $this->setTypeValue();
        $obj = $this->loadObject(true);

        $image = _PS_IMG_DIR_.'f/'.$obj->id;
        $image_url = ImageManager::thumbnail($image.'.png', $this->table.'_'.(int)$obj->id.'.'.$this->imageType, 350,
            $this->imageType, true, true);
        if(!$image_url)
            $image_url = ImageManager::thumbnail($image.'.jpg', $this->table.'_'.(int)$obj->id.'.'.$this->imageType,
                350, $this->imageType, true, true);

        $image_size = file_exists($image) ? filesize($image) / 1000 : false;

        $this->fields_form[0]['form'] = array(
            'legend' => array(
                'title' => $this->l('Feature value'),
                'icon' => 'icon-info-sign'
            ),
            'input' => array(
                array(
                    'type' => 'select',
                    'label' => $this->l('Feature'),
                    'name' => 'id_feature',
                    'options' => array(
                        'query' => Feature::getFeatures($this->context->language->id),
                        'id' => 'id_feature',
                        'name' => 'name'
                    ),
                    'required' => true
                ),
                array(
                    'type' => 'text',
                    'label' => $this->l('Value'),
                    'name' => 'value',
                    'lang' => true,
                    'size' => 33,
                    'hint' => $this->l('Invalid characters:').' <>;=#{}',
                    'required' => true
                ),
                array(
                    'type' => 'file',
                    'label' => $this->l('Image'),
                    'name' => 'image',
                    'display_image' => true,
                    'image' => $image_url ? $image_url : false,
                    'size' => $image_size
                ),
            ),
            'submit' => array(
                'title' => $this->l('Save'),
            ),
            'buttons' => array(
                'save-and-stay' => array(
                    'title' => $this->l('Save then add another value'),
                    'name' => 'submitAdd'.$this->table.'AndStay',
                    'type' => 'submit',
                    'class' => 'btn btn-default pull-right',
                    'icon' => 'process-icon-save'
                )
            )
        );

        $this->fields_value['id_feature'] = (int)Tools::getValue('id_feature');

        // Create Object FeatureValue
        $feature_value = new FeatureValue(Tools::getValue('id_feature_value'));

        $this->tpl_vars = array(
            'feature_value' => $feature_value,
        );

        $this->getlanguages();
        $helper = new HelperForm();
        $helper->show_cancel_button = true;

        $back = Tools::safeOutput(Tools::getValue('back', ''));
        if (empty($back)) {
            $back = self::$currentIndex.'&token='.$this->token;
        }
        if (!Validate::isCleanHtml($back)) {
            die(Tools::displayError());
        }

        $helper->back_url = $back;
        $helper->currentIndex = self::$currentIndex;
        $helper->token = $this->token;
        $helper->table = $this->table;
        $helper->identifier = $this->identifier;
        $helper->override_folder = 'feature_value/';
        $helper->id = $feature_value->id;
        $helper->toolbar_scroll = false;
        $helper->tpl_vars = $this->tpl_vars;
        $helper->languages = $this->_languages;
        $helper->default_form_language = $this->default_form_language;
        $helper->allow_employee_form_lang = $this->allow_employee_form_lang;
        $helper->fields_value = array_merge($this->getFieldsValue($feature_value), array('image_url' => 'test'));
        $helper->toolbar_btn = $this->toolbar_btn;
        $helper->title = $this->l('Add a new feature value');
        $this->content .= $helper->generateForm($this->fields_form);
    }

    protected function postImage($id)
    {
        $ret = parent::postImage($id);
        $file_name = _PS_IMG_DIR_.'f/'.$id.'.';
        if($_FILES['image']['name'] != null) {
            preg_match('/[a-zA-Z]+$/', $_FILES['image']['type'], $file_type);
            $file_name .= $file_type[0];
            if(!move_uploaded_file($_FILES['image']['tmp_name'], $file_name)) {
                $ret = false;
            }
        } else {
            if(file_exists($file_name.'png')) unlink($file_name.'png');
            if(file_exists($file_name.'jpg')) unlink($file_name.'jpg');
        }

        return $ret;
    }

    public function postProcess()
    {
        if (!Feature::isFeatureActive()) {
            return;
        }

        if ($this->table == 'feature_value' && ($this->action == 'save' || $this->action == 'delete' || $this->action == 'bulkDelete')) {
            Hook::exec('displayFeatureValuePostProcess',
                array('errors' => &$this->errors));
        } // send errors as reference to allow displayFeatureValuePostProcess to stop saving process
        else {
            Hook::exec('displayFeaturePostProcess',
                array('errors' => &$this->errors));
        } // send errors as reference to allow displayFeaturePostProcess to stop saving process

        parent::postProcess();

        if ($this->table == 'feature_value' && ($this->display == 'edit' || $this->display == 'add')) {
            $this->display = 'editFeatureValue';
        }
    }

    /**
     * Override processAdd to change SaveAndStay button action
     * @see classes/AdminControllerCore::processAdd()
     */
    public function processAdd()
    {
        $object = parent::processAdd();

        if (Tools::isSubmit('submitAdd'.$this->table.'AndStay') && !count($this->errors)) {
            if ($this->table == 'feature_value' && ($this->display == 'edit' || $this->display == 'add')) {
                $this->redirect_after = self::$currentIndex.'&addfeature_value&id_feature='.(int)Tools::getValue('id_feature').'&token='.$this->token;
            } else {
                $this->redirect_after = self::$currentIndex.'&'.$this->identifier.'=&conf=3&update'.$this->table.'&token='.$this->token;
            }
        } elseif (Tools::isSubmit('submitAdd'.$this->table.'AndStay') && count($this->errors)) {
            $this->display = 'editFeatureValue';
        }

        return $object;
    }

    /**
     * Override processUpdate to change SaveAndStay button action
     * @see classes/AdminControllerCore::processUpdate()
     */
    public function processUpdate()
    {
        $object = parent::processUpdate();

        if (Tools::isSubmit('submitAdd'.$this->table.'AndStay') && !count($this->errors)) {
            $this->redirect_after = self::$currentIndex.'&'.$this->identifier.'=&conf=3&update'.$this->table.'&token='.$this->token;
        }

        return $object;
    }

    /**
     * Call the right method for creating or updating object
     *
     * @return mixed
     */
    public function processSave()
    {
        if ($this->table == 'feature') {
            $id_feature = (int)Tools::getValue('id_feature');
            // Adding last position to the feature if not exist
            if ($id_feature <= 0) {
                $sql = 'SELECT `position`+1
						FROM `'._DB_PREFIX_.'feature`
						ORDER BY position DESC';
            // set the position of the new feature in $_POST for postProcess() method
                $_POST['position'] = DB::getInstance()->getValue($sql);
            }
            // clean \n\r characters
            foreach ($_POST as $key => $value) {
                if (preg_match('/^name_/Ui', $key)) {
                    $_POST[$key] = str_replace('\n', '', str_replace('\r', '', $value));
                }
            }
        }
        return parent::processSave();
    }

    /**
     * AdminController::getList() override
     * @see AdminController::getList()
     *
     * @param int         $id_lang
     * @param string|null $order_by
     * @param string|null $order_way
     * @param int         $start
     * @param int|null    $limit
     * @param int|bool    $id_lang_shop
     *
     * @throws PrestaShopException
     */
    public function getList($id_lang, $order_by = null, $order_way = null, $start = 0, $limit = null, $id_lang_shop = false)
    {
        if ($this->table == 'feature_value') {
            $this->_where .= ' AND (a.custom = 0 OR a.custom IS NULL)';
        }

        parent::getList($id_lang, $order_by, $order_way, $start, $limit, $id_lang_shop);

        if ($this->table == 'feature') {
            $nb_items = count($this->_list);
            for ($i = 0; $i < $nb_items; ++$i) {
                $item = &$this->_list[$i];

                $query = new DbQuery();
                $query->select('COUNT(fv.id_feature_value) as count_values');
                $query->from('feature_value', 'fv');
                $query->where('fv.id_feature ='.(int)$item['id_feature']);
                $query->where('(fv.custom=0 OR fv.custom IS NULL)');
                $res = Db::getInstance(_PS_USE_SQL_SLAVE_)->getValue($query);
                $item['value'] = (int)$res;
                unset($query);
            }
        }
    }

    public function ajaxProcessUpdatePositions()
    {
        if ($this->tabAccess['edit'] === '1') {
            $way = (int)Tools::getValue('way');
            $id_feature = (int)Tools::getValue('id');
            $positions = Tools::getValue('feature');

            $new_positions = array();
            foreach ($positions as $k => $v) {
                if (!empty($v)) {
                    $new_positions[] = $v;
                }
            }

            foreach ($new_positions as $position => $value) {
                $pos = explode('_', $value);

                if (isset($pos[2]) && (int)$pos[2] === $id_feature) {
                    if ($feature = new Feature((int)$pos[2])) {
                        if (isset($position) && $feature->updatePosition($way, $position, $id_feature)) {
                            echo 'ok position '.(int)$position.' for feature '.(int)$pos[1].'\r\n';
                        } else {
                            echo '{"hasError" : true, "errors" : "Can not update feature '.(int)$id_feature.' to position '.(int)$position.' "}';
                        }
                    } else {
                        echo '{"hasError" : true, "errors" : "This feature ('.(int)$id_feature.') can t be loaded"}';
                    }

                    break;
                }
            }
        }
    }
}

<?php

class ImportExcel extends AjaxImportModel
{
    protected $excelColumns = array(
        'reference' => 0,
        'features' => array(1, 6, 7, 8, 9),
        'attributes' => array(2, 3, 4, 5)
    );

    public function __construct()
    {
        global $context;
        $this->context = $context;
    }

    public function parseExcel($sheet, $from) {
        $result = '';
        for ($i = $from + 1; $i <= $sheet->getHighestRow(); $i++) {
            if($i == 1) {
                $this->setFirstLine($sheet);
                continue;
            }

            $reference = (string)$sheet->getCellByColumnAndRow($this->excelColumns['reference'], $i)->getValue();
            $id_product = Product::getByReference($reference);
            $this->counter++;
            if($id_product) {
                $this->setFeatures($sheet, $id_product, $i);
                $result .= $this->setAttributes($sheet, $id_product, $i);
            } else {
                $result .= sprintf('<p class="c-red">Товар %s отсутствует в базе;</p>', $reference);
            }

            if($this->counter >= Configuration::get('AJAXIMPORT_NBR_PRODUCTS'))
                break;
        }

        return $result;
    }

    public function setFirstLine($sheet)
    {
        foreach($this->excelColumns['features'] as $feature) {
            $name = trim((string)$sheet->getCellByColumnAndRow($feature, 1)->getValue());
            parent::setFeature($name);
        }
        foreach($this->excelColumns['attributes'] as $attribute) {
            $name = trim((string)$sheet->getCellByColumnAndRow($attribute, 1)->getValue());
            parent::setGroup($name);
        }
    }

    public function setFeatures($sheet, $id_product, $line)
    {
        $p = new Product($id_product, false, $this->context->language->id);
        $result = '';
        $features = array();

        foreach ($this->excelColumns['features'] as $feature) {
            $name = trim($sheet->getCellByColumnAndRow($feature, 1)->getValue());
            $value = trim($sheet->getCellByColumnAndRow($feature, $line)->getValue());
            $id_feature = Feature::getByName($name);

            if(!$id_feature) {
                $result .= sprintf('<p class="c-red">Характеристика %s отсутствует в базе;</p>', $name);
                continue;
            }

            if(!$value)
                continue;

            $row = array(
                'id' => $id_feature,
                'id_feature_value' => FeatureValue::addFeatureValueImport($id_feature, $value, $p->id, $this->context->language->id)
            );
            $p->deleteFeature($id_feature);

            $features[] = $row;
        }
        $p->setWsProductFeatures($features);

        return $result;
    }

    public function setAttributes($sheet, $id_product, $line) {
        
        $result = '';
        $attributes = array();

        foreach ($this->excelColumns['attributes'] as $attribute) {
            $name = trim($sheet->getCellByColumnAndRow($attribute, 1)->getValue());
            $value = trim($sheet->getCellByColumnAndRow($attribute, $line)->getValue());
            $id_group = AttributeGroup::getByName($name);

            if(!$id_group) {
                $result .= sprintf('<p class="c-red">%1$d. Группа %2$s отсутствует в базе;</p>', $line, $name);
                continue;
            }

            if(!$value)
                continue;

            $attributes[] = parent::setAttribute($id_group, $value);
        }

        if($attributes)
            $result .= $this->setCombinations($id_product, $attributes, $line);

        return $result;
    }

    public function setCombinations($id_product, $attributes, $line)
    {
        $p = new Product($id_product, false, $this->context->language->id);
        $attrsString = implode(',', $attributes);
        if($id_product_attribute = parent::checkCombination($id_product, $attributes)) {
            return sprintf('<p class="c-red">%3$s. Комбинация (%1$s) для товара %2$s уже существует;</p>', $attrsString, $p->name, $line);
        }


        $id_product_attribute = $p->addCombinationEntity(0, 0, 0, 0, 0, 0, false, false, null, false, Product::getDefaultAttribute($p->id) ? 0 : 1, false, false, 1, array(), '0000-00-00');

        $combination = new Combination((int)$id_product_attribute);
        $combination->setAttributes($attributes);

        return sprintf('<p class="c-green">%3$s. Комбинация (%1$s) для товара %2$s создана;</p>', $attrsString, $p->name, $line);
    }
}
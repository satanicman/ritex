<?php

class ImportXml extends AjaxImportModel
{
    public function __construct()
    {
        global $context;
        $this->context = $context;
    }

    /**
     * Импорт категорий из XML объекта
     * @param array $categories
     * @param int $from
     * @return bool|string
     */
    public function setCategories($categories, $from) {
        if(!$categories)
            return false;

        $result = '';
        for ($i = (int)$from; $i < count($categories); $i++) {
            $result .= parent::setCategory($categories[$i]->{'Наименование'}, $categories[$i]->{'Ид'});
            $this->counter++;
            if($this->counter >= Configuration::get('AJAXIMPORT_NBR_PRODUCTS'))
                break;
        }

        return $result;
    }

    /**
     * Импорт характеристик из XML объекта
     * @param array $features
     * @param int $from
     * @return bool|string
     */
    public function setFeatures($features, $from)
    {
        if(!$features)
            return false;

        $result = '';
        for ($i = (int)$from; $i < count($features); $i++) {
            $result .= parent::setFeature((string)$features[$i]->{'Наименование'}, (string)$features[$i]->{'Ид'});
            $this->counter++;
            if($this->counter >= Configuration::get('AJAXIMPORT_NBR_PRODUCTS'))
                break;
        }


        return $result;
    }

    /**
     * Импорт атрибут из XML объекта
     * @param array $groups
     * @param int $from
     * @return bool|string
     */
    public function setGroups($groups, $from)
    {
        if(!$groups)
            return false;

        $result = '';
        for ($i = (int)$from; $i < count($groups); $i++) {
            $result .= parent::setGroup($groups[$i]->{'Ид'}, $groups[$i]->{'Наименование'});
            $this->counter++;
            if($this->counter >= Configuration::get('AJAXIMPORT_NBR_PRODUCTS'))
                break;
        }

        return $result;
    }


    /**
     * Импорт товаров из XML объекта
     * @param array $products
     * @param int $from
     * @return bool|string
     */
    public function setProducts($products, $from)
    {
        if(!$products)
            return false;

        $result = '';
        for ($i = (int)$from; $i < count($products); $i++) {
            $message = '';
            $categories = array(Configuration::get('PS_HOME_CATEGORY'));
            if($products[$i]->{'Группы'}->{'Ид'}) {
                foreach((array)$products[$i]->{'Группы'}->{'Ид'} as $id_oneC) {
                    if(!$category_id = Category::getByIdOneC($id_oneC))
                        continue;

                    $categories[] = $category_id;
                }
            }

            $id_ineC = (string)$products[$i]->{'Ид'};
            $product_id = Product::getByIdOneC($id_ineC);

            $p = new Product($product_id, false, $this->context->language->id);
            $p->name = (string)$products[$i]->{'Наименование'};
            $p->link_rewrite = Tools::link_rewrite($p->name);
            $p->description = (string)$products[$i]->{'Описание'};
            $p->reference = (string)$products[$i]->{'Артикул'};
            $p->price = 0;
            $p->id_tax_rules_group = 0;
            $p->id_oneC = $id_ineC;
            $p->id_category_default = $categories[count($categories) - 1];

            if($product_id) {
                $p->update();
                $p->deleteFeatures();
                $p->deleteImages();
                $message .= '<p class="c-yellow">Товар %s обновлен;</p>';
            } else {
                $p->add();
                $message .= '<p class="c-green">Товар %s добавлен;</p>';
            }

            $features = array();
            foreach ($products[$i]->{'ЗначенияСвойств'}->{'ЗначенияСвойства'} as $feature) {
                $id_feature =  Feature::getByIdOneC($feature->{'Ид'});
                $features[] = array(
                    'id' => $id_feature,
                    'id_feature_value' => FeatureValue::addFeatureValueImport($id_feature, trim((string)$feature->{'Значение'}), $p->id, $this->context->language->id)
                );
            }

            $p->addToCategories($categories);
            $p->setWsProductFeatures($features);

            if($products[$i]->{'Картинка'})
                parent::setImages((string)$products[$i]->{'Картинка'}, $p);

            $result .= sprintf($message, $p->name);
            $this->counter++;
            if($this->counter >= Configuration::get('AJAXIMPORT_NBR_PRODUCTS'))
                break;
        }

        return $result;
    }

    /**
     * Обновление цен и количества из XML объекта
     * @param array $proposes
     * @param int $from
     * @return bool|string
     */
    public function updateProductPrice($proposes, $from)
    {
        if(!$proposes)
            return false;

        $result = '';
        for ($i = (int)$from; $i < count($proposes); $i++) {
            $message = "";

            $p = new Product(Product::getByIdOneC((string)$proposes[$i]->{'Ид'}), false, $this->context->language->id);
            if($proposes[$i]->{'Цены'}->{'Цена'}) {
                $p->price = (float)$proposes[$i]->{'Цены'}->{'Цена'}[0]->{'ЦенаЗаЕдиницу'};
                $message .= '<p class="c-yellow">%1$s - цена обновлена</p>';
            }
            $p->update();

            if($proposes[$i]->{'Количество'}) {
                StockAvailable::setQuantity($p->id, 0, $proposes[$i]->{'Количество'});
                $message .= '<p class="c-green">%1$s - кол-во обновлено</p>';
            }

            $result .= sprintf($message, $p->name);

            $this->counter++;
            if($this->counter >= Configuration::get('AJAXIMPORT_NBR_PRODUCTS'))
                break;
        }


        return $result;
    }
}
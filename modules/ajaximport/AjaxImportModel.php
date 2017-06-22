<?php

class AjaxImportModel extends ObjectModel
{
    protected static $tables_list = array('product', 'category', 'attribute_group', 'feature');
    public $counter = 0;
    protected $context;

    /**
     * Создаем полей, идентификаторов 1с, для дальнейшего обновления полей
     * @return bool
     */
    public static function addFields() {
        foreach(self::$tables_list as $table) {
            $fields = Db::getInstance()->executeS('SHOW FIELDS FROM `'._DB_PREFIX_.$table.'`');
            $exist = false;
            foreach ($fields as $field) {
                if($field['Field'] === 'id_oneC') {
                    $exist = true;
                }
            }

            if(!$exist) {
                Db::getInstance()->execute("ALTER TABLE  `"._DB_PREFIX_.$table."` ADD  `id_oneC` VARCHAR( 128 ) NULL;");
            }
        }

        return true;
    }

    /**
     * Удаление полей идентификаторов 1с
     * @return bool
     */
    public static function rmFields() {
        foreach(self::$tables_list as $table) {
            $fields = Db::getInstance()->executeS("SHOW FIELDS FROM `"._DB_PREFIX_.$table."`");
            $exist = false;
            foreach ($fields as $field) {
                if($field['Field'] === 'id_oneC') {
                    $exist = true;
                }
            }

            if($exist) {
                Db::getInstance()->execute("ALTER TABLE `"._DB_PREFIX_.$table."` DROP `id_oneC`;");
            }
        }

        return true;
    }

    public function setCategory($name, $id_oneC)
    {
        $message = '';
        $id = Category::getByIdOneC((string)$id_oneC);
        $c = new Category($id, $this->context->language->id);
        $c->name = (string)$name;
        $c->link_rewrite = Tools::link_rewrite($c->name);
        $c->id_oneC = (string)$id_oneC;
        $c->id_parent = Configuration::get('AJAXIMPORT_CATEGORY');

        if($id) {
            $c->update();
            $message .= '<p class="c-yellow">Категория %s обновлена;</p>';
        } else {
            $c->add();
            $message .= '<p class="c-green">Категория %s добавлена;</p>';
        }

        return sprintf($message, $c->name);
    }

    public function setFeature($name, $id_oneC = null)
    {
        $message = '';
        if($id_oneC)
            $id = Feature::getByIdOneC((string)$id_oneC);
        else
            $id = Feature::getByName((string)$name);

        $f = new Feature($id, $this->context->language->id);
        $f->name = (string)$name;
        $f->id_oneC = $id_oneC;

        if($id) {
            $message .= '<p class="c-yellow">Характерискитка %s обновлена;</p>';
            $f->update();
        } else {
            $message .= '<p class="c-green">Характерискитка %s добавлена;</p>';
            $f->add();
        }

        return sprintf($message, $f->name);
    }

    public function setGroup($name, $id_ineC = null)
    {
        $message = '';

        if($id_ineC)
            $id = AttributeGroup::getByIdOneC($id_ineC);
        else
            $id = AttributeGroup::getByName($name);

        $g = new AttributeGroup($id, $this->context->language->id);
        $g->name = (string)$name;
        $g->public_name  = (string)$name;
        $g->group_type = 'select';
        $g->is_color_group = 0;
        $g->id_oneC = $id_ineC;

        if($id) {
            $g->update();
            $message .= '<p class="c-yellow">Аттрибут %s обновлен;</p>';
        } else {
            $g->add();
            $message .= '<p class="c-green">Аттрибут %s добавлен;</p>';
        }

        return sprintf($message, $g->name);
    }

    protected function setAttribute($id_group, $name)
    {
        if(!$name)
            return false;

        $id = $this->getAttributeByName($name, $id_group);
        $attr = new Attribute($id, $this->context->language->id);
        $attr->name = $name;
        $attr->id_attribute_group = $id_group;

        if($id)
            $attr->update();
        else
            $attr->add();

        return $attr->id;
    }

    public function setImages($link, $product)
    {
        if (!empty($link) && $product->id) {
            $photo_isset = str_replace("\\", "/", _PS_ROOT_DIR_ . '/img/' . trim($link));
            if (file_exists($photo_isset)) {
                $image = new Image();
                $image->id_product = $product->id;
                if(!Image::getCover($product->id))
                    $image->cover = 1;
                $image->position = 0;
                $image->legend = array_fill_keys(Language::getIDs(), (string)$product->name);
                $image->save();
                $name = $image->getPathForCreation();
                copy($photo_isset, $name . '.' . $image->image_format);
                $types = ImageType::getImagesTypes('products');
                foreach ($types as $type)
                    ImageManager::resize($photo_isset, $name . '-' . $type['name'] . '.' . $image->image_format, $type['width'], $type['height'], $image->image_format);
            }
        }
        return true;
    }

    protected function getAttributeByName($name, $id_group) {
        if(!$name)
            return false;

        $sql = "
              SELECT 
                a.`id_attribute`
              FROM 
                `"._DB_PREFIX_."attribute_lang` as al
              LEFT JOIN
                `"._DB_PREFIX_."attribute` as a ON a.`id_attribute` = al.`id_attribute`
              WHERE 
                `name` LIKE '".$name."' 
                AND `id_attribute_group` = ".(int)$id_group;

        return Db::getInstance()->getValue($sql);
    }

    protected function checkCombination($id_product, $attributes)
    {
        $sql = "
              SELECT 
                pac.`id_product_attribute`,
                pac.`id_attribute`
              FROM 
                `"._DB_PREFIX_."product_attribute` as pa
              LEFT JOIN
                `"._DB_PREFIX_."product_attribute_combination` pac ON pac.`id_product_attribute` = pa.`id_product_attribute`
              WHERE 
                `id_product` LIKE '".(int)$id_product."'
              ORDER BY pac.`id_product_attribute`";

        $attrs = array();
        $id_product_attribute = 0;
        $counter = 0;
        $result = Db::getInstance()->executeS($sql);
        if($result) {
            foreach ($result as $row) {
                if ($id_product_attribute === 0)
                    $id_product_attribute = $row['id_product_attribute'];

                $attrs[$row['id_product_attribute']][] = $row['id_attribute'];

                $counter++;
                if ($id_product_attribute != $row['id_product_attribute'] || $counter === count($result)) {
                    if (!$this->compare_array_data($attrs[$id_product_attribute], $attributes)) {
                        $id_product_attribute = $row['id_product_attribute'];
                        continue;
                    }

                    return $row['id_product_attribute'];
                }
            }
        }

        return false;
    }

    public function compare_array_data($a1, $a2) {
        $a2 = array_flip($a2);
        foreach ($a1 as $el1) {
            unset($a2[$el1]);
        }

        return count($a2) ? false : true;
    }
}
<?php

class AjaxImportModel extends ObjectModel
{
    protected static $tables_list = array('product');
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
}
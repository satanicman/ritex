<?php

class Feature extends FeatureCore
{
    public $id_oneC;

    /**
     * @see ObjectModel::$definition
     */
    public static $definition = array(
        'table' => 'feature',
        'primary' => 'id_feature',
        'multilang' => true,
        'fields' => array(
            'position' =>    array('type' => self::TYPE_INT, 'validate' => 'isInt'),

            /* Lang fields */
            'name' =>        array('type' => self::TYPE_STRING, 'lang' => true, 'validate' => 'isGenericName', 'required' => true, 'size' => 128),
            // custom
            'id_oneC' =>        array('type' => self::TYPE_STRING, 'validate' => 'isString', 'size' => 128),
        ),
    );

    /**
     * Получить идентификатор характеристики по идентификатору 1с
     * @param string $id_oneC
     * @return bool|int
     */
    public static function getByIdOneC($id_oneC) {
        if(!$id_oneC)
            return false;

        return (int)Db::getInstance()->getValue("SELECT id_feature FROM `"._DB_PREFIX_."feature` WHERE `id_oneC` LIKE '".(string)$id_oneC."'");
    }

    /**
     * Получить идентификатор характеристики по названию
     * @param string $value
     * @return bool|int
     */
    public static function getByName($value) {
        if(!$value)
            return false;

        return (int)Db::getInstance()->getValue("SELECT id_feature FROM `"._DB_PREFIX_."feature_lang` WHERE `name` LIKE '".(string)$value."'");
    }
}

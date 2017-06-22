<?php

class AttributeGroup extends AttributeGroupCore
{
    public $id_oneC;

    public static $definition = array(
        'table' => 'attribute_group',
        'primary' => 'id_attribute_group',
        'multilang' => true,
        'fields' => array(
            'is_color_group' => array('type' => self::TYPE_BOOL, 'validate' => 'isBool'),
            'group_type' =>    array('type' => self::TYPE_STRING, 'required' => true),
            'position' =>        array('type' => self::TYPE_INT, 'validate' => 'isInt'),
            // custom
            'id_oneC' =>    array('type' => self::TYPE_STRING, 'validate' => 'isString'),
            // custom #END

            /* Lang fields */
            'name' =>            array('type' => self::TYPE_STRING, 'lang' => true, 'validate' => 'isGenericName', 'required' => true, 'size' => 128),
            'public_name' =>    array('type' => self::TYPE_STRING, 'lang' => true, 'validate' => 'isGenericName', 'required' => true, 'size' => 64),
        ),
    );

    /**
     * Получить идентификато группы по идентификатору 1с
     * @param string $id_oneC
     * @return bool|int
     */
    public static function getByIdOneC($id_oneC) {
        if(!$id_oneC)
            return false;

        return (int)Db::getInstance()->getValue("SELECT `id_attribute_group` FROM `"._DB_PREFIX_."attribute_group` WHERE `id_oneC` LIKE '".(string)$id_oneC."'");
    }

    /**
     * Получить идентификато группы по названию
     * @param string $name
     * @return bool|int
     */
    public static function getByName($name) {
        if(!$name)
            return false;

        return (int)Db::getInstance()->getValue("SELECT `id_attribute_group` FROM `"._DB_PREFIX_."attribute_group_lang` WHERE `name` LIKE '".(string)$name."'");
    }
}

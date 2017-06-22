<?php

class Category extends CategoryCore
{
    public $id_oneC;

    public static $definition = array(
        'table'          => 'category',
        'primary'        => 'id_category',
        'multilang'      => true,
        'multilang_shop' => true,
        'fields' => array(
            // custom
            'id_oneC'          =>   array('type' => self::TYPE_STRING),
            // custom #END
            'nleft'            =>   array('type' => self::TYPE_INT, 'validate' => 'isUnsignedInt'),
            'nright'           =>   array('type' => self::TYPE_INT, 'validate' => 'isUnsignedInt'),
            'level_depth'      =>   array('type' => self::TYPE_INT, 'validate' => 'isUnsignedInt'),
            'active'           =>   array('type' => self::TYPE_BOOL, 'validate' => 'isBool', 'required' => true),
            'id_parent'        =>   array('type' => self::TYPE_INT, 'validate' => 'isUnsignedInt'),
            'id_shop_default'  =>   array('type' => self::TYPE_INT, 'validate' => 'isUnsignedId'),
            'is_root_category' =>   array('type' => self::TYPE_BOOL, 'validate' => 'isBool'),
            'position'         =>   array('type' => self::TYPE_INT),
            'date_add'         =>   array('type' => self::TYPE_DATE, 'validate' => 'isDate'),
            'date_upd'         =>   array('type' => self::TYPE_DATE, 'validate' => 'isDate'),
            /* Lang fields */
            'name'             =>   array('type' => self::TYPE_STRING, 'lang' => true, 'validate' => 'isCatalogName', 'required' => true, 'size' => 128),
            'link_rewrite'     =>   array('type' => self::TYPE_STRING, 'lang' => true, 'validate' => 'isLinkRewrite', 'required' => true, 'size' => 128),
            'description'      =>   array('type' => self::TYPE_HTML, 'lang' => true, 'validate' => 'isCleanHtml'),
            'meta_title'       =>   array('type' => self::TYPE_STRING, 'lang' => true, 'validate' => 'isGenericName', 'size' => 128),
            'meta_description' =>   array('type' => self::TYPE_STRING, 'lang' => true, 'validate' => 'isGenericName', 'size' => 255),
            'meta_keywords'    =>   array('type' => self::TYPE_STRING, 'lang' => true, 'validate' => 'isGenericName', 'size' => 255),
        ),
    );




    /**
     * Получить идентификато категории по идентификатору 1с
     * @param string $id_oneC
     * @return bool|int
     */
    public static function getByIdOneC($id_oneC) {
        if(!$id_oneC)
            return false;

        return (int)Db::getInstance()->getValue("SELECT id_category FROM `"._DB_PREFIX_."category` WHERE `id_oneC` LIKE '".(string)$id_oneC."'");
    }
}

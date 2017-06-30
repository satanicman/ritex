<?php

class HomecategoriesOverride extends Homecategories
{
    public function hookDisplayHeader()
    {
        $this->context->controller->addCSS($this->_path.'homecategories.css');
    }

    public function hookDisplayHome($params)
    {
        $this->context->smarty->assign(
            array(
                'categories' => $this->getCategories(),
            )
        );

        return $this->display(__FILE__, '/views/templates/hooks/homecategories.tpl');
    }

    protected function getCategories()
    {
        $root_cat = new Category(12);
        $categories = $root_cat->getSubCategories($this->context->cookie->id_lang);
        $result = array();

        foreach ($categories as $category) {
            $c = new Category($category['id_category']);
            $category['children'] = $c->getSubCategories($this->context->cookie->id_lang);
            $result[] = $category;
        }

        return $result;
    }
}

<?php
class IndexController extends IndexControllerCore
{
    public function setMedia()
    {
        parent::setMedia();
        $this->addCSS(_THEME_CSS_DIR_.'index.css', 'all');
        $this->addCSS(_THEME_CSS_DIR_.'fonts.css', 'all');
    }
}

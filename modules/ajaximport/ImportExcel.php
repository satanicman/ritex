<?php

class ImportExcel extends AjaxImportModel
{
    public $excelColumns = array(
        'omega' => array(
            'line' => 2,
            'fields' => array(
                'name' => 1,
                'reference' => 0,
                'features' => array(2),
                'price' => 4
            )
        ),
        'continental' => array(
            'line' => 9,
            'fields' => array(
                'name' => 4,
                'reference' => 1,
                'price' => 13
            )
        )
    );

    public function __construct()
    {
        global $context;
        $this->context = $context;
    }

    public function parseExcel($reader, $from) {
        $result = '';
        for ($i = 0; $i < $reader->getSheetCount(); $i++) {
            for($i = $from; $i < Configuration::get('AJAXIMPORT_NBR_PRODUCTS'); $i++) {

            }
        }

        return $result;
    }
}
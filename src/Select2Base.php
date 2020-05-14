<?php

namespace QCubed\Plugin;

use QCubed as Q;
use QCubed\Project\Control\ControlBase;
use QCubed\Bootstrap as Bs;
use QCubed\Project\Application;


class Select2Base extends Select2Gen
{
    public function  __construct($objParentObject, $strControlId = null)
    {
        parent::__construct($objParentObject, $strControlId);
        $this->registerFiles();
    }

    protected function registerFiles()
    {
        $this->addJavascriptFile(QCUBED_SELECT2_ASSETS_URL . "/js/select2.min.js");
        //$this->AddCssFile(QCUBED_BOOTSTRAP_CSS); // make sure they know
        //$this->AddCssFile(QCUBED_FONT_AWESOME_CSS); // make sure they know
        $this->addCssFile(QCUBED_SELECT2_ASSETS_URL . "/css/select2.css");
        //Bs\Bootstrap::loadJS($this);
    }

    public function getJqControlId()
    {
        return $this->ControlId;
    }

    protected function GetResetButtonHtml()
    {
        return '';
    }
}


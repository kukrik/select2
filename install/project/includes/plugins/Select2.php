<?php

/**
 * The Select2 override file. This file gets installed into project/includes/plugins/select2
 *  during the initial installation of the plugin. After that, it is not touched.
 * Feel free to modify this file as needed.
 *
 * @see Select2Base
 */

namespace QCubed\Plugin;

use QCubed\Project\Control\ControlBase;
use QCubed\Project\Control\FormBase;
use QCubed\Exception\Caller;

class Select2 extends Select2ListBoxBase
{
    /**
     * Constructor method for initializing the object.
     *
     * @param mixed $objParentObject The parent object that serves as the backbone for this child class.
     * @param string|null $strControlId Optional control ID for uniquely identifying the instance.
     *
     * @throws Caller
     */
    public function  __construct(ControlBase|FormBase $objParentObject, ?string $strControlId = null)
    {
        parent::__construct($objParentObject, $strControlId);
        $this->removeCssClass("listbox");
        $this->registerFiles();
    }

    /**
     * Registers the necessary JavaScript and CSS files required for the functionality.
     *
     * @return void
     * @throws Caller
     */
    protected function registerFiles(): void
    {
        $this->addJavascriptFile(QCUBED_SELECT2_ASSETS_URL . "/js/select2.min.js");
        $this->addCssFile(QCUBED_SELECT2_ASSETS_URL . "/css/select2.css");
        $this->addCssFile(QCUBED_SELECT2_ASSETS_URL . "/css/select2-bootstrap.css");
    }
}

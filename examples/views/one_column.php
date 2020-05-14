<?php
require('../qcubed.inc.php');

error_reporting(E_ALL); // Error engine - always ON!
ini_set('display_errors', TRUE); // Error display - OFF in production env or real server
ini_set('log_errors', TRUE); // Error logging


use QCubed as Q;
use QCubed\Bootstrap as Bs;
use QCubed\Project\Control\ControlBase;
use QCubed\Project\Control\FormBase as Form;
use QCubed\Action\ActionParams;
use QCubed\Project\Application;
use QCubed\Js;
use QCubed\Html;

/**
 * Class SampleForm
 */
class OneColumnForm extends Form
{



    protected function formCreate()
    {
        $this->sideMenu_Create();
    }

    protected function naturalList_Create()
    {
        $this->tblList = new Q\Plugin\NaturalList($this);
        $this->tblList->CssClass = 'simple';
        $this->tblList->TagName = 'ol';
        $this->tblList->setDataBinder('NaturalMenu_Bind');
        $this->tblList->createNodeParams([$this, 'Menu_Draw']);
    }

    protected function navBar_Create()
    {
        $objMenuArray = Menu::loadAll(
            Q\Query\QQ::Clause(Q\Query\QQ::OrderBy(QQN::menu()->Left),
                Q\Query\QQ::expand(QQN::menu()->MenuContent)
            ));

        $this->navBar = new Bs\Navbar($this, 'navbar');
        $url = 'menu_examples.php';
        $this->navBar->HeaderText = Html::renderTag("img",
            ["class" => "logo", "src" => QCUBED_IMAGE_URL . "/qcubed_logo_footer.png", "alt" => "Logo"], null, true);
        $this->navBar->HeaderAnchor = $url;
        $this->navBar->StyleClass = Bs\Bootstrap::NAVBAR_INVERSE;

        $dlgBar = new Bs\NavbarList($this->navBar);

        foreach ($objMenuArray as $objMenu) {

            if (!$objMenu->MenuContent->IsEnabled == 0) {

                if ($objMenu->ParentId == null && $objMenu->Right == $objMenu->Left + 1) {
                    $this->objListMenu = new Bs\NavbarItem($objMenu->MenuContent->MenuText, null,
                        $objMenu->MenuContent->RedirectUrl);
                    $dlgBar->addMenuItem($this->objListMenu);

                } elseif (!in_array($objMenu->ParentId, $this->ControllableValues($objMenuArray, 'Id')) &&
                    $objMenu->Right !== $objMenu->Left + 1) {
                    $this->objListSubMenu = new Bs\NavbarDropdown($objMenu->MenuContent->MenuText);
                    $dlgBar->addMenuItem($this->objListSubMenu);
                }

                if (in_array($objMenu->ParentId, $this->ControllableValues($objMenuArray, 'Id')) &&
                    $objMenu->Depth == 1) {
                    $this->objListSubMenu->addItem(new Bs\NavbarItem($objMenu->MenuContent->MenuText, null,
                        $objMenu->MenuContent->RedirectUrl));
                }
            }
        }
    }

    protected function smartMenus_Create()
    {
        $this->smartMenus = new Bs\Navbar($this);
        $url = 'menu_examples.php';
        $this->smartMenus->HeaderText = Html::renderTag("img",
            ["class" => "logo", "src" => QCUBED_IMAGE_URL . "/qcubed_logo_footer.png", "alt" => "Logo"], null, true);
        $this->smartMenus->HeaderAnchor = $url;
        $this->smartMenus->StyleClass = Bs\Bootstrap::NAVBAR_INVERSE;

        $this->tblNav = new Q\Plugin\SmartMenus($this->smartMenus);
        $this->tblNav->CssClass = 'nav navbar-nav';
        $this->tblNav->TagName = 'ul';
        $this->tblNav->TagStyle = 'dropdown-menu';
        $this->tblNav->setDataBinder('SmartMenu_Bind');
        $this->tblNav->createNodeParams([$this, 'Menu_Draw']);
    }

    protected function sideMenu_Create()
    {
        $objMenuArray = Menu::loadAll(
            Q\Query\QQ::Clause(Q\Query\QQ::OrderBy(QQN::menu()->Left),
                Q\Query\QQ::expand(QQN::menu()->MenuContent)
            ));



        $this->sideMenu = new Bs\Navbar($this);
        $url = 'menu_examples.php';
        $this->sideMenu->HeaderText = Html::renderTag("img",
            ["class" => "logo", "src" => QCUBED_IMAGE_URL . "/qcubed_logo_footer.png", "alt" => "Logo"], null, true);
        $this->sideMenu->HeaderAnchor = $url;
        $this->sideMenu->StyleClass = Bs\Bootstrap::NAVBAR_INVERSE;
        //$this->sideMenu->addAction(new Q\Event\Click(), new Q\Action\Ajax('SubMenuList_Click'));
        $this->sideMenu->addAction(new Bs\Event\NavbarSelect(), new Q\Action\Ajax('SubMenuList_Click'));


        //$this->sideMenu->addAction(new Q\Event\Click(), new Q\Action\Ajax('SubMenuList_Click'), new Q\Js\VarName("ui.id"));


       // new Q\Action\Ajax('SubMenuList_Click').ActionParameter(Javascript::Var("ui.id"));


        $this->tblBar = new Bs\NavbarList($this->sideMenu);
        //$this->tblBar->EncryptValues = true;

        foreach ($objMenuArray as $objMenu) {

            if (!$objMenu->MenuContent->IsEnabled == 0) {
                if ($objMenu->ParentId == null && $objMenu->Right == $objMenu->Left + 1) {
                    $this->objListMenu = new Bs\NavbarItem($objMenu->MenuContent->MenuText, $objMenu->Id, '#'
                    /*$objMenu->MenuContent->RedirectUrl*/); //Temporarily disabled $RedirectUrl for testing
                    $this->tblBar->addMenuItem($this->objListMenu);

                    //$this->tblBar->ActionParameter = $objMenu->Id;
                    //$this->tblBar->addAction(new Q\Event\Click(), new Q\Action\Ajax('SubMenuList_Click'));
                    //print $this->tblBar->ActionParameter . "\n";

                } elseif (!in_array($objMenu->ParentId, $this->ControllableValues($objMenuArray, 'Id')) &&
                    $objMenu->Right !== $objMenu->Left + 1) {
                    $this->objListSubMenu = new Bs\NavbarDropdown($objMenu->MenuContent->MenuText, $objMenu->Id);
                    $this->tblBar->addMenuItem($this->objListSubMenu);

                    //$this->tblBar->ActionParameter = $objMenu->Id;
                    //$this->tblBar->addAction(new Q\Event\Click(), new Q\Action\Ajax('SubMenuList_Click'));
                    //print $this->tblBar->ActionParameter . "\n";

                }
            }
        }

        $this->tblSubMenu = new Q\Plugin\SideBar($this->sideMenu, 'menubar');
        $this->tblSubMenu->TagName = 'ul';
        $this->tblSubMenu->ItemTagStyle = 'sidemenu '; //Please leave empty space at the end
        $this->tblSubMenu->setDataBinder('SubMenuList_Bind');
        $this->tblSubMenu->createNodeParams([$this, 'Menu_Draw']);
    }

    public function ControllableValues($objArrays, $target)
    {
        $arrays = [];
        foreach ($objArrays as $objArray) {
            if ($objArray->$target !== null) {
                $arrays[] = $objArray->$target;
            }
        }
        return $arrays;
    }

    protected function NaturalMenu_Bind()
    {
        $this->tblList->DataSource = Menu::loadAll(
            Q\Query\QQ::Clause(Q\Query\QQ::OrderBy(QQN::menu()->Left),
                Q\Query\QQ::expand(QQN::menu()->MenuContent)
            ));
    }

    protected function SmartMenu_Bind()
    {
        $this->tblNav->DataSource = Menu::loadAll(
            Q\Query\QQ::Clause(Q\Query\QQ::OrderBy(QQN::menu()->Left),
                Q\Query\QQ::expand(QQN::menu()->MenuContent)
            ));
    }

    protected function SubMenuList_Bind()
    {
        $this->tblSubMenu->DataSource = Menu::loadAll(
            Q\Query\QQ::Clause(Q\Query\QQ::OrderBy(QQN::menu()->Left),
                Q\Query\QQ::expand(QQN::menu()->MenuContent)
            ));
    }

    public function Menu_Draw(Menu $objMenu)
    {
        $a['id'] = $objMenu->Id;
        $a['parent_id'] = $objMenu->ParentId;
        $a['depth'] = $objMenu->Depth;
        $a['left'] = $objMenu->Left;
        $a['right'] = $objMenu->Right;
        $a['text'] = Q\QString::htmlEntities($objMenu->MenuContent->MenuText);
        $a['redirect_url'] = $objMenu->MenuContent->RedirectUrl;
        $a['status'] = $objMenu->MenuContent->IsEnabled;
        return $a;
    }

    protected function SubMenuList_Click(ActionParams $params)
    {
        //$intMenuId = $this->sideMenu->SelectedValue;

        $intMenuId = $params->ActionParameter['value'];
        $objMenuArray =  Menu::loadAll();

        /*$objMenuArray = Menu::loadAll(
            Q\Query\QQ::Clause(Q\Query\QQ::OrderBy(QQN::menu()->Left)));*/

        $strTempArray = [];
        $strInTempArray = [];
        $strAddTempArray = [];
        foreach ($objMenuArray as $objMenu) {
            if ($intMenuId == $objMenu->ParentId) {
                $strTempArray[] = $objMenu->ParentId;
            }
        }
        foreach ($objMenuArray as $objMenu) {
            foreach (array_unique($strTempArray) as &$strTemp) {
                if ($strTemp == $objMenu->ParentId) {
                    $strInTempArray[] = $objMenu->Id;
                }
            }
        }
        foreach ($objMenuArray as $objMenu) {
            foreach ($strInTempArray as &$strTemp) {
                if ($strTemp == $objMenu->ParentId) {
                    $strAddTempArray[] = $objMenu->Id;
                }
            }
        }

        $strSelectedInValues = array_merge($strInTempArray, $strAddTempArray);

        $test = implode(', ', $strSelectedInValues);
        Application::displayAlert("Siin on tulemus: " . $test);

    }

}

OneColumnForm::run('OneColumnForm');

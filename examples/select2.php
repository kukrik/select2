<?php
require('qcubed.inc.php');

use QCubed as Q;
use QCubed\Project\Application;
use QCubed\Project\Control\ControlBase;
use QCubed\Project\Control\FormBase as Form;
use QCubed\Query\QQ;

// Define the Form with all our Controls
class ExamplesForm extends Form
{
    // Local declarations of our Controls
    protected $lblMessage;

    // Select2 of Persons
    protected $lstSingle;
    protected $lstMultiple;

    // Initialize our Controls during the Form Creation process
    protected function formCreate()
    {
        // Define our Label
        $this->lblMessage = new Q\Control\Label($this);
        $this->lblMessage->Text = '<None>';

        // Define the Select2, and create the first listitem as 'Select One'
        $this->lstSingle = new Q\Plugin\Select2($this);
        $this->lstSingle->Width = '250';
        $this->lstSingle->MinimumResultsForSearch = -1; // If you want to remove the search box, set it to "-1"

        $this->lstSingle->SelectionMode = Q\Control\ListBoxBase::SELECTION_MODE_SINGLE;
        $this->lstSingle->AddItem(t('- Select One -'), null);
        $this->lstSingle->Theme = 'bootstrap';

        // Add the items for the select2, pulling in from the Person table
        $objPersons = Person::LoadAll(QQ::Clause(QQ::OrderBy(QQN::Person()->LastName, QQN::Person()->FirstName)));
        if ($objPersons) foreach ($objPersons as $objPerson) {
            // We want to display the listitem as Last Name, First Name
            // and the VALUE of the listitem should be the person object itself
            $this->lstSingle->AddItem($objPerson->LastName . ', ' . $objPerson->FirstName, $objPerson);
        }
        // Declare a \QCubed\Event\Change to call a ajax action: the lstPersons_Change PHP method
        $this->lstSingle->AddAction(new Q\Event\Change(), new Q\Action\Ajax('lstSingle_Change'));

        // Define the Select2, and create the first listitem as 'Select One'
        $this->lstMultiple = new Q\Plugin\Select2($this);
        $this->lstMultiple->ContainerWidth = 'resolve';
        $this->lstMultiple->Width = '350';
        $this->lstMultiple->MinimumResultsForSearch = -1; // If you want to remove the search box, set it to "-1"

        $this->lstMultiple->SelectionMode = Q\Control\ListBoxBase::SELECTION_MODE_MULTIPLE;
        //$this->lstMultiple->AddItem(t('- Select One -'), null);
        $this->lstMultiple->Theme = 'bootstrap';

        // Add the items for the select2, pulling in from the Person table
        $objPersons = Person::LoadAll(QQ::Clause(QQ::OrderBy(QQN::Person()->LastName, QQN::Person()->FirstName)));
        if ($objPersons) foreach ($objPersons as $objPerson) {
            // We want to display the listitem as First Name Last Name,
            // and the VALUE of the listitem should be the person object itself
            $this->lstMultiple->AddItem($objPerson->FirstName .' '.$objPerson->LastName, $objPerson);
        }
        // Declare a \QCubed\Event\Change to call a ajax action: the lstMultiple_Change PHP method
        $this->lstMultiple->AddAction(new Q\Event\Change(), new Q\Action\Ajax('lstMultiple_Change'));
    }

    // Handle the changing of the select2
    protected function lstSingle_Change($strFormId, $strControlId, $strParameter)
    {
        // See if there is something selected
        // Note that in the HTML that gets rendered, the <option> values are arbitrary
        // index numbers.  However, we put in the whole Person object as the ListItem
        // value.  So the SelectedValue property of the ListControl will
        // do a proper lookup of the ListItem that was selected, and will return
        // to us the Person OBJECT (or NULL if they selected "- Select One -").
        $objPerson = $this->lstSingle->SelectedValue;

        if ($objPerson) {
            $this->lblMessage->Text = sprintf('%s %s, Person ID of %s', $objPerson->FirstName, $objPerson->LastName, $objPerson->Id);
        } else {
            // No one was selected
            $this->lblMessage->Text = '<None>';
        }
    }

    protected function lstMultiple_Change($strFormId, $strControlId, $strParameter)
    {
        // In this example, since our values are database ids, we use the ids to lookup the names and display them.

        $names = $this->lstMultiple->SelectedNames;

        if ($names) {
            $this->lblMessage->Text = implode(", ", $names);
        } else {
            // No one was selected
            $this->lblMessage->Text = '<None>';
        }
    }
}

// Run the Form we have defined
// The Form engine will look to intro.tpl.php to use as its HTML template include file
ExamplesForm::Run('ExamplesForm');


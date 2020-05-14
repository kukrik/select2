<?php
require('qcubed.inc.php');

use QCubed as Q;
use QCubed\Bootstrap as Bs;
use QCubed\Js;
use QCubed\Project\Application;
use QCubed\Project\Control\ControlBase;
use QCubed\Project\Control\FormBase as Form;
use QCubed\Query\QQ;

// Define the Qform with all our Qcontrols
class ExamplesForm extends Form
{
    // Local declarations of our Qcontrols
    protected $lblMessage;

    // A listbox of Persons
    protected $lstPersons;

    // Initialize our Controls during the Form Creation process
    protected function Form_Create()
    {
        // Define our Label
        $this->lblMessage = new Q\Control\Label($this);
        $this->lblMessage->Text = '<None>';

        // Define the ListBox, and create the first listitem as 'Select One'
        $this->lstPersons = new Q\Plugin\Select2($this);
        $this->lstPersons->SelectionMode = Q\Control\ListBoxBase::SELECTION_MODE_MULTIPLE;
        $this->lstPersons->AddItem(t('- Select One -'), null);

        // Add the items for the listbox, pulling in from the Person table
        $objPersons = Person::LoadAll(QQ::Clause(QQ::OrderBy(QQN::Person()->LastName, QQN::Person()->FirstName)));
        if ($objPersons) foreach ($objPersons as $objPerson) {
            // We want to display the listitem as Last Name, First Name
            // and the VALUE of the listitem should be the person object itself
            $this->lstPersons->AddItem($objPerson->LastName . ', ' . $objPerson->FirstName, $objPerson);
        }

        // Declare a QChangeEvent to call a server action: the lstPersons_Change PHP method
        $this->lstPersons->AddAction(new Q\Event\Change(), new Q\Action\Server('lstPersons_Change'));
    }

    // Handle the changing of the listbox
    protected function lstPersons_Change($strFormId, $strControlId, $strParameter)
    {
        // See if there is something selected
        // Note that in the HTML that gets rendered, the <option> values are arbitrary
        // index numbers.  However, we put in the whole Person object as the QListItem
        // value.  So the SelectedValue property of the QListControl will
        // do a proper lookup of the QListItem that was selected, and will return
        // to us the Person OBJECT (or NULL if they selected "- Select One -").
        $objPerson = $this->lstPersons->SelectedValue;

        if ($objPerson) {
            $this->lblMessage->Text = sprintf('%s %s, Person ID of %s',
                $objPerson->FirstName, $objPerson->LastName, $objPerson->Id);
        } else {
            // No one was selected
            $this->lblMessage->Text = '<None>';
        }
    }
}

// Run the Form we have defined
// The QForm engine will look to intro.tpl.php to use as its HTML template include file
ExamplesForm::Run('ExamplesForm');


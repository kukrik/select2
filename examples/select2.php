<?php
require('qcubed.inc.php');

use QCubed as Q;
use QCubed\Exception\Caller;
use QCubed\Exception\InvalidCast;
use QCubed\Project\Control\FormBase as Form;
use QCubed\Control\Label;
use QCubed\Query\QQ;

// Define the Form with all our Controls
class ExamplesForm extends Form
{
    // Local declarations of our Controls
    protected Label $lblMessage;

    // Select2 of Persons
    protected Q\Plugin\Select2 $lstSingle;
    protected Q\Plugin\Select2 $lstMultiple;

    protected Q\Plugin\Select2 $lstProjectPeople;

    // Initialize our Controls during the Form Creation process

    /**
     * Initializes and defines the components used in the form, including labels and Select2 controls.
     * The method sets up Single and Multiple selection dropdowns populated with data from the Person table.
     * It also attaches AJAX event handling to listen for changes in the dropdown selections.
     *
     * @return void
     * @throws Caller
     * @throws InvalidCast
     */
    protected function formCreate(): void
    {
        // Define our Label
        $this->lblMessage = new Label($this);
        $this->lblMessage->Text = '<None>';

        // Define the Select2 and create the first listitem as 'Select One'
        $this->lstSingle = new Q\Plugin\Select2($this);
        $this->lstSingle->Width = '250';
        $this->lstSingle->MinimumResultsForSearch = -1; // If you want to remove the search box, set it to "-1"

        $this->lstSingle->SelectionMode = Q\Control\ListBoxBase::SELECTION_MODE_SINGLE;
        $this->lstSingle->AddItem(t('- Select One -'), null);
        $this->lstSingle->Theme = 'bootstrap';

        // Add the items for the select2, pulling in from the Person table
        $objPersons = Person::loadAll(QQ::clause(QQ::orderBy(QQN::person()->LastName, QQN::person()->FirstName)));
        if ($objPersons) {
            foreach ($objPersons as $objPerson) {
                // We want to display the listitem as Last Name, First Name,
                // and the VALUE of the listitem should be the person object itself
                $this->lstSingle->addItem($objPerson->LastName . ', ' . $objPerson->FirstName, $objPerson->Id);
            }
        }
        // Declare a \QCubed\Event\Change to call an ajax action: the lstPersons_Change PHP method
        $this->lstSingle->AddAction(new Q\Event\Change(), new Q\Action\Ajax('lstSingle_Change'));


        // Define the Select2 and create the first listitem as 'Select One'
        $this->lstMultiple = new Q\Plugin\Select2($this);
        $this->lstMultiple->ContainerWidth = 'resolve';
        $this->lstMultiple->Width = '350';
        $this->lstMultiple->MinimumResultsForSearch = -1; // If you want to remove the search box, set it to "-1"

        $this->lstMultiple->SelectionMode = Q\Control\ListBoxBase::SELECTION_MODE_MULTIPLE;
        $this->lstMultiple->Theme = 'bootstrap';

        // Add the items for the select2, pulling in from the Person table
        $objPersons = Person::LoadAll(QQ::Clause(QQ::OrderBy(QQN::Person()->LastName, QQN::Person()->FirstName)));
        if ($objPersons) foreach ($objPersons as $objPerson) {
            // We want to display the listitem as First Name Last Name,
            // and the VALUE of the listitem should be the person object itself
            $this->lstMultiple->AddItem($objPerson->FirstName .' '.$objPerson->LastName, $objPerson);
        }
        // Declare a \QCubed\Event\Change to call an ajax action: the lstMultiple_Change PHP method
        $this->lstMultiple->AddAction(new Q\Event\Change(), new Q\Action\Ajax('lstMultiple_Change'));


        // Second list box: groups persons under their respective projects
        $this->lstProjectPeople = new Q\Plugin\Select2($this);
        $this->lstProjectPeople->ContainerWidth = 'resolve';
        $this->lstProjectPeople->Width = '350';
        $this->lstProjectPeople->MinimumResultsForSearch = -1; // If you want to remove the search box, set it to "-1"
        $this->lstProjectPeople->SelectionMode = Q\Control\ListBoxBase::SELECTION_MODE_SINGLE;
        $this->lstProjectPeople->Theme = 'bootstrap';

        $this->lstProjectPeople->addItem('- Select a person based on the project -', null);

        // Use expandAsArray to load related persons (team members) for each project
        $clauses = [QQ::expandAsArray(QQN::project()->PersonAsTeamMember)];
        // Build grouped options: each person appears under their project name
        foreach (Project::queryArray(QQ::all(), $clauses) as $objProject) {
            $projectName = $objProject->Name;
            $members = $objProject->_PersonAsTeamMemberArray ?? [];

            foreach ($members as $objPerson) {
                $personName = $objPerson->FirstName . ' ' . $objPerson->LastName;

                $this->lstProjectPeople->addItem(
                    $personName,        // Text shown in the list
                    $objPerson->Id,     // Value of the item
                    false,                      // Not selected by default
                    false,                      // Not disabled
                    $projectName        // Group label (optgroup)
                );
            }
        }

        // AJAX callback when the project-based list box value changes
        $this->lstProjectPeople->addAction(new Q\Event\Change(), new Q\Action\Ajax('lstProjectPeople_Change'));


    }

    // Handle the changing of the select2

    /**
     * Handles the event triggered when a selection is made in the single selection list.
     *
     * @param string $strFormId The ID of the form that triggered the event.
     * @param string $strControlId The ID of the control that triggered the event.
     * @param string $strParameter Additional parameters passed with the event, if any.
     *
     * @return void
     * @throws Caller
     * @throws InvalidCast
     */
    protected function lstSingle_Change(string $strFormId, string $strControlId, string $strParameter): void
    {
        // See if there is something selected
        // Note that in the HTML that gets rendered, the <option> values are arbitrary
        // index numbers.  However, we put in the whole Person object as the ListItem
        // value.  So the SelectedValue property of the ListControl will
        // do a proper lookup of the ListItem, which was selected, and will return
        // to us the Person OBJECT (or NULL if they selected "- Select One -").

        $intPersonId = intval($this->lstSingle->SelectedValue);
        $objPerson = Person::load($intPersonId);

        if ($intPersonId) {
            $this->lblMessage->Text = sprintf('%s %s, Person ID of %s', $objPerson->FirstName, $objPerson->LastName, $objPerson->Id);
        } else {
            $this->lblMessage->Text = '<None>';
        }
    }

    /**
     * Handles the change event for the lstMultiple list control.
     * This method updates the lblMessage label with the names
     * of the selected items or displays '<None>' if no items are selected.
     *
     * @param string $strFormId The ID of the form triggering the event.
     * @param string $strControlId The ID of the control triggering the event.
     * @param string $strParameter Additional parameters passed during the event.
     *
     * @return void
     */
    protected function lstMultiple_Change(string $strFormId, string $strControlId, string $strParameter): void
    {
        // In this example, since our values are database IDs, we use the IDs to look up the names and display them.

        $names = $this->lstMultiple->SelectedNames;

        if ($names) {
            $this->lblMessage->Text = implode(", ", $names);
        } else {
            // No one was selected
            $this->lblMessage->Text = '<None>';
        }
    }

    public function lstProjectPeople_Change(string $strFormId, string $strControlId, string $strParameter): void
    {
        $intPersonId = intval($this->lstProjectPeople->SelectedValue);
        $objPerson = Person::load($intPersonId);

        if ($objPerson) {
            $this->lblMessage->Text = 'You chose: ' . $objPerson->FirstName . ' ' . $objPerson->LastName;
        } else {
            $this->lblMessage->Text = 'Person not found!';
        }

    }

}

// Run the Form we have defined
// The Form engine will look to intro.tpl.php to use as its HTML template include a file
ExamplesForm::run('ExamplesForm');


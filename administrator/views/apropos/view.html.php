<?php

/**
 * @version     0.0.0
 * @package     com_diatem_joomla_3x_getconfig
 * @copyright   Copyright (C) 2014. Tous droits réservés.
 * @license     GNU General Public License version 2 ou version ultérieure ; Voir LICENSE.txt
 * @author      Diatem <contact@diatem.net> - http://www.diatem.net/
 */
// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.view');

/**
 * View class for a list of Diatem_joomla_3x_getconfig.
 */
class Diatem_joomla_3x_getconfigViewApropos extends JViewLegacy {

    protected $items;
    protected $pagination;
    protected $state;

    /**
     * Display the view
     */
    public function display($tpl = null) {
        $this->state = $this->get('State');
        
        

        // Check for errors.
        if (count($errors = $this->get('Errors'))) {
            throw new Exception(implode("\n", $errors));
        }

        Diatem_joomla_3x_getconfigHelper::addSubmenu('apropos');

        $this->addToolbar();

        $this->sidebar = JHtmlSidebar::render();
        parent::display($tpl);
    }

    /**
     * Add the page title and toolbar.
     *
     * @since	1.6
     */
    protected function addToolbar() {
        require_once JPATH_COMPONENT . '/helpers/diatem_joomla_3x_getconfig.php';

        $state = $this->get('State');
        $canDo = Diatem_joomla_3x_getconfigHelper::getActions($state->get('filter.category_id'));

        JToolBarHelper::title(JText::_('COM_DIATEM_JOOMLA_3X_GETCONFIG_TITLE_APROPOS'), 'apropos.png');

        //Check if the form exists before showing the add/edit buttons
        $formPath = JPATH_COMPONENT_ADMINISTRATOR . '/views/apropo';
        if (file_exists($formPath)) {

            if ($canDo->get('core.create')) {
                JToolBarHelper::addNew('apropo.add', 'JTOOLBAR_NEW');
            }

            if ($canDo->get('core.edit') && isset($this->items[0])) {
                JToolBarHelper::editList('apropo.edit', 'JTOOLBAR_EDIT');
            }
        }

        if ($canDo->get('core.edit.state')) {

            if (isset($this->items[0]->state)) {
                JToolBarHelper::divider();
                JToolBarHelper::custom('apropos.publish', 'publish.png', 'publish_f2.png', 'JTOOLBAR_PUBLISH', true);
                JToolBarHelper::custom('apropos.unpublish', 'unpublish.png', 'unpublish_f2.png', 'JTOOLBAR_UNPUBLISH', true);
            } else if (isset($this->items[0])) {
                //If this component does not use state then show a direct delete button as we can not trash
                JToolBarHelper::deleteList('', 'apropos.delete', 'JTOOLBAR_DELETE');
            }

            if (isset($this->items[0]->state)) {
                JToolBarHelper::divider();
                JToolBarHelper::archiveList('apropos.archive', 'JTOOLBAR_ARCHIVE');
            }
            if (isset($this->items[0]->checked_out)) {
                JToolBarHelper::custom('apropos.checkin', 'checkin.png', 'checkin_f2.png', 'JTOOLBAR_CHECKIN', true);
            }
        }

        //Show trash and delete for components that uses the state field
        if (isset($this->items[0]->state)) {
            if ($state->get('filter.state') == -2 && $canDo->get('core.delete')) {
                JToolBarHelper::deleteList('', 'apropos.delete', 'JTOOLBAR_EMPTY_TRASH');
                JToolBarHelper::divider();
            } else if ($canDo->get('core.edit.state')) {
                JToolBarHelper::trash('apropos.trash', 'JTOOLBAR_TRASH');
                JToolBarHelper::divider();
            }
        }

        if ($canDo->get('core.admin')) {
            JToolBarHelper::preferences('com_diatem_joomla_3x_getconfig');
        }

        //Set sidebar action - New in 3.0
        JHtmlSidebar::setAction('index.php?option=com_diatem_joomla_3x_getconfig&view=apropos');

        $this->extra_sidebar = '';
        //
    }

	protected function getSortFields()
	{
		return array(
		);
	}

}

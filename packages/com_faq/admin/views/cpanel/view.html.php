<?php
/**
 * @package     Faq
 * @subpackage  com_faq
 * @copyright   Copyright (C) 2013 Makesoft, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access.
defined('_JEXEC') or die;

jimport('joomla.application.component.view');

/**
 * View class for Cpanel.
 *
 * @package     Faq
 * @subpackage  com_faq
 * @since       2.5
 */
class FaqViewCpanel extends JView
{
	protected $items;

	/**
	 * Method to display the view.
	 *
	 * @param   string  $tpl  A template file to load. [optional]
	 *
	 * @return  mixed  A string if successful, otherwise a JError object.
	 *
	 * @since   2.5
	 */
	public function display($tpl = null)
	{
		// Initialise variables.
		$this->items = $this->get('Items');

		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			JError::raiseError(500, implode("\n", $errors));
			return false;
		}

		$this->addToolbar();

		parent::display($tpl);
	}

	/**
	 * Add the page title and toolbar.
	 *
	 * @return  void
	 *
	 * @since   2.5
	 */
	protected function addToolbar()
	{
		require_once JPATH_COMPONENT . '/helpers/faq.php';

		$canDo = FaqHelper::getActions();
		$user = JFactory::getUser();

		JToolBarHelper::title(JText::_('COM_FAQ_MANAGER_CPANEL'), 'cpanel.png');

		if ($canDo->get('core.admin'))
		{
			JToolBarHelper::preferences('com_faq');
			JToolBarHelper::divider();
		}

		JToolBarHelper::help('cpanel', $com = true);
	}
}
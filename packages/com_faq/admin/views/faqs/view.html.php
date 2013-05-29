<?php
/**
 * @package     Faq
 * @subpackage  com_faq
 * @copyright   Copyright (C) 2013 Rene Bentes Pinto. All rights reserved.
 * @license     GNU General Public License version 2 or later; see http://www.gnu.org/licenses/gpl-2.0.html
 */

// No direct access.
defined('_JEXEC') or die;

jimport('joomla.application.component.view');

/**
 * View class for a list of Faqs.
 *
 * @package     Faq
 * @subpackage  com_faq
 * @since       2.5
 */
class FaqViewfaqs extends JView
{
	protected $items;

	protected $pagination;

	protected $state;

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
		$this->items      = $this->get('Items');
		$this->pagination = $this->get('Pagination');
		$this->state      = $this->get('State');

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

		$state = $this->get('State');
		$canDo = FaqHelper::getActions($state->get('filter.category_id'));
		$user  = JFactory::getUser();

		JToolBarHelper::title(JText::_('COM_FAQ_MANAGER_FAQS'), 'faqs.png');

		if (count($user->getAuthorisedCategories('com_faq', 'core.create')) > 0)
		{
			JToolBarHelper::addNew('faq.add');
		}

		if (($canDo->get('core.edit')))
		{
			JToolBarHelper::editList('faq.edit');
		}

		if ($canDo->get('core.edit.state'))
		{
			if ($state->get('filter.published') != 2)
			{
				JToolBarHelper::divider();
				JToolBarHelper::publish('faqs.publish', 'JTOOLBAR_PUBLISH', true);
				JToolBarHelper::unpublish('faqs.unpublish', 'JTOOLBAR_UNPUBLISH', true);
			}

			if ($state->get('filter.published') != -1)
			{
				JToolBarHelper::divider();
				if ($state->get('filter.published') != 2)
				{
					JToolBarHelper::archiveList('faqs.archive');
				}
				elseif ($state->get('filter.published') == 2)
				{
					JToolBarHelper::unarchiveList('faqs.publish');
				}
			}
		}

		if ($canDo->get('core.edit.state'))
		{
			JToolBarHelper::checkin('faqs.checkin');
		}

		if ($state->get('filter.published') == -2 && $canDo->get('core.delete'))
		{
			JToolBarHelper::deleteList('', 'faqs.delete', 'JTOOLBAR_EMPTY_TRASH');
			JToolBarHelper::divider();
		}
		elseif ($canDo->get('core.edit.state'))
		{
			JToolBarHelper::trash('faqs.trash');
			JToolBarHelper::divider();
		}

		JToolBarHelper::help('faqs', $com = true);
	}
}
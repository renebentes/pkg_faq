<?php
/**
 * @package     Faq
 * @subpackage  com_faq
 * @copyright   Copyright (C) 2013 Rene Bentes Pinto. All rights reserved.
 * @license     GNU General Public License version 2 or later; see http://www.gnu.org/licenses/gpl-2.0.html
 */

// No direct access.
defined('_JEXEC') or die;

/**
 * View class for Cpanel.
 *
 * @package     Faq
 * @subpackage  com_faq
 * @since       2.5
 */
class FaqViewCpanel extends JViewLegacy
{
	protected $items;

	protected $modules = null;

	protected $iconmodules = null;

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
		$model = JModelLegacy::getInstance('Faqs', 'FaqModel', array('ignore_request' => true));
		$model->setState('list.select', 'a.id, a.title, a.created, a.hits');
		$model->setState('list.limit', 5);
		$model->setState('list.ordering', 'a.hits');
		$model->setState('list.direction', 'desc');

		$this->items = $model->getItems();

		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			JError::raiseError(500, implode("\n", $errors));
			return false;
		}

		$layout = $this->getLayout();
		$version = new JVersion();
		if ($version->isCompatible(3.0))
		{
			$this->setLayout($layout . '30');

			// Display the submenu position modules.
			$this->iconmodules = JModuleHelper::getModules('faq-icon');
		}

		// Display the cpanel modules.
		$this->modules = JModuleHelper::getModules('faq-cpanel');

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
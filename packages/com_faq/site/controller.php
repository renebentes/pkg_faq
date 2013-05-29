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
 * Faq Component Controller
 *
 * @package     Faq
 * @subpackage  com_faq
 * @since       2.5
 */
class FaqController extends JControllerLegacy
{
	/**
	 * Method to display a view.
	 *
	 * @param   boolean  $cachable   If true, the view output will be cached
	 * @param   array    $urlparams  An array of safe url parameters and their variable types, for valid values see {@link JFilterInput::clean()}.
	 *
	 * @return  JControllerLegacy  This object to support chaining.
	 *
	 * @since   2.5
	 */
	public function display($cachable = false, $urlparams = false)
	{
		// Initialise variables.
		$cachable = true;
		$user     = JFactory::getUser();

		// Set the default view name and format from the Request.
		// Note we are using f_id to avoid collisions with the router and the return page.
		// Frontend is a bit messier than the backend.
		$id    = JRequest::getInt('f_id');
		$vName = JRequest::getCmd('view', 'faqs');

		JRequest::setVar('view', $vName);

		if ($user->get('id') ||($_SERVER['REQUEST_METHOD'] == 'POST' && $vName = 'faqs'))
		{
			$cachable = false;
		}

		$safeurlparams = array(
			'id'				=> 'INT',
			'limit'				=> 'UINT',
			'limitstart'		=> 'UINT',
			'filter_order'		=> 'CMD',
			'filter_order_Dir'	=> 'CMD',
			'lang'				=> 'CMD'
		);

		// Check for edit form.
		if ($vName == 'form' && !$this->checkEditId('com_faq.edit.faq', $id))
		{
			// Somehow the person just went to the form - we don't allow that.
			return JError::raiseError(403, JText::sprintf('JLIB_APPLICATION_ERROR_UNHELD_ID', $id));
		}

		return parent::display($cachable, $safeurlparams);
	}
}
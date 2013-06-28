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
 * Faq JSON controller for Faq Component
 *
 * @package     Faq
 * @subpackage  com_faq
 * @since       2.5
 */
class FaqControllerFaq extends JControllerLegacy
{
	/**
 	 * Constructor
 	 *
 	 * @param   array  $config  An optional associative array of configuration settings.
 	 *
 	 * @see     JControllerLegacy
 	 * @since   2.5
 	*/
	public function __construct($config = array())
	{
		parent::__construct($config);

		$this->registerTask('savehitajax', 'saveHitAjax');
	}

	/**
	 * Method to save the submitted hit values for records via AJAX.
	 *
	 * @return  void
	 *
	 * @since   2.5
	 */
	public function saveHitAjax()
	{
		// Get the application.
		$app = JFactory::getApplication();

		// Get the input.
		$pk = JRequest::getInt('Itemid');

		// Get the model.
		$model = $this->getModel('Form', 'FaqModel');

		// Save the hit.
		$return = $model->hit($pk);

		if ($return)
		{
			echo "1";
		}

		// Close the application.
		$app->close();
	}
}
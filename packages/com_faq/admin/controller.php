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
	 * @var		string	The default view.
	 * @since	2.5
	 */
	protected $default_view = 'cpanel';

	/**
	 * Variable declaration for compatibility with future versions
	 *
	 * @var JInput
	 */
	protected $input;

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
		$this->input = JFactory::getApplication()->input;

		parent::__construct($config);
	}

	/**
	 * Method to display a view.
	 *
	 * @param   boolean  $cachable   If true, the view output will be cached.
	 * @param   array    $urlparams  An array of safe url parameters and their variable types, for valid values see {@link JFilterInput::clean()}.
	 *
	 * @return  JController  This object to support chaining.
     *
	 * @since   2.5
	 */
	public function display($cachable = false, $urlparams = false)
	{
		require_once JPATH_COMPONENT . '/helpers/faq.php';

		parent::display();

		return $this;
	}
}
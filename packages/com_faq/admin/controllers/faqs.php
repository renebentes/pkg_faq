<?php
/**
 * @package     Faq
 * @subpackage  com_faq
 * @copyright   Copyright (C) 2013 Rene Bentes Pinto. All rights reserved.
 * @license     GNU General Public License version 2 or later; see http://www.gnu.org/licenses/gpl-2.0.html
 */

// No direct access.
defined('_JEXEC') or die;

jimport('joomla.application.component.controlleradmin');

/**
 * Faqs list controller class.
 *
 * @package     Faq
 * @subpackage  com_faq
 * @since       2.5
 */

class FaqControllerFaqs extends JControllerAdmin
{
	/**
	 * @var		string	The prefix to use with controller messages.
	 * @since	2.5
	 */
	protected $text_prefix = 'COM_FAQ_FAQS';

	/**
	 * Constructor.
	 *
	 * @param   array  $config  An optional associative array of configuration settings.
     *
	 * @see     JController
	 * @since   2.5
	 */
	public function __construct($config = array())
	{
		parent::__construct($config);
	}

	/**
	 * Proxy for getModel.
     *
	 * @param   string  $name    The model name. Optional.
	 * @param   string  $prefix  The class prefix. Optional.
	 * @param   array   $config  Configuration array for model. Optional.
     *
	 * @return  object  The model.
     *
	 * @since	2.5
	 */
	public function getModel($name = 'Faq', $prefix = 'FaqModel', $config = array('ignore_request' => true))
	{
		$model = parent::getModel($name, $prefix, $config);

		return $model;
	}
}
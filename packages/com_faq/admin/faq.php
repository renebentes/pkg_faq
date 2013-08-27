<?php
/**
 * @package     Faq
 * @subpackage  com_faq
 * @copyright   Copyright (C) 2013 Rene Bentes Pinto. All rights reserved.
 * @license     GNU General Public License version 2 or later; see http://www.gnu.org/licenses/gpl-2.0.html
 */

// No direct access.
defined('_JEXEC') or die;

// Access check.
if (!JFactory::getUser()->authorise('core.manage', 'com_faq'))
{
	return new Exception(JText::_('JERROR_ALERTNOAUTHOR'),  403);
}

require_once JPATH_COMPONENT . '/helpers/faq.php';

$controller = JControllerLegacy::getInstance('Faq');
$controller->execute(JFactory::getApplication()->input->get('task'));
$controller->redirect();
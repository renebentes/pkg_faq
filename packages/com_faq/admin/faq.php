<?php
/**
 * @package     Faq
 * @subpackage  com_faq
 * @copyright   Copyright (C) 2013 Rene Bentes Pinto. All rights reserved.
 * @license     GNU General Public License version 2 or later; see http://www.gnu.org/licenses/gpl-2.0.html
 */

// No direct access.
defined('_JEXEC') or die;

// Get the input.
$input = JFactory::getApplication()->input;

// Access check.
if (!JFactory::getUser()->authorise('core.manage', 'com_faq'))
{
	return JError::raiseWarning(404, JText::_('JERROR_ALERTNOAUTHOR'));
}

// Register dependent classes.
JLoader::register('FaqHelper', __DIR__ . '/helpers/faq.php');

$controller = JControllerLegacy::getInstance('Faq');
$controller->execute($input->get('task'));
$controller->redirect();
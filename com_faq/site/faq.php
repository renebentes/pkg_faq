<?php
/**
 * @package     Faq
 * @subpackage  com_faq
 * @copyright   Copyright (C) 2013 Makesoft, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access.
defined('_JEXEC') or die;

// Include dependancies
require_once JPATH_COMPONENT . '/helpers/route.php';
JTable::addIncludePath(JPATH_COMPONENT_ADMINISTRATOR . '/tables');

// Execute the task.
$controller = JControllerLegacy::getInstance('Faq');
$controller->execute(JRequest::getCmd('task'));
$controller->redirect();
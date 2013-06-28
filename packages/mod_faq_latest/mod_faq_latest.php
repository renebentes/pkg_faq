<?php
/**
 * @package     Faq
 * @subpackage  mod_faq_latest
 * @copyright   Copyright (C) 2012 Rene Bentes Pinto, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see http://www.gnu.org/licenses/gpl-2.0.html
 */

// No direct access.
defined('_JEXEC') or die;

// Include dependencies.
require_once __DIR__ . '/helper.php';

$list = modFaqLatestHelper::getList($params);

$layout = $params->get('layout', 'default');
$version = new JVersion();
if ($version->isCompatible(3.0))
{
	$layout = $layout . '30';
}
require JModuleHelper::getLayoutPath('mod_faq_latest', $layout);
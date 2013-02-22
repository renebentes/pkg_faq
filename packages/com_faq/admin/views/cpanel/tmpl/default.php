<?php
/**
 * @package     Faq
 * @subpackage  com_faq
 * @copyright   Copyright (C) 2013 Makesoft, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access.
defined('_JEXEC') or die;

// Get document
$doc = JFactory::getDocument();
$doc->setTitle(JText::_('COM_FAQ_CPANEL_TITLE'));
$doc->addStyleSheet(JURI::root() . 'media/com_faq/css/backend.css');

// Include the component HTML helpers.
JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/html');

?>
<div class="adminform">
	<div class="cpanel-left">
		<div id="cpanel">
			<?php echo FaqHelper::button('cpanel', 'cpanel.png', JText::_('COM_FAQ_QUICKICON_CPANEL')); ?>
			<?php echo FaqHelper::button('faqs', 'faq.png', JText::_('COM_FAQ_QUICKICON_FAQS')); ?>
			<?php echo FaqHelper::button('categories', 'category.png', JText::_('COM_FAQ_QUICKICON_CATEGORIES')); ?>
		</div>
	</div>
	<div class="cpanel-right">
		<?php echo $this->loadTemplate('stats'); ?>
	</div>
</div>
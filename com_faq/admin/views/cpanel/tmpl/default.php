<?php
/**
 * @package     Faq
 * @subpackage  com_faq
 * @copyright   Copyright (C) 2013 Makesoft, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access.
defined('_JEXEC') or die;
?>

<div class="adminform">
	<div class="cpanel-left">
		<div id="cpanel">
			<?php echo FaqHelper::button('cpanel', 'cpanel.png', JText::_('COM_FAQ_QUICKICON_CPANEL')); ?>
			<?php echo FaqHelper::button('faqs', 'faq.png', JText::_('COM_FAQ_QUICKICON_FAQS')); ?>
			<?php echo FaqHelper::button('categories', 'category.png', JText::_('COM_FAQ_QUICKICON_CATEGORIES')); ?>
		</div>
	</div>
</div>
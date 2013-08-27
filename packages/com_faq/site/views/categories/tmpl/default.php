<?php
/**
 * @package     Faq
 * @subpackage  com_faq
 * @copyright   Copyright (C) 2013 Rene Bentes Pinto. All rights reserved.
 * @license     GNU General Public License version 2 or later; see http://www.gnu.org/licenses/gpl-2.0.html
 */

// No direct access.
defined('_JEXEC') or die;

// Include the component HTML helpers.
JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/html');
var_dump($this);
?>
<section class="categories-list<?php echo $this->pageclass_sfx; ?>">
	<?php if ($this->params->get('show_page_heading')) : ?>
	<div class="page-header">
		<h1>
			<?php echo $this->escape($this->params->get('page_heading')); ?>
		</h1>
	</div>
	<?php endif; ?>

	<?php if ($this->params->get('show_base_description') && ($this->params->get('categories_description') || $this->parent->description)) : ?>
	<div class="category-desc base-desc well well-small">
		<?php if ($this->params->get('categories_description')) : ?>
			<?php echo JHtml::_('content.prepare', $this->params->get('categories_description'), '', 'com_faq.categories'); ?>
		<?php elseif ($this->parent->description) : ?>
			<?php echo JHtml::_('content.prepare', $this->parent->description, '', 'com_faq.categories'); ?>
		<?php endif; ?>
	</div>
	<?php endif; ?>
	<?php echo $this->loadTemplate('items'); ?>
</section>
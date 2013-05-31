<?php
/**
 * @package     Faq
 * @subpackage  com_faq
 * @copyright   Copyright (C) 2013 Rene Bentes Pinto. All rights reserved.
 * @license     GNU General Public License version 2 or later; see http://www.gnu.org/licenses/gpl-2.0.html
 */

// No direct access.
defined('_JEXEC') or die;

// Get document
$doc = JFactory::getDocument();
$doc->addStyleSheet(JURI::root() . 'media/com_faq/css/frontend.css');

?>
<section class="category-list<?php echo $this->pageclass_sfx; ?>">
	<?php if ($this->params->get('show_page_heading') || $this->params->get('show_category_title', 1) || $this->params->get('page_subheading')) : ?>
		<div class="page-header">
			<?php if($this->params->get('show_page_heading')) : ?>
			<h1>
				<?php echo $this->escape($this->params->get('page_heading')); ?>
				<?php if($this->params->get('show_category_title') or $this->params->get('page_subheading')) : ?>
				<small>
					<?php echo $this->escape($this->params->get('page_subheading')); ?>
					<?php echo $this->escape($this->category->title);?>
				</small>
				<?php endif; ?>
			</h1>
			<?php elseif($this->params->get('show_category_title', 1) or $this->params->get('page_subheading')) : ?>
			<h2>
				<?php echo $this->escape($this->params->get('page_subheading')); ?>
				<?php echo $this->escape($this->category->title);?>
			</h2>
			<?php endif; ?>
		</div>
	<?php endif; ?>

	<?php if ($this->params->get('show_description') || ($this->params->get('show_description_image') && $this->category->getParams()->get('image'))) : ?>
	<div class="category-desc well well-small">
		<div class="media">
			<?php if ($this->params->get('show_description_image') && $this->category->getParams()->get('image')) : ?>
			<a class="pull-left" href="#"><img class="media-object" src="<?php echo $this->category->getParams()->get('image'); ?>"/></a>
			<?php endif; ?>
			<?php if ($this->params->get('show_description') && $this->category->description) : ?>
			<div class="media-body">
				<?php echo JHtml::_('content.prepare', $this->category->description, '', 'com_faq.category'); ?>
			</div>
			<?php endif; ?>
		</div>
	</div>
	<?php endif; ?>

	<?php echo $this->loadTemplate('items'); ?>

	<?php if (!empty($this->children[$this->category->id]) && $this->maxLevel != 0) : ?>
	<div class="cat-children">
		<?php if ($this->params->get('show_category_heading_title_text', 1) == 1) : ?>
		<h3><?php echo JTEXT::_('JGLOBAL_SUBCATEGORIES'); ?></h3>
		<?php endif; ?>
		<?php echo $this->loadTemplate('children'); ?>
	</div>
	<?php endif; ?>

</section>
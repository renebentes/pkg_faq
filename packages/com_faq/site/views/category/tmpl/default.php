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
<?php if (empty($this->items) && $this->params->get('show_no_results', 1)) : ?>
<p><?php echo JText::_('COM_SERVICE_NO_RESULTS'); ?></p>
<?php else : ?>
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

	<div class="accordion" id="accordion2">
		<?php
		$x = 1;
		foreach ($this->items as $item) : ?>
		<div class="accordion-group">
			<div class="accordion-heading">
				<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#collapse<?php echo $item->id; ?>" >
					<i class="icon-question-sign"></i>
					<?php echo $this->escape($item->title); ?>
				</a>
			</div>
			<div id="collapse<?php echo $item->id; ?>" class="accordion-body collapse<?php echo $x == 1 ? ' in' : ''; ?>">
				<div class="accordion-inner">
					<?php echo JHtml::_('content.prepare', $item->description); ?>
				</div>
			</div>
		</div>
		<?php
		$x++;
		endforeach; ?>
	</div>

	<?php if ($this->params->def('show_pagination', 2) == 1  || ($this->params->get('show_pagination') == 2 && $this->pagination->get('pages.total') > 1)) : ?>
	<nav class="pagination pagination-centered">
		<?php echo $this->pagination->getPagesLinks(); ?>
		<?php if ($this->params->def('show_pagination_results', 1)) : ?>
		<p class="counter muted"><?php echo $this->pagination->getPagesCounter(); ?></p>
		<?php endif; ?>
	</nav>
	<?php endif; ?>
</section>
<?php endif; ?>
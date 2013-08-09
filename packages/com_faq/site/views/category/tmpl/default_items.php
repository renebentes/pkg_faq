<?php
/**
 * @package     Faq
 * @subpackage  com_faq
 * @copyright   Copyright (C) 2013 Rene Bentes Pinto. All rights reserved.
 * @license     GNU General Public License version 2 or later; see http://www.gnu.org/licenses/gpl-2.0.html
 */

// No direct access.
defined('_JEXEC') or die;

$listOrder = $this->escape($this->state->get('list.ordering'));
$listDirn = $this->escape($this->state->get('list.direction'));
?>

<script src="<?php echo JUri::root() . 'media/com_faq/js/faq.js' ?>" type="text/javascript"></script>

<?php if (empty($this->items) && $this->params->get('show_no_results', 1)) : ?>
<p><?php echo JText::_('COM_FAQ_NO_RESULTS'); ?></p>
<?php else : ?>
<div id="faq-items" class="accordion">
	<?php
	$x = 1;
	foreach ($this->items as $item) : ?>
	<div class="accordion-group">
		<div class="accordion-heading">
			<a href="#collapse<?php echo $item->id; ?>" class="accordion-toggle" data-toggle="collapse" data-parent="#faq-items">
				<i class="icon-question-sign"></i>
				<?php echo $this->escape($item->title); ?>
			</a>
		</div>
		<div id="collapse<?php echo $item->id; ?>" class="accordion-body collapse<?php echo $x == 1 ? ' in' : ''; ?>">
			<div class="accordion-inner">
			<?php if ($this->params->get('show_author') or $this->params->get('show_publish_date')) : ?>
				<ul class="inline pull-right">
				<?php if ($this->params->get('show_author')) : ?>
					<li>
						<i class="icon-user"></i>
						<?php $author =  $item->author; ?>
						<?php $author = ($item->created_by_alias ? $item->created_by_alias : $author);?>

						<?php if (!empty($item->contactid ) &&  $this->params->get('link_author') == true) : ?>
							<?php echo JText::sprintf('COM_FAQ_WRITTEN_BY' , JHtml::_('link', JRoute::_('index.php?option=com_contact&view=contact&id='. $item->contactid), $author)); ?>
						<?php else :?>
							<?php echo JText::sprintf('COM_FAQ_WRITTEN_BY', $author); ?>
						<?php endif; ?>
					</li>
				<?php endif; ?>
				<?php if ($this->params->get('show_publish_date')) : ?>
					<li>
						<i class="icon-calendar"></i>
						<?php echo JText::sprintf('COM_FAQ_PUBLISHED_DATE_ON', JHtml::_('date', $item->publish_up, JText::_('DATE_FORMAT_LC3'))); ?>
					</li>
				<?php endif; ?>
				</ul>
				<div class="clearfix"></div>
			<?php endif; ?>

				<?php echo JHtml::_('content.prepare', $item->description); ?>

			<?php if ($this->params->get('show_hits') or $this->params->get('show_rating')) : ?>
				<ul class="unstyled pull-right">
				<?php if ($this->params->get('show_hits')) : ?>
					<li>
						<i class="icon-eye-open"></i>
						<span id="hits<?php echo $item->id; ?>">
							<?php echo JText::sprintf('COM_FAQ_HITS', $item->hits); ?>
						</span>
					</li>
				<?php endif; ?>
				<?php if ($this->params->get('show_rating')) : ?>
					<li class="hasTooltip" rel="tooltip" data-original-title="<?php echo JText::_('COM_FAQ_FIELD_RATING'); ?>">
						<ul class="inline">
							<li>
								<span id="up<?php echo $item->id; ?>"><?php echo !empty($item->vote_up) ? $item->vote_up : 0; ?></span>
								<a class="vote" href="#up<?php echo $item->id; ?>">
									<b class="icon-thumbs-up"></b>
								</a>
							</li>
							<li>
								<span id="down<?php echo $item->id; ?>"><?php echo !empty($item->vote_down) ? $item->vote_down : 0; ?></span>
								<a class="vote" href="#down<?php echo $item->id; ?>">
									<b class="icon-thumbs-down"></b>
								</a>
							</li>
						</ul>
					</li>
				<?php endif; ?>
				</ul>
				<div class="clearfix"></div>
			<?php endif; ?>
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
<?php endif;?>
<?php endif;?>
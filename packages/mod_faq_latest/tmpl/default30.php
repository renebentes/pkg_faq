<?php
/**
 * @package     Faq
 * @subpackage  mod_faq_latest
 * @copyright   Copyright (C) 2012 Rene Bentes Pinto, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see http://www.gnu.org/licenses/gpl-2.0.html
 */

// No direct access.
defined('_JEXEC') or die;

JHtml::_('bootstrap.tooltip');
?>
<div class="row-striped">
	<?php if (count($list)) : ?>
		<?php foreach ($list as $i => $item) : ?>
			<div class="row-fluid">
				<div class="span9">
					<?php echo JHtml::_('jgrid.published', $item->published, $i, '', false); ?>
					<?php if ($item->checked_out) : ?>
						<?php echo JHtml::_('jgrid.checkedout', $i, $item->editor, $item->checked_out_time); ?>
					<?php endif; ?>

					<strong class="row-title">
						<?php if ($item->link) : ?>
							<a href="<?php echo $item->link; ?>">
								<?php echo htmlspecialchars($item->title, ENT_QUOTES, 'UTF-8');?></a>
						<?php else : ?>
							<?php echo htmlspecialchars($item->title, ENT_QUOTES, 'UTF-8'); ?>
						<?php endif; ?>
					</strong>

					<small class="small" class="hasTooltip" title="<?php echo JText::_('MOD_FAQ_LATEST_CREATED_BY'); ?>">
						<?php echo $item->author_name;?>
					</small>
				</div>
				<div class="span3">
					<span class="small"><i
							class="icon-calendar"></i> <?php echo JHtml::_('date', $item->created, JText::_('DATE_FORMAT_LC5')); ?></span>
				</div>
			</div>
		<?php endforeach; ?>
	<?php else : ?>
		<div class="row-fluid">
			<div class="span12">
				<div class="alert"><?php echo JText::_('MOD_FAQ_LATEST_NO_MATCHING_RESULTS');?></div>
			</div>
		</div>
	<?php endif; ?>
</div>
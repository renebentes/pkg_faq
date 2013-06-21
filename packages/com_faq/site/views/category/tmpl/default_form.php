<?php
/**
 * @package     Faq
 * @subpackage  com_faq
 * @copyright   Copyright (C) 2013 Rene Bentes Pinto. All rights reserved.
 * @license     GNU General Public License version 2 or later; see http://www.gnu.org/licenses/gpl-2.0.html
 */

// No direct access.
defined('_JEXEC') or die;

JHtml::_('behavior.keepalive');
JHtml::_('behavior.formvalidation');
JHtml::_('behavior.tooltip');

?>

<div class="accordion faq-form" id="accordion1">
	<div class="accordion-group">
		<div class="accordion-heading">
			<a href="#collapseask" class="accordion-toggle" data-toggle="collapse" data-parent="#accordion1">
				<i class="icon-plus"></i>
				<?php echo JText::_('COM_FAQ_NEW_ASK'); ?>
			</a>
		</div>
		<div id="collapseask" class="accordion-body collapse fade">
			<div class="accordion-inner">
				<form id="faq-form" action="<?php echo JRoute::_('index.php?option=com_faq'); ?>" method="post" class="form-validate">
					<fieldset>
						<legend><?php echo JText::_('COM_FAQ_FORM_DEFAULT_LEGEND'); ?></legend>
						<div class="row-fluid">
							<?php foreach ($this->form->getFieldsets('writer') as $name):
								$element = $this->form->getFieldset($name);
								if ($name == 'jwriter' && !empty($element)) : ?>
									<div class="span6">
										<?php foreach ($element as $field): ?>
											<?php echo $field->label; ?>
											<?php echo $field->input; ?>
										<?php endforeach; ?>
									</div>
								<?php endif;
							endforeach; ?>
						</div>
						<?php echo $this->form->getInput('title'); ?>
						<div class="row-fluid">
							<div class="span6">
								<button type="submit" class="button validate btn"><i class="icon-chevron-right"></i><?php echo JText::_('COM_FAQ_FAQ_SEND'); ?></button>
								<?php echo $this->form->getInput('catid'); ?>
								<?php echo $this->form->getInput('published'); ?>
								<?php echo $this->form->getInput('access'); ?>
								<?php echo $this->form->getInput('language'); ?>
								<input type="hidden" name="task" value="contact.submit" />
								<input type="hidden" name="return" value="<?php echo $this->return_page;?>" />
								<?php echo JHtml::_( 'form.token' ); ?>
							</div>
						</div>
					</fieldset>
				</form>
			</div>
		</div>
	</div>
</div>
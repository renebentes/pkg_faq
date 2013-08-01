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
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');

?>
<script type="text/javascript">
	Joomla.submitbutton = function(task) {
		if (document.formvalidator.isValid(document.getElementById('faqForm'))) {
			Joomla.submitform(task, document.getElementById('faqForm'));
		}
		else {
			alert('<?php echo $this->escape(JText::_('JGLOBAL_VALIDATION_FORM_FAILED'));?>');
		}
	}
</script>

<div id="faq-form" class="accordion">
	<div class="accordion-group">
		<div class="accordion-heading">
			<a href="#collapseask" class="accordion-toggle" data-toggle="collapse" data-parent="#faq-form">
				<i class="icon-plus"></i>
				<?php echo JText::_('COM_FAQ_FORM_DEFAULT_LEGEND'); ?>
			</a>
		</div>
		<div id="collapseask" class="accordion-body collapse">
			<div class="accordion-inner">
				<form name="faqForm" id="faqForm" action="<?php echo JRoute::_('index.php?option=com_faq'); ?>" method="post" class="form-validate">
					<div class="row-fluid">
						<?php foreach ($this->form->getFieldsets('writer') as $fieldsets => $fieldset) :
							$element = $this->form->getFieldset($fieldset->name);
							if ($fieldset->name == 'jwriter' && !empty($element)) : ?>
								<?php foreach ($element as $field): ?>
									<div class="span6">
										<?php echo $field->label; ?>
										<?php echo $field->input; ?>
									</div>
								<?php endforeach; ?>
							<?php endif;
						endforeach;?>
					</div>
					<?php echo $this->form->getLabel('title'); ?>
					<?php echo $this->form->getInput('title'); ?>
					<div class="row-fluid">
						<div class="span6">
							<button type="button" class="validate button btn btn-info" onclick="Joomla.submitbutton('faq.submit')"><i class="icon-chevron-right"></i><?php echo JText::_('JSUBMIT'); ?></button>
							<?php $this->form->setValue('catid', null, $this->state->get('category.id')); ?>
							<?php $this->form->setValue('created_by', null, $this->params->get('created_by')); ?>
							<?php echo $this->form->getInput('catid'); ?>
							<?php echo $this->form->getInput('published'); ?>
							<?php echo $this->form->getInput('access'); ?>
							<?php echo $this->form->getInput('language'); ?>
							<?php echo $this->form->getInput('created_by'); ?>
							<input type="hidden" name="task" value="" />
							<input type="hidden" name="return" value="<?php echo $this->return_page;?>" />
							<?php echo JHtml::_('form.token'); ?>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
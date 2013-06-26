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

//$form = JForm::getInstance('com_faq.category', JPATH_ROOT . '/components/com_faq/models/forms/category.xml', array('control' => 'jform'));
$model = JModelLegacy::getInstance('Form', 'FaqModel', array('ignore_request' => true));
$form = $model->getForm();

?>
<script type="text/javascript">
	Joomla.submitbutton = function(task) {
		if (task == 'faq.submit' || document.formvalidator.isValid(document.id('faq-form'))) {
			alert(document.id('faq-form'));
			//Joomla.submitform(task);
		}
		else {
			alert('<?php echo $this->escape(JText::_('JGLOBAL_VALIDATION_FORM_FAILED'));?>');
		}
	}
</script>

<div class="accordion faq-form" id="accordion1">
	<div class="accordion-group">
		<div class="accordion-heading">
			<a href="#collapseask" class="accordion-toggle" data-toggle="collapse" data-parent="#accordion1">
				<i class="icon-plus"></i>
				<?php echo JText::_('COM_FAQ_FORM_DEFAULT_LEGEND'); ?>
			</a>
		</div>
		<div id="collapseask" class="accordion-body collapse fade">
			<div class="accordion-inner">
				<form name="faq-form" id="faq-form" action="<?php echo JRoute::_('index.php?option=com_faq'); ?>" method="post" class="form-validate">
					<div class="row-fluid">
						<?php foreach ($form->getFieldsets('writer') as $fieldsets => $fieldset) :
							$element = $form->getFieldset($fieldset->name);
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
					<?php echo $form->getLabel('title'); ?>
					<?php echo $form->getInput('title'); ?>
					<div class="row-fluid">
						<div class="span6">
							<button type="submit" class="validate button btn btn-info"><i class="icon-chevron-right"></i><?php echo JText::_('JSUBMIT'); ?></button>
							<?php $form->setValue('catid', null, $this->state->get('category.id')); ?>
							<?php echo $form->getInput('catid'); ?>
							<?php echo $form->getInput('published'); ?>
							<?php echo $form->getInput('access'); ?>
							<?php echo $form->getInput('language'); ?>
							<input type="hidden" name="task" value="faq.submit" />
							<?php echo JHtml::_( 'form.token' ); ?>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
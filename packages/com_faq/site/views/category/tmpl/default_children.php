<?php
/**
 * @package     Faq
 * @subpackage  com_faq
 * @copyright   Copyright (C) 2013 Rene Bentes Pinto. All rights reserved.
 * @license     GNU General Public License version 2 or later; see http://www.gnu.org/licenses/gpl-2.0.html
 */

// No direct access.
defined('_JEXEC') or die;

$html = '';
?>

<ul id="myTab"class="nav nav-tabs">
	<?php foreach($this->children[$this->category->id] as $id => $child) :
		if ($this->params->get('show_empty_categories') || $child->numitems || count($child->getChildren())) : ?>
		<li>
			<a href="<?php echo $this->escape($child->alias); ?>" data-toggle="tab"><?php echo $this->escape($child->title); ?></a>
		</li>
		<?php
			$html .= '<div id="myTabContent" class="tab-content">';
			$html .= '	<div class="tab-pane fade" id="' . $this->escape($child->alias) . '">';
			$html .= 'TEXTO QUALQUER';
			$html .= '	</div>';
			$html .= '</div>';
		endif;
	endforeach;?>
</ul>
<?php echo $html; ?>
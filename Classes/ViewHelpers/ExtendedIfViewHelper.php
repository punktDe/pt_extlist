<?php

/**
 * renders <f:then> child if $condition or $or is true, otherwise renders <f:else> child.
 *
 * @param boolean $condition View helper condition
 * @param boolean $or View helper condition
 * @return string the rendered string
 */
class Tx_PtExtlist_ViewHelpers_ExtendedIfViewHelper extends Tx_Fluid_Core_ViewHelper_AbstractConditionViewHelper {

	/**
	 * renders <f:then> child if $condition or $or is true, otherwise renders <f:else> child.
	 *
	 * @param boolean $condition View helper condition
	 * @param boolean $or View helper condition
	 * @return string the rendered string
	 * @api
	 */
	public function render($condition, $or) {
		if ($condition || $or) {
			return $this->renderThenChild();
		} else {
			return $this->renderElseChild();
		}
	}
}

?>
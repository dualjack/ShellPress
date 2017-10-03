<?php 
/**
	Admin Page Framework v3.8.15 by Michael Uno 
	Generated by PHP Class Files Script Generator <https://github.com/michaeluno/PHP-Class-Files-Script-Generator>
	<http://en.michaeluno.jp/shellpress>
	Copyright (c) 2013-2017, Michael Uno; Licensed under MIT <http://opensource.org/licenses/MIT> */
class SP_v1_0_7_AdminPageFramework_FieldType_default extends SP_v1_0_7_AdminPageFramework_FieldType {
    public $aDefaultKeys = array();
    public function _replyToGetField($aField) {
        return $aField['before_label'] . "<div class='shellpress-input-label-container'>" . "<label for='{$aField['input_id']}'>" . $aField['before_input'] . ($aField['label'] && !$aField['repeatable'] ? "<span " . $this->getLabelContainerAttributes($aField, 'shellpress-input-label-string') . ">" . $aField['label'] . "</span>" : "") . $aField['value'] . $aField['after_input'] . "</label>" . "</div>" . $aField['after_label'];
    }
}

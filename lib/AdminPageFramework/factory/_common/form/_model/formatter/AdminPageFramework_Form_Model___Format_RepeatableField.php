<?php 
/**
	Admin Page Framework v3.8.15 by Michael Uno 
	Generated by PHP Class Files Script Generator <https://github.com/michaeluno/PHP-Class-Files-Script-Generator>
	<http://en.michaeluno.jp/shellpress>
	Copyright (c) 2013-2017, Michael Uno; Licensed under MIT <http://opensource.org/licenses/MIT> */
class SP_v1_0_7_AdminPageFramework_Form_Model___Format_RepeatableSection extends SP_v1_0_7_AdminPageFramework_FrameworkUtility {
    static protected $_aStructure = array('min' => 0, 'max' => 0, 'disabled' => false,);
    static protected $_aStructure_Disabled = array('message' => 'The ability of repeating sections is disabled.', 'caption' => 'Warning', 'box_width' => 300, 'box_height' => 72,);
    protected $_aArguments = array();
    protected $_oMsg;
    public function __construct() {
        $_aParameters = func_get_args() + array($this->_aArguments, null);
        $this->_aArguments = $this->getAsArray($_aParameters[0]);
        $this->_oMsg = $_aParameters[1] ? $_aParameters[1] : SP_v1_0_7_AdminPageFramework_Message::getInstance();
    }
    public function get() {
        $_aArguments = $this->_aArguments + self::$_aStructure;
        unset($_aArguments[0]);
        if (!empty($_aArguments['disabled'])) {
            $_aArguments['disabled'] = $_aArguments['disabled'] + array('message' => $this->_getDefaultMessage(), 'caption' => $this->_oMsg->get('warning_caption'),) + self::$_aStructure_Disabled;
        }
        return $_aArguments;
    }
    protected function _getDefaultMessage() {
        return $this->_oMsg->get('repeatable_section_is_disabled');
    }
}
class SP_v1_0_7_AdminPageFramework_Form_Model___Format_RepeatableField extends SP_v1_0_7_AdminPageFramework_Form_Model___Format_RepeatableSection {
    protected function _getDefaultMessage() {
        return $this->_oMsg->get('repeatable_field_is_disabled');
    }
}

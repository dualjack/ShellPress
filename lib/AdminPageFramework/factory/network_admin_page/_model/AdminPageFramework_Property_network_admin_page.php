<?php 
/**
	Admin Page Framework v3.8.15 by Michael Uno 
	Generated by PHP Class Files Script Generator <https://github.com/michaeluno/PHP-Class-Files-Script-Generator>
	<http://en.michaeluno.jp/shellpress>
	Copyright (c) 2013-2017, Michael Uno; Licensed under MIT <http://opensource.org/licenses/MIT> */
class SP_v1_0_6_AdminPageFramework_Property_network_admin_page extends SP_v1_0_6_AdminPageFramework_Property_admin_page {
    public $_sPropertyType = 'network_admin_page';
    public $sStructureType = 'network_admin_page';
    public $sSettingNoticeActionHook = 'network_admin_notices';
    protected function _getOptions() {
        return $this->addAndApplyFilter($this->getElement($GLOBALS, array('aSP_v1_0_6_AdminPageFramework', 'aPageClasses', $this->sClassName)), 'options_' . $this->sClassName, $this->sOptionKey ? get_site_option($this->sOptionKey, array()) : array());
    }
    public function updateOption($aOptions = null) {
        if ($this->_bDisableSavingOptions) {
            return;
        }
        return update_site_option($this->sOptionKey, $aOptions !== null ? $aOptions : $this->aOptions);
    }
}

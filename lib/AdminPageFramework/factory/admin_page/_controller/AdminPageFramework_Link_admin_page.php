<?php 
/**
	Admin Page Framework v3.8.15 by Michael Uno 
	Generated by PHP Class Files Script Generator <https://github.com/michaeluno/PHP-Class-Files-Script-Generator>
	<http://en.michaeluno.jp/shellpress>
	Copyright (c) 2013-2017, Michael Uno; Licensed under MIT <http://opensource.org/licenses/MIT> */
class SP_v1_0_7_AdminPageFramework_Link_admin_page extends SP_v1_0_7_AdminPageFramework_Link_Base {
    public $oProp;
    public function _replyToSetFooterInfo() {
        if (!$this->oProp->isPageAdded()) {
            return;
        }
        parent::_replyToSetFooterInfo();
    }
    public function _addLinkToPluginDescription($asLinks) {
        if (!is_array($asLinks)) {
            $this->oProp->aPluginDescriptionLinks[] = $asLinks;
        } else {
            $this->oProp->aPluginDescriptionLinks = array_merge($this->oProp->aPluginDescriptionLinks, $asLinks);
        }
        add_filter('plugin_row_meta', array($this, '_replyToAddLinkToPluginDescription'), 10, 2);
    }
    public function _addLinkToPluginTitle($asLinks) {
        if (!is_array($asLinks)) {
            $this->oProp->aPluginTitleLinks[] = $asLinks;
        } else {
            $this->oProp->aPluginTitleLinks = array_merge($this->oProp->aPluginTitleLinks, $asLinks);
        }
        $this->_addFilterHook_PluginTitleActionLink();
    }
    protected $_sFilterSuffix_PluginActionLinks = 'plugin_action_links_';
    private function _addFilterHook_PluginTitleActionLink() {
        static $_sPluginBaseName;
        if (isset($_sPluginBaseName)) {
            return;
        }
        $_sPluginBaseName = plugin_basename($this->oProp->aScriptInfo['sPath']);
        add_filter($this->_sFilterSuffix_PluginActionLinks . $_sPluginBaseName, array($this, '_replyToAddPluginActionLinks'));
    }
    public function _replyToAddInfoInFooterLeft($sLinkHTML = '') {
        if (!$this->_isPageAdded()) {
            return $sLinkHTML;
        }
        $sLinkHTML = empty($this->oProp->aScriptInfo['sName']) ? $sLinkHTML : $this->oProp->aFooterInfo['sLeft'];
        $_sPageSlug = $this->oProp->getCurrentPageSlug();
        $_sTabSlug = $this->oProp->getCurrentTabSlug();
        return $this->addAndApplyFilters($this->oProp->oCaller, array($this->getAOrB($_sTabSlug, 'footer_left_' . $_sPageSlug . '_' . $_sTabSlug, null), 'footer_left_' . $_sPageSlug, 'footer_left_' . $this->oProp->sClassName), $sLinkHTML);
    }
    public function _replyToAddInfoInFooterRight($sLinkHTML = '') {
        if (!$this->_isPageAdded()) {
            return $sLinkHTML;
        }
        $_sPageSlug = $this->oProp->getCurrentPageSlug();
        $_sTabSlug = $this->oProp->getCurrentTabSlug();
        return $this->addAndApplyFilters($this->oProp->oCaller, array($this->getAOrB($_sTabSlug, 'footer_right_' . $_sPageSlug . '_' . $_sTabSlug, null), 'footer_right_' . $_sPageSlug, 'footer_right_' . $this->oProp->sClassName), $this->oProp->aFooterInfo['sRight']);
    }
    private function _isPageAdded() {
        if (!isset($_GET['page'])) {
            return false;
        }
        return ( bool )$this->oProp->isPageAdded($_GET['page']);
    }
    public function _replyToAddSettingsLinkInPluginListingPage($aLinks) {
        if (count($this->oProp->aPages) < 1) {
            return $aLinks;
        }
        $this->oProp->sLabelPluginSettingsLink = null === $this->oProp->sLabelPluginSettingsLink ? $this->oMsg->get('settings') : $this->oProp->sLabelPluginSettingsLink;
        if (!$this->oProp->sLabelPluginSettingsLink) {
            return $aLinks;
        }
        $_sLinkURL = preg_match('/^.+\.php/', $this->oProp->aRootMenu['sPageSlug']) ? add_query_arg(array('page' => $this->oProp->sDefaultPageSlug), admin_url($this->oProp->aRootMenu['sPageSlug'])) : "admin.php?page={$this->oProp->sDefaultPageSlug}";
        array_unshift($aLinks, '<a ' . $this->getAttributes(array('href' => esc_url($_sLinkURL), 'class' => 'apf-plugin-title-action-link apf-post-type',)) . '>' . $this->oProp->sLabelPluginSettingsLink . '</a>');
        return $aLinks;
    }
    public function _replyToAddLinkToPluginDescription($aLinks, $sFile) {
        if ($sFile !== plugin_basename($this->oProp->aScriptInfo['sPath'])) {
            return $aLinks;
        }
        $_aAddingLinks = array();
        foreach (array_filter($this->oProp->aPluginDescriptionLinks) as $_sLLinkHTML) {
            if (!$_sLLinkHTML) {
                continue;
            }
            if (is_array($_sLLinkHTML)) {
                $_aAddingLinks = array_merge($_sLLinkHTML, $_aAddingLinks);
                continue;
            }
            $_aAddingLinks[] = ( string )$_sLLinkHTML;
        }
        return array_merge($aLinks, $_aAddingLinks);
    }
    public function _replyToAddPluginActionLinks($aLinks) {
        $_aAddingLinks = array();
        foreach (array_filter($this->oProp->aPluginTitleLinks) as $_sLinkHTML) {
            if (is_array($_sLinkHTML)) {
                $_aAddingLinks = array_merge($_sLinkHTML, $aAddingLinks);
                continue;
            }
            $_aAddingLinks[] = ( string )$_sLinkHTML;
        }
        return array_merge($aLinks, $_aAddingLinks);
    }
}

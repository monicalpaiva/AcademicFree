<?php

/**
 * @file plugins/themes/default/AcademicFreeThemePlugin.inc.php
 *
 * Copyright (c) 2014-2017 Simon Fraser University Library
 * Copyright (c) 2003-2017 John Willinsky
 * Distributed under the GNU GPL v2. For full terms see the file docs/COPYING.
 * 
 * Modified by openjournaltheme.com 
 * contact : openjournaltheme@gmail.com
 * 
 * 
 * @class AcademicFreeThemePlugin
 * @ingroup plugins_themes_bootstrap3
 *
 * @brief Default theme
 */

import('lib.pkp.classes.plugins.ThemePlugin');
class AcademicFreeThemePlugin extends ThemePlugin {

	public $pluginDir;

	function __construct() {	
		$this->pluginDir = $this->getPluginPath();
	}



	/**
	 * Initialize the theme
	 *
	 * @return null
	 */
	public function init() {

		HookRegistry::register ('TemplateManager::display', array($this, 'loadTemplateData'));

		// Register option for bootstrap themes
		$this->addOption('bootstrapTheme', 'radio', array(
			'label' => 'plugins.themes.academic_free.options.academicFree.label',
			'description' => 'plugins.themes.academic_free.options.academicFree.description',
			'options' => array(	
				'yeti'       => 'plugins.themes.academic_free.options.academicFree.yeti',
			)
		));




		// default style is paper
		$bootstrapTheme = $this->getOption('bootstrapTheme');
		$this->addStyle('bootstrap', 'styles/yeti.less');
	
		$locale = AppLocale::getLocale();
		if (AppLocale::getLocaleDirection($locale) === 'rtl') {
			if (Config::getVar('general', 'enable_cdn')) {
				$this->addStyle('bootstrap-rtl', '//cdn.rawgit.com/morteza/bootstrap-rtl/v3.3.4/dist/css/bootstrap-rtl.min.css', array('baseUrl' => ''));
			} else {
				$this->addStyle('bootstrap-rtl', 'styles/bootstrap-rtl.min.css');
			}
		}

		// Load jQuery from a CDN or, if CDNs are disabled, from a local copy.
		$min = Config::getVar('general', 'enable_minified') ? '.min' : '';
		$request = Application::getRequest();
		if (Config::getVar('general', 'enable_cdn')) {
			$jquery = '//ajax.googleapis.com/ajax/libs/jquery/' . CDN_JQUERY_VERSION . '/jquery' . $min . '.js';
			$jqueryUI = '//ajax.googleapis.com/ajax/libs/jqueryui/' . CDN_JQUERY_UI_VERSION . '/jquery-ui' . $min . '.js';
		} else {
			// Use OJS's built-in jQuery files
			$jquery = $request->getBaseUrl() . '/lib/pkp/lib/vendor/components/jquery/jquery' . $min . '.js';
			$jqueryUI = $request->getBaseUrl() . '/lib/pkp/lib/vendor/components/jqueryui/jquery-ui' . $min . '.js';
		}
		// Use an empty `baseUrl` argument to prevent the theme from looking for
		// the files within the theme directory
		$this->addScript('jQuery', $jquery, array('baseUrl' => ''));
		$this->addScript('jQueryUI', $jqueryUI, array('baseUrl' => ''));
		$this->addScript('jQueryTagIt', $request->getBaseUrl() . '/lib/pkp/js/lib/jquery/plugins/jquery.tag-it.js', array('baseUrl' => ''));

		// Load Bootstrap
		$this->addScript('bootstrap', 'bootstrap/js/bootstrap.min.js');

		// Add navigation menu areas for this theme
		$this->addMenuArea(array('primary', 'user'));	

		$this->addStyle('classy', 'styles/academic_free.css');

		


		//header option
		$this->addOption('headerTheme', 'radio', array(
			'label' => 'plugins.themes.academic_free.headerTheme.name',
			'description' => 'plugins.themes.academic_free.headerTheme.description',
			'options' => array(
				'red' => 'plugins.themes.academic_free.headerTheme.red',
				'edu'   => 'plugins.themes.academic_free.headerTheme.edu',
				'dots'  => 'plugins.themes.academic_free.headerTheme.dots',
				'diamond'  => 'plugins.themes.academic_free.headerTheme.diamond',
				'blue'  => 'plugins.themes.academic_free.headerTheme.blue',
			
			)
		));


	



		$headerTheme = $this->getOption('headerTheme');
		$this->addStyle('headerTheme', 'styles/header/blue.css');
	


	}

	/**
	 * Get the display name of this plugin
	 * @return string
	 */
	function getDisplayName() {
		return __('plugins.themes.academic_free.name');
	}

	/**
	 * Get the description of this plugin
	 * @return string
	 */
	function getDescription() {
		return __('plugins.themes.academic_free.description');
	}


	/**
	 * Fired when the `TemplateManager::display` hook is called.
	 *
	 * @param string $hookname
	 * @param array $args [$templateMgr, $template, $sendContentType, $charset, $output]
	 */
	public function loadTemplateData($hookName, $args) {

		// Retrieve the TemplateManager
		$templateMgr = $args[0];

		$baseUrl = Config::getVar('general', 'base_url');

		// Attach a custom piece of data to the TemplateManager
        $myCustomData = 'This is my custom data. It could be any PHP variable.';
		$templateMgr->assign('pluginDir', $this->getPluginPath());
		$templateMgr->assign('pluginImageDir', $baseUrl.'/'.$this->getPluginPath().'/images/');
		$templateMgr->assign('themeTag', '<small class="pull-right" style="margin-top: 20px"> Academic Free Theme <br> by <a href="https://openjournaltheme.com"> openjournaltheme.com </a> </small>');

	}
}

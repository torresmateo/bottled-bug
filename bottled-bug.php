<?php
/*
Plugin Name: Bottled Bug
Description: Hides Development Tests on Production Environments
Version: 1.0
Author: Symangy Team
Author URI: https://github.com/torresmateo/bottled-bug
License:GPL3
*/

/*
This program is free software; you can redistribute it and/or modify 
it under the terms of the GNU General Public License as published by 
the Free Software Foundation; version 2 of the License.

This program is distributed in the hope that it will be useful, 
but WITHOUT ANY WARRANTY; without even the implied warranty of 
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the 
GNU General Public License for more details. 

You should have received a copy of the GNU General Public License 
along with this program; if not, write to the Free Software 
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA 
*/

class BottledBug{

	//for use in filtering to avid infinite recursive loops
	private $allCatsArray;
	//for category edition
	private $finalSet;

	//============================================================================================================================
	//						  INSTALL/UNINSTALL
	//============================================================================================================================

	function __construct(){
		/*
		$this->allCatsArray = get_all_category_ids();
		//database access for configuration retrieval
		include_once('configuration-access.php');
		//input handler, and database acces for insertions, deletions and modifications
		include_once('input-handler.php');
		//activation and deactivation hooks
		register_activation_hook(__FILE__, array($this,'install'));
		register_deactivation_hook(__FILE__, array($this, 'uninstall'));
		
		//interface actions

		//adds the css files for "pretty-ness"
		add_action('admin_head', array($this,'adminCSS'));
		//adds the js files for "awsum-ness"
		add_action('admin_head', array($this,'adminJS'));
		//restriction actions

		//adds the edition screen restrictions
		add_action('pre_get_posts', array($this,'restrictEditScreen'));
		//adds the edition screen restrictions
		add_action('load-post.php', array($this,'restrictPostEdition'));
		//adds the category filter for widgets, metaboxes and other admin places
		add_action('list_terms_exclusions', array($this,'filterCategories'));

		//restriction filters

		//users cannot modify post categories other than those in configuration
		add_filter('wp_insert_post_data',array($this,'disableCategoryUpdate'), '99', 2);
	
		
		$this->evalInput();
		*/
		//interface handler
		include_once('interface-builder.php');

		//adds the coniguration settings
		add_action('admin_init', array($this, 'adminInit'));

		//adds the configuration menu to the dashboard
		add_action('admin_menu', array($this,'adminMenu'));
		
		add_action('wp_head',array($this,'templateCSS'));
	}

	function install(){
		//create database
		$this->createTables();
	}

	function uninstall(){
		//erase database
		$this->dropTables();
	}

	
	//============================================================================================================================
	//							 TEMPLATE MODIFIERS
	//============================================================================================================================
	
	function templateCSS(){
		echo '<link rel="stylesheet" type="text/css" media="screen" href="'.plugin_dir_url(__FILE__).'/views/template/css/style.css" />';
	}


	//============================================================================================================================
	//							 ADMINISTRATOR GUI
	//============================================================================================================================
	
	function adminInit(){
		register_setting('bottledBugOptions', 'bottledMainSettings', array($this, 'validateOptions'));
		add_settings_section('bottled_main', 'Main Settings', array($this,'plugin_section_text'), 'bottledMainOptions'); 
		add_settings_field('plugin_text_string', 'Plugin Text Input', array($this,'plugin_setting_string'), 'bottledMainOptions', 'bottled_main');
	}
	
	function validateOptions($input){
		$newinput['text_string'] = trim($input['text_string']);
		return $newinput;
	}

	function plugin_section_text() {
		echo '<p>Main description of this section here.</p>';
	}


	function plugin_setting_string() {
		$options = get_option('bottledMainSettings');
		echo "<input id='plugin_text_string' name='bottledMainSettings[text_string]' size='40' type='text' value='{$options['text_string']}' />";
	}
	//outputs the CSS link
	public function adminCSS(){
		echo '<link rel="stylesheet" type="text/css" media="screen" href="'.plugin_dir_url(__FILE__).'/views/template/css/style.css" />';
	}

	//outputs the JavaScript link
	public function adminJS(){
		//load jQuery
		echo '<script type="text/javascript"> if (window.jQuery == undefined) document.write( unescape(\'%3Cscript src="http://code.jquery.com/jquery-latest.min.js" type="text/javascript"%3E%3C/script%3E\') );</script>';
		//load the plugin script
		echo '<script type="text/javascript" src="'.plugin_dir_url(__FILE__).'/views/template/js/moderate-categories.js"></script>';
	}

	//Adds the "Moderate Categories" menu to the admin dashboard
	public function adminMenu(){
		add_menu_page(  'Bottled Bug',
						'Bottled Bug ',
						'manage_options',
						'bottled-bug',
						array($this,'mainMenu'),
						plugin_dir_url(__FILE__).'/views/template/img/top.logo.png');
	}

	//prints the AWSUM config screen (role-categories screen)
	public function mainMenu(){
		if (!current_user_can('manage_options'))  {
			wp_die( __('You do not have sufficient permissions to access this page.') );
		}
		$target = 'error';//default value is error page

		//if we are inside one of our tabs
		if(isset($_GET['tab'])){
			switch($_GET['tab']){
				case '1': $target = 'userMenu'; break;
				case '2': $target = 'how-to-Page'; break;
				default: $target = 'error'; //because redundancy is never really redundant enough!
			}
		}else{//if not set, must be mainMenu
			$target = 'mainMenu';
		}
		$configMenu = new InterfaceBuilder($target);

		//paint it!
		$configMenu->build();
	}

}



$bottledBug = new BottledBug();
?>

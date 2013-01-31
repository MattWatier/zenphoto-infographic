<?php
/**
 * Loads Colorbox JS and CSS scripts for selected theme page scripts.
 *
 * Note that this plugin does not attach Colorbox to any element because there are so many different options and usages.
 * You need to do this in your theme yourself. Visit the {@link http://colorpowered.com/colorbox/ colorbox} site for information.
 *
 * The plugin has built in support for 5 example Colorbox themes shown below:
 *
 * 		<img src="%WEBPATH%/%ZENFOLDER%/%PLUGIN_FOLDER%/colorbox_js/themes/example1.jpg" />
 * 		<img src="%WEBPATH%/%ZENFOLDER%/%PLUGIN_FOLDER%/colorbox_js/themes/example2.jpg" />
 * 		<img src="%WEBPATH%/%ZENFOLDER%/%PLUGIN_FOLDER%/colorbox_js/themes/example3.jpg" />
 * 		<img src="%WEBPATH%/%ZENFOLDER%/%PLUGIN_FOLDER%/colorbox_js/themes/example4.jpg" />
 * 		<img src="%WEBPATH%/%ZENFOLDER%/%PLUGIN_FOLDER%/colorbox_js/themes/example5.jpg" />
 *
 * If you select <i>custom (within theme)</i> on the plugin option for Colorbox you need to place a folder
 * <i>colorbox</i> containing a <i>colorbox.css</i> file and a folder <i>images</i> within the current theme
 * to override to use a custom Colorbox theme.
 *
 * @author Stephen Billard (sbillard)
 *
 * @package plugins
 */

$plugin_is_filter = 9|THEME_PLUGIN;
$plugin_description = gettext('Loads Colorbox JS and CSS scripts for selected theme page scripts.');
$plugin_notice = gettext('Note that this plugin does not attach Colorbox to any element. You need to do this on your theme yourself.');
$plugin_author = 'Stephen Billard (sbillard)';
$option_interface = 'colorbox';

if (OFFSET_PATH) {
	zp_register_filter('admin_head','colorbox::css');
} else {
	global $_zp_gallery, $_zp_gallery_page;
	if (getOption('colorbox_'.$_zp_gallery->getCurrentTheme().'_'.stripSuffix($_zp_gallery_page))) {
		zp_register_filter('theme_head','colorbox::css');
	}
}

class colorbox {

	function __construct() {
		//	These are best set by the theme itself!
		setOptionDefault('colorbox_theme','example1');
	}

	function getOptionsSupported() {
		global $_zp_gallery;
		$opts  = array(gettext('Colorbox theme') => array('key' => 'colorbox_theme', 'type' => OPTION_TYPE_SELECTOR,
										'selections' => array(gettext('Example1') => "example1", gettext('Example2') => "example2", gettext('Example3') => "example3", gettext('Example4') => "example4",gettext('Example5') => "example5",gettext('Custom (theme based)') => "custom"),
										'desc' => gettext("The Colorbox script comes with 5 example themes you can select here. If you select <em>custom (within theme)</em> you need to place a folder <em>colorbox_js</em> containing a <em>colorbox.css</em> file and a folder <em>images</em> within the current theme to override to use a custom Colorbox theme."))
									);
		$exclude = array('404.php','themeoptions.php','theme_description.php');
		foreach (array_keys($_zp_gallery->getThemes()) as $theme) {
			$curdir = getcwd();
			$root = SERVERPATH.'/'.THEMEFOLDER.'/'.$theme.'/';
			chdir($root);
			$filelist = safe_glob('*.php');
			$list = array();
			foreach($filelist as $file) {
				if (!in_array($file,$exclude)) {
					$list[$script = stripSuffix(filesystemToInternal($file))] = 'colorbox_'.$theme.'_'.$script;
				}
			}
			chdir($curdir);
			$opts[$theme] = array('key' => 'colorbox_'.$theme.'_scripts', 'type' => OPTION_TYPE_CHECKBOX_ARRAY,
																	'checkboxes' => $list,
																	'desc' => gettext('The scripts for which Colorbox is enabled. {Should have been set by the themes!}')
											);
		}

		return $opts;
	}

	function handleOption($option, $currentValue) {
	}

	static function css() {
		global $_zp_gallery;
		$inTheme = false;
		if (OFFSET_PATH) {
			$themepath = 'colorbox_js/themes/example4/colorbox.css';
		} else {
			$theme = getOption('colorbox_theme');
			if(empty($theme)) {
				$themepath = 'colorbox_js/themes/example4/colorbox.css';
			} else {
				if($theme == 'custom') {
					$themepath = 'colorbox_js/colorbox.css';
				} else {
					$themepath = 'colorbox_js/themes/'.$theme.'/colorbox.css';
				}
				$inTheme = $_zp_gallery->getCurrentTheme();
			}
		}
		$css = getPlugin($themepath,$inTheme,true);
		?>
		<link rel="stylesheet" href="<?php echo $css; ?>" type="text/css" />
		<?php
		$navigator_user_agent = ( isset( $_SERVER['HTTP_USER_AGENT'] ) ) ? strtolower( $_SERVER['HTTP_USER_AGENT'] ) : '';
		if (stristr($navigator_user_agent, "msie") && !stristr($navigator_user_agent, '9')) {
			include(dirname(__FILE__).'/colorbox_js/colorbox_ie.css.php');
		}
		?>
		<script type="text/javascript" src="<?php echo FULLWEBPATH."/".ZENFOLDER.'/'.PLUGIN_FOLDER; ?>/colorbox_js/jquery.colorbox-min.js"></script>
		<?php
	}

}
?>
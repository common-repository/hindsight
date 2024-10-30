<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://rockerbox.com
 * @since      1.0.0
 *
 * @package    Hindsight
 * @subpackage Hindsight/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Hindsight
 * @subpackage Hindsight/admin
 * @author     Rockerbox <farid@rockerbox.com>
 */
class Hindsight_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
		$this->addPluginToMenu();

		// Add settings to db if they didn't exist before
		$blank_settings = serialize(array(
			'pixels'	=> array(
				'allpages'		=>	'',
				'conversion'	=>	''
			)
		));
		add_option('hindsight', $blank_settings);

		if(isset($_POST['pixel_code'])) {
			$settings = serialize(array(
				'pixels'	=> array(
					'allpages'		=>	$_POST['pixel_code'],
					'conversion'	=>	''
				)
			));
			update_option('hindsight', $settings);

			$js_pixel_code = str_replace(array('<script type="text/javascript">','</script>'), '', $_POST['pixel_code']);

			$fp = fopen(WP_PLUGIN_DIR . '/hindsight/public/js/rockerbox-hindsight-pixel.js', 'w');
			fwrite($fp, $js_pixel_code);
			fclose($fp);
		}

		// Get settings from database
		$this->settings = unserialize(get_option('hindsight'));
	}

	private function addPluginToMenu() {
		add_action( 'admin_menu', function() {
			add_options_page( 'Hindsight', 'Hindsight', 'manage_options', 'hindsight', function() {
				$page = '<div class="wrap">
				<h2>Configure Hindsight</h2>
				<h3>Basic Options</h3>
				<p>Copy your pixel code from the <a href="http://hindsight.getrockerbox.com/crusher/settings/pixel/setup" target="_blank">pixel setup page in Hindsight</a>.</p>
				<form action="admin.php?page=hindsight" method="POST">
					<table class="form-table">
						<tr valign="top">
							<th scope="row">All Pages Pixel</th>
							<td>
								<textarea id="pixel_code" name="pixel_code" placeholder="Paste your pixel code here" rows="8" cols="100">'. $this->settings['pixels']['allpages'] .'</textarea>
								<!--<div id="status" style="width: 250px; text-align: center; border: solid 2px #ff2244; font-weight: bold; color: #fff; text-transform: uppercase; background-color: #ff2244; padding: 10px; margin-top: 20px;">Hindsight is currently disabled</div>-->
							</td>
						</tr>

						<!--
						<tr valign="top">
							<th scope="row">Conversion Pixel <sup>(optional)</sup></th>
							<td><textarea placeholder="Paste your pixel code here" rows="8" cols="100"></textarea></td>
						</tr>
						-->
					</table>

					<p class="submit">
						<input type="submit" name="submit" id="submit" class="button button-primary" value="Save Changes">
					</p>
				</form>
				</div>';

				echo $page;
			}, 'dashicons-chart-bar', null );
			// add_submenu_page( 'myplugin/myplugin-admin-page.php', 'My Sub Level Menu Example', 'Sub Level Menu', 'manage_options', 'myplugin/myplugin-admin-sub-page.php', 'myplguin_admin_sub_page' );
		} );
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Hindsight_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Hindsight_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/hindsight-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Hindsight_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Hindsight_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/hindsight-admin.js', array( 'jquery' ), $this->version, false );

	}

}

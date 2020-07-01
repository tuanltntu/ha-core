<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://tuanltntu.com
 * @since      1.0.0
 *
 * @package    Ha_Core
 * @subpackage Ha_Core/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Ha_Core
 * @subpackage Ha_Core/admin
 * @author     Tuan Le <tuanltntu@gmail.com>
 */
class Ha_Core_Admin {

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
		 * defined in Ha_Core_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Ha_Core_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_register_style( $this->plugin_name, 'https://unpkg.com/ant-design-vue@1.6.2/dist/antd.min.css', '', '', false );
		wp_enqueue_style( $this->plugin_name . '-global', plugin_dir_url( __FILE__ ) . 'css/ha-core-admin.css', '', $this->version, 'all' );

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
		 * defined in Ha_Core_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Ha_Core_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_register_script( 'ha-axios-js', 'https://unpkg.com/axios/dist/axios.min.js', '', '', false );
		wp_register_script( 'ha-vue-js', 'https://cdn.jsdelivr.net/npm/vue/dist/vue.min.js', '', '', false );		
		wp_register_script( 'ha-moment-js', 'https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js', '', '', false );
		wp_register_script( 'ha-moment-vi-js', 'https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/locale/vi.js', '', '', false );
		wp_register_script( 'ha-ant-design-vue-js', 'https://unpkg.com/ant-design-vue@1.6.2/dist/antd.min.js', '', '', false );
		wp_register_script( 'ha-color-js', plugin_dir_url( __FILE__ ) . 'js/libs/vue-color.min.js', '', '', false );

		wp_register_script( 'ha-core-mixin', plugin_dir_url( __FILE__ ) . 'js/ha-core-mixin.js', array ('jquery'), $this->version, false );
		wp_register_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/ha-core-admin.js', array ('jquery', 'ha-moment-js', 'ha-moment-vi-js', 'ha-axios-js', 'ha-vue-js', 'ha-ant-design-vue-js', 'ha-core-mixin'), $this->version, false );
		wp_register_script( $this->plugin_name . '-overview', plugin_dir_url( __FILE__ ) . 'js/ha-core-overview.js', array ($this->plugin_name), $this->version, false );
		
		wp_localize_script( $this->plugin_name, 'HA', ['ajax_url' => admin_url( 'admin-ajax.php' )] );
	}

	public function admin_menu(){
		add_menu_page( 'HA Plugins', 'HA Plugins', 'manage_options', HA_MENU, [$this, 'overview'], 'dashicons-building', 40 );
		add_submenu_page(HA_MENU, __('Overview', $this->plugin_name), __('Overview', $this->plugin_name), 'manage_options', HA_MENU, [$this, 'overview']);
	}
	
	public function overview(){
		/*
			[
				[
					'name' 			=> '',
					'image'			=> '',
					'link'			=> '',
					'description'	=> ''
				]
			]
		*/

        $showOverview = apply_filters(HA_CORE . 'hide_overview', 1);

        if($showOverview) {
            $plugins = file_get_contents(HA_PLUGINS);
            if ($plugins) {
                $plugins = json_decode($plugins);
            }
            $purchase_code = get_option('ha_purchase_code');

            $localize = [
                'menu_items' => Ha_Helpers::get_menu_items(),
                'currentPage' => [HA_MENU],
                'currentOpen' => [''],
                'menu_url' => get_admin_url() . 'admin.php?page=',
                'title' => __('Overview', $this->plugin_name),
                'logo' => HA_LOGO,
                'api' => [],
                'data' => [
                    'items' => $plugins,
                    'purchase_code' => $purchase_code
                ]
            ];


            wp_enqueue_style($this->plugin_name);
            wp_enqueue_script($this->plugin_name . '-overview');
            wp_localize_script($this->plugin_name . '-overview', 'HA', $localize);

            include 'partials/ha-core-admin-display.php';
        }
	}
}

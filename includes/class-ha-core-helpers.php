<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://tuanltntu.com
 * @since      1.0.0
 *
 * @package    Ha_Helpers
 * @subpackage Ha_Helpers/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Ha_Helpers
 * @subpackage Ha_Helpers/includes
 * @author     Tuan Le <tuanltntu@gmail.com>
 */
class Ha_Helpers {

	public static function clean($input, $requires = [], &$errors = []){

		foreach($input as $k=>$val){
			if(is_array($val)){
				$input[$k] = self::clean($val, $requires, $errors);
			}else{
				if(!$val && $requires && array_key_exists($k, $requires)){
					$errors[] = $requires[$k];
				}else{
					$input[$k] = wp_kses_post($val);
				}
			}
		}
		
		if($errors){
			$errors = apply_filters(HA_CORE . 'required_error', [
				'message'	=> __('Please fill: ', HA_CORE) . implode(', ', $errors)
			], $errors);
			wp_send_json_error($errors);
		}
		
		return $input;
	}
	
	public static function verify_nonce($plugin_name, $action = ''){
		$action = $action ? $action : $plugin_name;
		$inputJSON = file_get_contents('php://input');
		if($inputJSON){
			$input = json_decode($inputJSON, TRUE);
			if($input['_nonce'] && $input['data']){
				
				if ( ! wp_verify_nonce( $input['_nonce'], $action ) ){
					wp_send_json_error(apply_filters($plugin_name . '_nonce_error', ['message'	=> __('Request is not valid', $plugin_name)]));
				}
				return $input['data'];
			}
		}
		wp_send_json_error(apply_filters($plugin_name . '_request_error', ['message'	=> __('Request is not valid', $plugin_name)]));
	}
	
	public static function get_menu_items(){

        $menu = [];
        $showOverview = apply_filters(HA_CORE . 'hide_overview', 1);

        if($showOverview) {
            $menu[] = [
                'key' => HA_MENU,
                'title' => __('Overview', HA_CORE),
                'icon' => 'file-search',
                'order' => 5,
            ];
        }

		$menu = apply_filters(HA_CORE . '_menu', $menu);
		
		usort($menu, function($a, $b){
			return $a['order'] > $b['order'];
		}); 
		
		return $menu;
	}
	
	public static function get_header(){
		include HA_CORE_PATH . 'admin/partials/ha-core-header.php';
	}
	
	public static function get_footer(){
		include HA_CORE_PATH . 'admin/partials/ha-core-footer.php';
	}

	/** Process upgrade plugin **/

    public static function plugin_info( $res, $action, $args ){

        if( 'plugin_information' !== $action ) {
            return false;
        }

        $plugin_slug = '';

        if( $plugin_slug !== $args->slug ) {
            return false;
        }

        if( false == $remote = get_transient( HA_CORE . '_update_' . $plugin_slug ) ) {

            $remote = wp_remote_get( HA_PLUGINS . $plugin_slug . '/upgrade.json', array(
                    'timeout' => 10,
                    'headers' => array(
                        'Accept' => 'application/json'
                    ) )
            );

            if ( ! is_wp_error( $remote ) && isset( $remote['response']['code'] ) && $remote['response']['code'] == 200 && ! empty( $remote['body'] ) ) {
                set_transient( HA_CORE .'_update_' . $plugin_slug, $remote, 43200 ); // 12 hours cache
            }

        }

        if( ! is_wp_error( $remote ) && isset( $remote['response']['code'] ) && $remote['response']['code'] == 200 && ! empty( $remote['body'] ) ) {

            $remote = json_decode( $remote['body'] );
            $res = new stdClass();

            $res->name = $remote->name;
            $res->slug = $plugin_slug;
            $res->version = $remote->version;
            $res->tested = $remote->tested;
            $res->requires = $remote->requires;
            $res->author = $remote->author;
            $res->author_profile = $remote->author_profile;
            $res->download_link = $remote->download_url;
            $res->trunk = $remote->download_url;
            $res->requires_php = $remote->requires_php;
            $res->last_updated = $remote->last_updated;
            $res->sections = $remote->sections;

            if( !empty( $remote->sections->screenshots ) ) {
                $res->sections['screenshots'] = $remote->sections->screenshots;
            }

            $res->banners = array(
                'low' => $res->banners->low,
                'high' => $res->banners->high
            );
            return $res;

        }

        return false;

    }
}
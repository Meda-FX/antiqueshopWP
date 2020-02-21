<?php
/**
 * @since 		1.7       2020-02-19      Release
 * @package 	FDC
 * @subpackage 	FDC/Admin
 */
// ───────────────────────────
namespace FDC\Admin;
use FDC as fdc;
use FDC\Core\Fdc_Abstract as fdcBase;
use FDC\Core\Fdc_Helper as fdcHelper;
// ───────────────────────────
/*
|--------------------------------------------------------------------------
| Class responsible for integration plugins
|--------------------------------------------------------------------------
*/
class Fdc_Integration extends fdcBase{
    public
	/**
	 * Data passed by AJAX for manipulation
	 * @access  public
     * @since   1.7     2020-02-19      Release
     * @var     array
	 */
	$request = [];

	private
	/**
	 * Variable with the generator library
	 * @access	private
	 * @since 	1.7		2020-02-19		Release
	 * @var		LZFakeTextGenerator
	 */
    $LZFAKE;

    /**
     * Class initializer
     *
     * @since   1.7     2020-02-19      Release
     * @param   string  $output
     */
    public function __construct( $data = [], $output = 'array' ){
		// ─── Set data request ────────
		$this->request = $data;

		// ─── Instance Lib Fake ────────
        require_once fdc\PATH . 'include/libs/LZFakeTextGenerator.php';
		$this->LZFAKE = new \LZFakeTextGenerator;
    }

	/**
	 * Register fake views and clicks
	 *
	 * @since	1.7		2020-02-19		Release
	 * @return 	array
	 */
	public function yuzo_update_views(){
		global $wpdb;
		$i = 0;
		$return = [];

		// ─── Get Country ────────

		$code_countries = [
			'EC' => 'Ecuador',
			'US' => 'United States',
			'CA' => 'Canada',
			'MX' => 'Mexico',
			'ES' => 'Spain',
			'IT' => 'Italia',
			'JP' => 'Japon',
		];

		// ─── Get Yuzos ────────
		$yuzos = $wpdb->get_results("SELECT ID FROM {$wpdb->prefix}posts WHERE post_type = 'yuzo' and post_status = 'publish'");
		$yuzos_ids = [];
		if( $wpdb->num_rows > 0 ){
			foreach ($yuzos as $key => $value) {
				$yuzos_ids[] = $value->ID;
			}
		}
		if( count($yuzos_ids) == 0 ) return;

		// ─── Get City ────────
		$city = [
			'Guayaquil',
			'Maimi',
			'Ottawa',
			'Tijuana',
			'Barcelona',
			'Roma',
			'Roma',
			'Tokio'
		];

		// ─── States ────────
		$states = [
			'Alabama',
			'Alaska',
			'Arizona',
			'Arkansas',
			'California',
			'Colorado',
		];

		// ─── Type click ────────
		$type_click = [
			'w', // Widget
			'c', // Content
			's', // Shortcode
		];

		// ─── Device ────────
		$device = [
			'm', // mobile
			't', // tablet
			'd', // desktop
		];

		// ─── Price ────────
		$price = [
			'0.05',
			'0.10',
			'0.15',
			'0.20',
			'0.25',
			'0.30',
		];


		// ─── Post date min and max ────────
		$post_max_date = $wpdb->get_row("SELECT MAX(ID) as ID_MAX_DATE FROM {$wpdb->prefix}posts");
		$post_max_date = $post_max_date->ID_MAX_DATE;
		$post_min_date = $wpdb->get_row("SELECT MIN(ID) as ID_MIN_DATE FROM {$wpdb->prefix}posts");
		$post_min_date = $post_min_date->ID_MIN_DATE;
		$sql_max_date = $wpdb->get_row("SELECT post_date FROM {$wpdb->prefix}posts WHERE ID = $post_max_date");
		$max_date = $sql_max_date->post_date;
		$sql_min_date = $wpdb->get_row("SELECT post_date FROM {$wpdb->prefix}posts WHERE ID = $post_min_date");
		$min_date = $sql_min_date->post_date;
		$range_date = function( $from, $to ){
			$min = strtotime($from);//you can change it to your timestamp;
			$max = strtotime($to);//you can change it to your timestamp;
			// Generate random number using above bounds
			$val = rand($min, $max);

			// Convert back to desired date format
			return date('Y-m-d H:i:s', $val);
		};

		$post_types =  ! empty( $this->request['post_type'] ) ? $this->request['post_type'] : 'post' ;
		$array_post_types = explode(",",$post_types);
		if(  ! empty( $array_post_types )  ){
			foreach ($array_post_types as $key => $value) {
				$res = $wpdb->get_results( "SELECT ID FROM {$wpdb->prefix}posts WHERE post_type = '$value'" );
				if( $wpdb->num_rows > 0 ){
					$total_posts  = $wpdb->num_rows;
					$total_views  = 0;
					$total_clicks = 0;
					$post_ids     = null;
					foreach ($res as $k => $v) {
						$post_ids[] = $v->ID;
					}
					$wpdb->query(
						"DELETE A
							FROM {$wpdb->prefix}yuzoviews A
							INNER JOIN {$wpdb->prefix}posts B
							ON A.post_id = B.ID
						WHERE B.post_type = '$value'"
					);

					foreach ($post_ids as $id) {
						$views = (int) self::numberToGenerate( 0, 0, (int)$this->request['from'], (int)$this->request['to'] );
						$total_views += $views;
						$sql_insert = "INSERT INTO {$wpdb->prefix}yuzoviews(
							ID,
							post_id,
							views,
							last_viewed,
							modified
						) VALUES (
							0,
							$id,
							$views,
							'". date('Y-m-d H:i:s') ."',
							". time() .")";
						$h = $wpdb->query( $sql_insert );

						// ─── Clicks ────────
						$max = (int) self::numberToGenerate( 0, 0, (int)$this->request['from2'], (int)$this->request['to2'] );
						$wpdb->query(
							"DELETE
								FROM {$wpdb->prefix}yuzoclicks
							WHERE post_id = $id"
						);
						foreach (range((int)$this->request['from2'], $max) as $something) {
							$total_clicks++;
							$date = $range_date($min_date,$max_date);
							$sql_insert = "INSERT INTO {$wpdb->prefix}yuzoclicks(
								ID,
								post_id,
								date_click,
								timestamp_click,
								ip,
								la,
								lo,
								country,
								country_code,
								region,
								city,
								device,
								url,
								where_is,
								browser_details,
								type_click,
								post_from,
								yuzo_list_id,
								price_per_click,
								level_click
							) VALUES (
								0,
								$id,
								'". $date ."',
								". strtotime($date) .",
								'". $this->LZFAKE->get_ip() ."',
								'',
								'',
								'". $this->randomElement( array_values($code_countries) ) ."',
								'". $this->randomElement( array_keys($code_countries) ) ."',
								'". $this->randomElement( $states ) ."',
								'". $this->randomElement( $city ) ."',
								'". $this->randomElement( $device ) ."',
								'',
								'single|singular',
								'',
								'". $this->randomElement( $type_click ) ."',
								". $this->randomElement( $post_ids ) .",
								". $this->randomElement( $yuzos_ids ) .",
								'". $this->randomElement( $price ) ."',
								1
							)";
							$y = $wpdb->query( $sql_insert );
						}
						$return[] = [ 'id' => $id, 'url' => admin_url('post.php?post='. $id .'&action=edit'), 'link' => get_permalink($id) ];
					}
				}
			}
		}

		// ─── Return a valid ID to know that I insert everything correctly ────────
		if( $h ){
			fdcHelper::update_option('fdc-setting',((int)fdcHelper::get_option('fdc-setting','yuzo_views')) + $total_views,'yuzo_views');
			fdcHelper::update_option('fdc-setting',((int)fdcHelper::get_option('fdc-setting','yuzo_clicks')) + $total_clicks,'yuzo_clicks');
			return [
				'total_clicks' => $total_clicks,
				'total_views'  => $total_views,
				'total_posts'  => $total_posts,
				'return'       => $return,
			];
		}

		return 0;

	}

	public static function yuzo_update_views_button(){
		if ( is_plugin_active( 'yuzo-related-post/yuzo.php' ) || is_plugin_active( 'yuzo/yuzo.php' ) ){
			echo '<div class="fdc-footer-bottom"><a data-nonce="';
			echo wp_create_nonce('fdc_nonce_yuzo');
			echo '" class="button button-primary fdc-button-yuzo">Generate</a></div>';
		}else{
			echo '
<div data-id="444442as1" class="pf-field pf-field-submessage">
<div class="pf-submessage pf-submessage-info">
<strong>Info:</strong><br>
To be able to generate Clicks and Views to the posts you must install and active Yuzo plugin.
<div class="clear"></div>
</div><br />';
			echo '<div class="fdc-footer-bottom"><a data-nonce="';
			echo '" class="button button-primary" target="_black" href="https://wordpress.org/plugins/yuzo-related-post/">Install Yuzo</a></div>';
			echo '</div>';
		}
	}
}
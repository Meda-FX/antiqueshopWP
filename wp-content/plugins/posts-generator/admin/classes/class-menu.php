<?php
/**
 * @since 		1.6     2019-11-24     Release
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
| Class responsible for generating random menus
|--------------------------------------------------------------------------
|
*/
class Fdc_Menu extends fdcBase{
    public
	/**
	 * Data passed by AJAX for manipulation
	 * @access  public
     * @since   1.6     2019-11-24      Release
     * @var     array
	 */
	$request = [];

	private
	/**
	 * Number of fake content to generate
	 * @access  public
     * @since   1.6     2019-11-24      Release
     * @var     int
	 */
	$number_to_generate = 10,
	/**
	 * Variable with the generator library
	 * @access	private
	 * @since 	1.6		2019-11-24		Release
	 * @var		LZFakeTextGenerator
	 */
    $LZFAKE,
    /**
	 * Contains all the parent ID menus that are generated
	 * @access	private
	 * @since 	1.6		2019-11-24		Release
	 * @var		LZFakeTextGenerator
	 */
	$parents = [],
	/**
	 * Terms already generated so as not to consult again
	 * @access	private
	 * @since 	1.6		2019-11-24		Release
	 */
    $terms_saved = [];

    /**
     * Class initializer
     *
     * @since   1.6     2019-11-24      Release
     * @param   string  $output
     */
    public function __construct( $data = [], $output = 'array' ){

        // ─── Instance Lib Fake ────────
        require_once fdc\PATH . 'include/libs/LZFakeTextGenerator.php';
        $this->LZFAKE = new \LZFakeTextGenerator;

		// ─── Set data request ────────
		$this->request = $data;
    }

    private function calculate_numbers(){
        $type_q    = $this->request['type'] ? 1 : 0;
        $num_exact = $this->request['exact'];
        $num_from  = $this->request['from'];
        $num_to    = $this->request['to'];
        return (int) $this->numberToGenerate( $type_q, $num_exact, $num_from, $num_to );
    }

    private function set_parent(){
        return empty( $this->parents ) ? null : self::randomElement( $this->parents, 60, [null] );
    }

    /**
	 * Get a slug location menu
	 *
	 * @since	1.6				2019-11-24	Release
	 *
	 * @param 	array|string	$role		Role/s to be used
	 * @return 	void
	 */
	private function get_location() {
		return empty($this->request['location']) ? null
				: ( isset($this->request['location'][0]) ? $this->request['location'][0] : null );
    }

    /**
     * Random Terms Generator
     *
     *
     * @since   1.6     2019-11-24      Release
     *
     * @param   int     $num            Number of terms to generate
     * @return  array
     */
    public function generate_term() {
        $num_words_by_term = rand( 1, 2);
        $term = [];
        foreach ( range(1,$num_words_by_term) as $something ) {
            $term[] = ucfirst($this->LZFAKE->get_term());
        }

        return implode( ' ', $term );
	}

	/**
	 * Get the menu link
	 *
	 * @since   1.6.79  2020-01-08      Link update for the generation
	 *
	 * @return  string
	 */
	private function get_link_menu(){

		return 'https://wordpress.org/plugins/yuzo-related-post/';

		// 1 = term, 2 = page, 3 = post
		$type = rand(1,1);
		if( $type == 1 ){
			$tax = self::randomElement( ['category','post_tag'] );
			if( empty( $this->terms_saved[$tax] ) ){
				$terms = get_terms([
					'taxonomy'   => $tax,
					'hide_empty' => false,
				]);
				// Sanitize id term
				$terms_ids = [];
				foreach ($terms as $value) {
					$terms_ids[] = $value->term_id;
					$this->terms_saved[$tax][] = $value->term_id;
				}
			} else { $terms_ids = $this->terms_saved[$tax]; }

			if( ! empty( $terms_ids ) ){
				$term_id = self::randomElement( $terms_ids );
				return admin_url('term.php?taxonomy='. $tax .'&tag_ID=' . $term_id);
			}
		}
	}

	/**
	 * Register fake Menus
	 *
	 * @since	1.6		2019-11-24		Release
	 *
	 * @return 	void
	 */
	public function composer_fake(){

        // ─── Assign the total to general globally for this class ────────
		$this->number_to_generate = (int) $this->numberToGenerate(
			$this->request['type'],
			$this->request['exact'],
			$this->request['from'],
			$this->request['to']
		);
		// ─── Sepup custom meta ────────
		//$custom_meta = fdcHelper::setup_custom_meta( $custom_meta , 'terms' );

		$i = 0;
		$return = [];
		if( ! empty( $this->number_to_generate ) ){

            // ─── Get Location ────────
			$location =  $this->get_location();
			if( ! $location ) return;

			// ─── Name and location of the menu ────────
			$menuname     = 'Post Generator - '.$location;
			$menulocation = $location;

			// ─── We remove the current menu from that location in case there is ────────
			wp_delete_nav_menu( $menuname );

			// ─── Create the new menu ────────
			$menu_id = wp_create_nav_menu($menuname);
			update_term_meta( $menu_id, '_menu_pg', 1 );

			// ─── Fetch single nivel ────────
			foreach (range(1, $this->number_to_generate) as $pos) {

				$term_id = wp_update_nav_menu_item($menu_id, 0, array(
					'menu-item-title'     => $pos == 1 ? $term_name = 'Home' : $term_name = $this->generate_term(),
					'menu-item-classes'   => \strtolower( \str_replace(" ", "-", $term_name )),
					'menu-item-url'       => $pos == 1 ? home_url( '/' ) : $this->get_link_menu(),
					'menu-item-status'    => 'publish',
				) );
				// A meta is added to know what was generated by this plugin
				add_post_meta($term_id, '_menu_item_pg', '1');

				$return['parent'][] = [ 'id' => $term_id ];
				$i++;

				if( $pos != 1 ){
					$this->parents[] = $term_id;

					// Check if you also have to create multi-level menu
					if( $this->request['level'] == 'multi' ){
						// Get submenus range that will generate
						if(  ! empty( $this->request['m_type'] )  ){
							$to   = (int)$this->request['m_from'];
							$from = (int)$this->request['m_to'];
						}else{
							$to   = 1;
							$from = (int)$this->request['m_exact'];
						}

						// Save the current parents generated from this menu
						$current_parent = [];
						$current_parent[] = $current_menu_parent = $term_id;

						// Fetch!
						foreach (range( $to, $from ) as $posi) {
							$term_id = wp_update_nav_menu_item($menu_id, 0, array(
								'menu-item-title'     => $term_name = $this->generate_term(),
								'menu-item-classes'   => \strtolower( \str_replace(" ", "-", $term_name )),
								'menu-item-url'       => $this->get_link_menu(),
								'menu-item-status'    => 'publish',
								'menu-item-parent-id' => self::randomElement( $current_parent, 65, [$current_menu_parent] ),
							));

							// A meta is added to know what was generated by this plugin
							add_post_meta($term_id, '_menu_item_pg', '1');

							$current_parent[] = $term_id;
							$return['child'][] = [ 'id' => $term_id, 'location' => $location ];
							$i++;
						}
					}
				}

			}

			// ─── Assign the menu in the selected position ────────
			if( $term_id ){ // && ! has_nav_menu( $menulocation ) ){
				$locations = get_theme_mod('nav_menu_locations');
				$locations[$menulocation] = $menu_id;
				set_theme_mod( 'nav_menu_locations', $locations );
			}

		}

		// ─── Return a valid ID to know that I insert everything correctly ────────
		if( $term_id ){
			fdcHelper::update_option('fdc-setting',((int)fdcHelper::get_option('fdc-setting','menus')) + $i,'menus');
			return $return;
		}

		return 0;
	}
}
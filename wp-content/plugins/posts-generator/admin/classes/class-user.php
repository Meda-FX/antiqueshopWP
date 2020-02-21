<?php
/**
 * @since 		1.0     2019-09-08     Release
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
| Class responsible for generating random users
|--------------------------------------------------------------------------
|
| PHP class capable of generating millions of random name combinations (first names and surnames)
| for use as demo data in applications and other projects.
| @see https://github.com/edwardr/php-random-name-generator
*/
class Fdc_User extends fdcBase{
    public
    /**
     * Format that are allowed for data conversion
     * @access  public
     * @since   1.0     2019-09-08      Release
     * @var     array
     */
    $allowedFormats = ['array', 'json', 'associative_array'],
    /**
     * Format that is required by default
     * @access  public
     * @since   1.0     2019-09-08      Release
     * @var     string
     */
	$inputFormat = 'json',
	/**
	 * Data passed by AJAX for manipulation
	 * @access  public
     * @since   1.0     2019-09-14      Release
     * @var     array
	 */
	$request = [];

	private
	/**
	 * Number of fake content to generate
	 * @access  public
     * @since   1.0     2019-09-14      Release
     * @var     int
	 */
	$number_to_generate = 10,
	/**
	 * Current name to manipulate
	 * @access  public
     * @since   1.0     2019-09-14      Release
     * @var     string
	 */
	$current_first_name = '',
	/**
	 * Current name to manipulate
	 * @access  public
     * @since   1.0     2019-09-14      Release
     * @var     string
	 */
	$current_last_name = '',
	/**
	 * Variable with the generator library
	 * @access	public
	 * @since 	1.0		2019-09-21		Release
	 * @var		LZFakeTextGenerator
	 */
	$LZFAKE;

    /**
     * Class initializer
     *
     * @since   1.0     2019-09-08      Release
     * @param   string  $output
     */
    public function __construct( $data = [], $output = 'array' ){
		// ─── Set data request ────────
		$this->request = $data;
        if ( !in_array( $output, $this->allowedFormats ) ) {
			throw new Exception('Unrecognized format');
		}
		$this->output = $output;
    }

    /**
     * Random Name Generator
     *
     * It can generate more than a million combinations
     *
     * @since   1.0     2019-09-08      Release
	 * @since   1.6.79  2020-01-08      Link update for the generation
     *
     * @param   int     $num            Number of names to generate
     * @return  array
     */
    public function generate_names( $num ) {

		if ( ! is_numeric( $num ) ) {
			throw new Exception('Not a number');
		}

		// Maximum number per request
		if( $num > 200 ){ $num = 200; }

		require_once fdc\PATH . 'include/libs/LZFakeTextGenerator.php';
		$this->LZFAKE = new \LZFakeTextGenerator;

		// ─── Custom settings ────────
		$this->LZFAKE->set_new_link_a('https://wordpress.org/plugins/yuzo-related-post/');

		$count = range(1, $num );
		$name_r = array();

		foreach( $count as $name ) {
			$count++;

			$first_name = $this->LZFAKE->get_name();
			$last_name  = $this->LZFAKE->get_lastname();

			if( $this->output == 'array' ) {
				$name_arr[] = $first_name . ' ' . $last_name;
			} elseif( $this->output == 'associative_array' || $this->output == 'json' ) {
				$name_arr[] = array( 'first_name' => $first_name, 'last_name' => $last_name );
			}
		}

		if( $this->output == 'json' ) {
			$name_arr = json_encode( $name_arr );
		}

		return $name_arr;
	}

	/**
	 * Generates a description for the user
	 *
	 * @since	1.0		2019-09-21		Release
	 *
	 * @param 	string 	$length			Text size (paragraph)
	 * @param 	array 	$tags			Tag inline allowed
	 * @return 	void
	 */
	public function generate_description(){
		$length          = $this->request['length_paragraph'];
		$num_exact       = $this->request['exact_paragraph'];
		$num_from        = $this->request['from_paragraph'];
		$num_to          = $this->request['to_paragraph'];
		$type_q          = $this->request['quantities_type_paragraph'];
		$tag_user_allow  = ['strong','code','em','a'];
		$tag_block_allow = ['h1','h2','h3','h4','h5','h6','h7'];
		$tag_selected    = ! empty( $this->request['tags'] ) && is_array( $this->request['tags'] ) ? $this->request['tags'] : [];
		$tags            = \array_intersect( $tag_user_allow, $tag_selected );
		$tags            = empty( $tags ) ? null : $tags;
		$tags_block      = $this->LZFAKE->array_rand_slice( \array_intersect( $tag_block_allow, $tag_selected ), 1 );
		$number          = (int) $this->numberToGenerate( $type_q, $num_exact, $num_from, $num_to );

		$str = [];
		foreach (range( 1, $number ) as $nothing) {
			$str[] =  $this->LZFAKE->set_tag_block( $this->LZFAKE->set_tag_inline( $this->LZFAKE->get_paragraph( $length, true ), $tags ), $tags_block );
		}

		return implode( '<br />', $str );
	}

	/**
	 * Get a randomly available role
	 *
	 * @since	1.0				2019-09-09	Release
	 *
	 * @param 	array|string	$role		Role/s to be used
	 * @return 	void
	 */
	private function role( $role = null ) {
		if ( is_null( $role ) ) {
			$role = array_keys( get_editable_roles() );
		}

		return self::randomElement( $role );
	}

	/**
	 * Gets first name
	 *
	 * @since	1.0		2019-10-10		Release
	 * @return 	string
	 */
	private function get_first_name(){
		return ucfirst( $this->current_first_name );
	}

	/**
	 * Gets last name
	 *
	 * @since	1.0		2019-10-10		Release
	 * @return 	string
	 */
	private function get_last_name(){
		return ucfirst( $this->current_last_name );
	}

	/**
	 * Gets user name
	 *
	 * @since	1.0		2019-10-10		Release
	 * @return 	string
	 */
	private function get_user(){
		return ucfirst( $this->get_first_name() ) . ( $this->get_last_name() );
	}

	/**
	 * Get email
	 *
	 * @since	1.0		2019-10-10		Release
	 * @return 	string
	 */
	private function get_email(){
		return strtolower( $this->get_first_name() . $this->get_last_name() ) . '@' . $this->LZFAKE->get_server_domain();
	}

	/**
	 * Get password
	 *
	 * @since	1.0		2019-10-10		Release
	 * @return 	string
	 */
	private function get_password(){
		return 'admin';
	}

	/**
	 * Gets name combined
	 *
	 * @since	1.0		2019-10-10		Release
	 * @return 	string
	 */
	private function get_nicename(){
		return ucfirst( $this->get_first_name() ) . " " . ucfirst( $this->get_last_name() );
	}

	/**
	 * Get a role
	 *
	 * @since	1.0		2019-10-10		Release
	 * @return 	string
	 */
	private function get_role(){
		// ─── Check if there are roles ────────
		return ! empty( $this->request['roles'] ) && is_array( $this->request['roles'] ) ? self::randomElement( $this->request['roles'] ) : 'administrator';
	}

	/**
	 * Save the names for manipulation
	 *
	 * @since	1.0		2019-09-21		Release
	 *
	 * @param 	string 	$names			names to manipulate
	 * @return 	void
	 */
	private function setup_names( $names = '' ){
		$data = explode(' ', $names );
		$this->current_first_name = isset( $data[0] ) ? strtolower($data[0]) : '';
		$this->current_last_name  = isset( $data[1] ) ? strtolower($data[1]) : '';
	}

	/**
	 * Register fake users
	 *
	 * @since	1.0		2019-10-08		Release
	 *
	 * @param 	integer $number			Number of records to be generated
	 * @param 	array 	$custom_meta	Additional goals to be added
	 * @return 	void
	 */
	public function composer_fake( $number = 10, $custom_meta = [] ){
		$user_id = null;

		// ─── Assign the total to general globally for this class ────────
		$this->number_to_generate = $number;

		// ─── Get random names ────────
		$array_names = $this->generate_names( $this->number_to_generate );

		// ─── Sepup custom meta ────────
		$custom_meta = fdcHelper::setup_custom_meta( $custom_meta , 'users' );

		$i = 0;
		$return = [];
		if( ! empty( $array_names ) ){

			foreach ($array_names as $value) {

				// Set current name
				$this->setup_names( $value );
				$i++;

				// Array of user data array
				$user_data_array = [
					'first_name'    => $this->get_first_name(),
					'last_name'     => $this->get_last_name(),
					'user_login'    => $this->get_user(),
					'user_email'    => $this->get_email(),
					'user_pass'     => $this->get_password(),
					'user_nicename' => $this->get_nicename(),
					'role'          => $this->get_role()
				];

				// Insert user
				$user_id = wp_insert_user( $user_data_array );

				// Add meta description (user biography)
				update_user_meta( $user_id, 'description', $this->generate_description() );
				// Add meta value that identifies that this record is created by Generate Content
				update_user_meta( $user_id, 'cg', 1 );

				// Custom meta
				fdcHelper::insert_custom_meta( $custom_meta , 'users', $user_id, $this->LZFAKE );

				// Output
				$return[] = [ 'id' => $user_id, 'url' => admin_url('user-edit.php?user_id=' . $user_id) ];
			}

		}

		// ─── Return a valid ID to know that I insert everything correctly ────────
		if( $user_id ){
			fdcHelper::update_option('fdc-setting',((int)fdcHelper::get_option('fdc-setting','users')) + $i,'users');
			return $return;
		}

		return 0;

	}
}
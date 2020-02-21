<?php
/**
 * @since 		1.0     2019-10-10     Release
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
| Class responsible for generating random terms
|--------------------------------------------------------------------------
|
*/
class Fdc_Term extends fdcBase{
    public
	/**
	 * Data passed by AJAX for manipulation
	 * @access  public
     * @since   1.0     2019-10-10      Release
     * @var     array
	 */
	$request = [];

	private
	/**
	 * Number of fake content to generate
	 * @access  public
     * @since   1.0     2019-10-10      Release
     * @var     int
	 */
	$number_to_generate = 10,
	/**
	 * Variable with the generator library
	 * @access	private
	 * @since 	1.0		2019-10-10		Release
	 * @var		LZFakeTextGenerator
	 */
    $LZFAKE,
    /**
	 * Contains all the parent ID terms that are generated
	 * @access	private
	 * @since 	1.0		2019-10-10		Release
	 * @var		LZFakeTextGenerator
	 */
	$parents = [],
	/**
	 * Save the ids of the hierarchical categories so as not to consult again
	 * this saves more resources and increases speed
	 *
	 * @access	private
	 * @since 	1.0		2019-10-14		Release
	 * @var		array
	 */
	$tax_already = [];

    /**
     * Class initializer
     *
     * @since   1.0     2019-10-10      Release
	 * @since   1.6.79  2020-01-08      Link update for the generation
	 *
     * @param   string  $output
	 * @param	array	$data
     */
    public function __construct( $data = [], $output = 'array' ){

        // ─── Instance Lib Fake ────────
        require_once fdc\PATH . 'include/libs/LZFakeTextGenerator.php';
		$this->LZFAKE = new \LZFakeTextGenerator;

		// ─── Custom settings ────────
		$this->LZFAKE->set_new_link_a('https://wordpress.org/plugins/yuzo-related-post/');

		// ─── Set data request ────────
		$this->request = $data;
    }

    /**
     * Random Terms Generator
     *
     *
     * @since   1.0     2019-10-10      Release
     *
     * @param   int     $num            Number of terms to generate
     * @return  array
     */
    public function generate_term() {

		$from_word = !empty( $this->request['from_words'] )  ? $this->request['from_words'] : 1;
        $to_word   = !empty( $this->request['to_words'] ) ?  $this->request['to_words'] : 2;

        $num_words_by_term = rand( (int)$from_word, $to_word);
        $term = [];
        foreach ( range(1,$num_words_by_term) as $something ) {
            $term[] = ucfirst($this->LZFAKE->get_term());
        }

        return implode( ' ', $term );
	}

	/**
	 * Generates a description for the term
	 *
	 * @since	1.0		2019-10-10		Release
	 *
	 * @param 	string 	$length			Text size (paragraph)
	 * @param 	array 	$tags			Tag inline allowed
	 * @return 	string
	 */
	public function generate_description(){
		$length          = $this->request['length_paragraph'];
		$num_exact       = $this->request['exact_paragraph'];
		$num_from        = $this->request['from_paragraph'];
		$num_to          = $this->request['to_paragraph'];
		$type_q          = $this->request['quantities_type_paragraph'];
		$tag_allow       = ['strong','code','em','a'];
		$tag_block_allow = ['h1','h2','h3','h4','h5','h6','h7'];
		$tag_selected    = ! empty( $this->request['tags'] ) && is_array( $this->request['tags'] ) ? $this->request['tags'] : [];
		$tags            = \array_intersect( $tag_allow, $tag_selected );
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
	 * @since	1.0				2019-10-10	Release
	 *
	 * @param 	array|string	$role		Role/s to be used
	 * @return 	void
	 */
	private function get_taxonomy() {
		return self::randomElement( array_filter((array)( $this->request['taxonomies']) ) );
    }

    private function set_parent( $array_terms = [] ){
		return ! empty( $array_terms ) ? ( $this->LZFAKE->frequency( 40 ) ? self::randomElement( $array_terms ) : 0 ) : 0;
    }

	/**
	 * Register fake Terms
	 *
	 * @since	1.0		2019-10-10		Release
	 * @since	1.5		2019-11-11		The public link of each generated term was added
	 *
	 * @param 	integer $number			Number of records to be generated
	 * @param 	array 	$custom_meta	Additional goals to be added
	 * @return 	void
	 */
	public function composer_fake( $number = 10, $custom_meta = [] ){

		// ─── Assign the total to general globally for this class ────────
		$to = $this->number_to_generate = $number;
		//$from = 

		// ─── Sepup custom meta ────────
		$custom_meta = fdcHelper::setup_custom_meta( $custom_meta , 'terms' );

		$i = 0;
		$return = [];
		if( ! empty( $this->number_to_generate ) ){

			foreach (range(1,$this->number_to_generate) as $something) {

                $i++;
				$parents = [];
				$term_args = [];

                // ─── Get Term ────────
                $term_current = $this->generate_term();

                // ─── Get Taxonomy random ────────
                $taxonomy =  $this->get_taxonomy();

				// ─── Insert term ────────
                $term_data = wp_insert_term( $term_current, $taxonomy );

                // ─── If there is an error when inserting then continue with the other term ────────
                if( ! is_array( $term_data ) ) continue;

                // ─── Get term id ────────
                $term_id = $term_data['term_id'];

                // ─── Verify if the taxonomy is hierarchical or not ────────
                if( is_taxonomy_hierarchical( $taxonomy ) ){

					if( empty( $this->parents )  ){
						// Get all term of taxonomy
						$terms = get_terms([
							'taxonomy'   => $taxonomy,
							'hide_empty' => false,
						]);
						// Sanitize id term
						$terms_ids = [];
						foreach ($terms as $value) {
							$terms_ids[] = $value->term_id;
						}
						$this->parents = $terms_ids;
					}

                    // Get a parent term
					$parent = $this->set_parent( $this->parents );

                    // So keep the parents for future children in the next loop
                    $this->parents[] = $term_id;

                    // If the parent ID is different from 0 then this will be a child term
                    if( $parent != 0 ){
						$term_args['parent'] = $parent;
                    }

				}

				// ─── Get description ────────
				$description = $this->generate_description();
				$term_args['description'] = $description;
				// ─── Update Meta ────────
				wp_update_term( $term_id, $taxonomy, $term_args );

				// ─── Add meta value that identifies that this record is created by Generate Content ────────
				update_term_meta( $term_id, 'cg', 1 );

				// Custom meta
				fdcHelper::insert_custom_meta( $custom_meta , 'terms', $term_id, $this->LZFAKE );

				// Output
				$return[] = [ 'id' => $term_id, 'url' => admin_url('term.php?taxonomy='. $taxonomy .'&tag_ID=' . $term_id), 'link' => get_term_link($term_id) ];

			}

		}

		// ─── Return a valid ID to know that I insert everything correctly ────────
		if( $term_id ){
			fdcHelper::update_option('fdc-setting',((int)fdcHelper::get_option('fdc-setting','terms')) + $i,'terms');
			return $return;
		}

		return 0;

	}
}
<?php
/**
 * @since 		1.0     2019-10-11     Release
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
| Class responsible for generating random post
|--------------------------------------------------------------------------
|
*/
class Fdc_Post extends fdcBase{
    public
	/**
	 * Data passed by AJAX for manipulation
	 * @access  public
     * @since   1.0     2019-10-11      Release
     * @var     array
	 */
	$request = [];

	private
	/**
	 * Number of fake content to generate
	 * @access  public
     * @since   1.0     2019-10-11      Release
     * @var     int
	 */
	$number_to_generate = 10,
	/**
	 * Variable with the generator library
	 * @access	private
	 * @since 	1.0		2019-10-11		Release
	 * @var		LZFakeTextGenerator
	 */
    $LZFAKE,
    /**
	 * Save the ids of the hierarchical categories so as not to consult again
	 * this saves more resources and increases speed
	 *
	 * @access	private
	 * @since 	1.0		2019-10-14		Release
	 * @var		array
	 */
	$tax_already = [],
	/**
	 * Save all taxonomies of each post type that has been selected
	 *
	 * @access	private
	 * @since 	1.2		2019-11-01		Release
	 * @var		array
	 */
	$tax_slug_already = [],
	/**
	 * Current post id that is running
	 *
	 * @access	private
	 * @since	1.0		2019-10-17		Release
	 * @var 	int
	 */
	$current_post_id = 0,
	/**
	 * Current post title. Serive for several purposes
	 *
	 * @access	private
	 * @since	1.0		2019-10-17		Release
	 * @var		string
	 */
	$current_post_title = '',
	/**
	 * Get the current source
	 *
	 * @access	private
	 * @since	1.0		2019-10-17		Release
	 * @var		string
	 */
	$current_source = '',
	/**
	 * All post fields that are currently running
	 *
	 * @access	private
	 * @since	1.0		2019-10-17		Release
	 * @var		array
	 */
	$current_post_args = [],
	/**
	 * Count the number of image entered in a post
	 * this serves to have a record of the url, width, height
	 *
	 * @access	private
	 * @since	1.0		2019-10-17		Release
	 * @var		int
	 */
	$post_image_index  = 0,
	/**
	 * Registers the images that are entered in a current post.
	 *
	 * @access	private
	 * @since	1.0		2019-10-17		Release
	 * @var		array
	 */
	$array_post_image = [];

    /**
     * Class initializer
     *
     * @since   1.0     2019-10-11      Release
	 * @since	1.6.1	2019-11-26		Increased capacity for the execution of posts
	 * @since   1.6.79  2020-01-08      Link update for the generation
	 *
     * @param   string  $output
     */
    public function __construct( $data = [], $output = 'array' ){

        // ─── Instance Lib Fake ────────
        require_once fdc\PATH . 'include/libs/LZFakeTextGenerator.php';
		$this->LZFAKE = new \LZFakeTextGenerator;

		// ─── Custom settings ────────
		$this->LZFAKE->set_new_link_a('https://wordpress.org/plugins/yuzo-related-post/');

		// ─── Set data request ────────
		$this->request = $data;

		// ─── Increase memory capacity temporarily ────────
		ini_set('memory_limit', '128M');
		ini_set('post_max_size', '24M');
		ini_set('memory_limit', '128M');
		ini_set('max_execution_time', 0);

    }

    /**
     * Random Title Generator
     *
     * @since   1.0     2019-10-11      Release
	 * @since	1.5		2019-11-11		Now calculate the range of words for the title as well as its maximum size in letters
     *
     * @param   int     $num            Number of terms to generate
     * @return  array
     */
    public function generate_title() {
		$min = (int)$this->request['title_from'];
		$max = (int)$this->request['title_to'];
		$pretitle = ucfirst( $this->LZFAKE->get_sentence($min,$max) );
		$title =  ! empty( $this->request['title_length'] ) ? substr( $pretitle, 0, (int)$this->request['title_length'] ) : $pretitle;
        return $title;
	}

	/**
	 * Generate the post status
	 *
	 * @since	1.0		2019-10-13		Release
	 * @return 	string
	 */
	private function generate_status(){
		return  ! empty( $this->request['post_status'] )
				? self::randomElement( array_filter( explode( ",", $this->request['post_status'] ) ), 80, ['publish'] )
				: 'publish';
	}

	/**
	 * Generate the comment status
	 *
	 * @since	1.0		2019-10-13		Release
	 * @return 	string
	 */
	private function generate_comment_status(){
		return  ! empty( $this->request['comment_status'] )
				? self::randomElement( array_filter( explode( ",", $this->request['comment_status'] ) ), 80, ['open'] )
				: 'open';
	}

	/**
	 * Generate the author of the post
	 *
	 * @since	1.0		2019-10-13		Release
	 * @return 	string
	 */
	private function generate_author(){
		return 	! empty( $this->request['users'] )
				? self::randomElement( array_filter( explode( ",", $this->request['users'] ) ) )
				: ( is_array( $array_users = get_users(['fields' => 'ID']) ) ? self::randomElement( $array_users ) : 1 ) ;
	}

	/**
	 * Generate the post type
	 *
	 * @since	1.0		2019-10-13		Release
	 * @return 	string
	 */
	private function generate_post_type(){
		return 	! empty( $this->request['post_type'] )
				? self::randomElement(
					array_filter(
						explode( ",", $this->request['post_type'] )
					), 70, \array_intersect(
						['post'], explode( ",", $this->request['post_type'] )
					)
				)
				: 'post';
	}

	/**
	 * Generate a date to the post
	 *
	 * @since	1.0		2019-10-13		Release
	 * @return 	string
	 */
	private function generate_date(){
		// Convert to timetamps
		// Generate random number using above bounds
		// Convert back to desired date format
		return 	date('Y-m-d H:i:s',
				mt_rand(
					strtotime( $this->request['date_from'] ),
					strtotime( $this->request['date_to'] ) ) );
	}

	/**
	 * Generate the categories to be inserted
	 *
	 * @since	1.0		2019-10-13		Release
	 * @since	1.2		2019-11-01		Supports Custom post type
	 * @since	1.4		2019-11-07		Now you can generate taxonomies for CPT
	 *
	 * @return 	array
	 */
	private function generate_category2(){
		$all_ids = [];

		// ─── Check if it is activated to generate this taxonomy ────────
		if( ! ( ! empty( $this->request['tax_type'] ) && in_array( (int)$this->request['tax_type'] , [1,2] ) ) ) return $all_ids;

		$data    = ! empty( $this->request['tax_cate'] ) ? $this->request['tax_cate'] : '';
		$array   = $this->fusion_array_under_the_same_key( $data, ',', '|' );

		// ─── Get all taxonomies of the selected posts type ────────
		$this->tax_slug_already = empty( $this->tax_slug_already ) ? $this->get_all_tax_from_cpt( array_filter( explode(",", $this->request['post_type']) ) ) : $this->tax_slug_already;
		$tax_hierarchical = $this->tax_slug_already['hierarchical'];

		if(  ! empty( $tax_hierarchical )  ){
			foreach ($tax_hierarchical as $key => $value) {
				$term_ids = [];
				if( in_array( 'all', $array[$value] ) ){
					if( empty( $this->tax_already[$value] )  ){
						$terms = get_terms([
							'taxonomy'   => $value,
							'hide_empty' => false,
						]);
						if(  ! empty( $terms )  ){
							foreach ($terms as $k => $v) {
								$term_ids[] = $v->term_id;
								$this->tax_already[$key][] = $v->term_id;
							}
						}
					}else{
						$term_ids = $this->tax_already[$value];
					}
				}else{
					$term_ids = $array[$value];
				}

				$all_ids[$value] = $term_ids;
			}
		}

		return $all_ids;
	}

	/**
	 * Generate the tags to be inserted
	 *
	 * @since	1.0		2019-10-13		Release
	 * @since	1.4		2019-11-07		Now you can generate taxonomies for CPT
	 *
	 * @return 	array
	 */
	private function generate_tags2(){
		$all_ids = [];

		// ─── Get the tax number to get ────────
		$num_terms = rand( (int)$this->request['tax_from'], (int)$this->request['tax_to'] );

		// ─── Check if it is activated to generate this taxonomy ────────
		if( ! ( ! empty( $this->request['tax_type'] ) && in_array( (int)$this->request['tax_type'] , [1,3] ) ) ) return $all_ids;

		$data    = ! empty( $this->request['tax_tag'] ) ? $this->request['tax_tag'] : '';
		$array   = ! empty( $data ) ? $this->fusion_array_under_the_same_key( $data, ',', '|' ) : [];

		// ─── Get all taxonomies of the selected posts type ────────
		$this->tax_slug_already = empty( $this->tax_slug_already ) ? $this->get_all_tax_from_cpt( array_filter( explode(",", $this->request['post_type']) ) ) : $this->tax_slug_already;
		$tax_no_hierarchical    = $this->tax_slug_already['no_hierarchical'];

		if( empty( array_filter(explode(",",$data)) ) ){ // empty = all

			if( ! empty( $tax_no_hierarchical ) ){
				foreach ($tax_no_hierarchical as $key => $value) {
					$term_ids = [];
					if( empty( $this->tax_already[$value] )  ){
						$terms = get_terms([
							'taxonomy'   => $value,
							'hide_empty' => false,
						]);
						if(  ! empty( $terms )  ){
							foreach ($terms as $k => $v) {
								$term_ids[] = $v->term_id;
								$this->tax_already[$value][] = $v->term_id;
							}
						}
					}else{
						$term_ids = $this->tax_already[$value];
					}

					$all_ids[$value] = $term_ids;
				}
			}

		}else{
			if( ! empty( $array ) ){
				foreach( $array as $key => $value ){
					$all_ids[$key] = $value;
				}
			}
		}

		return $all_ids;
	}

	private function insert_taxonomies_post(){

		// ─── Hierarchical Taxonomy ────────
		foreach ($this->generate_category2() as $key => $value) {
			$num_terms = rand( (int)$this->request['tax_from'], (int)$this->request['tax_to'] );
			wp_set_post_terms( $this->current_post_id, self::randomElements( $value, $num_terms ) , $key);
		}

		// ─── No Hierarchical Taxonomy ────────
		foreach ($this->generate_tags2() as $key => $value) {
			$num_terms = rand( (int)$this->request['tax_from'], (int)$this->request['tax_to'] );
			wp_set_post_terms( $this->current_post_id, self::randomElements( $value, $num_terms ) , $key);
		}

	}

	/**
	 * Generates a description for the post
	 *
	 * @since	1.0		2019-10-11		Release
	 *
	 * @param 	string 	$length			Text size (paragraph)
	 * @param 	array 	$tags			Tag inline allowed
	 * @return 	string
	 */
	public function generate_content(){

		$type_q    = $this->request['content_type'];
		$num_exact = $this->request['content_exact'];
		$num_from  = $this->request['content_from'];
		$num_to    = $this->request['content_to'];
		$length    = $this->request['content_length'];

		$tag_inline_support = $this->LZFAKE->all_tag_inline_supported;
		$tag_block_support  = $this->LZFAKE->all_tag_block_supported;
		unset($tag_block_support['img']);

		$tag_selected = explode( ',', ! empty( $this->request['content_tag'] ) ? $this->request['content_tag'] : '');
		$tag_block_selected = explode( ',', ( ! empty( $this->request['content_tag_block'] ) ? $this->request['content_tag_block'] : '') );

		$tags_inline  = \array_intersect( $tag_inline_support, $tag_selected );
		$tags_inline  = empty( $tags_inline ) ? [] : $tags_inline;

		$tags_block = \array_intersect( $tag_block_support, $tag_block_selected );
		$tags_block = empty( $tags_block ) ? [] : $tags_block;
		$number     = (int) $this->numberToGenerate( $type_q, $num_exact, $num_from, $num_to );

		// ─── Generate all paragraphs in array ────────
		$str = [];
		foreach (range( 1, $number ) as $nothing) {
			$str[] =  $this->LZFAKE->set_tag_inline( $this->LZFAKE->get_paragraph( $length, true ), $tags_inline );
		}

		// ─── All the text in array we put it in paragraphs in a single string ────────
		$text_content = '<p>'. implode( '</p><p>', $str ) . '</p>';

		return $this->LZFAKE->set_tag_block( $text_content, $tags_block );
	}

	/**
	 * Get a randomly available role
	 *
	 * @since	1.0				2019-10-11	Release
	 *
	 * @param 	array|string	$role		Role/s to be used
	 * @return 	void
	 */
	private function get_taxonomy() {
		return self::randomElement( array_filter((array)( $this->request['taxonomies']) ) );
    }

	/**
	 * Get all the taxonomies of the Custom post Type that have been selected
	 *
	 * @since	1.2		2019-11-02		Release
	 *
	 * @param 	array 	$cpt			Custom post type selected
	 * @return 	array
	 */
	private function get_all_tax_from_cpt( array $cpt ){

		$taxonomies_array = [ 'hierarchical' => [], 'no_hierarchical' => [] ];
		if( ! empty( $cpt ) ){
			foreach ($cpt as $value) {
				// Get all taxonomy from CPT include builtin tax
				$taxonomies = get_object_taxonomies( $value, 'objects' );
				foreach ( $taxonomies  as $taxonomy ){
					if ( is_taxonomy_hierarchical( $taxonomy->name ) ){
						$taxonomies_array['hierarchical'][] = $taxonomy->name;
					}else{
						$taxonomies_array['no_hierarchical'][] = $taxonomy->name;
					}
				}
			}
		}

		return $taxonomies_array;

	}

	/**
	 * create Array associative array that has the same key
	 *
	 * @param 	string 	$string				Main character string
	 * @param 	string 	$primary_separate	Main separator
	 * @param 	string 	$second_separate	Secondary separator
	 * @return 	array
	 */
	private function fusion_array_under_the_same_key( $string, $primary_separate, $second_separate ){

		$first_array  = null;
		$second_array = null;
		$tree_array   = array();

		if( ! $string ){ return; }
		$first_array  = explode($primary_separate,$string);
		if( is_array($first_array) && $first_array){
			$k = 1;
			foreach ($first_array as $first_array_key => $first_array_value) {
				if( $first_array_value ){
					$second_array = explode($second_separate,$first_array_value);
					$k_string = str_pad("$k", 3, "0", STR_PAD_LEFT);
					$_key = isset($second_array[1]) ? $second_array[1] : null;
					if( $_key == null){ continue; }
					$_key = "{$k}_string-" . $_key;
					$tree_array[$_key] = isset($second_array[0])?$second_array[0]:null;
					$k++;
				}
			}
		}

		$AssocSAPerDomain = array();
		$TempDomain       = "";
		$TempDomain_first = 0;
		if( is_array($tree_array) ){
			foreach($tree_array as $id_domain => $id_sa){
				if( !$TempDomain && $TempDomain_first == 0 ){  $TempDomain = substr(strrchr($id_domain, "-"), 1); $TempDomain_first = 1; }
				$currentDomain = substr(strrchr($id_domain, "-"), 1);
				$AssocSAPerDomain[$currentDomain][] = $id_sa;
				$TempDomain = substr(strrchr($id_domain, "-"), 1);
			}
		}
		return $AssocSAPerDomain;
	}

	private function fdc_sanitize_file_name( $name = '' ){
		return strlen($name) > 40 ? strtolower(substr($name,0,40)) . rand(1,100) : strtolower($name) . rand(1,100);
	}

	/**
	 * Save an image with a sanitized file name
	 *
	 * @since	1.6.1	2019-11-26		Update doc
	 * @param 	string 	$file
	 * @param 	int 	$post_id
	 * @param 	string 	$desc
	 * @param 	string 	$file_name
	 * @return 	int
	 */
	private function media_sideload_image_custom($file, $post_id, $desc = null, $file_name = null){
		if ( ! empty($file) ) {
			// Download file to temp location
			$tmp = download_url( $file );

			$file_array['name']     = sanitize_file_name($this->fdc_sanitize_file_name($file_name)) . '.jpg';
			$file_array['tmp_name'] = $tmp;

			// If error storing temporarily, unlink
			if ( is_wp_error( $tmp ) ) {
				@unlink($file_array['tmp_name']);
				$file_array['tmp_name'] = '';
			}

			// do the validation and storage stuff
			$id = @media_handle_sideload( $file_array, $post_id, $desc );

			// If error storing permanently, unlink
			if ( is_wp_error($id) ) {
				@unlink( $file_array['tmp_name'] );
			}
			return $id;
		}
		return null;
	}

	private function get_source( $type = 1 ){
		$s = $type == 1 ? array_filter( explode(',',  ! empty( $this->request['image_featured_source'] ) ? $this->request['image_featured_source'] : []  ) )
						: array_filter( explode(',',  ! empty( $this->request['image_post_source'] ) ? $this->request['image_post_source'] : []  ) );

		return ! empty( $s ) 	? self::randomElement( $s, 70, ['unsplash'] )
								: 'unsplash';
	}

	private function get_url_image( $type = 1, $source = null ){
		$this->post_image_index++;
		if( $type == 1 ){
			$width  = (int)$this->request['image_featured_width'] != 0 ? (int)$this->request['image_featured_width'] : rand(1000,1220);
			$height = (int)$this->request['image_featured_height'] != 0 ? (int)$this->request['image_featured_height'] : rand(640,700);
		}elseif( $type == 2 ){
			if( $source == 'ipsumimage' ){

			}else{
				$width  = (int)$this->request['image_post_width'] != 0 ? (int)$this->request['image_post_width'] : rand(1000,1220);
				$height = (int)$this->request['image_post_height'] != 0 ? (int)$this->request['image_post_height'] : rand(640,700);
			}
			// ─── These sieves are saved to be able to have them in an index ────────
			$this->array_post_image[ $this->post_image_index ]['width'] = $width;
			$this->array_post_image[ $this->post_image_index ]['height'] = $height;
		}

		$source = ! $source ? $this->current_source = $this->get_source( $type ) : $source;
		switch ( $source ) {
			case 'unsplash':
				return "https://source.unsplash.com/random/{$width}x{$height}/";
				break;
			case 'picsum':
				return "https://picsum.photos/{$width}/{$height}/";
			case 'ipsumimage':
				return "https://ipsumimage.appspot.com/{$width}x{$height},3B5BDB?l=Fake+Ultimate&f=fff";
				break;
			default:
				return "https://source.unsplash.com/random/{$width}x{$height}/";
				break;
		}
	}

	private function generate_featured_image(){
		$id_attach = self::media_sideload_image_custom( self::get_url_image(), $this->current_post_id, null, $this->current_source . '-'. $this->current_post_title );
		if( $id_attach ){
			// update_post_meta( $this->current_post_id, '_thumbnail_id', $id_attach );
			set_post_thumbnail( $this->current_post_id, $id_attach);
		}
	}

	private function generate_post_imagen(){
		$num_images = rand( (int)$this->request['image_post_from'], (int)$this->request['image_post_to'] );
		$num_images = !$num_images ? 1 : $num_images;

		foreach (range(1,$num_images) as $something) {
			// Generate normal images
			$id_attach = self::media_sideload_image_custom( self::get_url_image(2), $this->current_post_id, null, $this->current_source . '-'. $this->current_post_title );
			if( $id_attach ){
				$this->array_post_image[ $this->post_image_index ]['src'] = wp_get_attachment_url( $id_attach );
				$this->array_post_image[ $this->post_image_index ]['attach_id'] = $id_attach;
			}
		}

	}

	private function insert_images_in_content( $content ){
		if( ! in_array( $this->request['imagen_type'], [1,3,5] ) ) return $content;
		if( $this->post_image_index == 0 ) return $content;
		$i = 1;
		foreach ($this->array_post_image as $value) {
			// ─── Add configuration image ────────
			$this->LZFAKE->set_attr_image([
				'alt'    => $this->current_post_title . '-' . $i,
				'width'  => $value['width'],
				'height' => $value['height'],
				'class'  => 'wp-image-' . $value['attach_id'],
				'src'    => $value['src'],
			]);
			$content = $this->LZFAKE->set_imagen_in_content(
				'block',
				$content
			);
			$i++;
		}

		return $content;
	}

	private function insert_irregular_images_in_content( $content ){
		if( ! in_array( $this->request['imagen_type'], [1,3,5] ) ) return $content;
		$banners_ids = explode(',',$this->request['image_post_banner']);
		if( empty( $banners_ids )  ) return $content;

		$i = 1;
		$banners_ids = self::randomElements($banners_ids,rand(1,count($banners_ids)));
		foreach ($banners_ids as $value) {
			// ─── Add configuration image ────────
			// $banner_id = array_rand($banners_ids,1);
			$this->LZFAKE->set_attr_image([
				'alt'    => $this->current_post_title . '-' . $i,
				/* 'width'  => $value['width'],
				'height' => $value['height'], */
				'class'  => 'wp-image-banner ' . $value . ' ' . self::getClassIrregularImage( $value ),
				'src'    => self::getIrregularImage( $value ),
			]);
			$content = $this->LZFAKE->set_imagen_in_content( 'inline', $content );
			$i++;
		}

		return $content;
	}

	private function getIrregularImage( $id ){
		if( $id == 'banner-square' ){
			return fdc\URL . 'public/assets/images/banner-350.png';
		}elseif( $id == 'banner-vertical' ){
			return fdc\URL . 'public/assets/images/banner-185x500.png';
		}elseif( $id == 'banner-horizontal' ){
			return fdc\URL . 'public/assets/images/banner-500x154.png';
		}
	}

	private function getClassIrregularImage( $id ){
		if( $id == 'banner-square' || $id == 'banner-vertical' ){
			return $this->LZFAKE->get_align_image();
		}elseif( $id == 'banner-horizontal' ){
			return $this->LZFAKE->get_align_image_center();
		}
	}

	/**
	 * Select what type of image you will generate
	 *
	 * @since	1.6.78	2019-12-23	Doc Update
	 * @return 	void
	 */
	private function generate_image(){
		//if ( ! defined('ALLOW_UNFILTERED_UPLOADS') ) { define('ALLOW_UNFILTERED_UPLOADS', true); }

		// ─── Validate what type of images and where it will be generated ────────
		if( $this->request['imagen_type'] == 2 ){
			$this->generate_featured_image();
		}elseif( $this->request['imagen_type'] == 3){
			$this->generate_post_imagen();
		}elseif( $this->request['imagen_type'] == 1 ){
			$this->generate_featured_image();
			$this->generate_post_imagen();
		}elseif( $this->request['imagen_type'] == 5 ){
			return $this->LZFAKE->frequency() ? $this->generate_featured_image() : $this->generate_post_imagen();
		}elseif( $this->request['imagen_type'] == 4 ){
			null;
		}

	}

	/**
	 * Insert social link into content
	 *
	 * @since	1.5		2019-11-11		Release
	 *
	 * @param 	string 	$content		Content where links will be inserted
	 * @param 	integer $number			Social link number to be inserted into a post
	 * @return 	string
	 */
	private function generate_content_social( $content, $number = 1 ){

		// ─── If there is social content selected ────────
		if( empty( $this->request['content_social'] )  ) return $content;

		$social_avalible = [
			'yt' => [
				'https://www.youtube.com/watch?v=gf8nOnqEkWg',
				'https://www.youtube.com/watch?v=EzKkl64rRbM',
				'https://www.youtube.com/watch?v=q0hyYWKXF0Q',
			],
			'fb' => [
				'https://www.facebook.com/FacebookGaming/photos/a.142423742454918/2752746048089328',
				'https://www.facebook.com/boredpanda/posts/10158296024794252',
				'https://www.facebook.com/BoredPandaArt/videos/705681839915286/',
			],
			'tw' => [
				'https://twitter.com/elonmusk/status/1192113294036754432',
				'https://twitter.com/elonmusk/status/1189342895142309888',
				'https://twitter.com/NASA/status/1192939897532944384',
			],
			'in' => [
				'https://www.instagram.com/p/B3-LCqmF2Ee/',
				'https://www.instagram.com/p/B4TCgIepWaP/',
				'https://www.instagram.com/p/B1KW0XSB839/',
			],
			'sc' => [
				'https://soundcloud.com/hardphol/tones-and-i-dance-monkey-vadim-adamov-hardphol-remix-radio-edit',
				'https://soundcloud.com/tiesto/knock-you-out-ti-sto-ft-emily',
				'https://soundcloud.com/erloonsouza/she-wolf-falling-to-pieces',
			],
		];

		foreach (range( 1, $number ) as $something) {
			$social_index = self::randomElement( array_filter( explode( ",", $this->request['content_social'] ) ) );
			$text_source  = wp_oembed_get(self::randomElement( $social_avalible[ $social_index ] ));
			$content      = $this->LZFAKE->set_insert_content( $content, $text_source );
		}


		return $content;
	}

	/**
	 * Register fake posts
	 *
	 * @since	1.0		2019-10-11		Release
	 * @since	1.2		2019-11-02		New alternative functions added (generate_category2 and generate_tag2)
	 * @since	1.5		2019-11-11		The public link of each generated post was added
	 * @since	1.6.1	2019-11-26		Validation that does not exceed 15 post generated per shipment, this will avoid overloading
	 *
	 * @param 	integer $number			Number of records to be generated
	 * @param 	array 	$custom_meta	Additional goals to be added
	 * @return 	void
	 */
	public function composer_fake( $number = 10, $custom_meta = [] ){
		$user_id = null;

		// ─── Assign the total to general globally for this class ────────
		$this->number_to_generate = $number;
		/**
		 * Ensures that it will not generate more of this value, why? the problem comes that the more post with images this consumes many internal resources
		 * @since 1.6.1	2019-11-26
		 */
		$this->number_to_generate = $this->number_to_generate > 15 ? 15 : $this->number_to_generate;

		// ─── Sepup custom meta ────────
		$custom_meta = fdcHelper::setup_custom_meta( $custom_meta , 'posts' );

		$i = 0;
		$return = [];
		if( ! empty( $this->number_to_generate ) ){

			foreach (range(1,$this->number_to_generate) as $something) {

                $i++;

                // // Title ────────
				$title = $this->generate_title();

				// // Status ────────
				$status = $this->generate_status();

				// // Author ────────
				$author = $this->generate_author();

				// // Post type ────────
				$post_type = $this->generate_post_type();

				// // Post date ────────
				$post_date = $this->generate_date();

				// Post comment status
				$comment_status = $this->generate_comment_status();

				// // Post content ────────
				// Configure the frequency that the inline tag will appear in a paragraph ────────
				$this->LZFAKE->set_frec_taginl_paragraph( 80 );

				// Configure the frequency that the inline tag will appear in a sentence after accepting that ────────
				// show in that paragraph.
				$this->LZFAKE->set_frec_taginl_sentence( 70 );

				// Set the frequency of each selected block tag within this text ────────
				$this->LZFAKE->set_frec_tagblock( 80 );

				// Configure the blocks that will be repeated to have more possibilities to be in content
				$this->LZFAKE->set_array_tags_repeat( [
					'h2' => 4,
					'h3' => 2,
					'h4' => 1
				] );
				$content = $this->generate_content();

                $new_post = array(
                    'post_title'    => $title,
                    'post_content'  => '',
                    'post_status'   => $status,
                    'post_date'     => $post_date,
                    'post_author'   => $author,
					'post_type'     => $post_type,
					'comment_status'=> $comment_status,
                    /* 'post_category' => $categories,
                    'tags_input'    => $tags, */
				);

				$this->current_post_args  = $new_post;
				$this->current_post_id    = wp_insert_post( $new_post );
				$this->current_post_title = $title;

				// Insert terms in the taxomonies
				$this->insert_taxonomies_post();

				// Generate images to be inserted
				self::generate_image();

				$this->LZFAKE->set_align_imagen('aligncenter','center');
				$this->LZFAKE->set_align_imagen('alignleft','left');
				$this->LZFAKE->set_align_imagen('alignright','right');

				$content = self::insert_images_in_content( $content );
				$content = self::insert_irregular_images_in_content( $content );
				$content = self::generate_content_social( $content, rand(1,2) );

				// ─── Add readmore ────────
				// this usually goes after the first paragraph
				// OPTIMIZE: This is done for each iteration, you could let it only be done once 
				$tag_block_selected = explode( ',', ( ! empty( $this->request['content_tag_block'] ) ? $this->request['content_tag_block'] : '') );
				if( in_array( 'read-more' ,$tag_block_selected ) ){
					$content = $this->LZFAKE->set_insert_content( $content, '<!--more-->', 1 );
				}
				// ─── Add next page ────────
				if( in_array( 'next-page' ,$tag_block_selected ) ){
					$arr_paragraph = $this->LZFAKE->get_all_array_paragraph( $content );
					$max_paragraph = count( $arr_paragraph ) ? count( $arr_paragraph ) : 1;
					foreach(range(1,rand(1,2)) as $something){
						$content = $this->LZFAKE->set_insert_content( $content, '<!--nextpage-->', rand(2, $max_paragraph - 1) );
					}
				}

				wp_update_post( [
					'ID'           => $this->current_post_id,
					'post_content' => $content,
				] );

				// ─── Add meta value that identifies that this record is created by Generate Content ────────
				update_post_meta( $this->current_post_id, 'cg', 1 );

				// Custom meta
				fdcHelper::insert_custom_meta( $custom_meta , 'posts', $this->current_post_id, $this->LZFAKE );

				// Output
				$return[] = [ 'id' => $this->current_post_id, 'url' => admin_url('post.php?post='. $this->current_post_id .'&action=edit'), 'link' => get_permalink($this->current_post_id) ];

				// ─── Image data variables are reset ────────
				$this->array_post_image = [];
				$this->post_image_index = 0;
			}

		}

		// ─── Return a valid ID to know that I insert everything correctly ────────
		if( $this->current_post_id ){
			fdcHelper::update_option('fdc-setting',((int)fdcHelper::get_option('fdc-setting','posts')) + $i,'posts');
			return $return;
		}

		return 0;

	}
}
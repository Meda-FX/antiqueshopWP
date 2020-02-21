<?php
/**
 * @since 		1.0     2019-10-20     Release
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
| Class responsible for generating random comments
|--------------------------------------------------------------------------
|
*/
class Fdc_Comment extends fdcBase{
    public
	/**
	 * Data passed by AJAX for manipulation
	 * @access  public
     * @since   1.0     2019-10-20      Release
     * @var     array
	 */
	$request = [];

	private
	/**
	 * Number of fake content to generate
	 * @access  public
     * @since   1.0     2019-10-20      Release
     * @var     int
	 */
	$number_to_generate = 10,
	/**
	 * Variable with the generator library
	 * @access	private
	 * @since 	1.0		2019-10-20		Release
	 * @var		LZFakeTextGenerator
	 */
    $LZFAKE,
    /**
	 * Contains all the parent ID comments that are generated
	 * @access	private
	 * @since 	1.0		2019-10-20		Release
	 * @var		LZFakeTextGenerator
	 */
    $parents = [];

    /**
     * Class initializer
     *
     * @since   1.0     2019-10-20      Release
     * @since   1.6.79  2020-01-08      Link update for the generation
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
    }

    /**
     * Random Comments Generator
     *
     *
     * @since   1.0     2019-10-20      Release
     *
     * @param   int     $num            Number of comments to generate
     * @return  array
     */
    public function generate_author() {

		$from_word = !empty( $this->request['from_words'] )  ? $this->request['from_words'] : 1;
        $to_word   = !empty( $this->request['to_words'] ) ?  $this->request['to_words'] : 2;

        $num_words_by_comment = rand( (int)$from_word, $to_word);
        $comment = [];
        foreach ( range(1,$num_words_by_comment) as $something ) {
            $comment[] = ucfirst($this->LZFAKE->get_comment());
        }

        return implode( ' ', $comment );
	}

	/**
	 * Generates a description for the comment
	 *
	 * @since	1.0		2019-10-20		Release
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
		$tag_block_allow = ['h3','h4','h5'];
		$tag_selected    = ! empty( $this->request['tags'] ) && is_array( $this->request['tags'] ) ? $this->request['tags'] : [];
		$tags            = \array_intersect( $tag_allow, $tag_selected );
		$tags            = empty( $tags ) ? null : $tags;
		$tags_block      = $this->LZFAKE->array_rand_slice( \array_intersect( $tag_block_allow, $tag_selected ), 1 );
		$number          = (int) $this->numberToGenerate( $type_q, $num_exact, $num_from, $num_to );

		$str = [];
		foreach (range( 1, $number ) as $nothing) {
			$str[] =  $this->LZFAKE->set_tag_block( $this->LZFAKE->set_tag_inline( $this->LZFAKE->get_paragraph( $length, true ), $this->LZFAKE->frequency(50) ? $tags : [] ), $tags_block );
		}

		return implode(  '<br />', $str );
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
						(array)$this->request['post_type']
					), 70, \array_intersect(
						['post'], explode( ",", $this->request['post_type'] )
					)
				)
				: 'post';
    }

    private function generate_posts_ids(){
        return (array)$this->get_posts_fields([
            'post_type'      => $this->generate_post_type(),
            'numberposts'    => -1,
            'fields'         => ['ID'],
            'comment_status' => 'open',
            'posts_per_page' => -1
        ]);
    }

    // https://wordpress.stackexchange.com/questions/108288/how-to-return-only-certain-fields-using-get-posts
    private function get_posts_fields( $args = array() ) {
        $valid_fields = array(
            'ID'           => '%d',  'post_author'    => '%d',
            'post_type'    => '%s',  'post_mime_type' => '%s',
            'post_title'   => false, 'post_name'      => '%s',
            'post_date'    => '%s',  'post_modified'  => '%s',
            'menu_order'   => '%d',  'post_parent'    => '%d',
            'post_excerpt' => false, 'post_content'   => false,
            'post_status'  => '%s',  'comment_status' => '%s',  'ping_status'   => false,
            'to_ping'      => false, 'pinged'         => false, 'comment_count' => '%d'
        );
        $defaults = array(
            'post_type'      => 'post',
            'post_status'    => 'publish',
            'orderby'        => 'post_date',
            'order'          => 'DESC',
            'posts_per_page' => get_option('posts_per_page'),
        );
        global $wpdb;
        $args = wp_parse_args($args, $defaults);
        $where = "";
        foreach ( $valid_fields as $field => $can_query ) {
            if ( isset($args[$field]) && $can_query ) {
            if ( $where != "" )  $where .= " AND ";
                $where .= $wpdb->prepare( $field . " = " . $can_query, $args[$field] );
            }
        }
        if ( isset($args['search']) && is_string($args['search']) ) {
            if ( $where != "" )  $where .= " AND ";
            $where .= $wpdb->prepare("post_title LIKE %s", "%" . $args['search'] . "%");
        }
        if ( isset($args['include']) ) {
            if ( is_string($args['include']) ) $args['include'] = explode(',', $args['include']);
            if ( is_array($args['include']) ) {
                $args['include'] = array_map('intval', $args['include']);
                if ( $where != "" )  $where .= " OR ";
                $where .= "ID IN (" . implode(',', $args['include'] ). ")";
            }
        }
        if ( isset($args['exclude']) ) {
            if ( is_string($args['exclude']) ) $args['exclude'] = explode(',', $args['exclude']);
            if ( is_array($args['exclude']) ) {
                $args['exclude'] = array_map('intval', $args['exclude']);
                if ( $where != "" ) $where .= " AND ";
                $where .= "ID NOT IN (" . implode(',', $args['exclude'] ). ")";
            }
        }
        extract($args);
        $iscol = false;
        if ( isset($fields) ) {
            if ( is_string($fields) ) $fields = explode(',', $fields);
            if ( is_array($fields) ) {
                $fields = array_intersect($fields, array_keys($valid_fields));
                if( count($fields) == 1 ) $iscol = true;
                $fields = implode(',', $fields);
            }
        }
        if ( empty($fields) ) $fields = '*';
        if ( ! in_array($orderby, $valid_fields) ) $orderby = 'post_date';
        if ( ! in_array( strtoupper($order), array('ASC','DESC')) ) $order = 'DESC';
        if ( ! intval($posts_per_page) && $posts_per_page != -1)
            $posts_per_page = $defaults['posts_per_page'];
        if ( $where == "" ) $where = "1";
        $q = "SELECT $fields FROM $wpdb->posts WHERE " . $where;
        $q .= " ORDER BY $orderby $order";
        if ( $posts_per_page != -1) $q .= " LIMIT $posts_per_page";
        return $iscol ? $wpdb->get_col($q) : $wpdb->get_results($q);
    }

    private function calculate_numbers_comments(){
        $type_q    = $this->request['type'] ? 1 : 0;
        $num_exact = $this->request['exact'];
        $num_from  = $this->request['from'];
        $num_to    = $this->request['to'];
        return (int) $this->numberToGenerate( $type_q, $num_exact, $num_from, $num_to );
    }

    private function set_parent(){
        return empty( $this->parents ) ? 0 : self::randomElement( $this->parents, 60, [0] );
    }

	/**
	 * Register fake Comments
	 *
	 * @since	1.0		2019-10-20		Release
	 *
	 * @param 	integer $number			Number of records to be generated
	 * @param 	array 	$custom_meta	Additional goals to be added
	 * @return 	void
	 */
	public function composer_fake( $custom_meta = [] ){
        // ─── Get all ids posts ────────
        $posts_ids = $this->generate_posts_ids();
        if( empty( $posts_ids )  ) return 0;

		// ─── Sepup custom meta ────────
		$custom_meta = fdcHelper::setup_custom_meta( $custom_meta , 'comments' );

        $i = 0;
        $return = [];
        foreach ($posts_ids as $post_id) {
            // Reset the relatives comments for each post
            $this->parents = null;
            $this->parents[] = 0;

            // Calculate the number of comments for this posts
            $this->number_to_generate = $this->calculate_numbers_comments();

            if( ! empty( $this->number_to_generate ) ){

                foreach (range(1,$this->number_to_generate) as $something) {
                    $i++;

                    // ─── Get Comment ────────
                    $comment = $this->generate_description();

                    // ─── Insert comment ────────
                    $data = array(
                        'comment_post_ID'      => $post_id,
                        'comment_author'       => $names = $this->LZFAKE->get_name() . ' ' . $this->LZFAKE->get_lastname(),
                        'comment_author_email' => $this->LZFAKE->get_email( $names ),
                        'comment_author_url'   => $this->LZFAKE->frequency(70) ? $this->LZFAKE->get_domain_company() : '',
                        'comment_content'      => $comment,
                        'comment_type'         => '',
                        'comment_parent'       => $this->set_parent(),
                        'user_id'              => 0,
                        'comment_author_IP'    => $this->LZFAKE->get_ip(),
                        'comment_agent'        => 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.9.0.10) Gecko/2009042316 Firefox/3.0.10 (.NET CLR 3.5.30729)',
                        'comment_date'         => current_time('mysql'),
                        'comment_approved'     => 1,
                    );

                    $comment_id = wp_insert_comment( $data );
                    $this->parents[] = $comment_id;

                    // ─── Add meta value that identifies that this record is created by Generate Content ────────
                    update_comment_meta( $comment_id, 'cg', 1 );

                    // Custom meta
                    fdcHelper::insert_custom_meta( $custom_meta , 'comments', $comment_id, $this->LZFAKE );

                    // Output
                    $return[] = [ 'id' => $comment_id, 'url' => get_comment_link($comment_id) ];
                }

            }
        }

		// ─── Return a valid ID to know that I insert everything correctly ────────
		if( $comment_id ){
            fdcHelper::update_option('fdc-setting',((int)fdcHelper::get_option('fdc-setting','comments')) + $i,'comments');
			return $return;
		}

		return 0;

	}
}
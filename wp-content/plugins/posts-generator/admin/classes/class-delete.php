<?php
/**
 * @since 		1.0     2019-10-23     Release
 * @package 	FDC
 * @subpackage 	FDC/Admin
 */
// ───────────────────────────
namespace FDC\Admin;
use FDC as fdc;
// ───────────────────────────
/*
|--------------------------------------------------------------------------
| Class to delete the data
|--------------------------------------------------------------------------
|
*/
class Fdc_Delete{
    public
	/**
	 * Data passed by AJAX for manipulation
	 * @access  public
     * @since   1.0     2019-10-23      Release
     * @var     array
	 */
	$request = [],
	/**
	 * Variable database object, it is set to avoid repeated code
	 * @access  public
     * @since   1.0     2019-10-23      Release
     * @var     array
	 */
	$wpdb = null;

    /**
     * Class initializer
     *
     * @since   1.0     2019-10-23      Release
     * @param   string  $output
     */
    public function __construct( $data = [] ){
		global $wpdb;
		// ─── Set data request ────────
		$this->request = $data;

		// ─── Database Variable ────────
		$this->wpdb = $wpdb;
	}

	/**
	 * Execute the removal function that is selected
	 * @since	1.5.6	2019-11-25		- Doc
	 * 									- Added delete menu in function
	 *
	 * @return 	void
	 */
	public function delete(){
		$objects = $this->request['objects'];
		$status  = false;
		if( ! empty( $objects ) ){
			$objects = explode(',',$objects);
			foreach ($objects as $object) {

				switch ($object) {
					case 'users':
						$status = $this->delete_users();
						break;
					case 'terms':
						$status = $this->delete_terms();
						break;
					case 'posts':
						$status = $this->delete_posts();
						break;
					case 'comments':
						$status = $this->delete_comments();
						break;
					case 'menus':
						$status = $this->delete_menus();
						break;
					default:
						# code...
						break;
				}

			}


		}

		return $status;
	}

	private function delete_users(){
		if( $this->request['type'] == 'only' ){
			$array_ids = [];
			$array_users = $this->wpdb->get_results("SELECT user_id FROM {$this->wpdb->prefix}usermeta WHERE meta_key = 'cg'", ARRAY_A );
			if(  ! empty( $array_users )  ){
				foreach ($array_users as $value) {
					$array_ids[] = $value['user_id'];
				}
			}
			return $this->wpdb->query(
							"DELETE A,B
								FROM {$this->wpdb->prefix}users A
							INNER JOIN {$this->wpdb->prefix}usermeta B
								ON A.ID = B.user_id
							WHERE B.user_id IN (". implode(',',$array_ids) .") AND A.ID <> 1 ");
		}else{
			return 	$this->wpdb->query(
				"DELETE A,B
					FROM {$this->wpdb->prefix}users A
				INNER JOIN {$this->wpdb->prefix}usermeta B
					ON A.ID = B.user_id
				WHERE A.ID <> 1");
		}
	}

	/**
	 * Remove terms
	 * @since	1.5.6	2019-11-25	- Update Doc
	 * 								- Elimination is improved so you don't delete menus that are also terms
	 *
	 * @return 	void
	 */
	private function delete_terms(){

		if( $this->request['type'] == 'only' ){
			$array_ids     = [];
			$array_ids_tax = [];
			$array_terms   = $this->wpdb->get_results("SELECT term_id FROM {$this->wpdb->prefix}termmeta WHERE meta_key = 'cg'", ARRAY_A );
			if(  ! empty( $array_terms )  ){
				foreach ($array_terms as $value) {
					$array_ids[] = $value['term_id'];
				}
			}
			$array_terms_tax = $this->wpdb->get_results("SELECT term_taxonomy_id FROM {$this->wpdb->prefix}term_taxonomy WHERE term_id IN (". implode(',',$array_ids) .")", ARRAY_A );
			if(  ! empty( $array_terms_tax )  ){
				foreach ($array_terms_tax as $value) {
					$array_ids_tax[] = $value['term_taxonomy_id'];
				}
			}

			$this->wpdb->query("DELETE FROM {$this->wpdb->prefix}term_relationships WHERE term_taxonomy_id IN (". implode(',',$array_ids_tax) .") AND term_taxonomy_id <> 1");
			$this->wpdb->query("DELETE FROM {$this->wpdb->prefix}term_taxonomy WHERE term_id IN (". implode(',',$array_ids) .") AND term_id <> 1");

			$this->wpdb->query("DELETE FROM {$this->wpdb->prefix}termmeta WHERE term_id IN (". implode(',',$array_ids) .") AND term_id <> 1");
			return $this->wpdb->query("DELETE FROM {$this->wpdb->prefix}terms WHERE term_id IN (". implode(',',$array_ids) .") AND term_id <> 1");
		}else{
			// As Menu is also a term, we must exclude it from deletion of the term
			$array_terms_tax = $this->wpdb->get_results("SELECT term_id FROM {$this->wpdb->prefix}term_taxonomy WHERE taxonomy = 'nav_menu')", ARRAY_A );
			$array_ids_term_menu = [];
			$sql_menu_term = '';
			$sql_menu_term_tax = '';
			if(  ! empty( $array_terms_tax )  ){
				foreach ($array_terms_tax as $value) {
					$array_ids_term_menu[] = $value['term_id'];
				}
				if( ! empty( $array_ids_term_menu ) ){
					$sql_menu_term_tax = "  AND term_taxonomy_id NOT IN (". implode(',',$array_ids_term_menu) .") ";
					$sql_menu_term = "  AND term_id NOT IN (". implode(',',$array_ids_term_menu) .") ";
				}
			}
			$this->wpdb->query("DELETE FROM {$this->wpdb->prefix}term_relationships WHERE term_taxonomy_id <> 1 $sql_menu_term_tax");
			$this->wpdb->query("DELETE FROM {$this->wpdb->prefix}term_taxonomy WHERE term_id <> 1 $sql_menu_term");
			$this->wpdb->query("DELETE FROM {$this->wpdb->prefix}termmeta WHERE term_id <> 1 $sql_menu_term");
			return $this->wpdb->query("DELETE FROM {$this->wpdb->prefix}terms WHERE term_id <> 1 $sql_menu_term");
		}
	}

	/**
	 * Post Deletion
	 *
	 * @since	1.5.6	2019-11-25	- Update doc
	 * 								- It is validated so that this elimination does not affect the removal of the menu items
	 * @return void
	 */
	private function delete_posts(){
		if( $this->request['type'] == 'only' ){
			$array_ids     = [];
			$array_items   = $this->wpdb->get_results("SELECT post_id FROM {$this->wpdb->prefix}postmeta WHERE meta_key = 'cg'", ARRAY_A );
			if(  ! empty( $array_items )  ){
				foreach ($array_items as $value) {
					$array_ids[] = $value['post_id'];
				}
			}
			$this->wpdb->query("DELETE FROM {$this->wpdb->prefix}term_relationships WHERE object_id IN (". implode(',',$array_ids) .") AND object_id <> 1");
			$this->wpdb->query("DELETE FROM {$this->wpdb->prefix}postmeta WHERE post_id IN (". implode(',',$array_ids) .") AND post_id <> 1");
			$this->wpdb->query("DELETE FROM {$this->wpdb->prefix}posts WHERE post_parent IN (". implode(',',$array_ids) .") AND post_parent <> 1");
			return $this->wpdb->query("DELETE FROM {$this->wpdb->prefix}posts WHERE ID IN (". implode(',',$array_ids) .") AND ID <> 1");
		}else{
			// As Menu is also a term, we must exclude it from deletion of the post
			$array_terms_tax = $this->wpdb->get_results("SELECT term_id FROM {$this->wpdb->prefix}term_taxonomy WHERE taxonomy = 'nav_menu')", ARRAY_A );
			$array_ids_term_menu = [];
			$sql_menu_term_tax = '';
			if(  ! empty( $array_terms_tax )  ){
				foreach ($array_terms_tax as $value)
					$array_ids_term_menu[] = $value['term_id'];
				if( ! empty( $array_ids_term_menu ) ){ $sql_menu_term_tax = "  AND term_taxonomy_id NOT IN (". implode(',',$array_ids_term_menu) .") "; }
			}

			// Since the Menus also have a post meta, then those of items menu should be excluded
			$array_ids   = [];
			$array_items = $this->wpdb->get_results("SELECT DISTINCT post_id FROM {$this->wpdb->prefix}postmeta WHERE meta_key = '_menu_item_object_id'", ARRAY_A );
			$sql_not_ids = '';
			if(  ! empty( $array_items )  ){
				foreach ($array_items as $value) { $array_ids[] = $value['post_id']; }
				if( ! empty( $array_ids ) ){ $sql_not_ids = "  AND post_id NOT IN (". implode(',',$array_ids) .") "; }
			}

			$this->wpdb->query("DELETE FROM {$this->wpdb->prefix}term_relationships WHERE object_id <> 1 $sql_menu_term_tax ");
			$this->wpdb->query("DELETE FROM {$this->wpdb->prefix}postmeta WHERE post_id <> 1 $sql_not_ids");
			return $this->wpdb->query("DELETE FROM {$this->wpdb->prefix}posts WHERE ID <> 1  AND post_type <> 'nav_menu_item'");
		}
	}

	private function delete_comments(){
		if( $this->request['type'] == 'only' ){
			$array_ids       = [];
			$array_posts_ids = [];
			$array_comments  = $this->wpdb->get_results("SELECT comment_id FROM {$this->wpdb->prefix}commentmeta WHERE meta_key = 'cg'", ARRAY_A );
			if(  ! empty( $array_comments )  ){
				foreach ($array_comments as $value) {
					$array_ids[] = $value['comment_id'];
				}
			}

			$posts_ids = $this->wpdb->get_results("SELECT comment_post_ID FROM {$this->wpdb->prefix}comments WHERE comment_id IN (". implode(',',$array_ids) .") ", ARRAY_A );
			if(  ! empty( $posts_ids )  ){
				foreach ($posts_ids as $value) {
					$array_posts_ids[] = $value['comment_post_ID'];
				}
			}

			$this->wpdb->query(
				"DELETE A,B
					FROM {$this->wpdb->prefix}comments A
				INNER JOIN {$this->wpdb->prefix}commentmeta B
					ON A.comment_ID = B.comment_id
				WHERE B.comment_id IN (". implode(',',$array_ids) .") AND A.comment_ID <> 1 "
			);

			if(  ! empty( $array_posts_ids )  ){
				foreach ($array_posts_ids as $value) {
					wp_update_comment_count_now( $value );
				}
			}

			return true;
		}else{
			$this->wpdb->query("UPDATE {$this->wpdb->prefix}posts SET comment_count = 1 WHERE ID = 1");
			return 	$this->wpdb->query(
				"DELETE A,B
					FROM {$this->wpdb->prefix}comments A
				INNER JOIN {$this->wpdb->prefix}commentmeta B
					ON A.comment_ID = B.comment_id
				WHERE A.comment_ID <> 1");
		}
	}

	/**
	 * Menu Removal
	 *
	 * @since	1.5.6	2019-11-25	Release
	 * @return 	void
	 */
	private function delete_menus(){
		if( $this->request['type'] == 'only' ){
			$array_ids_terms[] = 0;
			$array_terms     = $this->wpdb->get_results("SELECT DISTINCT term_id FROM {$this->wpdb->prefix}termmeta WHERE meta_key = '_menu_pg'", ARRAY_A );
			if(  ! empty( $array_terms )  ){
				foreach ($array_terms as $value) { $array_ids_terms[] = $value['term_id']; wp_delete_nav_menu( $value['term_id'] ); }
			}
			return true;

			/* $this->wpdb->query("DELETE FROM {$this->wpdb->prefix}term_relationships WHERE term_taxonomy_id IN (". implode(',',$array_ids_terms) .") ");
			$this->wpdb->query("DELETE FROM {$this->wpdb->prefix}term_taxonomy WHERE term_id IN (". implode(',',$array_ids_terms) .") ");

			$this->wpdb->query("DELETE FROM {$this->wpdb->prefix}termmeta WHERE term_id IN (". implode(',',$array_ids_terms) .") ");
			$this->wpdb->query("DELETE FROM {$this->wpdb->prefix}terms WHERE term_id IN (". implode(',',$array_ids_terms) .") ");

			$array_ids[0]  = 0;
			$array_items   = $this->wpdb->get_results("SELECT DISTINCT post_id FROM {$this->wpdb->prefix}postmeta WHERE meta_key = '_menu_item_pg'", ARRAY_A );
			if(  ! empty( $array_items )  ){ foreach ($array_items as $value) { $array_ids[] = $value['post_id']; } }
			$this->wpdb->query("DELETE FROM {$this->wpdb->prefix}postmeta WHERE post_id IN (". implode(',',$array_ids) .") "); */

			// ─── Remuevo del theme la seleccion del menu ────────
			/* $locations = get_theme_mod('nav_menu_locations');
			$locations[$menulocation] = $menu_id;
			set_theme_mod( 'nav_menu_locations', $locations ); */

			//return $this->wpdb->query("DELETE FROM {$this->wpdb->prefix}posts WHERE ID IN (". implode(',',$array_ids) .") ");
		}else{
			$array_terms_tax = $this->wpdb->get_results("SELECT term_id FROM {$this->wpdb->prefix}term_taxonomy WHERE taxonomy = 'nav_menu'", ARRAY_A );
			if(  ! empty( $array_terms_tax )  ){
				foreach ($array_terms_tax as $value){
					wp_delete_nav_menu( $value['term_id'] );
				}
				//$array_ids_term_menu[] = $value['term_id'];
			}
			return true;
			/* $this->wpdb->query("DELETE FROM {$this->wpdb->prefix}term_relationships WHERE object_id <> 1 AND term_taxonomy_id IN (". implode(',',$array_ids_term_menu) .") ");
			$this->wpdb->query("DELETE FROM {$this->wpdb->prefix}term_taxonomy WHERE term_id <> 1 AND term_id IN (". implode(',',$array_ids_term_menu) .")");
			$this->wpdb->query("DELETE FROM {$this->wpdb->prefix}terms WHERE term_id <> 1 AND term_id IN (". implode(',',$array_ids_term_menu) .")");

			$array_ids[] = 0;
			$array_items = $this->wpdb->get_results("SELECT DISTINCT post_id FROM {$this->wpdb->prefix}postmeta WHERE meta_key = '_menu_item_object_id'", ARRAY_A );
			if(  ! empty( $array_items )  ){ foreach ($array_items as $value) { $array_ids[] = $value['post_id']; } }

			$this->wpdb->query("DELETE FROM {$this->wpdb->prefix}postmeta WHERE post_id IN (". implode(',',$array_ids) .") ");
			return $this->wpdb->query("DELETE FROM {$this->wpdb->prefix}posts WHERE ID IN (". implode(',',$array_ids) .") "); */
		}
	}

}
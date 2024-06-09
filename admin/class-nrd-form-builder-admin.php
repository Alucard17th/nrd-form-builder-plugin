<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://github.com/Alucard17th
 * @since      1.0.0
 *
 * @package    Nrd_Form_Builder
 * @subpackage Nrd_Form_Builder/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Nrd_Form_Builder
 * @subpackage Nrd_Form_Builder/admin
 * @author     Noureddine Eddallal <eddallal.noureddine@gmail.com>
 */
class Nrd_Form_Builder_Admin {

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
		 * defined in Nrd_Form_Builder_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Nrd_Form_Builder_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/nrd-form-builder-admin.css', array(), $this->version, 'all' );

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
		 * defined in Nrd_Form_Builder_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Nrd_Form_Builder_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/nrd-form-builder-admin.js', array( 'jquery' ), $this->version, false );

		// FORM BUILDER
		wp_enqueue_script( $this->plugin_name.'-form-builder', 'https://cdnjs.cloudflare.com/ajax/libs/jQuery-formBuilder/3.19.7/form-builder.min.js', array( 'jquery' ), '3.19.7', true );
		wp_enqueue_script( $this->plugin_name.'-form-render', 'https://cdnjs.cloudflare.com/ajax/libs/jQuery-formBuilder/3.19.7/form-render.min.js', array( 'jquery' ), '3.19.7', true );
	}

	public function check_license_status() {
        $isActive = get_option('nrd_form_bd_license_active');
        return $isActive == 'active' ? true : false;
    }

	public function nrd_form_bd_please_activate_plugin() {
		if(!$this->check_license_status()) {
			$page_slug = 'nrd-form-bd-menu-slug';
			$page_url = menu_page_url($page_slug, false);

			echo // Customize the message below as needed
			'<div class="notice notice-error is-dismissible">
				<p>Please activate NRD Form Builder to use it.</p>
				<p><a href="' . $page_url . '" class="">Activate NRD Form Builder</a></p>
			</div>'; 
		}
	}

	// Function to add the menu and the page
	function custom_dashboard_menu() {
		add_menu_page(
			'NRD Form BD',          // Page title
			'NRD Form BD',                // Menu title
			'manage_options',             // Capability
			'nrd-form-bd-menu-slug',           // Menu slug
			array($this, 'custom_dashboard_page_html'), // Function to display the page content
			'dashicons-forms',    // Icon URL or dashicons class
			20                            // Position in the menu
		);
	}

    public function custom_dashboard_page_html() {
        if (!current_user_can('manage_options')) {
            return;
        }
        ?>
        <div class="wrap">
			
            <h1>NRD Form Builder</h1>
			<div class="nrd-form-bd-card">
				<div class="nrd-form-bd-card-content">
					<div class="logo-row">
					<svg xmlns="http://www.w3.org/2000/svg" width="50" height="41" fill="none" viewBox="0 0 54 41"><path fill="#2A2E4E" d="M54 1.5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0Z"></path><path fill="#2A2E4E" fill-rule="evenodd" d="M13.75 40.794C6.156 40.794 0 34.638 0 27.044V1h5v26.044a8.75 8.75 0 0 0 8.75 8.75c4.893 0 8.75-3.771 8.75-8.544h5v7.5a1.25 1.25 0 0 0 2.5 0v-8.875a6.25 6.25 0 0 1-7.5-6.125V7.25a6.25 6.25 0 1 1 12.5 0v27.5a1.25 1.25 0 1 0 2.5 0V7.25a6.25 6.25 0 1 1 12.5 0v27.5a6.25 6.25 0 0 1-10 5A6.222 6.222 0 0 1 36.25 41a6.222 6.222 0 0 1-3.75-1.25 6.251 6.251 0 0 1-9.466-2.47c-2.456 2.197-5.723 3.514-9.284 3.514Zm30-4.794c-.69 0-1.25-.56-1.25-1.25V7.25a1.25 1.25 0 1 1 2.5 0v27.5c0 .69-.56 1.25-1.25 1.25ZM30 19.75a1.25 1.25 0 0 1-2.5 0V7.25a1.25 1.25 0 1 1 2.5 0v12.5Z" clip-rule="evenodd"></path><path fill="#2A2E4E" fill-rule="evenodd" d="M7.5 27.25a6.25 6.25 0 1 0 12.5 0v-20a6.25 6.25 0 1 0-12.5 0v20Zm6.25 1.25c-.69 0-1.25-.56-1.25-1.25v-20a1.25 1.25 0 1 1 2.5 0v20c0 .69-.56 1.25-1.25 1.25Z" clip-rule="evenodd"></path></svg>
					</div>
					<?php
						// Get the value of the setting we've registered with register_setting()
						$licenseKey = get_option('nrd_form_bd_field_license_key');
						$isActive = get_option('nrd_form_bd_license_active');
						echo '<div class="nrd-form-bd-left">';
						echo '<h3>License Key</h3>';
						echo '<p>Add your license key to get started.</p>';
						echo '<input type="text" name="license_key" id="nrd-form-bd-license-key" placeholder="Enter your license key here..." value="' . $licenseKey . '">';
						echo '<h3>Status: ' . ($isActive == 'active' ? '<span class="nrd-form-bd-activate">Active<span>' : '<span class="nrd-form-bd-inactive">Inactive<span>') . '</h3>';
					
						if($isActive != 'active') {
							echo '<button type="button" class="button button-primary nrd-form-bd-button" id="nrd-form-bd-activate-button">Activate</button>';
						}else{
							echo '<button type="button" class="button button-primary nrd-form-bd-button" id="nrd-form-bd-activated-button" disabled>Activated</button>';

							echo '<button type="button" class="button nrd-form-bd-button" id="nrd-form-bd-deactivate-button">Deactivate</button>';
						}
						echo '</div>';
						echo '<div class="nrd-form-bd-right">';
						echo '<h3>Activate your license key to get started.</h3>';
						echo '<iframe src="https://drive.google.com/file/d/1bR4inV79KgZ-1-enNvOpb6TIuGgCqd7c/preview" width="640" height="480" allow="autoplay" allowfullscreen></iframe>';
						echo '</div>';

					?>
			</div>
			</div>
        </div>
        <?php
    }

	public function register_cpt_nrd_form_bd() {
		$labels = array(
			'name' => 'NRD BD Forms',
			'singular_name' => 'NRD BD Form',
			'menu_name' => 'NRD BD Forms',
			'name_admin_bar' => 'NRD BD Form',
			'add_new' => 'Add New',
			'add_new_item' => 'Add New NRD BD Form',
			'new_item' => 'New NRD BD Form',
			'edit_item' => 'Edit NRD BD Form',
			'view_item' => 'View NRD BD Form',
			'all_items' => 'All NRD BD Form',
			'search_items' => 'Search NRD BD Form',
			'parent_item_colon' => 'Parent NRD BD Form:',
			'not_found' => 'No NRD BD Form found.',
			'not_found_in_trash' => 'No NRD BD Form found in Trash.'
		);

		$args = array(
			'labels' => $labels,
			'public' => true,
			'publicly_queryable' => true,
			'show_ui' => true,
			'show_in_menu' => true,
			'query_var' => true,
			'rewrite' => array('slug' => 'nrd-form-bd'),
			'capability_type' => 'post',
			'has_archive' => true,
			'hierarchical' => false,
			'menu_position' => null,
			'supports' => array('title')
		);

		register_post_type('nrd-form-bd', $args);
	}
	public function add_custom_meta_box() {
		add_meta_box(
			'nrd_form_bd_meta_box',          // Unique ID
			'From Builder',         // Box title
			array($this, 'custom_meta_box_html'),  // Content callback, must be of type callable
			'nrd-form-bd',                    // Post type
			'normal',                         // Context
		);

		add_meta_box(
			'nrd_form_bd_meta_box_drive_sheet',          // Unique ID
			'Google Sheet ID',         // Box title
			array($this, 'custom_meta_box_html_drive_sheet'),  // Content callback, must be of type callable
			'nrd-form-bd',                    // Post type
			'side',                         // Context
		);
	}
	public function custom_meta_box_html($post) {
		// wp_nonce_field('nrd_form_bd_meta_box', 'nrd_form_bd_meta_box_nonce');
		$screen = get_current_screen();
		$content = $post->post_content;
		if($content != ''){
			$json_content = json_encode($content);
			echo '<script>
				let formData = JSON.parse(' . $json_content . ');
				console.log(formData);
			</script>';
		}

		if ($screen->base == 'post' && isset($_GET['action']) && $_GET['action'] == 'edit') {
			$post_id = isset($_GET['post']) ? intval($_GET['post']) : 0;

			echo '<input type="hidden" name="post_id" id="post_id" value="' . $post_id . '">';
			echo '<div id="short-code-preview">Short Code: 
			<code class="nrd-short-code">[nrd_form_bd id="' . $post_id . '"]</code>
			<span class="nrd-short-code-copy" style="display: none; color: #7F8184;">Link copied to clipboard.</span>
			</div>';
		}
		echo '<div class="wrap">';
		echo '<div id="fb-editor"></div>';
		echo '</div>';
	}
	public function custom_meta_box_html_drive_sheet($post) {
		// wp_nonce_field('nrd_form_bd_meta_box', 'nrd_form_bd_meta_box_nonce');
		$screen = get_current_screen();
		
		
		if ($screen->base == 'post' && isset($_GET['action']) && $_GET['action'] == 'edit') {
			$post_id = isset($_GET['post']) ? intval($_GET['post']) : 0;
			$linkedSheetId = get_post_meta($post_id, 'nrd_form_bd_google_sheet_id', true);
			echo '<h3>Google Sheet ID</h3>';
			echo '<input type="text" name="google_sheet_id" id="google_sheet_id" style="width: 100%;" value="' . $linkedSheetId . '">';
		}
		
	}
	public function hide_publish_box() {
		remove_meta_box('submitdiv', 'nrd-form-bd', 'side');
	}
	public function disable_autosave_for_nrd_form_bd( $pagehook ) {
		global $post_type, $current_screen;
		if($post_type == 'nrd-form-bd' ){
			wp_deregister_script( 'autosave' );
		}
	}
	public function save_nrd_wp_fb()
	{
		$post_id = $_POST['post_id'];
		
		if(!$post_id){
			// Create an array of post data for the new post
			$new_post = array(
				'post_title'   => $_POST['title'], // Valid post name
				'post_content' => $_POST['content'], // Unslashed post data - Set the content of the new post
				'post_type'    => 'nrd-form-bd',
				'post_status'  => 'publish', // Unslashed post data - Set the status of the new post to 'publish'
			);

			// Insert post into the database
			$post_id = wp_insert_post($new_post, true); // Use $wp_error set to true for error handling

			// Check if there was an error during post insertion
			if (is_wp_error($post_id)) {
				// Error occurred while inserting the post
				wp_send_json_error( "Error: " . $post_id->get_error_message() );
			} else {
				// The post was successfully inserted, and $post_id contains the post ID
				wp_send_json_success( html_entity_decode(get_edit_post_link( $post_id )) );
			}
		}else{
			// Update the post into the database
			$update_post = array(
				'ID'           => $post_id,
				'post_title'   => $_POST['title'], // Valid post name
				'post_content' => $_POST['content'], // Unslashed post data - Set the content of the new post
			);

			// Insert post into the database
			$post_id = wp_update_post($update_post, true); // Use $wp_error set to true for error handling

			update_post_meta($post_id, 'nrd_form_bd_google_sheet_id', $_POST['google_sheet_id']);

			if (is_wp_error($post_id)) {
				wp_send_json_error( "Error: " . $post_id->get_error_message() );
			} else {
				wp_send_json_success( html_entity_decode(get_edit_post_link( $post_id )) );
			}
		}
	}




	function isLicenseActive() {

		$license_key = get_option( 'nrd_form_builder_license_key' );
		$license_status = get_option( 'nrd_form_builder_license_status' );
		$license_expiry = get_option( 'nrd_form_builder_license_expiry' );
		$license_email = get_option( 'nrd_form_builder_license_email' );
		$license_name = get_option( 'nrd_form_builder_license_name' );

		if ( $license_key && $license_status == 'valid' && $license_expiry > time() && $license_email && $license_name ) {
			return true;
		}
		return false;
	}

	public function save_nrd_license_response()
	{
		$status = $_POST['status'] == 'active' ? 'active' : 'inactive';
		$option = update_option('nrd_form_bd_license_active', $status);
		$license = update_option('nrd_form_bd_field_license_key', $_POST['license_key']);
		wp_send_json_success( 'License Updated to ' . $status . ' || ' . $license);

		// if(!$option && $option == ''){
		// 	// Error occurred while inserting the post
		// 	$option = add_option('nrd_form_bd_license_active', $status);
		// 	wp_send_json_success( 'License Activated' . $status);
		// 	// wp_send_json_error( 'License Activation Failed' );
		// }else {
		// 	// The post was successfully inserted, and $post_id contains the post ID
		// 	$option = update_option('nrd_form_bd_license_active', $status);
		// 	wp_send_json_success( 'License Updated '. $option);
		// }
	}

	// SAVE THE EXCEL SHEET 
}

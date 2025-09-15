<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://github.com/Alucard17th
 * @since      1.0.0
 *
 * @package    Nrd_Form_Builder
 * @subpackage Nrd_Form_Builder/includes
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
 * @package    Nrd_Form_Builder
 * @subpackage Nrd_Form_Builder/includes
 * @author     Noureddine Eddallal <eddallal.noureddine@gmail.com>
 */
class Nrd_Form_Builder {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Nrd_Form_Builder_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		if ( defined( 'NRD_FORM_BUILDER_VERSION' ) ) {
			$this->version = NRD_FORM_BUILDER_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'nrd-form-builder';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Nrd_Form_Builder_Loader. Orchestrates the hooks of the plugin.
	 * - Nrd_Form_Builder_i18n. Defines internationalization functionality.
	 * - Nrd_Form_Builder_Admin. Defines all hooks for the admin area.
	 * - Nrd_Form_Builder_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-nrd-form-builder-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-nrd-form-builder-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-nrd-form-builder-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-nrd-form-builder-public.php';

		$this->loader = new Nrd_Form_Builder_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Nrd_Form_Builder_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Nrd_Form_Builder_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Nrd_Form_Builder_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );

		$this->loader->add_action( 'admin_menu', $plugin_admin, 'custom_dashboard_menu' );
		$this->loader->add_action( 'admin_notices', $plugin_admin, 'nrd_form_bd_please_activate_plugin' );
		
		$this->loader->add_action( 'wp_ajax_save_nrd_license_response', $plugin_admin, 'save_nrd_license_response' );
		$this->loader->add_action( 'wp_ajax_nopriv_save_nrd_license_response', $plugin_admin, 'save_nrd_license_response' );

		$isActive = get_option('nrd_form_bd_license_active') == 'active' ? true : false;
		if($isActive){
			$this->loader->add_action( 'init', $plugin_admin, 'register_cpt_nrd_form_bd' );
			$this->loader->add_action( 'add_meta_boxes', $plugin_admin, 'add_custom_meta_box' );
			$this->loader->add_action( 'admin_menu', $plugin_admin, 'hide_publish_box' );
			$this->loader->add_action( 'wp_ajax_save_nrd_wp_fb', $plugin_admin, 'save_nrd_wp_fb' );
			$this->loader->add_action( 'wp_ajax_nopriv_save_nrd_wp_fb', $plugin_admin, 'save_nrd_wp_fb' );
			$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'disable_autosave_for_nrd_form_bd' );

			$this->loader->add_action('init', $plugin_admin, 'register_cpt_nrd_form_bd_submission');
			// Admin UI: columns for submissions list
			$this->loader->add_action('add_meta_boxes', $plugin_admin, 'add_submission_details_metabox');
			$this->loader->add_filter('manage_nrd-form-bd-submission_posts_columns', $plugin_admin, 'submissions_columns');
			$this->loader->add_action('manage_nrd-form-bd-submission_posts_custom_column', $plugin_admin, 'submissions_column_content', 10, 2);
			$this->loader->add_filter('manage_edit-nrd-form-bd-submission_sortable_columns', $plugin_admin, 'submissions_sortable_columns');

			// Optional: put Submissions under the same top-level menu
			$this->loader->add_action('admin_menu', $plugin_admin, 'register_submissions_submenu');
		}
	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Nrd_Form_Builder_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );

		$this->loader->add_action( 'wp_ajax_post_nrd_wp_fb', $plugin_public, 'post_nrd_wp_fb' );
		$this->loader->add_action( 'wp_ajax_nopriv_post_nrd_wp_fb', $plugin_public, 'post_nrd_wp_fb' );
		$this->loader->add_action( 'init', $plugin_public, 'register_shortcodes' );
	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Nrd_Form_Builder_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

}

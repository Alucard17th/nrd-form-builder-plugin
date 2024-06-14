<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://github.com/Alucard17th
 * @since      1.0.0
 *
 * @package    Nrd_Form_Builder
 * @subpackage Nrd_Form_Builder/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Nrd_Form_Builder
 * @subpackage Nrd_Form_Builder/public
 * @author     Noureddine Eddallal <eddallal.noureddine@gmail.com>
 */
class Nrd_Form_Builder_Public {

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
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
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

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/nrd-form-builder-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
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

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/nrd-form-builder-public.js', array( 'jquery' ), $this->version, false );
		wp_enqueue_script( $this->plugin_name.'-form-render-public', 'https://cdnjs.cloudflare.com/ajax/libs/jQuery-formBuilder/3.19.7/form-render.min.js', array( 'jquery' ), '3.19.7', true );
		wp_localize_script( $this->plugin_name, 'my_ajax_object', array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ) );
	}

	// SHORTCODE TO RENDER FORM
	public function register_shortcodes() {
        function render_nrd_form_bd($atts) {
			$atts = shortcode_atts( array(
                'id' => 'World', // Set a default value for the 'id' parameter
              ), $atts );
            $id = $atts['id'];

			$post = get_post($id);
			$content = $post->post_content;
			$linkedSheetId = get_post_meta($id, 'nrd_form_bd_google_sheet_id', true);
			$json_content = json_encode($content);
			$output = '<form id="fb-rendered-form" enctype="multipart/form-data"></form>';
			$output .=  '<input type="hidden" id="google_sheet_id" value="' . $linkedSheetId . '">';

			if($content != ''){
				$json_content = json_encode($content);
				$output .=  '<script>
					let formRenderData = JSON.parse(' . $json_content . ');
					console.log(formRenderData);
				</script>';
			}
            return $output;
        }
        add_shortcode('nrd_form_bd', 'render_nrd_form_bd');
	}

	public function post_nrd_wp_fb()
	{
		if (!function_exists('wp_handle_upload')) {
			require_once(ABSPATH . 'wp-admin/includes/file.php');
		}

		$uploaded_files = array();
		$errors = array();

		// Loop through all files in the $_FILES array
		if(!empty($_FILES)) {
			foreach ($_FILES as $file_key => $file) {
				// Check if the file is valid and not empty
				if ($file['error'] === UPLOAD_ERR_OK && $file['size'] > 0) {
					// Handle the file upload
					$upload_overrides = array('test_form' => false);
					$movefile = wp_handle_upload($file, $upload_overrides);
		
					if ($movefile && !isset($movefile['error'])) {
						// File uploaded successfully, insert it into the media library
						$attachment = array(
							'guid'           => $movefile['url'],
							'post_mime_type' => $movefile['type'],
							'post_title'     => preg_replace('/\.[^.]+$/', '', basename($movefile['file'])),
							'post_content'   => '',
							'post_status'    => 'inherit'
						);
		
						$attach_id = wp_insert_attachment($attachment, $movefile['file']);
						require_once(ABSPATH . 'wp-admin/includes/image.php');
						$attach_data = wp_generate_attachment_metadata($attach_id, $movefile['file']);
						wp_update_attachment_metadata($attach_id, $attach_data);
		
						$uploaded_files[$file_key] = wp_get_attachment_url($attach_id);
					} else {
						$errors[$file_key] = 'Error uploading file: ' . $movefile['error'];
					}
				} else {
					$errors[$file_key] = 'File upload error: ' . $file['error'];
				}
			}
		}
		
		// from POST array create a new array but remove action and google_sheet_id
		$dataArray = $_POST;
		unset($dataArray['action']);
		unset($dataArray['google_sheet_id']);

		// merge dataArray and uploaded_files array to create final array
		$form_data = array_merge($dataArray, $uploaded_files);
		$googleSheetId = $_POST['google_sheet_id'];

		// $form_data = [];
		// parse_str($_POST['data'], $form_data);
		// if (empty($errors)) {
		// 	wp_send_json_success(array('message' => 'Files uploaded successfully', 'files' => $uploaded_files));
		// } else {
		// 	wp_send_json_error(array('message' => 'Some files could not be uploaded', 'errors' => $errors));
		// }

		if($googleSheetId != ''){
			if ($this->addLeadToGoogleSheets($form_data, $googleSheetId)) {
				wp_send_json_success('Lead added to Google Sheets successfully.');
			} else {
				wp_send_json_error('Failed to add lead to Google Sheets. Please check the error log.');
			}
		}
		wp_send_json_success('Lead added to Google Sheets successfully.');

		// get wordpress admin email 
		// $adminEmail = get_option('admin_email');
		// // Format the form data into an HTML email
        // $email_body = "<h1>New Form Submission</h1>";
        // $email_body .= "<table border='1' cellpadding='5' cellspacing='0'>";
        // foreach ($form_data as $key => $value) {
        //     $email_body .= "<tr>";
        //     $email_body .= "<th style='text-align:left;'>".esc_html($key)."</th>";
        //     $email_body .= "<td>".esc_html($value)."</td>";
        //     $email_body .= "</tr>";
        // }
        // $email_body .= "</table>";

        // // Email headers
        // $headers = array('Content-Type: text/html; charset=UTF-8');

        // // Send the email
        // $to = 'recipient@example.com'; // Replace with your recipient email address
        // $subject = 'New Form Submission';
        // $sent = wp_mail($to, $subject, $email_body, $headers);


        // if ($sent) {
        //     wp_send_json_success(array(
        //         'status' => 'success',
        //         'message' => 'Form data received and email sent successfully'
        //     ));
        // } else {
        //     wp_send_json_error(array(
        //         'status' => 'error',
        //         'message' => 'Failed to send email'
        //     ));
        // }

		wp_die();
	}

	function addLeadToGoogleSheets($leadData, $googleSheetId) {
		require_once plugin_dir_path(__DIR__) . 'vendor/autoload.php';

		// configure the Google Client
		$client = new \Google_Client();
		$client->setApplicationName('Google Sheets with Primo');
		$client->setScopes([\Google_Service_Sheets::SPREADSHEETS]);
		$client->setAccessType('offline');
		$client->setAuthConfig(__DIR__ . '/credentials.json');
	
		$service = new Google_Service_Sheets($client);
		// $spreadsheetId = "10v7vxW_ZMFsOoQIeK8EMMdNnKHwQ5raLNR84Hyat4Is";
		$spreadsheetId = $googleSheetId;
	
		$range = "Sheet1"; // Sheet name
		// Prepare headers and values
        $headers = array_keys($leadData);
        $values = array_values($leadData);

        // Get the existing headers from the sheet
        $response = $service->spreadsheets_values->get($spreadsheetId, $range);
        $existingHeaders = $response->getValues() ? $response->getValues()[0] : [];

        // If headers are not present, add them
        if (empty($existingHeaders)) {
            $headerBody = new Google_Service_Sheets_ValueRange([
                'values' => [$headers]
            ]);
            $service->spreadsheets_values->append(
                $spreadsheetId,
                $range,
                $headerBody,
                ['valueInputOption' => 'RAW']
            );
        }

        // Append values
        $valueBody = new Google_Service_Sheets_ValueRange([
            'values' => [$values]
        ]);
        $params = [
            'valueInputOption' => 'RAW'
        ];

        $result = $service->spreadsheets_values->append(
            $spreadsheetId,
            $range,
            $valueBody,
            $params
        );
	}

	
}

<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://www.tukutoi.com/
 * @since      1.0.0
 *
 * @package    Tkt_Fasterpress
 * @subpackage Tkt_Fasterpress/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Tkt_Fasterpress
 * @subpackage Tkt_Fasterpress/public
 * @author     TukuToi <hello@tukutoi.com>
 */
class Tkt_Fasterpress_Public {

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
	 * The Options of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      array    $options    	The Options of this plugin.
	 */
	private $options;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    		The version of this plugin.
	 * @param      array     $options    		The options of this plugin.
	 */
	public function __construct( $plugin_name, $version, $human_plugin_name ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
		$this->options = $this->get_options();
		$this->human_plugin_name = $human_plugin_name;

	}

	private function get_options(){

        $options = get_option( $this->plugin_name );
		
		//The plugin might not yet have saved any options at all
		if(!is_array($options)){
			$options = array();
		}
		
        $defaults = array(
            $this->plugin_name .'_style_handles_to_remove' 	=> array(),
            $this->plugin_name .'_script_handles_to_remove' => array(),
            $this->plugin_name .'_archives_to_exclude'		=> array(),
            $this->plugin_name .'_single_objects_to_exclude'=> array(),	
            $this->plugin_name .'_script_styles_log'		=> 0,
            $this->plugin_name .'_load_frontend_menu'		=> 0,
            $this->plugin_name .'_remove_emojys'			=> 0,
            $this->plugin_name .'_fe_scripts'				=> array(),
            $this->plugin_name .'_fe_scripts_reg'			=> array(),
            $this->plugin_name .'_fe_styles'				=> array(),
            $this->plugin_name .'_fe_styles_reg'			=> array(),
        );

        foreach ($defaults as $option => $default) {

            if( !array_key_exists($option, $options) ){

                $options[$option] = $default;
            }
        }
        
        return $options;

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
		 * defined in Tkt_Fasterpress_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Tkt_Fasterpress_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name .'-fe-styles', plugin_dir_url( __FILE__ ) . 'css/tkt_fasterpress-public.css', array(), $this->version, 'all' );

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
		 * defined in Tkt_Fasterpress_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Tkt_Fasterpress_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name .'-fe-scripts', plugin_dir_url( __FILE__ ) . 'js/tkt_fasterpress-public.js', array( 'jquery' ), $this->version, true );

	}

	/**
	 * Remove Scripts
	 *
	 * @since    1.0.0
	 * @access   public
	 * @todo There are hardcoded values here. Transform to settings.
	 */
	public function cleanup_scripts() {
	
		$scripts_to_remove = $this->options[$this->plugin_name .'_script_handles_to_remove'];
	    
		if ( !is_admin() ) {
		 	foreach( $scripts_to_remove as $script ) {
		 		/**
				 *  How to succesfully dequeue scripts and styles in WP without calling wp_deregister_style
		 		 * 	
		 		 *  also @see https://codex.wordpress.org/Plugin_API/Action_Reference
				 *  
				 *  1) The question of "when"
				 *		wp_enqueue_scripts 	When scripts and styles are enqueued.	[This works with a high priority on most cases but not wp-embed i.e.]
				 *		[...]
				 *		wp_head 	Used to print scripts or data in the head tag on the front end.		[Works only to remove scripts added with footer = true.]
				 *		wp_print_styles		Before styles in the $handles queue are printed. 	[Works for removing styles in all cases.]
				 *		wp_print_scripts	Before scripts in the $handles queue are printed. [Works only to remove scripts added with footer = false.]
				 *  	[...]
				 *  	wp_footer [too late, even if scripts are added to footer.]
				 *	
				 * 	2) The question of wether or
				 *  	We should use wp_head AND wp_print_scripts to remove scripts, and can use just wp_print_styles for styles.
				 * 
				 *  3) The question of what
				 *  	No wp_deregister_script() or wp_deregister_style() seems needed, when following the above. 
				 *  	A simple wp_dequeue_script() or wp_dequeue_style() will do.
				 *  
				 *  4) The Question of the Great HeadAche or also, why programs should not be too smart...
				 * 		If ANY script has ANY Dependency set, 
				 *		then removing the script set as Dependecy will NEVER work, 
				 * 		unless ALL scripts requiring the Dependency are also dequeued! 
				 * 		<!!!!> Classic case: jquery is set as $dep. Removing jquery wont work as long the script requiring it is loaded<!!!>
				 * 		<!!!!> The exact same is valid for CSS. For example, removing dashicons will only work if admin-bar style is removed too.<!!!>
				 * 
				 *  I hope I saved you some headache and this comment will compliment in your work... I guess I will need it myself often enough.
				 */
		 		wp_dequeue_script( $script );
			}
		}
	    
	}

	/**
	 * Remove Styles
	 *
	 * @since    1.0.0
	 * @access   public
	 */
	public function cleanup_styles() {

		$styles_to_remove = $this->options[$this->plugin_name .'_style_handles_to_remove'];
	    if ( !is_admin() ) {

	    	/**
	    	 * And for CSS it seems enough to hook into wp_print_styles
	    	 * Again here, the scripts loading a dependency must be disabled in order to dequeue the dependency!
			 */
	 		foreach( $styles_to_remove as $style ) {
		        wp_dequeue_style( $style );
			}

	    }

	}

	/**
	 * Remove WP Emojy scripts and styles
	 * Maybe disable Emojy in device.
	 *
	 * @since    1.0.0
	 * @access   public
	 */

	public function disable_emojis() {

		if($this->options[$this->plugin_name .'_remove_wp_emojis'] != 1)
			return;

		// Prevent Emoji from loading on the front-end
		remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
		remove_action( 'wp_print_styles', 'print_emoji_styles' );

		// Remove from admin area also
		remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
		remove_action( 'admin_print_styles', 'print_emoji_styles' );

		// Remove from RSS feeds also
		remove_filter( 'the_content_feed', 'wp_staticize_emoji');
		remove_filter( 'comment_text_rss', 'wp_staticize_emoji');

		// Remove from Embeds
		remove_filter( 'embed_head', 'print_emoji_detection_script' );

		// Remove from emails
		remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );

		// Disable from TinyMCE editor. Currently disabled in block editor by default
		add_filter( 'tiny_mce_plugins', array($this,'disable_emojis_tinymce') );

		// Remove emoji CDN hostname from DNS prefetching hints.
		add_filter( 'wp_resource_hints', array($this,'disable_emojis_remove_dns_prefetch'), 10, 2 );

		/** 
		 * We may want fast sites but we may not want to completely remove the fun.
		 */
		if($this->options[$this->plugin_name .'_disable_emojis'] != 1)
			return;
		
		add_filter( 'option_use_smilies', '__return_false' );

	}

	/**
	 * Filter function used to remove the tinymce emoji plugin.
	 * 
	 * @param   array 	$plugins  
	 * @return  array 	Difference betwen the two arrays
	 * @access 	private
	 */
	public function disable_emojis_tinymce( $plugins ) {

		if( is_array($plugins) ) {
			$plugins = array_diff( $plugins, array( 'wpemoji' ) );
		}
		return $plugins;

	}

	/**
	 * Remove emoji CDN hostname from DNS prefetching hints.
	 *
	 * @param  array  $urls          URLs to print for resource hints.
	 * @param  string $relation_type The relation type the URLs are printed for.
	 * @return array                 Difference betwen the two arrays.
	 * @access 	private
	 */
	public function disable_emojis_remove_dns_prefetch( $urls, $relation_type ) {

		if ( 'dns-prefetch' == $relation_type ) {

			$emoji_svg_url_bit = 'https://s.w.org/images/core/emoji/';
			
			foreach ( $urls as $key => $url ) {

				if ( strpos( $url, $emoji_svg_url_bit ) !== false ) {
					unset( $urls[$key] );
				}

			}

		}

		return $urls;

	}

	/**
	 * Maybe styles and scripts in head and footer.
	 *
	 * @todo maybe move this to menu modal.
	 * @access 	public
	 */
	public function maybe_log_scripts_and_styles(){

		if ( $this->options[$this->plugin_name .'_script_styles_log'] == 1 && current_user_can('administrator') ){
			add_action( 'wp_footer', array($this,'print_script_and_styles'),999);
			add_action( 'wp_footer', array($this, 'store_fe_styles_and_scripts_option'), 999);
		}

	}

	/**
	 * Registered styles and scripts Callback.
	 *
	 * @access 	public
	 */
	private function log_scripts_styles() {

		global $wp_scripts, $wp_styles;

	    $result = [];
	    $result['scripts'] = [];
	    $result['styles'] = [];

	    foreach( $wp_scripts->registered as $script_name => $script_instance ) :
	       $result['scripts'][] = '<code>'. $script_name . '</code> (<small><code>'. $script_instance->src .'</code></small>)';
	    endforeach;

	    foreach( $wp_styles->registered as $style_name => $style_instance ) :
	       $result['styles'][] = '<code>'. $style_name . '</code> (<small><code>'. $style_instance->src .'</code></small>)';
	    endforeach;

	    return $result;

	}

	/**
	 * Enqueued (done) styles and scripts Callback.
	 *
	 * @access 	private
	 */
	private function scripts_and_styles_done(){

		global $wp_scripts, $wp_styles;

		foreach( $wp_scripts->done as $script_name ) :
	       $result['scripts'][] =  $script_name;
	    endforeach;

	    foreach( $wp_scripts->registered as $script_name => $object ) :
	       $result['scripts_reg'][$script_name] =  $object;
	    endforeach;

	    foreach( $wp_styles->done as $style_name ) :
	       $result['styles'][] =  $style_name;
	    endforeach;

	    foreach( $wp_styles->registered as $style_name => $object ) :
	       $result['styles_reg'][$style_name] =  $object;
	    endforeach;

	    return $result;

	}

	/**
	 * Update option with scripts and stiles loaded in front end
	 *
	 * @access 	public
	 */
	public function store_fe_styles_and_scripts_option(){

		$this->options[$this->plugin_name .'_fe_scripts'] 		= $this->scripts_and_styles_done()['scripts'];
		$this->options[$this->plugin_name .'_fe_scripts_reg'] 	= $this->scripts_and_styles_done()['scripts_reg'];
		$this->options[$this->plugin_name .'_fe_styles'] 		= $this->scripts_and_styles_done()['styles'];
		$this->options[$this->plugin_name .'_fe_styles_reg'] 	= $this->scripts_and_styles_done()['styles_reg'];
		
		update_option( $this->plugin_name, $this->options);

	}

	/**
	 * Build list to print scripts and styles in Front end
	 *
	 * @access 	public
	 */
	public function print_script_and_styles() {

		$html = '<strong>All Registered Styles</strong><ol>';
		
		foreach ($this->log_scripts_styles()['scripts'] as $script) {
			$html .= '<li>'. $script .'</li>';
		}

		$html .= '</ol><strong>All Registerted Scripts</strong><ol>';
		
		foreach ($this->log_scripts_styles()['styles'] as $style) {
			$html .= '<li>'. $style .'</li>';
		}

		$html .= '</ol>';

		echo $html;

	}

	/**
     * Remove default things from header
     * @todo review this as it seems stupid to remove most of these things SEO related.
     * @since 1.0.0
     * @access private
     */
	public function theme_clean_header() {
        remove_action('wp_head', 'wp_generator');
        remove_action('wp_head', 'wlwmanifest_link');
        remove_action('wp_head', 'rest_output_link_wp_head');
        remove_action('wp_head', 'wp_resource_hints', 2);
        remove_action('wp_head', 'rsd_link');
        remove_action('wp_head', 'wp_shortlink_wp_head');
        remove_action('wp_head', 'wp_oembed_add_discovery_links');
        remove_action('wp_head', 'rel_canonical');
    }
    //add_action('init', 'theme_clean_header');

    /**
     * Add Front End Admin Bar Menu
     * @since 1.0.0
     * @access public
     */
	public function tkt_fasterpress_fe_admin_bar_menu ( WP_Admin_Bar $admin_bar ) {

	    if ( !current_user_can( 'administrator' ) || is_admin() || $this->options[$this->plugin_name .'_load_frontend_menu'] != 1 ) {
	        return;
	    }

	    $title = '';//add logo 16x16
		$title .= '<span class="ab-label">'. $this->human_plugin_name .'</span>';

	    $admin_bar->add_menu( array(
	        'id'    => $this->plugin_name .'_fe_admin_bar_menu',
	        'parent' => null,
	        'group'  => null,
	        'title' => $title, 
	        'href'  => '',
	        'meta' => [
	            'title' => __( $this->human_plugin_name, $this->plugin_name ), 
	        ]
	    ) );
	    $admin_bar->add_menu( array(
	        'id'    => $this->plugin_name .'_fe_styles_and_scripts',
	        'parent' => $this->plugin_name .'_fe_admin_bar_menu',
	        'group'  => null,
	        'title' => 'All Front End Styles and Scripts', 
	        'href'  => '/',
	        'meta' => [
	            'title' => __( 'Front End Styles and Scripts', $this->plugin_name ), 
	            'onclick'	=> 'event.preventDefault();document.getElementById("tkt_fe_styles_scripts_modal").style.display = "flex"',
	        ]
	    ) );

		?>
	    <div id="tkt_fe_styles_scripts_modal" class="tkt_modal">

		  <!-- Modal content -->
		  <div class="tkt_modal_wrap">
		    <div class="tkt_modal_header">
		    	<span class="tkt_modal_title"><?php echo $this->human_plugin_name . ' | All Front End Scripts and Styles' ?></span><span id="tkt_fe_styles_scripts_modal_close" class="tkt_modal_close">&times;</span>
		    </div>
		    <div class="tkt_modal_content">
		    	<strong>All Enqueued Styles:</strong>
		    	<ol>
			   	<?php 
			   	foreach($this->scripts_and_styles_done()['styles'] as $style){echo '<li>'. $style .'</li>';}; 
			   	?></ol><strong>All Enqueued Scripts:</strong><ol><?php
			   	foreach($this->scripts_and_styles_done()['scripts'] as $script){echo '<li>'. $script .'</li>';};
			   	?></ol><?php
			   	$this->print_script_and_styles();
			   	?>
		  </div>
		</div>

		</div>

	    <?php
	}

}

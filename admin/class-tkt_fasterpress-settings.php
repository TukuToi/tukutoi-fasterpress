<?php

/**
 * The settings of the plugin.
 *
 * @link       https://www.tukutoi.com/
 * @since      1.0.0
 *
 * @package    Tkt_Fasterpress
 * @subpackage Tkt_Fasterpress/admin
 */

/**
 * Class Tkt_Fasterpress_Admin_Settings
 *
 */
class Tkt_Fasterpress_Admin_Settings {

    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $plugin_name    The ID of this plugin.
     */
    private $plugin_name;

    /**
     * The Human Name of the plugin
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $human_plugin_name    The humanly readable plugin name
     */
    private $human_plugin_name;

    /**
     * The version of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $version    The current version of this plugin.
     */
    private $version;

    /**
     * TukuToi Common Code
     *
     * @since    1.0.0
     * @access   private
     * @var      TKT_Common    $common    TKT_Common instance.
     */
    private $common;

    

    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     * @param      string       $plugin_name       The name of this plugin.
     * @param      string       $version           The version of this plugin.
     * @param      string       $human_plugin_name The human name of this plugin.
     * @param      TKT_Common   $common            The TukuToi Common Code Class.
     */
    public function __construct( $plugin_name, $human_plugin_name, $version, $common ) {

        $this->plugin_name  = $plugin_name;
        $this->human_plugin_name = $human_plugin_name;
        $this->version      = $version;
        $this->common       = $common;
        $this->options      = $this->get_options();

    }



    /**
     * Provide a method get all options of this plugin
     * 
     * @since 1.0.0
     * @access private
     */
    private function get_options(){
		
        $options = get_option( $this->plugin_name );
		
		//The plugin might not yet have saved any options at all
		if(!is_array($options)){
			$options = array();
		}
		
        $defaults = array(
            $this->plugin_name .'_style_handles_to_remove'  => array(),
            $this->plugin_name .'_script_handles_to_remove' => array(),
            $this->plugin_name .'_archives_to_exclude'      => array(),
            $this->plugin_name .'_single_objects_to_exclude'=> array(), 
            $this->plugin_name .'_script_styles_log'        => 0,
            $this->plugin_name .'_load_frontend_menu'       => 0,
            $this->plugin_name .'_remove_emojys'            => 0,
            $this->plugin_name .'_disable_emojis'           => 0,
            $this->plugin_name .'_fe_scripts'               => array(),
            $this->plugin_name .'_fe_scripts_reg'           => array(),
            $this->plugin_name .'_fe_styles'                => array(),
            $this->plugin_name .'_fe_styles_reg'            => array(),
        );
	
        foreach ($defaults as $option => $default) {

            if( !array_key_exists($option, $options) ){

                $options[$option] = $default;
            }
        }
        
        return $options;

    }

    /**
     * Provide a method to ensure data we get from Options is as expected
     * 
     * @since 1.0.0
     * @param $option           string  the option suffix
     * @param $expected_output  mixed   the expected output type 
     * @access private
     */
    private function prepare_options($option, $expected_output){

        if( null !== $this->options[$this->plugin_name.$option] && !empty( $this->options[$this->plugin_name.$option] ) ){
            return  $this->options[$this->plugin_name.$option];
        }
        else{
            return $expected_output;
        }

        return '';

    }

    /**
     * Enqueue Styles in Settings page
     * (registered in Tkt_Fasterpress_Admin)
     * @since    1.0.0
     * @access   public
     */
    public function enqueue_styles() {

        wp_enqueue_style( $this->plugin_name . '-styles' );
        wp_enqueue_style( $this->plugin_name . '-select2' );

    }

    /**
     * Enqueue Styles in Settings page
     * (registered in Tkt_Fasterpress_Admin)
     * @since    1.0.0
     * @access   public
     */
    public function enqueue_scripts() {

        wp_enqueue_script($this->plugin_name . '-scripts');
        wp_enqueue_script($this->plugin_name . '-select2');

    }

    /**
     * Add Menu Page of this plugin
     *
     * @since 1.0.0
     * @access public
     */
    public function setup_plugin_menu() {

        $pages[] = add_submenu_page( 
            $this->common->get_common_name(), 
            $this->human_plugin_name, 
            'FasterPress', 
            'manage_options', 
            $this->plugin_name, 
            array($this,'render_settings_page_content'), 
            2 
        );

        foreach ($pages as $page) {
            add_action( "admin_print_styles-{$page}", array($this->common,'enqueue_styles') );
            add_action( "admin_print_styles-{$page}", array($this,'enqueue_styles') );
            add_action( "admin_print_scripts-{$page}", array($this,'enqueue_scripts') );
        }

    }

    /**
     * Render Settings Page
     *
     * @since 1.0.0
     * @access public
     */
    public function render_settings_page_content( $active_tab = '' ) {
        $this->common->set_render_settings_page_content($active_tab = '', $this->plugin_name, $this->plugin_name, $this->plugin_name);
    }

    /**
     * This Plugins Settings Options.
     *
     * @return array
     * @since 1.0.0
     * @access public
     */
    public function settings_options() {

        $options = array(
            $this->plugin_name .'_style_handles_to_remove'      => "All styles to remove",
            $this->plugin_name .'_script_handles_to_remove'     => "All scripts to remove",
            $this->plugin_name .'_archives_to_exclude'          => "Archives to exclude from optimization (Post or tax type slug)",
            $this->plugin_name .'_single_objects_to_exclude'    => "Pages or Posts or else single objects to exclude (Numeric ID)",
            $this->plugin_name .'_script_styles_log'            => "Log Scripts and Styles",
            $this->plugin_name .'_load_frontend_menu'           => "Load Frontend Admin Menu",
            $this->plugin_name .'_remove_wp_emojis'             => "Remove WP Emojy Scripts and Styles",
            $this->plugin_name .'_disable_emojis'               => "Remove Emojy even if Device supports it",
        );

        return $options;

    }

    /**
     * Provide Defaults for this Plugins Settings Options.
     *
     * @return array
     * @since 1.0.0
     * @access public
     */
    public function settings_options_defaults() {

        
        $defaults = array(
            $this->plugin_name .'_style_handles_to_remove'      => "",
            $this->plugin_name .'_script_handles_to_remove'     => "",
            $this->plugin_name .'_archives_to_exclude'          => "",
            $this->plugin_name .'_single_objects_to_exclude'    => "",
            $this->plugin_name .'_script_styles_log'            => 0,
            $this->plugin_name .'_load_frontend_menu'           => 0,
            $this->plugin_name .'_remove_wp_emojis'             => 1,
            $this->plugin_name .'_disable_emojis'               => 0,
        );

        return $defaults;

    }

    /**
     * Initialise all Option Settings
     *
     * @since 1.0.0
     * @access public
     */
    public function initialize_settings() {

        // If the options don't exist, create them.
        if( false == $this->options ) {
            $default_array = $this->settings_options_defaults();
            add_option( $this->plugin_name, $default_array );
        }

     
        // register a new section
        add_settings_section(
            $this->plugin_name,
            __( 'Speed Options', $this->plugin_name ),
            array( $this, 'general_options_callback'),
            $this->plugin_name
        );

        //Why create as many functions as there are options? Just use foreach($settings_options) to create each settings field
        foreach ($this->settings_options() as $option => $name) {
            add_settings_field(
                $option,
                __( $name, $this->plugin_name ),
                array($this, $option . '_cb'),
                $this->plugin_name,
                $this->plugin_name,
                [
                    'label_for' => $option,
                    'class' => $this->plugin_name .'_row',
                    $this->plugin_name .'_custom_data' => 'custom',
                ]
            );

        }

        register_setting( $this->plugin_name, $this->plugin_name );

    }

    public function preserve_options($value, $old_value, $option){
        if($option == $this->plugin_name ){
            $must_keep = [
                $this->plugin_name .'_fe_scripts' => $old_value[$this->plugin_name .'_fe_scripts'], 
                $this->plugin_name .'_fe_scripts_reg' => $old_value[$this->plugin_name .'_fe_scripts_reg'], 
                $this->plugin_name .'_fe_styles' => $old_value[$this->plugin_name .'_fe_styles'], 
                $this->plugin_name .'_fe_styles_reg' => $old_value[$this->plugin_name .'_fe_styles_reg'], 
            ];
            
            if( is_admin() )
                $value = array_merge($value,$must_keep);
        }
        return $value;
    }

    /**
     * General Options Callback API
     * @since 1.0.0
     * @access public
     */
    public function general_options_callback() {
        $this->common->set_general_options_callback('Control the Speed Options of ', get_bloginfo('name'), ' centrally in one place', $this->plugin_name);
    }

    public function tkt_fasterpress_archives_to_exclude_cb( $args ) {

        $post_archives          = get_post_types( array( 'public' => true, '_builtin' => false ), 'objects' );
        $tax_archives           = get_taxonomies( array( 'public' => true), 'objects' );
        $archives_to_exclude    = $this->prepare_options( '_archives_to_exclude', array() );
        $all_archives           = array_merge( $post_archives, $tax_archives );
        
        $select_options = array();
        $selected = '';
        ?>
        <span class="description"><?php _e( 'Add comma separated Archive Types to exclude from optimization', $this->plugin_name ); ?></span>
        <ul class="tkt-option-input <?php echo $this->plugin_name ?>-archives-list">
        
            <li>
                <select class="tkt-select2"  name="<?php echo $this->plugin_name ?>[<?php echo esc_attr( $args['label_for'] ); ?>][]" multiple="multiple" >
                <?php
                foreach($all_archives as $archive_name => $archive_object){
                    if( array_key_exists( $this->plugin_name .'_archives_to_exclude', $this->options ) )
                        $selected = in_array($archive_name, $archives_to_exclude) ? 'selected' : '';
                    echo '<option value="' . $archive_name . '"' . $selected . '>' . $archive_object->label . '</option>';
                }
                ?>
                </select>
            </li>
            
        </ul>
        <?php
    }

    public function tkt_fasterpress_script_handles_to_remove_cb( $args ) {
    
        global $wp_scripts;
        
        $scripts_loaded_fronted     = $this->prepare_options( '_fe_scripts', array() );
        $scripts_registered_fronted = $this->prepare_options( '_fe_scripts_reg', array() );
        $scripts_to_remove          = $this->prepare_options( '_script_handles_to_remove', array() );

        $scripts_registered_backend = $wp_scripts->registered;
        $scripts_loaded_backend     = $wp_scripts->done;
        $all_registered_scripts     = array_merge($scripts_registered_backend, $scripts_registered_fronted);

        $selected                   = '';
        $script_loaded_backend      = '';
        $script_loaded_frontend     = '';

        ?>
        <span class="description"><?php _e( 'Select scripts to remove. Be <strong>careful</strong> with this.<br>TukuToi FasterPress allows you to remove <em><strong>any script</strong></em> enqueued in your Website.<br> Dequeuing all of them will inevitably break some functionality of your Website!', $this->plugin_name ); ?></span>
        <ul class="tkt-option-input <?php echo $this->plugin_name ?>-scripts-list">
        
            <li>
                <select class="tkt-select2"  name="<?php echo $this->plugin_name ?>[<?php echo esc_attr( $args['label_for'] ); ?>][]" multiple="multiple" >
                <?php
                foreach($all_registered_scripts as $script_name => $script_instance){

                    $sep = '';

                    if( in_array( $script_name, $scripts_loaded_backend ) )
                        $script_loaded_backend  = $script_name;
                    if( in_array( $script_name, $scripts_loaded_fronted ) )
                        $script_loaded_frontend = $script_name;
                    if( array_key_exists( $this->plugin_name .'_script_handles_to_remove', $this->options ) )
                        $selected = in_array( $script_name, $scripts_to_remove ) ? 'selected' : '';

                    $script_loaded_backend  = $script_loaded_backend    == $script_name ? 'Loaded in Backend'   : '';
                    $script_loaded_frontend = $script_loaded_frontend   == $script_name ? 'Loaded in Frontend'  : '';

                    if (!empty($script_loaded_backend) && !empty($script_loaded_frontend))
                        $sep = ', ';

                    echo '<option loaded="'. $script_loaded_backend . $sep . $script_loaded_frontend .'" title="'. $script_instance->src .'" value="' . $script_name . '"' . $selected . '>' . $script_name . '</option>';

                }
                ?>
                </select>
            </li>
            
        </ul>
        <?php
    }


    public function tkt_fasterpress_style_handles_to_remove_cb( $args ) {
 
        global $wp_styles;
        
        $styles_loaded_fronted     = $this->prepare_options( '_fe_styles', array() );
        $styles_registered_fronted = $this->prepare_options( '_fe_styles_reg', array() );
        $styles_to_remove          = $this->prepare_options( '_style_handles_to_remove', array() );

        $styles_registered_backend = $wp_styles->registered;
        $styles_loaded_backend     = $wp_styles->done;
        $all_registered_styles    = array_merge($styles_registered_backend, $styles_registered_fronted);

        $selected               = '';
        $style_loaded_backend   = '';
        $style_loaded_frontend  = '';

        ?>
        <span class="description"><?php _e( 'Select styles to remove. Be <strong>careful</strong> with this.<br>TukuToi FasterPress allows you to remove <em><strong>any style</strong></em> enqueued in your Website.<br> Dequeuing all of them will inevitably destroy the look and layout of some parts of your Website!', $this->plugin_name ); ?></span>
        <ul class="tkt-option-input <?php echo $this->plugin_name ?>-styles-list">
        
            <li>
                <select class="tkt-select2" name="<?php echo $this->plugin_name ?>[<?php echo esc_attr( $args['label_for'] ); ?>][]" multiple="multiple" >
                <?php
                foreach($all_registered_styles as $style_name => $style_instance){

                    $sep = '';

                    if( in_array( $style_name, $styles_loaded_backend ) )
                        $style_loaded_backend  = $style_name;
                    if( in_array( $style_name, $styles_loaded_fronted ) )
                        $style_loaded_frontend = $style_name;
                    if( array_key_exists( $this->plugin_name .'_style_handles_to_remove', $this->options ) )
                        $selected = in_array( $style_name, $styles_to_remove ) ? 'selected' : '';

                    $style_loaded_backend  = $style_loaded_backend    == $style_name ? 'Loaded in Backend'   : '';
                    $style_loaded_frontend = $style_loaded_frontend   == $style_name ? 'Loaded in Frontend'  : '';

                    if (!empty($style_loaded_backend) && !empty($style_loaded_frontend))
                        $sep = ', ';

                    echo '<option loaded="'. $style_loaded_backend . $sep . $style_loaded_frontend .'" title="'. $style_instance->src .'" value="' . $style_name . '"' . $selected . '>' . $style_name . '</option>';

                }
                ?>
                </select>
            </li>
            
        </ul>
        <?php
    }

    function tkt_fasterpress_single_objects_to_exclude_cb( $args ) {
        // output the field
        ?><span class="description"><?php esc_html_e( 'Add post ID of each page, post or else single object that should be excluded from optimization', $this->plugin_name ); ?>
            </span>
            <input type="text" class="tkt-option-input" id="<?php echo esc_attr( $args['label_for'] ); ?>" data-custom="<?php echo esc_attr( $args[$this->plugin_name .'_custom_data'] ); ?>" name="<?php echo $this->plugin_name ?>[<?php echo esc_attr( $args['label_for'] ); ?>]" value="<?php echo $this->options[$this->plugin_name .'_single_objects_to_exclude'] ? $this->options[$this->plugin_name .'_single_objects_to_exclude'] : ''?>">     
        <?php   
    }

    function tkt_fasterpress_script_styles_log_cb( $args ) {

        // output the field
        ?><span class="description"><?php _e( 'Log loaded Scripts and Styles to the <code>wp_header</code> for setup and debugging purposes.<br>After activating this load any front end page and find the <code>body</code> tag; <br>somewhere just after it you will see a large HTML comment delimited with <code>&lt;!-- --&gt;</code> HTML comment tags. <br>Copy that and insert it in any browser or else tool capable to render HTML, removing the HTML comments. <br>It will display a numbered list of all Styles and Scripts registered in your site.', $this->plugin_name ); ?>
            </span>
            
            <input type='checkbox' name="<?php echo $this->plugin_name ?>[<?php echo esc_attr( $args['label_for'] ); ?>]"
            class="tkt-option-input" 
            id="<?php echo esc_attr( $args['label_for'] ); ?>" 
            data-custom="<?php echo esc_attr( $args[$this->plugin_name .'_custom_data'] ); ?>"
            value="1"
            <?php checked( $this->options[$this->plugin_name .'_script_styles_log'], 1 ); ?> />
        <?php
    }

    function tkt_fasterpress_remove_wp_emojis_cb( $args ) {

        // output the field
        ?><span class="description"><?php _e( 'This will dequeue all WordPress Emojy Styles and Scripts, however if the device suports Emojy then they will still display.', $this->plugin_name ); ?>
            </span>
            
            <input type='checkbox' name="<?php echo $this->plugin_name ?>[<?php echo esc_attr( $args['label_for'] ); ?>]"
            class="tkt-option-input" 
            id="<?php echo esc_attr( $args['label_for'] ); ?>" 
            data-custom="<?php echo esc_attr( $args[$this->plugin_name .'_custom_data'] ); ?>"
            value="1"
            <?php checked( $this->options[$this->plugin_name .'_remove_wp_emojis'], 1 ); ?> />
        <?php
    }

    function tkt_fasterpress_disable_emojis_cb( $args ) {

        // output the field
        ?><span id="if_remove_wp_emojis"><span class="description"><?php _e( 'Fully remove Emojy, even if the device supports it.', $this->plugin_name ); ?>
            </span>
            
            <input type='checkbox' name="<?php echo $this->plugin_name ?>[<?php echo esc_attr( $args['label_for'] ); ?>]"
            class="tkt-option-input" 
            id="<?php echo esc_attr( $args['label_for'] ); ?>" 
            data-custom="<?php echo esc_attr( $args[$this->plugin_name .'_custom_data'] ); ?>"
            value="1"
            <?php checked( $this->options[$this->plugin_name .'_disable_emojis'], 1 ); ?> />
        </span>
        <?php
    }

    function tkt_fasterpress_load_frontend_menu_cb( $args ) {
        // output the field
        ?><span class="description"><?php _e( 'Load TukuToi FasterPress (Frontend) Admin Menu for setup and debugging purposes', $this->plugin_name ); ?>
            </span>
            <input 
            type="checkbox" 
            class="tkt-option-input" 
            id="<?php echo esc_attr( $args['label_for'] ); ?>" 
            data-custom="<?php echo esc_attr( $args[$this->plugin_name .'_custom_data'] ); ?>" 
            name="<?php echo $this->plugin_name ?>[<?php echo esc_attr( $args['label_for'] ); ?>]" 
            value="1"
            <?php checked( $this->options[$this->plugin_name .'_load_frontend_menu'] , 1 ); ?> />
        <?php
    }


}

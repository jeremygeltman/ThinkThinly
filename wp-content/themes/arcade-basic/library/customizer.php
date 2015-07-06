<?php
/**
 * Set up the default theme options
 *
 * @since 1.0.0
 */
function bavotasan_default_theme_options() {
	//delete_option( 'theme_mods_arcade_basic' );
	return array(
		'arc' => 400,
		'arc_inner' => 400,
		'fittext' => '',
		'header_icon' => 'fa-heart',
		'width' => '1170',
		'layout' => 'right',
		'primary' => 'col-md-8',
		'display_author' => 'on',
		'display_date' => 'on',
		'display_comment_count' => 'on',
		'display_categories' => 'on',
	);
}

function bavotasan_theme_options() {
	$bavotasan_default_theme_options = bavotasan_default_theme_options();

	$return = array();
	foreach( $bavotasan_default_theme_options as $option => $value ) {
		$return[$option] = get_theme_mod( $option, $value );
	}

	return $return;
}

if ( class_exists( 'WP_Customize_Control' ) ) {
	class Bavotasan_Textarea_Control extends WP_Customize_Control {
	    public $type = 'textarea';

	    public function render_content() {
	        ?>
	        <label>
	        <span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
	        <textarea rows="5" class="custom-textarea" <?php $this->link(); ?>><?php echo esc_textarea( $this->value() ); ?></textarea>
	        </label>
	        <?php
	    }
	}

    class Bavotasan_Text_Description_Control extends WP_Customize_Control {
        public $description;

	    public function render_content() {
			?>
			<label>
				<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
                <input type="text" value="<?php echo esc_attr( $this->value() ); ?>" <?php $this->link(); ?> />
            </label>
            <p class="description more-top"><?php echo ( $this->description ); ?></p>
			<?php
        }
    }

	class Bavotasan_Icon_Select_Control extends WP_Customize_Control {
		public function render_content() {
			?>
			<div id="widgets-right" class="widget-content">
			<label class="customize-control-select"><span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
				<span class="custom-icon-container"><i class="fa <?php echo esc_attr( $this->value() ); ?>"></i></span>
				<input type="hidden" class="image-widget-custom-icon" value="<?php echo esc_attr( $this->value() ); ?>" <?php $this->link(); ?> />
				<a href="#" class="view-icons"><?php _e( 'View Icons', 'arcade' ); ?></a> | <a href="#" class="delete-icon"><?php _e( 'Remove Icon', 'arcade' ); ?></a>
				<?php bavotasan_font_awesome_icons( false ); ?>
			</label>
			</div>
			<?php
		}
	}

	class Bavotasan_Post_Layout_Control extends WP_Customize_Control {
	    public function render_content() {
			if ( empty( $this->choices ) )
				return;

			$name = '_customize-radio-' . $this->id;

			?>
			<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
			<?php
			foreach ( $this->choices as $value => $label ) :
				?>
				<label>
					<input type="radio" value="<?php echo esc_attr( $value ); ?>" name="<?php echo esc_attr( $name ); ?>" <?php $this->link(); checked( $this->value(), $value ); ?> />
					<?php
					echo '<div class="' . esc_attr( $value ) . '"></div>'; ?>
				</label>
				<?php
			endforeach;
			echo '<p class="description">' . __( 'The sidebar will not appear on the Post Block page template.', 'arcade' ) . '</p>';
	    }
	}
}

class Bavotasan_Customizer {
	public function __construct() {
		add_action( 'customize_register', array( $this, 'customize_register' ) );
		add_action( 'customize_controls_print_styles', array( $this, 'customize_controls_print_styles' ) );
	}

	public function customize_controls_print_styles() {
		wp_enqueue_script( 'bavotasan_image_widget', BAVOTASAN_THEME_URL . '/library/js/admin/image-widget.js', array( 'jquery' ), '', true );
		wp_enqueue_style( 'bavotasan_image_widget_css', BAVOTASAN_THEME_URL . '/library/css/admin/image-widget.css' );
		wp_enqueue_style( 'font_awesome', BAVOTASAN_THEME_URL .'/library/css/font-awesome.css', false, '4.3.0', 'all' );
	}

	/**
	 * Adds theme options to the Customizer screen
	 *
	 * This function is attached to the 'customize_register' action hook.
	 *
	 * @param	class $wp_customize
	 *
	 * @since 1.0.0
	 */
	public function customize_register( $wp_customize ) {
		$bavotasan_default_theme_options = bavotasan_theme_options();

		$wp_customize->add_setting( 'arc', array(
			'default' => $bavotasan_default_theme_options['arc'],
            'sanitize_callback' => 'absint',
		) );

		$wp_customize->add_control( new Bavotasan_Text_Description_Control( $wp_customize, 'arc', array(
			'label' => __( 'Arc Radius', 'arcade' ),
			'section' => 'title_tagline',
			'priority' => 10,
		) ) );

		$wp_customize->add_setting( 'arc_inner', array(
			'default' => $bavotasan_default_theme_options['arc_inner'],
            'sanitize_callback' => 'absint',
		) );

		$wp_customize->add_control( new Bavotasan_Text_Description_Control( $wp_customize, 'arc_inner', array(
			'label' => __( 'Arc Radius (Inner Pages)', 'arcade' ),
			'section' => 'title_tagline',
			'description' => __( 'The space and rotation for each letter will be calculated using the arc radius and the width of the site title. Leave blank for no arc.', 'arcade' ),
			'priority' => 20,
		) ) );

		$wp_customize->add_setting( 'fittext', array(
			'default' => $bavotasan_default_theme_options['fittext'],
            'sanitize_callback' => array( $this, 'sanitize_checkbox' ),
		) );

		$wp_customize->add_control( 'fittext', array(
			'label' => __( 'Use Fittext for long site title', 'arcade' ),
			'section' => 'title_tagline',
			'type' => 'checkbox',
			'priority' => 30,
		) );

		$wp_customize->add_setting( 'header_icon', array(
			'default' => $bavotasan_default_theme_options['header_icon'],
            'sanitize_callback' => 'esc_attr',
		) );

		$wp_customize->add_control( new Bavotasan_Icon_Select_Control( $wp_customize, 'header_icon', array(
			'label' => __( 'Header Icon', 'arcade' ),
			'section' => 'title_tagline',
			'priority' => 40,
		) ) );

		// Layout section panel
		$wp_customize->add_section( 'bavotasan_layout', array(
			'title' => __( 'Layout', 'arcade' ),
			'priority' => 35,
		) );

		$wp_customize->add_setting( 'width', array(
			'default' => $bavotasan_default_theme_options['width'],
            'sanitize_callback' => 'absint',
		) );

		$wp_customize->add_control( 'width', array(
			'label' => __( 'Site Width', 'arcade' ),
			'section' => 'bavotasan_layout',
			'priority' => 10,
			'type' => 'select',
			'choices' => array(
				'1170' => __( '1200px', 'arcade' ),
				'992' => __( '992px', 'arcade' ),
			),
		) );

		$choices =  array(
			'col-md-2' => '17%',
			'col-md-3' => '25%',
			'col-md-4' => '34%',
			'col-md-5' => '42%',
			'col-md-6' => '50%',
			'col-md-7' => '58%',
			'col-md-8' => '66%',
			'col-md-9' => '75%',
			'col-md-10' => '83%',
		);

		$wp_customize->add_setting( 'primary', array(
			'default' => $bavotasan_default_theme_options['primary'],
            'sanitize_callback' => 'esc_attr',
		) );

		$wp_customize->add_control( 'primary', array(
			'label' => __( 'Main Content Width', 'arcade' ),
			'section' => 'bavotasan_layout',
			'priority' => 15,
			'type' => 'select',
			'choices' => $choices,
		) );

		$wp_customize->add_setting( 'layout', array(
			'default' => $bavotasan_default_theme_options['layout'],
            'sanitize_callback' => 'esc_attr',
		) );

		$layout_choices = array(
			'left' => __( 'Left', 'arcade' ),
			'right' => __( 'Right', 'arcade' ),
		);

		$wp_customize->add_control( new Bavotasan_Post_Layout_Control( $wp_customize, 'layout', array(
			'label' => __( 'Sidebar Layout', 'arcade' ),
			'section' => 'bavotasan_layout',
			'size' => false,
			'priority' => 25,
			'choices' => $layout_choices,
		) ) );

		$colors = array(
			'default' => __( 'Default', 'arcade' ),
			'info' => __( 'Light Blue', 'arcade' ),
			'primary' => __( 'Blue', 'arcade' ),
			'danger' => __( 'Red', 'arcade' ),
			'warning' => __( 'Yellow', 'arcade' ),
			'success' => __( 'Green', 'arcade' ),
		);

		// Posts panel
		$wp_customize->add_section( 'bavotasan_posts', array(
			'title' => __( 'Posts', 'arcade' ),
			'priority' => 45,
			'description' => __( 'These options do not affect the Post Block page template.', 'arcade' ),
		) );

		$wp_customize->add_setting( 'display_author', array(
			'default' => $bavotasan_default_theme_options['display_author'],
            'sanitize_callback' => array( $this, 'sanitize_checkbox' ),
		) );

		$wp_customize->add_control( 'display_author', array(
			'label' => __( 'Display Author', 'arcade' ),
			'section' => 'bavotasan_posts',
			'type' => 'checkbox',
			'priority' => 10,
		) );

		$wp_customize->add_setting( 'display_date', array(
			'default' => $bavotasan_default_theme_options['display_date'],
            'sanitize_callback' => array( $this, 'sanitize_checkbox' ),
		) );

		$wp_customize->add_control( 'display_date', array(
			'label' => __( 'Display Date', 'arcade' ),
			'section' => 'bavotasan_posts',
			'type' => 'checkbox',
			'priority' => 20,
		) );

		$wp_customize->add_setting( 'display_categories', array(
			'default' => $bavotasan_default_theme_options['display_categories'],
            'sanitize_callback' => array( $this, 'sanitize_checkbox' ),
		) );

		$wp_customize->add_control( 'display_categories', array(
			'label' => __( 'Display Categories', 'arcade' ),
			'section' => 'bavotasan_posts',
			'type' => 'checkbox',
			'priority' => 30,
		) );

		$wp_customize->add_setting( 'display_comment_count', array(
			'default' => $bavotasan_default_theme_options['display_comment_count'],
            'sanitize_callback' => array( $this, 'sanitize_checkbox' ),
		) );

		$wp_customize->add_control( 'display_comment_count', array(
			'label' => __( 'Display Comment Count', 'arcade' ),
			'section' => 'bavotasan_posts',
			'type' => 'checkbox',
			'priority' => 40,
		) );
	}

	/**
	 * Sanitize checkbox options
	 *
	 * @since 1.0.2
	 */
    public function sanitize_checkbox( $value ) {
        if ( 'on' != $value )
            $value = false;

        return $value;
    }
}
$bavotasan_customizer = new Bavotasan_Customizer;
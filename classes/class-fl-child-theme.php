<?php

/**
 * Helper class for theme functions.
 *
 * @class FLChildTheme
 */

add_action( 'after_setup_theme', 'FLChildTheme::setup', 10 );

final class FLChildTheme {
	
	/**
     * @method setup
     */
    static public function setup() {
		
		// Temp
		add_action( 'wp_footer', 					__CLASS__ . '::hard_refresh', 10 );
		
		// Actions
		// add_action( 'after_setup_theme', 		__CLASS__ . '::add_fonts', 11 );
		// add_action( 'fl_head', 					__CLASS__ . '::add_typekit' );
		add_action( 'wp_enqueue_scripts', 			__CLASS__ . '::enqueue_scripts', 1000 );
		add_action( 'upload_mimes', 				__CLASS__ . '::add_file_types_to_uploads', 10, 1 );

		// Filters
		add_filter( 'style_loader_src', 			__CLASS__ . '::remove_version', 10, 1 );
		add_filter( 'script_loader_src', 			__CLASS__ . '::remove_version', 10, 1 );
		add_filter( 'wp_prepare_themes_for_js', 	__CLASS__ . '::theme_display_mods' );
	}
	
	/**
     * @method hard_refresh
     */
    static public function hard_refresh() {
		?>
		<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/js-cookie/2.2.0/js.cookie.min.js"></script>
		<script type="text/javascript">
		(function($){
			//give it a new name each time you need to do this
			var cookieName = 'refreshv1';
			var inOneMinute = new Date(new Date().getTime() + 1 * 60 * 1000);
			//get the cookie
			var c = Cookies.get(cookieName);
			//if it doesn't exist this is their first time and they need the refresh
			if (c == null) {
				//set cookie so this happens only once
				Cookies.set(cookieName, true, { expires: inOneMinute });
				//do a "hard refresh" of the page, clearing the cache
				location.reload(true);
			}
		})(jQuery);
		</script>
		<?php
	}
    
    /**
     * @method enqueue_scripts
     */
    static public function enqueue_scripts() {
		wp_enqueue_style( 'fl-child-styles', FL_CHILD_THEME_URL . '/style.css' );
	}
	
	/**
     * @method remove_version
     */
	static public function remove_version( $src ) {
		if ( strpos( $src, '?ver=' ) )
			$src = remove_query_arg( 'ver', $src );
		
		return $src;
	}
	
	/**
	 * @method theme_display_mods
	 */
	static public function theme_display_mods( $themes ) {
		if ( $themes['sbm-bb-theme'] ) {
			$logo_text = apply_filters( 'fl-logo-text', FLTheme::get_setting( 'fl-logo-text' ) );
			$logo_image = FLTheme::get_setting( 'fl-logo-image' );
			$theme_screenshot = $logo_image ? $logo_image : '//via.placeholder.com/732x550/ffffff/000000/?text=' . $logo_text . ' Theme';
			
			$themes['sbm-bb-theme']['name'] = get_bloginfo('name') . ' Theme';
			$themes['sbm-bb-theme']['screenshot'][0] = $theme_screenshot;
		}

		return $themes;
	}
	
	/**
	 * @method theme_display_mods
	 */
	static public function add_file_types_to_uploads( $file_types ) {
		$new_filetypes = array();
		$new_filetypes['svg'] = 'image/svg+xml';
		$file_types = array_merge( $file_types, $new_filetypes );
		
		return $file_types;
	}
	
	/**
     * @method add_typekit
     */
	 /*
	static public function add_typekit() {
		echo '<script src="https://use.typekit.net/exf6ikj.js"></script><script>try{Typekit.load({ async: true });}catch(e){}</script>';
	}
	*/
	
	/**
     * @method add_fonts
     */
	 /*
	static public function add_fonts() {
		if ( class_exists( 'FLBuilderFontFamilies' ) && class_exists( 'FLFontFamilies' ) ) {		
			$odudo = array(
				"fallback" => "sans-serif",
				"weights"  => array(
					"300",
					"400",
					"600",
					"700",
				)
			);
			
			FLBuilderFontFamilies::$system['Odudo Soft'] = $odudo;
			FLFontFamilies::$system['Odudo Soft'] = $odudo;
			
			$new_font = array(
				"fallback" => "serif",
				"weights"  => array(
					"300",
					"400",
					"600",
				)
			);
		}
	}
	*/
}
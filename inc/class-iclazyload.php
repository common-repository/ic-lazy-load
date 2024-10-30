<?php
/**
 * Main class
 * @since 1.0
 * @author ITclan BD
 */
 
if ( !class_exists( 'ITCLAN_Lazy_Load' ) ) {
	
	class ITCLAN_Lazy_Load {
		
		function __construct( $options = null ) {
			add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ), 1 );
			add_filter( 'script_loader_tag', array( $this, 'add_async_attribute' ), 10, 2 );
			
			add_filter( 'the_content', array( $this, 'modify_image_attributes' ), 99 );
			add_filter( 'post_thumbnail_html', array( $this, 'modify_image_attributes' ), 99 );
			add_filter( 'widget_text', array( $this, 'modify_image_attributes' ), 99 );
		}
		
		/**
		* Enqueue scripts
		*/
		public function enqueue_scripts() {
			$lazysizes_js_ver  = date("ymd-Gis", filemtime( plugin_dir_path( dirname(__FILE__) ) . 'assets/js/lazysizes.min.js' ));
			wp_enqueue_script( 
				'lazysizes',
				plugins_url( 'assets/js/lazysizes.min.js', dirname(__FILE__) ),
				array(),
				$lazysizes_js_ver,
				false
			);
			
			$inline_scripts = '
				(function(){
					window.lazySizesConfig = window.lazySizesConfig || {};
					lazySizesConfig.loadMode = 0; // 0, 1, 2, 3
				})();
			';
			wp_add_inline_script( 'lazysizes', $inline_scripts );
			
			// enqueue style
			$lazyload_css_ver = date("ymd-Gis", filemtime( plugin_dir_path( dirname(__FILE__) ) . 'assets/css/lazy-load.css' ));
			wp_enqueue_style( 
				'lazy-load',
				plugins_url( 'assets/css/lazy-load.css', dirname(__FILE__) ),
				array(),
				$lazyload_css_ver
			);
		}
		
		/**
		* Filters the HTML script tag
		*/
		public function add_async_attribute($tag, $handle) {
			if ( 'lazysizes' !== $handle )
				return $tag;
			return str_replace( ' src', ' async="async" src', $tag );
		}
		
		/**
		* Modify image attributes in content
		*/
		public function modify_image_attributes($content) {
			$content = mb_convert_encoding($content, 'HTML-ENTITIES', "UTF-8");
			$dom = new DOMDocument();
			@$dom->loadHTML($content);

			// image attributes change
			foreach ($dom->getElementsByTagName('img') as $node) {  
				$oldsrc = $node->getAttribute('src');
				$node->setAttribute("data-src", $oldsrc );
				$newsrc = plugins_url( 'assets/images/three-dots.svg', dirname(__FILE__) );
				$node->setAttribute("src", $newsrc);
				
				$oldsrcset = $node->getAttribute('srcset');
				$node->setAttribute('data-srcset', $oldsrcset );
				$newsrcset = '';
				$node->setAttribute('srcset', $newsrcset);
				
				$classes = $node->getAttribute('class');
				$newclasses = $classes . ' lazyload blur-up';
				$node->setAttribute('class', $newclasses);
				
				$node->setAttribute('data-sizes', 'auto');
			}
			// iframe attributes change
			foreach ($dom->getElementsByTagName('iframe') as $node) {  
				$oldsrc = $node->getAttribute('src');
				$node->setAttribute("data-src", $oldsrc );
				$newsrc = plugins_url( 'assets/images/three-dots.svg', dirname(__FILE__) );
				$node->setAttribute("src", $newsrc);
				
				$classes = $node->getAttribute('class');
				$newclasses = $classes . ' lazyload blur-up';
				$node->setAttribute('class', $newclasses);
			}
			$newHtml = preg_replace('/^<!DOCTYPE.+?>/', '', str_replace( array('<html>', '</html>', '<body>', '</body>'), array('', '', '', ''), $dom->saveHTML()));
			return $newHtml;
		}
	
	}

	$itclanlazyload = new ITCLAN_Lazy_Load();

}
<?php
/**
 * Plugin Name: scratchblocks for WP
 * Plugin URI:  http://ht79.info/
 * Description: 
 * Version:	 1.0.0
 * Author:	  Takashi Hosoya
 * Author URI:  http://ht79.info/
 * License:	 GPLv2
 * Text Domain: scratchblocks-for-wp
 * Domain Path: /languages
 */


define( 'SCRATCHBLOCKS_FOR_WP_URL',  plugins_url( '', __FILE__ ) );
define( 'SCRATCHBLOCKS_FOR_WP_PATH', dirname( __FILE__ ) );

$scratchblocks_for_wp = new Scratchblocks_For_WP();
$scratchblocks_for_wp->register();

class Scratchblocks_For_WP { 
	
	private $vertion = "";
	private $langs = "";
	
	function __construct()
	{ 
		$data = get_file_data( 
	   		__FILE__,
			array( 'ver' => 'Version', 'langs' => 'Domain Path' )
		);
		$this->version = $data['ver'];
		$this->langs = $data['langs'];
	}
	public function register(){
		add_action( 'plugins_loaded', array( $this, 'plugins_loaded' ) );
	}

	public function plugins_loaded( )
	{
		load_plugin_textdomain( 
			'scratchblocks-for-wp',
			false,
			dirname(  plugin_basename(  __FILE__ ) ).$this->langs
		);

		add_action( 'wp_enqueue_scripts', array( $this, 'wp_enqueue_scripts' ) );
		add_action( 'wp_head', array( $this, 'wp_head' ), 100 );
		add_shortcode( 'scratchblocks', array(  $this, 'scratchblocks' ) );
	}
	public function scratchblocks( $atts, $content = null  ) 
	{
    	extract( shortcode_atts( array( 
			'element' => 'pre',
		), $atts));

		$output = '<'.$element.' class="blocks">';
		$output .= $content;
		$output .= '</'.$element.'>';
		return $output;
	}
	public function wp_head( )
	{
    	echo "<script>\n";
    	echo "\tjQuery(document).ready( function( ) {\n";
		echo "\t\tscratchblocks2.parse('.blocks');\n";
		echo "\t});\n";
    	echo "</script>\n";
	}
	public function wp_enqueue_scripts( )
	{
		wp_enqueue_style( 
			'scratchblocks2-style',
		    plugins_url(  'lib/scratchblocks2.css', __FILE__ ),
			array( ),
			$this->version,
			'all'
		);

		wp_enqueue_script( 
			'scratchblocks2',
		    plugins_url(  'lib/scratchblocks2.js', __FILE__ ),
			array(  'jquery' ),
			$this->version,
			true
		);

	}

} 

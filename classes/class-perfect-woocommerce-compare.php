<?php
namespace Perfect_Woocommerce_Compare;
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

class Perfect_Woocommerce_Compare{

  const COOKIE_NAME = 'perfect_woocommerce_compare';

  function __construct(){
    add_action( 'plugins_loaded', array( $this, 'load_textdomain' ) );
    add_action( 'woocommerce_after_shop_loop_item', array( $this, 'compare_product_button' ) );
    add_shortcode( 'pwc-compare-product-button', array( '\Perfect_Woocommerce_Compare\Shortcodes\Compare_Product_Button', 'compare_product_button' ) );
    add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
    add_action( 'wp_ajax_pwc_add_product', array( $this, 'ajax_add_to_compare' ) );
    add_action( 'wp_ajax_nopriv_pwc_add_product', array( $this, 'ajax_add_to_compare' ) );
    add_action( 'wp_footer', function(){ echo self::get_template('compare-modal'); } );
  }

  public function enqueue_scripts(){
    wp_register_script( 'pwc-functions', PWC_PLUGIN . '/assets/js/functions.js', array('jquery'), PWC_PLUGIN_VERSION, true );
    $data_from_server = array( 'ajaxUrl' => admin_url('admin-ajax.php'), 'pluginUrl' => PWC_PLUGIN );
    wp_localize_script( 'pwc-functions', 'dataFromServer', $data_from_server );
    wp_enqueue_script( 'pwc-functions' );
    wp_enqueue_style( 'pwc-functions', PWC_PLUGIN . '/assets/css/styles.css', array(), PWC_PLUGIN_VERSION, 'all' );
  }

  public function ajax_add_to_compare(){
    $product_id       = (int)$_POST['product_id'];
    $current_user_id  = get_current_user_id();
    $result           = false;
    $current_products = array();

    if( get_post_status( $product_id ) == 'publish' ){

      if( is_user_logged_in() ){
        //if user is logged in save data as user meta
        $meta_val = get_user_meta( $current_user_id, self::COOKIE_NAME );
        $meta_val = $meta_val[0];
        if( !in_array( $product_id, $meta_val ) ){
          $meta_val[] = $product_id;
          update_user_meta( $current_user_id, self::COOKIE_NAME, $meta_val );
        }
        $current_products = $meta_val;
        $result = true;
      }else{
        //if user is not logged in save data in a cookie
        $cookie_val = ( isset( $_COOKIE[self::COOKIE_NAME] ) ) ? explode( ',', $_COOKIE[self::COOKIE_NAME] ) : array();
        if( !in_array( $product_id, $cookie_val ) ){
          $cookie_val[] = $product_id;
          $result = setcookie( self::COOKIE_NAME, implode( ',', $cookie_val ), time() + (86400 * 7), '/' ); // 86400 = 1 day
          $current_products = $cookie_val;
        }else{
          $result = true;
        }
      }

    }

    if( $result ){

      $modal_content_data = array( 'products' => $meta_val );
      $_pf = new \WC_Product_Factory();
      foreach( $current_products as $product_id ){
        $product_object = $_pf->get_product( $product_id );
        $modal_content_data['products'][$product_object->get_id()] = array(
          'title'         => $product_object->get_name(),
          'price'         => $product_object->get_price_html(),
          'image'         => get_the_post_thumbnail( $product_object->get_id(), 'shop_catalog' ),
          'availability'  => $product_object->get_availability(),
          'desc'          => $product_object->get_short_description(),
          'cats'          => $product_object->get_category_ids(),
        );
      }

      wp_send_json_success([
        'cookie' => $_COOKIE[self::COOKIE_NAME],
        'msg'    => sprintf(
          '<strong>%1$s</strong> %2$s %3$s',
          __( 'WELL!', 'perfect-wc-compare' ),
          __( 'The product was added to your', 'perfect-wc-compare' ),
          '<a href="#" rel="nofollow" class="pwc-compare-list-link">' . __( 'compare list', 'perfect-wc-compare' ) . '</a>'
        ),
        'modalContent' => self::get_template( 'compare-modal-content', '', $modal_content_data )
      ]);

    }else{
      wp_send_json_error([
        'cookie' => $_COOKIE[self::COOKIE_NAME],
        'msg'    => sprintf(
          '<strong>%1$s</strong> %2$s %3$s',
          __( 'OOPS!', 'perfect-wc-compare' ),
          __( 'The product was not added to your', 'perfect-wc-compare' ),
          '<a href="#" rel="nofollow" class="pwc-compare-list-link">' . __( 'compare list', 'perfect-wc-compare' ) . '</a>'
        )
      ]);
    }

    wp_die();
  }

  public function compare_product_button(){
    echo do_shortcode('[pwc-compare-product-button]');
  }

  public function load_textdomain(){
    load_plugin_textdomain( 'perfect-wc-compare', false, PWC_PLUGIN_PATH . '/lang' );
  }

  public static function get_template( $name, $folder = '', $data = array() ){
    ob_start();
    if( $folder ) $folder = $folder . '/';
    $template_file = dirname( __DIR__ ) . '/templates/' . $folder . $name . '.php';
    include $template_file;
    return ob_get_clean();
  }

}

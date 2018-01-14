<?php
namespace Perfect_Woocommerce_Compare\Shortcodes;
use Perfect_Woocommerce_Compare;
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

class Compare_Product_Button{

  public static function compare_product_button( $atts ) {
    $template_data = array(
      'compare_icon'  => apply_filters( 'pwc-compare-icon', __( 'Compare', 'perfect-wc-compare' ) )
    );
    echo \Perfect_Woocommerce_Compare\Perfect_Woocommerce_Compare::get_template(
      'compare-product-button',
      'shortcodes',
      $template_data
    );
  }

}

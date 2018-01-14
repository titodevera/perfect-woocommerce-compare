<?php
/*
Plugin Name: Perfect WooCommerce Compare
Description: Perfect WooCommerce Compare allows you to compare products
Version: 1.0.0
Author: Alberto de Vera Sevilla
Author URI: https://profiles.wordpress.org/titodevera/
Text Domain: perfect-wc-compare
Domain Path: /lang
License: GPL3

Perfect WooCommerce Compare version 1.0.0, Copyright (C) 2016 Alberto de Vera Sevilla

Perfect WooCommerce Compare is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

Perfect WooCommerce Compare is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with Perfect WooCommerce Compare.  If not, see <http://www.gnu.org/licenses/>.

*/
namespace Perfect_Woocommerce_Compare;
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

//plugin constants
define( 'PWC_PLUGIN', plugins_url( '', __FILE__ ) );
define( 'PWC_PLUGIN_PATH', dirname( __FILE__ ) );
define( 'PWC_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );
define( 'PWC_PLUGIN_VERSION', '1.0.0' );

include_once ABSPATH . 'wp-admin/includes/plugin.php';
if( is_plugin_active( 'woocommerce/woocommerce.php' ) ){

  require 'classes/class-perfect-woocommerce-compare.php';
  new Perfect_Woocommerce_Compare();
  require 'classes/shortcodes/class-compare-product-button.php';
  new Shortcodes\Compare_Product_Button();

}elseif( is_admin() ){

  add_action( 'admin_notices', function() {
    printf(
      '<div class="%1$s"><p>%2$s</p></div>',
      'notice notice-error',
      __( 'Perfect WooCommerce Compare needs WooCommerce to run. Please, install and active WooCommerce plugin.', 'perfect-wc-compare' )
    );
  });

}

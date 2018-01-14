<?php
/**
 * @version 1.0.0
 */
 namespace Perfect_Woocommerce_Compare\Templates;
 defined( 'ABSPATH' ) or die( 'No script kiddies please!' );
 extract( $data );
 global $product;
?>

<a href="#" class="pwc-compare-product-button" data-product-id="<?php echo $product->get_id();?>">
  <?php echo $compare_icon; ?>
</a>

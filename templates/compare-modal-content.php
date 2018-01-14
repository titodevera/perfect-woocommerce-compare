<?php
/**
 * @version 1.0.0
 */
 namespace Perfect_Woocommerce_Compare\Templates;
 defined( 'ABSPATH' ) or die( 'No script kiddies please!' );
 extract( $data );
?>

<?php foreach( $products as $product ): ?>

  <div class="pwc-product">
    <div><?php echo $product['title']; ?></div>
    <div><?php echo $product['price']; ?></div>
    <div><?php echo $product['image']; ?></div>
    <div><?php echo $product['availability']; ?></div>
    <div><?php echo $product['desc']; ?></div>
    <div><?php echo $product['cats']; ?></div>
  </div>

<?php endforeach; ?>

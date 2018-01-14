jQuery.noConflict();

jQuery(document).ready(function( $ ) {

  $('#pwc-compare-modal').on('click', function(e){
    e.preventDefault();
    if( e.target === this ){
      $(this).fadeOut();
    }
  });

  $('.pwc-compare-product-button').on( 'click', function(e){
    e.preventDefault();
    var $clickedButton = $(this);
    var $productLi = $(this).closest('li');

    var data = { 'action': 'pwc_add_product', 'product_id': $clickedButton.data('product-id') };
    $.post(dataFromServer.ajaxUrl, data, function(response) {

      if( response.success ) $clickedButton.addClass('pwc-compare-product-added');

      var $pwcMessage = $clickedButton.parent().find('.pwc-compare-product-msg');
      $pwcMessage.remove();
      $clickedButton.after('<span class="pwc-compare-product-msg">'+response.data.msg+'</span>');
      $pwcMessage = $clickedButton.parent().find('.pwc-compare-product-msg');//update reference
      ( response.success ) ? $pwcMessage.addClass('pwc-ok') : $pwcMessage.addClass('pwc-error');
      setTimeout(function () { $pwcMessage.remove(); }, 12000);

      $('.pwc-compare-list-link',$productLi).off('click');
      $productLi.on('click','.pwc-compare-list-link', function(e){
        e.preventDefault();
        $( "#pwc-compare-modal" ).show();

        $( "#pwc-compare-modal-inner" ).html( response.data.modalContent );
      });

    });

  });

});

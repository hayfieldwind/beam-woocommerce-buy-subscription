jQuery(document).ready(function($){

    $cart_item_link = $(".woocommerce-checkout .cart .product-name");

    $cart_item_link.on("click", function(e){
        
        $this = $(this);
        
        //disable link
        e.preventDefault();

        $this.append("<div>teste</div>");



    });

});
if (typeof dvizh == "undefined" || !dvizh) {
    var dvizh = {};
}
Array.prototype.diff = function(a) {
    return this.filter(function(i){return a.indexOf(i) < 0;});
};

dvizh.modificationconstruct = {
    dvizhShopUpdatePriceUrl: null,
    init: function() {
        $(document).on('change', '.product-add-modification-form .filters select', this.generateName);

        $(document).on("beforeChangeCartElementOptions", function(e, modelId) {
            dvizh.modificationconstruct.setModification(modelId);
        });
    },
    setModification: function(modelId) {
        var options = $('.dvizh-cart-buy-button'+modelId).data('options');
        var csrfToken = yii.getCsrfToken();
        $('.dvizh-shop-price-' + modelId).css('opacity', 0.3);
        jQuery.post(dvizh.modificationconstruct.dvizhShopUpdatePriceUrl, {options: options, productId: modelId,  _csrf : csrfToken},
            function (answer) {
                data = answer;
                if(data.modification && (data.modification.amount > 0 | data.modification.amount == null)) {
                    $('.dvizh-shop-price-' + modelId).html(data.modification.price);
                    $('.dvizh-cart-buy-button' + modelId).data('price', data.modification.price);
                } else {
                    $('.dvizh-shop-price-' + modelId).html(data.product_price);
                    $('.dvizh-cart-buy-button' + modelId).data('price', data.product_price);

                    alert("Данной модификации нет в наличии.");
                }
                $('.dvizh-shop-price-' + modelId).css('opacity', 1);

            }, "json");
    },
    generateName: function() {
        var name = '';
        $('.product-add-modification-form .filters select').each(function(i, el) {
            var val = $(this).find('option:selected').text();
            if(val) {
                name = name+' '+val;
            }
        });

        if(name != '') {
            $('#modification-name').val(name);
        }
    }
}

dvizh.modificationconstruct.init();

if (typeof dvizh == "undefined" || !dvizh) {
    var dvizh = {};
}
Array.prototype.diff = function(a) {
    return this.filter(function(i){return a.indexOf(i) < 0;});
};

dvizh.modificationconstruct = {
    modifications: null,
    init: function() {
        $(document).on('change', '.product-add-modification-form .filters select', this.generateName);
        
        $(document).on("beforeChangeCartElementOptions", function(e, options) {
            dvizh.modificationconstruct.setModification(options);
        });
    },
    setModification: function(options) {
        if(dvizh.modificationconstruct.modifications) {
            var cartOptions = options;
            $.each(dvizh.modificationconstruct.modifications, function(i, m) {
                var options = [];
                $.each(cartOptions, function(i, co) {
                    options.push(co);
                });

                var filter_value = $.makeArray(m.filter_value);

                if(options.length == filter_value.length) {
                    var result = options.diff(filter_value);
                    if(result.length == 0) {
                        if(m.price > 0) {
                            $('.dvizh-shop-price-'+m.product_id).html(m.price);
                            $(document).trigger("shopSetModification", m);
                        }
                    }
                }
            });
        }
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
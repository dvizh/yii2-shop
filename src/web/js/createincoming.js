if (typeof dvizh == "undefined" || !dvizh) {
    var dvizh = {};
}

dvizh.createincoming = {
    init: function() {
        $('.incoming-create .new-input').focus();
        $(document).on('change', ".incoming-create .new-input", this.findProduct);
        $(document).on('click', ".incoming-create input", function() { $(this).select(); });
        $(document).on('click', '.delete-incoming-row', this.deleteRow)
        $(document).on('keypress', ".incoming-create .new-input", function(e) {
            if(e.which == 13) {
                $(".incoming-create .new-input").change();
                return false;
            }
        });
    },
    shopPriceTypesArray: [],
    findProduct: function() {
        var input = $(this);
        productCode = $(this).val();

        if(productCode) {
            $(input).css('opacity', '0.2');
            $.post($(this).data('info-service'), {productCode: productCode},
                function(json) {
                    $(input).css('opacity', '1');
                    if(json.status == 'success') {
                        dvizh.createincoming.renderRow(json.id, json.name, json.code);
                    }
                    else {
                        alert(json.message);
                    }
                    $('.incoming-create .new-input').select();
                    
                }, "json");
        }
    },
    deleteRow: function() {
        $(this).parents('.incoming-row').remove();
        return false;
    },
    renderRow: function(id, name, code, prices) {
        if(!$('.hidden-incoming-product-id[value='+id+']').length) {
            var input = '<input class="hidden-incoming-product-id" type="hidden" value="'+id+'" />';
            var count = '<input type="text" name="element['+id+']" value="1" style="width: 30px;" />';
            
            var price = '';
            $(dvizh.createincoming.shopPriceTypesArray).each(function(i, el) {
                console.log(this);
                price += '<input type="text" name="price['+id+']['+this.id+']" value="" style="width: 80px;" title="'+this.name+'" placeholder="'+this.name+'" />';
            });
            
            if(code) {
                code = '('+code+')';
            }
            
            $('#incoming-list').append('<div class="row incoming-row"><div class="col-md-3">'+input+id+'. <strong>'+name+'</strong> '+code+'</div><div class="col-md-1"> '+count+' </div><div class="col-md-7"> '+price+' </div><div class="col-md-1"> <a href="#" class="delete-incoming-row" style="font-weight: bold; color: red;">X</a> </div></div>');
        }
        return true;
    }
}

dvizh.createincoming.init();
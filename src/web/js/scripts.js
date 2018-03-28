if (typeof dvizh == "undefined" || !dvizh) {
    var dvizh = {};
}

dvizh.shop = {
    priceTypeName: '[data-role=type-price]',
    namePrice: '[data-role=price-name]',
    priceFormBtn: '[data-role=price-from-btn]',
    priceForm: '[data-role=price-form]',
    linkMassUpdate: '[data-role=link-mass-update]',

    init: function() {
        $(document).on('change', 'table input:checkbox', this.checkSelectedRows);
        $(document).on('change', dvizh.shop.priceTypeName, this.appointName);
        $(document).on('click', dvizh.shop.priceFormBtn, this.displayPriceForm);
        $(document).on('click', '.dvizh-mass-delete', this.massDeletion);
        $(document).on('click', '.dvizh-mass-update-btn', this.sendInMassUpdate);
        $(document).on('click', '.cm-off', this.uncheckAllCheckboxes);
        $(document).on('click', '.cm-on', this.selectAllCheckboxes);
        $(document).on('click', '.notification-error', this.showNotification);
    },

    displayPriceForm: function () {
        $(dvizh.shop.priceForm).toggle();
    },
    appointName: function () {
        var self = this,
            priceTypeName = $(self).find('option:selected').text(),
            priceTypeValue = $(self).val();

        if(priceTypeValue) {
            $(dvizh.shop.namePrice).val(priceTypeName);
        } else {
            $(dvizh.shop.namePrice).val('');
        }

    },
    sendInMassUpdate: function () {
        var images = $('.dvizh-mass-edit-images:checked').val(),
            prices = $('.dvizh-mass-edit-prices:checked').val(),
            otherEntities = [],
            modelsId = [],
            attributes = [],
            filtersId = [],
            fieldsId = [];

        $('table input:checkbox:checked').each(function(){
            modelsId.push($(this).val());
        });
        $('.dvizh-mass-edit-filds input:checkbox:checked').each(function(){
            attributes.push($(this).val());
        });
        $('.dvizh-mass-edit-filters input:checkbox:checked').each(function(){
            filtersId.push($(this).val());
        });
        $('.dvizh-mass-edit-more-fields input:checkbox:checked').each(function(){
            fieldsId.push($(this).val());
        });

        if(images) {
            otherEntities.push(images);
        }
        if(prices) {
            otherEntities.push(prices);
        }

        var data = {
            modelsId: modelsId.join(','),
            attributes: attributes.join(','),
            filters: filtersId.join(','),
            fields: fieldsId.join(','),
            otherEntities: otherEntities.join(','),
        };
        var count = attributes.length + filtersId.length + fieldsId.length;

        if(count || images || prices) {
            $(dvizh.shop.linkMassUpdate).data('params', data).attr('data-params', data);
            $(dvizh.shop.linkMassUpdate).trigger('click');
        } else {
            dvizh.shop.showNotification();
        }


    },
    uncheckAllCheckboxes: function () {
        var type = $(this).data('type');
        $('.dvizh-mass-edit-'+type+' input:checkbox:checked').each(function(){
            $(this).prop("checked", false)
        });
    },
    selectAllCheckboxes: function () {
        var type = $(this).data('type');
        $('.dvizh-mass-edit-'+type+' input:checkbox').each(function(){
            $(this).prop("checked", true)
        });
    },
    checkSelectedRows: function () {
        var empty = true;
        $('table input:checkbox').each(function () {
            var checkbox = this;
            if ($(checkbox).prop("checked") === true) {
                empty = false;
                return false;
            }
        });

        if (empty === false) {
            $('.dvizh-mass-controls').removeClass( "disabled");
        } else {
            $('.dvizh-mass-controls').addClass( "disabled");
        }
    },
    massDeletion: function () {
        var model = $(this).data('model');
        var action = $(this).data('action');
        var modelId = [];
        var confirmation = confirm("Удалить выбранные элементы?");
        $('table input:checkbox:checked').each(function(){
            modelId.push($(this).val());
        });

        if(confirmation === true) {
            $.post({
                url: action,
                data: {modelId: modelId, model: model},
                success: function (response) {
                    if(response === true) {
                        location.reload();
                    }
                }
            });
        }
    },
    showNotification: function () {
        $('.notification-error').slideToggle("2000");
    },
};

dvizh.shop.init();

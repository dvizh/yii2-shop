if (typeof dvizh == "undefined" || !dvizh) {
    var dvizh = {};
}

dvizh.shop = {
    init: function() {
        $(document).on('change', 'table input:checkbox', this.checkSelectedRows);
        $(document).on('click', '.dvizh-mass-delete', this.massDeletion);
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
            $('.dvizh-mass-delete').prop( "disabled", false );

        } else {
            $('.dvizh-mass-delete').prop( "disabled", true );
        }
    },
    massDeletion: function () {
        var model = $(this).data('model');
        var modelId = [];
        var confirmation = confirm("Удалить выбранные элементы?");
        $('table input:checkbox:checked').each(function(){
            modelId.push($(this).val());
        });

        if(confirmation === true) {
            $.post({
                url: '/backend/web/shop/product/mass-deletion',
                data: {modelId: modelId, model: model},
                success: function (response) {
                    if(response === true) {
                        location.reload();
                    }
                }
            });
        }
    },
};

dvizh.shop.init();

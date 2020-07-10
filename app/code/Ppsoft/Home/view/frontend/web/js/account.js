require(
    [
        'jquery',
        'Magento_Ui/js/modal/modal'
    ],
    function(
        $,
        modal
    ) {
        var options = {
            title: "¿ Cómo pagar mi cuota ?",
            type: 'popup',
            responsive: true,
            innerScroll: true,
            modalClass: 'account-popup',
            buttons:[]
        };

        var popup = modal(options, $('#popup-modal'));
        $("#clickme").on('click',function(){
            $("#popup-modal").modal("openModal");
            $("#popup-modal").show();
        });
    }
);
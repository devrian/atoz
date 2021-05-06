(function ($) {

    'use strict'

    function initSearchOrder() {
        const list = $('.lists')
        const selector = $("#search")
        const routeFetch = $("#routeFetch").val()

        selector.on("keyup", (e) => {
            let request = {}
            let value = e.target.value
            if (value !== "") request.order_no = value

            $.ajax({
                type: "GET",
                url: routeFetch,
                data: request,
                success: ((response) => list.html(response))
            })
        })
    }


    function init() {
        initSearchOrder()
    }

    init()

})(jQuery)


function HiPanelChart(id, options = {}, autoload = false) {
    const element = $(`.` + id);
    const requestOptions = options;

    function getAjaxOptions(event) {
        if (event) {
            event.preventDefault();
        }

        return $.extend(true, {
            url: element.attr('action'),
            data: element.serializeArray(),
            type: 'POST',
            success: function (html) {
                const chartWrapper = $(`.` + id + `-chart-wrapper`);
                chartWrapper.closest('.box').find('.box-body').html(html);
            }
        }, requestOptions);
    }

    function drawChart(event) {
        $.ajax(getAjaxOptions(event));
    }

    function bindEvents() {
        element.on('change.updateChart', drawChart);
    }

    this.init = function () {
        bindEvents();

        if (autoload) {
            drawChart();
        }
    };
}

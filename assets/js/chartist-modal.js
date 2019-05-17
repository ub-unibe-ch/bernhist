const $ = require('jquery');
require('bootstrap');
const Chartist = require('chartist');

let chart;
let chartistModal = $('.chartist-modal');

chartistModal.on('shown.bs.modal', function() {
    $('.chartist').each(function () {
        let url = $(this).data('url');

        $.get(url, function (data) {
            let max = null;

            for (let i = 0; i < data.series[0].length; i++) {
                let value = data.series[0][i];
                if (value !== null && (max === null || max < value)) {
                    max = value;
                }
            }

            let kDigits = 0;
            if (max < 10000) {
                kDigits = 2;
            } else if (max < 100000) {
                kDigits = 1;
            }


            let numLabels = data.labels.length;

            let options = {
                lineSmooth: Chartist.Interpolation.none({
                    fillHoles: true,
                }),
                axisY: {
                    labelInterpolationFnc: function formatLabel(value) {
                        if (value < 1000) {
                            return value;
                        }

                        if (value < 1000000) {
                            return (value / 1000).toFixed(kDigits) + ' k';
                        }

                        return (value / 1000000).toFixed(1) + ' M';
                    }
                },
                axisX: {
                    labelInterpolationFnc: function skipLabels(value, index) {
                        return index % Math.round(numLabels / 20) === 0 || numLabels < 21 ? value : null;
                    }
                }
            };

            let responsiveOptions = [
                ['screen and (min-width: 768px) and (max-width: 991px)', {
                    showPoint: false,
                    axisX: {
                        labelInterpolationFnc: function skipLabels(value, index) {
                            // Will return Mon, Tue, Wed etc. on medium screens
                            return index % Math.round(numLabels / 15) === 0 || numLabels < 16 ? value : null;
                        }
                    }
                }],
                ['screen and (min-width: 576px) and (max-width: 767px)', {
                    showPoint: false,
                    axisX: {
                        labelInterpolationFnc: function (value, index) {
                            // Will return Mon, Tue, Wed etc. on medium screens
                            return index % Math.round(numLabels / 10) === 0 || numLabels < 11 ? value : null;
                        }
                    }
                }],
                ['screen and (max-width: 575px)', {
                    showLine: false,
                    axisX: {
                        labelInterpolationFnc: function (value, index) {
                            return index % Math.round(numLabels / 5) === 0 || numLabels < 6 ? value : null;
                        }
                    }
                }]
            ];

            chart = new Chartist.Line('.chartist', data, options, responsiveOptions);

            let seq = 0,
                delays = 25,
                durations = 250;
            chart.on('created', function() {
                seq = 0;
            });

            chart.on('draw', function(data) {

                if(data.type === 'line') {
                    seq++;
                    data.element.animate({
                        opacity: {
                            begin: seq * delays + 100,
                            dur: durations + 250,
                            from: 0,
                            to: 1
                        }
                    });
                } else if(data.type === 'point') {
                    seq++;
                    data.element.animate({
                        x1: {
                            begin: seq * delays,
                            dur: durations,
                            from: data.x - 10,
                            to: data.x,
                            easing: 'easeOutQuart'
                        },
                        x2: {
                            begin: seq * delays,
                            dur: durations,
                            from: data.x - 10,
                            to: data.x,
                            easing: 'easeOutQuart'
                        },
                        opacity: {
                            begin: seq * delays,
                            dur: durations,
                            from: 0,
                            to: 1,
                            easing: 'easeOutQuart'
                        }
                    });
                }
            });
        });
    });
});

chartistModal.on('hidden.bs.modal', function(){
    chart.detach();
    $(this).find('.chartist').html('');
});
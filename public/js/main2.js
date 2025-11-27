$(document).ready(function(){
    var containerWidth = $("#container").width()-60;
    $('.slider-input').jRange({
        from: 1,
        to: 7,
        step: .1,
        scale: [1, 2, 3, 4, 5, 6, 7],
        format: '%s',
        width: containerWidth,
        showLabels: true,
        snap: true,
        theme: "theme-blue"
    });
});
// Time Range Slider
$('#day').click(function()
{
    var containerWidth = $("#container").width()-60;

    $('.slider-input').jRange('setValue','1');
    $('.slider-input').removeData('plugin_jRange');
    $('.slider-input').next().remove();
    $('.slider-input').jRange({
        from: 1,
        to: 7,
        step: .1,
        scale: [1, 2, 3, 4, 5, 6, 7],
        format: '%s',
        width: containerWidth,
        showLabels: true,
        snap: true,
        theme: "theme-blue"
    });
});

$('#month').click(function() {
    var containerWidth = $("#container").width()-60;

    $('.slider-input').jRange('setValue','1');
    $('.slider-input').removeData('plugin_jRange');
    $('.slider-input').next().remove();
    $('.slider-input').jRange({
        from: 1,
        to: 12,
        step: .1,
        scale: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12],
        format: '%s',
        width: containerWidth,
        showLabels: true,
        snap: true,
        theme: "theme-blue"
    });

});

$('#week').click(function() {
    var containerWidth = $("#container").width()-60;

    $('.slider-input').jRange('setValue','1');
    $('.slider-input').removeData('plugin_jRange');
    $('.slider-input').next().remove();
    $('.slider-input').jRange({
        from: 1,
        to: 4,
        step: .1,
        scale: [1, 2, 3, 4],
        format: '%s',
        width: containerWidth,
        showLabels: true,
        snap: true,
        theme: "theme-blue"
    });

});

$('#year').click(function() {
    var containerWidth = $("#container").width()-60;

    $('.slider-input').jRange('setValue','1');
    $('.slider-input').removeData('plugin_jRange');
    $('.slider-input').next().remove();
    $('.slider-input').jRange({
        from: 1,
        to: 5,
        step: .1,
        scale: [1, 2, 3, 4, 5],
        format: '%s',
        width: containerWidth,
        showLabels: true,
        snap: true,
        theme: "theme-blue"
    });

});

var startDate;
var endDate;

$('.calender-btn').daterangepicker({
        autoApply: true,
        autoUpdateInput: false,
        maxDate: moment().format('MM/DD/YYYY'),
        locale:{
            cancelLabel: 'Clear'
        }
});






var canvasWidth = $('.col-md-8').width();
$("#myChart").attr("width" ,canvasWidth);

// Chart Global Configuration

// Data
var data = {
    labels: ["January", "February", "March", "April", "May", "June", "July"],
    datasets: [
        {
            label: "My First dataset",
            fillColor: "rgba(46, 204, 113,0.2)",
            strokeColor: "rgba(46, 204, 113,1)",
            pointColor: "rgba(46, 204, 113,1)",
            pointStrokeColor: "#fff",
            pointHighlightFill: "#fff",
            pointHighlightStroke: "rgba(46, 204, 113,1)",
            data: [65, 59, 80, 81, 56, 55, 40]
        },
    ]
};

// Initialize Chart


$( '.chosen--select' ).chosen();
$( '.chosen--select--responses' ).chosen({disable_search_threshold: 10});




$(".dropdown-menu li a").click(function(){
  $(this).parents(".dropdown").find('.btn').html($(this).text());
  $(this).parents(".dropdown").find('.btn').val($(this).data('value'));
});


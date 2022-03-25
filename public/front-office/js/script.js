jQuery(document).ready(function () {
    var searchRequest = null;
    $("#searchExcursion").keyup(function () {
        var minlength = 0;
        var that = this;
        var value = $(this).val();
        var entitySelector = $("#entitiesNavE").html('');
        if (value.length >= minlength) {
            if (searchRequest != null)
                searchRequest.abort();
            searchRequest = $.ajax({
                type: "POST",
                url: "/frontrechercheE",
                data: {
                    'q': value
                },
                dataType: "text",
                success: function (msg) { // we need to check if the value is the same
                    if (value == $(that).val()) {
                        var result = JSON.parse(msg);
                        $.each(result, function (key, arr) {
                            $.each(arr, function (id, value) {
                                if (id != 'error') {
                                    var div ='' +
                                        '<div class="col-md-4 ftco-animate fadeInUp ftco-animated">'+
                                        '<div class="destination">'+
                                        '<a href="excursion/' + value[0] + '" class="img img-2 d-flex justify-content-center align-items-center" style="background-image: url(' + value[5] + ' );">'+
                                        '<div class="icon d-flex justify-content-center align-items-center">'+
                                        '<span class="icon-search2"></span>'+
                                        '</div>'+
                                        '</a>'+
                                        '<div class="text p-3">'+
                                        '<div class="d-flex">'+
                                        '<div class="one">'+
                                        '<h3>'+
                                        '<a href="excursion/' + value[0] + '' + value[0] + '">' + value[1] + ' </a>'+
                                        '</h3>'+
                                        '</div>'+
                                        '<div class="two">' +
                                        '<span class="price">' + value[3] + ' DT</span>' +
                                        '</div>'+
                                        '</div>'+
                                        '<p>' + value[2] + ' </p>'+
                                        '<p className="days"><span>' + value[6] + '</span></p>'+
                                        '<hr>'+
                                        '<p class="bottom-area d-flex">'+
                                        '<span>'+
                                        '<i class="icon-map-o"></i>'+
                                        value[7] + ' </span>'+
                                        '<span class="ml-auto">'+
                                        '<a href="excursion/' + value[0] + '">Découvrir</a>'+
                                        '</span>'+
                                        '</p>'
                                        '</div>'+
                                        '</div>'+
                                        '</div>';
                                    entitySelector.append(div)
                                } else {
                                    entitySelector.append('<li style="color:red;">' + value + '</li>');
                                }
                            });
                        });
                    }
                }
            });
        }
    });
});

//filtre ville
$(function(){
    // bind change event to select
    $('#dynamic_select').on('change', function () {
        var ville = $(this).val()
        var minlength = 0;
        var that = this;
        var value = $(this).val();
        var entitySelector = $("#entitiesNavE").html('');
        $.ajax({
            type: "POST",
            url: "/dynamic_select_ville_excursion",
            data: {
                'ville': ville
            },
            dataType: "text",
            success: function(response) {
                console.log(response)
                if (value == $(that).val()) {
                    var result = JSON.parse(response);
                    $.each(result, function (key, arr) {
                        $.each(arr, function (id, value) {
                            if (id != 'error') {
                                var div ='' +
                                    '<div class="col-md-4 ftco-animate fadeInUp ftco-animated">'+
                                    '<div class="destination">'+
                                    '<a href="excursion/' + value[0] + '" class="img img-2 d-flex justify-content-center align-items-center" style="background-image: url(' + value[5] + ' );">'+
                                    '<div class="icon d-flex justify-content-center align-items-center">'+
                                    '<span class="icon-search2"></span>'+
                                    '</div>'+
                                    '</a>'+
                                    '<div class="text p-3">'+
                                    '<div class="d-flex">'+
                                    '<div class="one">'+
                                    '<h3>'+
                                    '<a href="excursion/' + value[0] + '' + value[0] + '">' + value[1] + ' </a>'+
                                    '</h3>'+
                                    '</div>'+
                                    '<div class="two">' +
                                    '<span class="price">' + value[3] + ' DT</span>' +
                                    '</div>'+
                                    '</div>'+
                                    '<p>' + value[2] + ' </p>'+
                                    '<p className="days"><span>' + value[6] + '</span></p>'+
                                    '<hr>'+
                                    '<p class="bottom-area d-flex">'+
                                    '<span>'+
                                    '<i class="icon-map-o"></i>'+
                                    value[7] + ' </span>'+
                                    '<span class="ml-auto">'+
                                    '<a href="excursion/' + value[0] + '">Découvrir</a>'+
                                    '</span>'+
                                    '</p>'
                                '</div>'+
                                '</div>'+
                                '</div>';
                                entitySelector.append(div)
                            } else {
                                entitySelector.append('<li style="color:red;">' + value + '</li>');
                            }
                        });
                    });
                }
            }
        });

        // var url = "dynamic_select_ville/"+$(this).val(); // get selected value
        // if (url) { // require a URL
        //     window.location = url; // redirect
        // }
        // return false;
    });
});
$(function(){
    // bind change event to select
    $('#dynamic_select_cat_excursion').on('change', function () {
        var url = $(this).val(); // get selected value
        if (url) { // require a URL
            window.location = url; // redirect
        }
        return false;
    });
    $('#dynamic_select_ville_excursion').on('change', function () {
        var url = $(this).val(); // get selected value
        if (url) { // require a URL
            window.location = url; // redirect
        }
        return false;
    });
});
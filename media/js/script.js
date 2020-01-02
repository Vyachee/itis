let thisLocation = window.location.href;

function getReport(reportNumber) {
    return thisLocation + 'createReport/' + reportNumber + '.php';
}


$.ajax({ 
    type: 'GET', 
    url: thisLocation + "handler/isParsed.php", 
    dataType: 'json',
    success: function (data) { 
        if(data.status != 'true') {
            $('#report').hide();
            $('#generate').show();
        }
    }
});


// REPORT 1
$.ajax({ 
    type: 'GET', 
    url: getReport(1), 
    data: { get_param: 'value' }, 
    dataType: 'json',
    success: function (data) { 
        let i = 0;
        $.each(data, function(index, element) {
            if(i == 0) {
                $('#topOfCountries').append('<li class="list-group-item text-dark" title="Больше всего">' + element.country + '<div class="badge bg-dark text-white float-right mt-1" title="Количество">' + element.count + '</div></li>');
            }   else {
                $('#topOfCountries').append('<li class="list-group-item bg-secondary">' + element.country + '<div class="badge bg-dark text-white float-right mt-1" title="Количество">' + element.count + '</div></li>');
            }
            i++;
        })
    }
});


// GETTING CATEGORIES AND REPORT 2


$.ajax({ 
    type: 'GET', 
    url: thisLocation + "handler/getCategories.php", 
    data: { get_param: 'value' }, 
    dataType: 'json',
    success: function (data) { 
        $.each(data, function(index, element) {
            $('#selectCat').append('<option value="'+ element +'">'+ element +'</option>');
        })
    }
});


$("#selectCat").change(function() {

    $.ajax({ 
        type: 'GET', 
        url: getReport(2) + "?categorie=" + $('#selectCat').val(),
        dataType: 'json',
        success: function (data) { 
            $("#topOfCategories").empty();

            let i = 0;
            $.each(data.countries, function(index, element) {
                if(i == 0) {
                    $('#topOfCategories').append('<li class="list-group-item text-dark" title="Чаще всего">' + element.country + '<div class="badge bg-dark text-white float-right mt-1" title="Количество">' + element.count + '</div></li>');
                }   else {
                    $('#topOfCategories').append('<li class="list-group-item bg-secondary">' + element.country + '<div class="badge bg-dark text-white float-right mt-1" title="Количество">' + element.count + '</div></li>');
                }

                i++;
            })
        }
    });

});

// REPORT 4

$.ajax({ 
    type: 'GET', 
    url: getReport(4), 
    data: { get_param: 'value' }, 
    dataType: 'json',
    success: function (data) { 
        $('#load').text(data.average);
    }
});

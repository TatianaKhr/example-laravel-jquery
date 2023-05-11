export function getFilters() {
    let filters = {};

    if ($('#keyword').val()) {
        let keyword = $('#keyword').val().match(/[\wа-я]+/ig);
        filters['keyword'] = keyword[0];
    }

    if ($('#min-price').val() !== '' || $('#max-price').val() !== '') {
        filters['price'] = [$('#min-price').val(), $('#max-price').val()].toString();
    }

    if ($('.search_tag option:selected').length > 0) {
        let tagsId = [];

        $('.search_tag option:selected').each(function (i) {
            tagsId.push($(this).val());
            $('.search_tag')[0].sumo.unSelectItem(i);
        });

        filters['tagsId'] = tagsId;
    }

    return filters;
}

export function getUrlParams() {
    let urlParams = new URLSearchParams(window.location.search);
    let filtersUrl = {};

    for (let [key, value] of urlParams) {
        if (key === 'keyword') {
            value = value.match(/^[a-zA-Z]*$/);
            value = value[0];
        } else if (key === 'price') {
            value = value.replace(/[^0-9.,]/g, '');
            value = value.split(",");
            value[0] = value[0].replace(/^0+/, '');
            value[1] = value[1].replace(/^0+/, '');
            value = value.join(',');
        } else if (key === 'tagsId') {
            value = value.replace(/[^0-9.,]/g, '');
        }

        filtersUrl[key] = value;
    }

    $.each(filtersUrl, function (key, value) {
        if (value === "" || value === "," | value === null) {
            delete filtersUrl[key];
        }
    });

    $.each(filtersUrl, function (key, value) {
        if (key === 'tagsId') {
            $('.search_tag').SumoSelect().sumo.selectItem(value.toString());
        } else if (key === 'keyword') {
            $('#keyword').val(value);
        } else if (key === 'price') {
            let price = value.split(",");
            $('#min-price').val(price[0]);
            $('#max-price').val(price[1]);
        }
    })

    return filtersUrl;
}

export function updateUrl(filters) {
    let urlFilter = "";
    let index = 1;
    let filterLength = Object.keys(filters).length;

    $.each(filters, function (key, value) {
        if (filterLength > index) {
            value += '&';
        }

        urlFilter += key + "=" + value;
        index++;
    });

    let url =$(location).attr('href').split('?')[0];
    window.history.replaceState(null, null, url + "?" + urlFilter);
}

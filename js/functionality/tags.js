import {errorHandler} from '/js/components/functionality/common.js';

export function tags({search = false, select = false, data = {}, dishesTags = {}}) {
    $.ajax({
        url: `/catalog/dish/tags`,
        method: 'get',
        dataType: 'json',
        data: data,
        success(data) {
            search === true ? showSearchTags(data['data']) : select === true ? showSelectTags(data['data']) : dishesTags.length > 0 ? showDishesTags({
                data: data['data'], dishesTags: dishesTags
            }) : null
        },
        error(data) {
            errorHandler()
        }
    });
}

function showSearchTags(data) {
    $.each(data, function (index, item) {
        $('.search_tag')
            .append($("<option></option>")
                .attr("value", item['id'])
                .attr("id", item['id'])
                .text(item['title']));
    })

    $('.search_tag').SumoSelect().sumo?.reload();
}

function showSelectTags(data) {
    $.each(data, function (i, item) {
        $('.select-tags').append($('<option>', {
            value: item['id'], text: item['title'],
        }));
    });
}

function showDishesTags({data, dishesTags}) {
    let tagsArray = dishesTags.map(function (tag) {
        return tag.id;
    });

    $.each(data, function (i, item) {
        if (tagsArray.indexOf(item['id']) !== -1) {
            $('.select-tags').append($('<option>', {
                value: item['id'], text: item['title'], selected: true
            }));
        } else {
            $('.select-tags').append($('<option>', {
                value: item['id'], text: item['title'], selected: false
            }));
        }
    });
}

import {checkImages, constant, errorHandler} from '/js/components/functionality/common.js';

const {userId} = constant()

function dishesTable() {
    $.ajax({
        url: `/user/dish/liked/${userId}`,
        type: 'get',
        success(data) {
            list(data['data'])
        },
        error(data) {
            errorHandler()
        }
    });
}

function list(data) {
    let numberDish = 1

    $.each(data, function (i, item) {
        item['created_at'] = new Date(item['created_at']).toLocaleDateString("en-US", {
            day: 'numeric',
            month: 'short',
            year: 'numeric'
        });

        let images = checkImages(item['dish_images']);// return of this function  variables
        let tagTitleList = item['tags'].map((tag) => tag['title'])
        let tagsRow = tagTitleList.map((tagTitle) => `<span>${tagTitle}</span>`)
        let previewImage = images["previewImage"];
        let row = `<tr>
               <th scope="row" data-id=${item['id']}">${numberDish}</th>
                 <td><figure class="effect-ming tm-video-item" style="height: 50px; width: 75px">
                    <img src="/storage/${previewImage}" alt="preview Image" class="img-fluid">
                  </figure></td>
                 <td> <h5 class="dish-action"><a href="/catalog/dish/${item['id']}" id="show-dish-btm" style="color:inherit;">${item['title']}</a></h5></td>
                <td>${item['price']}$</td>
                 <td>${tagsRow}</td>
              </tr>`

        $('#tbody').append(row);
        numberDish += 1
    })
}

$(function () {
    dishesTable();
})

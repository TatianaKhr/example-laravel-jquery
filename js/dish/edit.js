import {checkImages, constant, errorHandler} from '/js/components/functionality/common.js';
import {tags} from '/js/components/functionality/tags.js';

const {userId,dishId} = constant()

$(function () {
    showUsersDish();

    $('#update_dish_button').on("click", function () {
        let formData = new FormData();
        let title = $('#title').val().replace(/[^\w\s]/gi, '');
        let description = $('#description').val().replace(/[^a-zA-Za-åa-ö-w-я 0-9/@%!"#?¨'_.,]+/g, "");
        let ingredients = $('#ingredients').val().replace(/[^a-zA-Za-åa-ö-w-я 0-9/@%!"#?¨'_.,]+/g, "");
        let tags = $('.select-tags').val();

        formData.append(' _method', 'PATCH');
        formData.append("user_id", userId);
        formData.append("id", $('#dish-card').attr('data-id'));
        formData.append("title", title);
        formData.append("description", description);
        formData.append("ingredients", ingredients);
        formData.append("price", $('#price').val());

        if ($('#preview_image')[0].files[0]) {
            formData.append("preview_image", $('#preview_image')[0].files[0]);
        }

        if ($('#main_image')[0].files[0]) {
            formData.append("main_image", $('#main_image')[0].files[0]);
        }

        for (let i = 0; i < tags.length; i++) {
            formData.append('tag_ids[]', tags[i]);
        }

        $.ajax({
            url: `/user/dish/${dishId}`,
            type: 'post',
            data: formData,
            processData: false,
            contentType: false,
            success: function (data) {
                Swal.fire(
                    'Good job!',
                    'New dish has been create successfully!',
                    'success'
                )
                window.location.href = "/home";
            },
            error: function (data) {
                $.each(data.responseJSON.errors, function (field_name, error) {
                    $('#dish_card').find('[name=' + field_name + ']').addClass('is-invalid').after(`<div class=" invalid-feedback"> ${ error } </div>`)
                })
            }
        });
    });
});

function showUsersDish(data = {}) {
    $.ajax({
        url: `/user/dish/${dishId}/edit`,
        method: 'get',
        dataType: 'json',
        data: data,
        success: function (data) {
            data = data['data'];

            tags({
                dishesTags: data['tags'],
            })

            let images = checkImages(data['dish_images']);
            let title = `<input id="title" type="text"
                                       class="form-control rounded-0" name="title"
                                       value="${data['title']}" required autocomplete="Recipe title" autofocus>`
            let ingredients = `<textarea id="ingredients" class="form-control rounded-0"
                                  name="ingredients" required autocomplete="ingredients">${data['ingredients']}</textarea>`
            let description = `<textarea id="description" class="form-control rounded-0"
                                  name="description" required autocomplete="description">${data['description']}</textarea>`
            let price = ` <input type="number" min="0.00" max="10000.00" step="0.01"  name="price"  value="${data['price']}" id="price"/>`
            let preview_image = `<img src="/storage/${images["previewImage"]}" width="200" class="img-fluid img-thumbnail">`
            let main_image = `<img src="/storage/${images["mainImage"]}" width="200" class="img-fluid img-thumbnail">`

            $('#input-group-title').append(title);
            $('#input-group-ingredients').append(ingredients);
            $('#input-group-description').append(description);
            $('#input-group-price').append(price);
            $('#input-group-preview-image').prepend(preview_image);
            $('#input-group-main-image').prepend(main_image);
        },
        error: function () {
            errorHandler()
        }
    });
}


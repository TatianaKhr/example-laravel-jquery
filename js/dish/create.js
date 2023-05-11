import {tags} from '/js/components/functionality/tags.js';

$(function () {
    $('#search-form').remove();

    tags({
        select: true
    });

    $('#create_dish_button').on("click", function () {
        let formData = new FormData();
        let title = $('#title').val().replace(/[^\w\s]/gi, '');
        let description = $('#description').val().replace(/[^a-zA-Za-åa-ö-w-я 0-9/@%!"#?¨'_.,]+/g, "");
        let ingredients = $('#ingredients').val().replace(/[^a-zA-Za-åa-ö-w-я 0-9/@%!"#?¨'_.,]+/g, "");
        let tags = $('.select-tags').val();

        for (let i = 0; i < tags.length; i++) {
            formData.append('tag_ids[]', tags[i]);
        }

        formData.append("user_id", $('#user-edit').attr('data-id'));
        formData.append("title", title);
        formData.append("description", description);
        formData.append("ingredients", ingredients);
        formData.append("price", $('#price').val());
        formData.append("preview_image", $('#preview_image')[0].files[0]);
        formData.append("main_image", $('#main_image')[0].files[0]);

        $.ajax({
            url: `/user/dish/store`,
            method: 'post',
            data: formData,
            processData: false,
            contentType: false,
            success: function (data) {
                Swal.fire(
                    'Good job!',
                    'You dish have been successfully stored!',
                    'success'
                )
            },
            error: function (data) {
                $.each(data.responseJSON.errors, function (fieldName, error) {
                    $('#dish_card').find('[name=' + fieldName + ']').addClass('is-invalid').after(`<div class=" invalid-feedback"> ${error} </div>`)
                })
            }
        });
    });
});


import {checkImages, constant, errorHandler} from '/js/components/functionality/common.js';

const {userId, dishId} = constant()

function initDish(data = {}) {
    $.ajax({
        url: `/catalog/dish/show/${dishId}`,
        method: 'get',
        dataType: 'json',
        data: data,
        success(data) {
            data = data['data'];
            data['created_at'] = new Date(data['created_at']).toLocaleDateString("en-US", {
                day: 'numeric',
                month: 'short',
                year: 'numeric'
            });

            let images = checkImages(data['dish_images']);
            let mainImage = '/storage/' + images["mainImage"];
            let title = `<h1 class="col-12 tm-text-primary">${data['title']}</h1>`;
            let image;

            if (parseInt(data['user_id']) !== parseInt(userId)) {
                image = `<img src="${mainImage}" alt="Image" class="img-fluid" style="width: 1155px; height: 650px ;">`
            } else {
                image = `<figure class="effect-ming tm-video-item main-image">
                    <img src="${mainImage}" alt="Image" class="img-fluid" style="width: 1155px; height: 650px ;">
                    <figcaption class="d-flex align-items-center justify-content-center action">
                       <h2 class="dish-action justify-content-between">
                       <div class="row mt-3 mb-0" style="color:inherit; font-size: 5rem">
                        <div class="col mr-4" id="edit-btn"><a href="/user/dish/${data['id']}/edit" style="color:inherit;">Edit</a></div>
                       <div class="col mr-4" id="delete-btn" data-id="${dishId}">Delete</div></div></h2>
                    </figcaption>
                  </figure>`
            }

            let description = `<h4 class="tm-text-gray-dark mb-3">${data['description']}</h4>`;
            let price = `<span class="tm-text-gray-dark">Price: </span><span class="tm-text-primary">${data['price']}$</span>`
            let created = `<span class="tm-text-gray-dark">Published: </span><span class="tm-text-gray">${data['created_at']}$</span>`

            $('.dish-title').append(title);
            $('.dish-image').append(image);
            $('.dish-description').append(description);
            $(".dish-ingredients").html(data['ingredients']);
            $('.dish-price').append(price);
            $('.dish-created-data').append(created);

            if (data['tags']) {
                $.each(data['tags'], function (i, item) {
                    $('.dish-tags').append(`<a href="#" class="tm-text-primary mr-4 mb-2 d-inline-block">${item.title}</a>`);
                })
            } else {
                $('.dish-tags').remove()
            }

            $('#edit-btn, #delete-btn, #show-dish-btm')
                .on('mouseenter', function () {
                    $(this).css("color", "#5c6772");
                })
                .on('mouseleave', function () {
                    $(this).css("color", "inherit");
                });
        },
        error(data) {
            errorHandler()
        }
    });
}

$(function () {
    initDish();

    $('#dish-card').on("click", "#delete-btn", function () {
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `/user/dish/${dishId}`,
                    method: 'delete',
                    dataType: 'json',
                    data: {
                        _method: 'delete'
                    },
                    success(data) {
                        Swal.fire(
                            'Deleted!',
                            'Your dish has been deleted.',
                            'success'
                        )
                        window.location.href = "/"
                    },
                    error(data) {
                        errorHandler()
                    }
                });
            }
        })
    });
});

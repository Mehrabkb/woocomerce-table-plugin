window.onload = function(){
    new DataTable('#finalTbl',{
        "language": {
            "lengthMenu": "نمایش _MENU_ در هر صفحه",
            "zeroRecords": "هیچ چیزی برای نمایش وجود ندارد",
            "info": "نمایش _PAGE_ از _PAGES_",
            "infoEmpty": "خالی",
            "infoFiltered": "(filtered from _MAX_ total records)",
            "search": "جستجو : ",
            'paginate': {
                'previous': 'قبلی',
                'next': 'بعدی'
              }
        }

    });
    let shortCodeTable = document.querySelector('#shortcode-tbl');
    if(shortCodeTable != null){
        new DataTable('#shortcode-tbl',{
            responsive : true,
            "language": {
                "lengthMenu": "نمایش _MENU_ در هر صفحه",
                "zeroRecords": "هیچ چیزی برای نمایش وجود ندارد",
                "info": "نمایش _PAGE_ از _PAGES_",
                "infoEmpty": "خالی",
                "infoFiltered": "(filtered from _MAX_ total records)",
                "search": "جستجو : ",
                'paginate': {
                    'previous': 'قبلی',
                    'next': 'بعدی'
                },
            },
        })
    }
}
function changInputCount(el){
    let input = el.parentElement.querySelector('input')
    if(el.getAttribute('data-role') == 'decrease'){
        if(parseInt(input.value) > 0){
            input.value = decreaseInputValue(parseInt(input.value));
        }
    }else if(el.getAttribute('data-role') == 'increase'){
        input.value = increaseInputValue(parseInt(input.value));
    }
    print_final_price(input.value , parseInt(el.parentElement.parentElement.querySelector('.unit-price').getAttribute('data-pure-price')) , el.parentElement.parentElement.querySelector('.final-price'));
}
function decreaseInputValue(value){
    return parseInt(value) - 1;
}
function increaseInputValue(value){
    return parseInt(value) + 1;
}
function print_final_price(inputValue , unitPrice , finalEl){
    inputValue = parseInt(inputValue);
    finalEl.innerHTML =  showMoney((inputValue * unitPrice).toString());
}
function showMoney(nStr) {
    nStr += '';
    x = nStr.split('.');
    x1 = x[0];
    x2 = x.length > 1 ? '.' + x[1] : '';
    var rgx = /(\d+)(\d{3})/;
    while (rgx.test(x1)) {
        x1 = x1.replace(rgx, '$1' + ',' + '$2');
    }
    return x1 + x2;
}
jQuery(document).ready(function($){
    $('.add_to_card_ajax_button').on('click' , function(e){
        e.preventDefault();
        let that = $(this);
        let url = that.parent().parent().attr('data-url-ajax');
        let product_id = that.parent().parent().attr('data-product-id');
        let inputCount = parseInt(that.parent().parent().find('input#count').val()) ;
        $.ajax({
            type: 'POST',
            url: url,
            data: {
                action: 'add_to_cart',
                product_id: product_id,
                quantity: inputCount // Include the quantity data
            },
            success: function(response) {
                alert('Item added to cart!');
                // Optionally update cart icon or message
            },
            error: function(xhr, status, error) {
                alert('Error adding item to cart. Please try again later.');
                console.log(error);
                // Optionally display an error message
            }
        });
    });
});
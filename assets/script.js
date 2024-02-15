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
}
function decreaseInputValue(value){
    return parseInt(value) - 1;
}
function increaseInputValue(value){
    return parseInt(value) + 1;
}
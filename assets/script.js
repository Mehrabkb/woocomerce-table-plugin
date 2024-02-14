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
    console.log('outif');
    if(shortCodeTable != null){
        console.log('inif');
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
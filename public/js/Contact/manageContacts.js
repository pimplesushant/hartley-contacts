$(function () {
    $('#contacts-list').DataTable({
        paging: true,
        lengthChange: true,
        searching: true,
        ordering: true,
        info: true,
        bSort: true,
        autoWidth: true,
        processing: true,
        serverSide: true,
        ajax: {
            type: 'GET',
            url: 'getContacts',
            dataSrc: 'data'
        },
        columns: [
            {
                data: "id", searchable: false, orderable: false,
                render: function (data, type, row, meta) {
                    return meta.row + meta.settings._iDisplayStart + 1;
                }
            },
            {
                data: 'name', filterable: true,
                render : function (data, type, row, meta) {
                    return '<a href="contacts/'+row.id+'">'+data+'</a>';
                }
            },
            {data: 'email', filterable: true},
            {data: 'contacts', searchable: false, filterable: true, orderable: false},
            {
                data: 'photo', searchable: false, filterable: false, orderable: false,
                render : function (data, type, row, meta) {
                    return '<img class="img img-circle img-sm img-responsive" src="'+data+'" alt="'+row.name+'"/>';
                }
            },
            {data: 'action', searchable: false, filterable: false, orderable: false}
        ],
        order: [[ 2, 'asc' ]]
    });

    $(document).on('click', 'tbody .view_contact', function () {
        var data_row = $("#contacts-list").DataTable().row($(this).closest('tr')).data();
        $('.detail-name').text(data_row.name);
        $('#detail-email').text(data_row.email);
        $('#detail-primary').text(data_row.primary_phone);
        $('#detail-secondary').text((data_row.secondary_phone != null) ? data_row.secondary_phone : 'NA');
        $('#detail-image').attr('src', data_row.photo).attr('alt', data_row.name);
        $('#viewContactDetails').modal('show');
    })

    $(".modal#viewContactDetails").on("hidden.bs.modal", function(){
        $('.detail-name, #detail-email, #detail-primary, #detail-secondary').text('');
        $('#detail-image').attr('src', '#').attr('alt', '#');
    });

    $(document).on('click','.delete_contact', function(e){
        e.preventDefault();
        $('#delete_it').attr('data-cid',$(this).data('id'))
        $('#confirmContactDelete').modal('show');
    });

    $(document).on('click','#delete_it', function(e){
        e.preventDefault();
        $('#delete'+$(this).data('cid')).submit();
    });

});
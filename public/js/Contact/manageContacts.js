$(function () {
    $.extend($.fn.dataTable.defaults, {
        paging: true,
        lengthChange: true,
        searching: true,
        ordering: false,
        info: true,
        bSort: true,
        autoWidth: true,
        processing: true,
        serverSide: true,
        columns: [
            {
                data: "id", searchable: false,
                render: function (data, type, row, meta) {
                    return meta.row + meta.settings._iDisplayStart + 1;
                }
            },
            {data: 'name', filterable: true},
            {data: 'email', filterable: true},
            {data: 'contacts', filterable: true},
            {
                data: 'photo', searchable: false, filterable: false,
                render: function (data, type, row, meta) {
                    return '<img class="img img-circle img-sm img-responsive" src="' + data + '" alt="' + row.name + '"/>';
                }
            },
            {data: 'action', searchable: false, filterable: false}
        ],
        order: [[2, 'asc']],
        ajax: {
            type: 'GET',
            dataSrc: 'data'
        },
    });

    $('#contacts-list-1').DataTable({
        ajax: {
            url: 'getContacts',
        }
    });

    $('#contacts-list-2').DataTable({
        ajax: {
            url: 'getContacts/1',
        }
    });

    $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
        $($.fn.dataTable.tables(true)).css('width', '100%');
        $($.fn.dataTable.tables(true)).DataTable().columns.adjust().draw();
    });

    $(document).on('click', 'tbody .view_contact', function () {
        var data_row = $("#" + $(this).closest('tr').parent().parent().attr('id')).DataTable().row($(this).closest('tr')).data();
        $('.detail-name').text(data_row.name);
        $('#detail-email').text(data_row.email);
        $('#detail-primary').text(data_row.primary_phone);
        $('#detail-secondary').text((data_row.secondary_phone != null) ? data_row.secondary_phone : 'NA');
        $('#detail-image').attr('src', data_row.photo).attr('alt', data_row.name);
        $('#viewContactDetails').modal('show');
    });

    $(".modal#viewContactDetails").on("hidden.bs.modal", function () {
        $('.detail-name, #detail-email, #detail-primary, #detail-secondary').text('');
        $('#detail-image').attr('src', '#').attr('alt', '#');
    });

    $(document).on('click', '.delete_contact', function (e) {
        e.preventDefault();
        $('#delete_it').attr('data-cid', $(this).data('id'));
        $('#confirmContactDelete').modal('show');
    });

    $(document).on('click', '#delete_it', function (e) {
        e.preventDefault();
        $('#delete' + $(this).data('cid')).submit();
    });

});
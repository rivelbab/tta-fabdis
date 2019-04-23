$(function() {
    // Setting datatable defaults
    $.extend( $.fn.dataTable.defaults, {
        autoWidth: false,
        colReorder: true,
        dom: '<"datatable-header"fBl><"datatable-scroll-wrap"t><"datatable-footer"ip>',
        language: {
            search: '<span>Filtre:</span> _INPUT_',
            searchPlaceholder: 'Tapez pour filter...',
            lengthMenu: '<span>Afficher:</span> _MENU_',
            paginate: { 'first': 'First', 'last': 'Last', 'next': '&rarr;', 'previous': '&larr;' }
        }
    });

    // comparateur des prix
    $('#comparateurPrix').DataTable({
        lengthMenu: [[50, 100, -1], [50, 100, "All"]],
        language: {
            search: '<span>Filtre:</span> _INPUT_',
            searchPlaceholder: 'Tapez pour filter...',
            lengthMenu: '<span>Afficher </span> _MENU_',
            paginate: { 'first': 'First', 'last': 'Last', 'next': '&rarr;', 'previous': '&larr;' }
        },
        colReorder: true,
        rowReoder: true,
        stateSave: true,
        realtime: true,
        //select: true,
        //================================
        columnDefs: [
            {
                orderable: false,
                className: 'select-checkbox',
                targets: 0
            }
        ],
        select: {
            style: 'os',
            selector: 'td:first-child',
            style: 'multi'
        },
        order: [[1, 'asc']],
        //================================
        buttons: {
            buttons: [
                {extend: 'selectAll', className: 'btn btn-default',text: '<i class="icon-checkbox-checked2"></i>'},
                {extend: 'selectNone', className: 'btn btn-default',text: '<i class="icon-checkbox-unchecked2"></i>'},
                {
                    extend: 'colvisGroup',
                    className: 'btn btn-default',
                    text: '<i class="icon-eye"></i>',
                    show: ':hidden'
                },
                {
                    extend: 'colvisGroup',
                    text: '<i class="icon-eye-blocked2"></i>',
                    className: 'btn btn-danger',
                    hide: [0, 2, 3, 4, 5, 7, 8, 11, 12, 13, 14, 15, 16]
                },
                {
                    extend: 'csvHtml5',
                    header: false,
                    className: 'btn btn-default',
                    text: '<i class="icon-download4"></i> Exporter la vue',
                    fieldSeparator:';',
                    extension: '.csv',
                    charset: 'utf-8',
                    fieldBoundary: '',
                    bom: true,
                    exportOptions: {
                        columns: ':visible',
                    },
                    action: function(e, dt, node, config) {
                        if (confirm("Avez-vous cliquer sur le bouton rouge pour masquer les éléments à ne pas exporter ?")){
                            var id = $("#fournisseurId").text();
                            var path = $("#path").attr('data-path');
                            $.ajax({
                                type: 'POST',
                                url: path,
                                data: {myData: id},
                                success: function(response){}
                            });
                            $.fn.dataTable.ext.buttons.csvHtml5.action.call(this, e, dt, node, config);
                        }
                    }
                },
                {
                    extend: 'csvHtml5',
                    header: false,
                    className: 'btn btn-default',
                    text: '<i class="icon-download10"></i> Exporter la selection',
                    fieldSeparator:';',
                    extension: '.csv',
                    charset: 'utf-8',
                    fieldBoundary: '',
                    bom: true,
                    charset: 'utf-8',
                    exportOptions: {
                        columns: ':visible',
                        modifier: {
                            selected: true
                        }
                    },
                    action: function(e, dt, node, config) {
                        if (confirm("Avez-vous cliquer sur le bouton rouge pour masquer les éléments à ne pas exporter ?")){
                            var id = $("#fournisseurId").text();
                            var path = $("#path").attr('data-path');
                            $.ajax({
                                type: 'POST',
                                url: path,
                                data: {myData: id},
                                success: function(response){}
                            });
                            $.fn.dataTable.ext.buttons.csvHtml5.action.call(this, e, dt, node, config);
                        }
                    }
                },
                {
                    extend: 'print',
                    header: true,
                    className: 'btn btn-default',
                    text: '<i class="icon-printer2"></i>',
                    exportOptions: {
                        columns: ':visible',
                    }
                },
                {
                    extend: 'colvis',
                    text: '<i class="icon-three-bars"></i> <span class="caret"></span>',
                    className: 'btn bg-blue btn-icon'
                }
            ]
        },
    });

    //======== classic datatable ============
    $('.classicDatatable').DataTable({
        lengthMenu: [[10, 50, 100, -1], [10, 50, 100, "All"]],
        language: {
            search: '<span>Filtre:</span> _INPUT_',
            searchPlaceholder: 'Tapez pour filter...',
            lengthMenu: '<span>Afficher </span> _MENU_',
            paginate: { 'first': 'First', 'last': 'Last', 'next': '&rarr;', 'previous': '&larr;' }
        },
        colReorder: true,
        rowReoder: true,
        stateSave: true,
        realtime: true,
        //select: true,
        //================================
        columnDefs: [],
        order: [[1, 'asc']],
        //================================
        buttons: {
            buttons: [
                {
                    extend: 'csvHtml5',
                    header: false,
                    className: 'btn btn-default',
                    text: '<i class="icon-download4"></i> Exporter la vue',
                    fieldSeparator:';',
                    extension: '.csv',
                    charset: 'utf-8',
                    fieldBoundary: '',
                    bom: true,
                    exportOptions: {
                        columns: ':visible',
                    }
                },
                {
                    extend: 'colvis',
                    text: '<i class="icon-three-bars"></i> <span class="caret"></span>',
                    className: 'btn bg-blue btn-icon'
                }
            ]
        },
    });
    //======== classic datatable with no boutton============
    $('.classicDatatableNoBtn').DataTable({
        lengthMenu: [[100, -1], [100, "All"]],
        language: {
            search: '<span>Recherche</span> _INPUT_',
            searchPlaceholder: 'Tapez pour rechercher...',
            lengthMenu: '<span>Afficher:</span> _MENU_',
            paginate: { 'first': 'First', 'last': 'Last', 'next': '&rarr;', 'previous': '&larr;' }
        },
        colReorder: true,
        rowReoder: true,
        stateSave: true,
        realtime: true,
        select: true,
        order: [[1, 'asc']],
        //================================
        buttons: {
            buttons: [
                {
                    extend: 'csvHtml5',
                    header: true,
                    className: 'btn btn-success',
                    text: '<i class="icon-download4"></i> Exporter vers excel',
                    fieldSeparator:';',
                    extension: '.csv',
                    charset: 'utf-8',
                    fieldBoundary: '',
                    bom: true,
                    exportOptions: {
                        columns: ':visible',
                    }
                },
                {
                    extend: 'colvis',
                    text: '<i class="icon-three-bars"></i> <span class="caret"></span>',
                    className: 'btn bg-blue btn-icon'
                }
            ]
        },
    });

    // Enable Select2 select for the length option
    $('.dataTables_length select').select2({
        minimumResultsForSearch: Infinity,
        width: 'auto'
    });
});

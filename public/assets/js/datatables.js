function excelPhones(data) {
    if ($.fn.DataTable.isDataTable("#phonesExcel")) {
        $('#phonesExcel').DataTable().clear().destroy();
    }
    
    $("#phonesExcel").DataTable({
        language: {
            url: 'https://cdn.datatables.net/plug-ins/1.13.4/i18n/es-ES.json',
        },
        ordering: false,
        paging: true,
        processing: true,
        data: data,

        "columns": [
            { "data": "nombres", "className": "text-center" },
            { "data": "telefonos", "className": "text-center" },
            {
                "data": null,
                "render": function(data, type, row) {
                    return '<input class="form-check-input" type="checkbox" value="" id="flexCheckChecked" checked>';                    
                },
                "className": "text-center"
            }

        ],
        destroy: true, // Añadir esta línea para permitir la re-inicialización
    });

    $("#clearexcel").css("display", "block");
    $("#sendExcelMen").css("display", "block");
    $("#messageExcel").css("display", "block");
}

export { excelPhones };
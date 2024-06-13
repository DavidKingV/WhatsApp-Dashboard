import { sendMessage, uploadExcel,sendExcelMessage } from '../../ajax/dashboard.js';

$(function() {
    $("#clearexcel, #sendExcelMen, #messageExcel").css("display", "none");
});


$("#sendWhatsApp").on("submit", async function(e) {
    e.preventDefault();
    let messageData = $(this).serialize();
    const action = 'sendWhatsApp';

    messageData += `&action=${action}`;

    if($(this).valid()){
        await sendMessage(messageData);
    }else{
        return false;
    }
});

$("#sendExcelData").on("submit", async function(e) {
    e.preventDefault();

    let excel = new FormData(this);
    
    await uploadExcel(excel);
   
});

$("#clearexcel").on("click", function() {
    $("#phonesExcel").DataTable().clear().destroy();
    $("#clearexcel").css("display", "none");
    $("#sendExcelMen").css("display", "none");
    $("#messageExcel").css("display", "none");
});


$("#sendExcelMen").on("click", async function() {  
    let table = $('#phonesExcel').DataTable();
    let message = $("#messageExcelArea").val();
    let countryCode = '521';
    
    var phoneNumbers = [];
    table.$('input[type="checkbox"]:checked').each(function() {
        var row = $(this).closest('tr');
        var phoneNumber = table.row(row).data().telefonos;
        phoneNumbers.push(phoneNumber);
    });

    if (phoneNumbers.length > 0) {
    await sendExcelMessage(phoneNumbers, message, countryCode);
    }else{
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'No hay números seleccionados',
            showConfirmButton: false,
            timer: 1500
        });
    }
    
});



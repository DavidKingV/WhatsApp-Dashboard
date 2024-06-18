import { sendMessage, uploadExcel,sendExcelMessage, sendExcelMediaMessage } from '../../ajax/dashboard.js';

$(function() {
    $("#clearexcel, #sendExcelMen, #messageExcel, #mediaSpace").css("display", "none");
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
    $("#mediaSpace").css("display", "none");
    $("#whatsappFile").val('');
});

//crea una funcion para comprobar si un input del tipo file tiene un archivo seleccionado

function checkFile() {
    var file = $("#whatsappFile").prop('files')[0];
    if (file) {
        return true;
    }else{
        return false;
    }
}

//crea una funcion para guardar el archivo seleccionado en el input file en la carpeta public/assets/temp

async function saveFile() {
    const fileInput = $('#whatsappFile')[0];
    const file = fileInput.files[0];

    const formData = new FormData();
    formData.append('whatsappFile', file);

    try {
        const response = await axios.post('../php/config/media.php', formData, {
            headers: {
                'Content-Type': 'multipart/form-data'
            }
        });
        return response.data;
    } catch (error) {
        console.log(error);
        return false;
    }

}


$("#sendExcelMen").on("click", async function() {  

    let loadingSwal = Swal.fire({
        title: 'Procesando...',
        text: 'Por favor, espere.',
        allowOutsideClick: false,
        showConfirmButton: false,
        onBeforeOpen: () => {
            Swal.showLoading()
        }
    });

    let closeLoadingSwal = () => {
        if (Swal.isVisible() && Swal.getTitle().innerText === 'Procesando...') {
            Swal.close();
        }
    }

    if (!checkFile()) {

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
        closeLoadingSwal();
        }else{
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'No hay números seleccionados',
                showConfirmButton: false,
                timer: 1500
            });
        }

    }else{
        try {
            const fileSaved = await saveFile();
            if (fileSaved && fileSaved.path) {
                console.log(fileSaved.path);            
                    
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
                    console.log(fileSaved.path);
                await sendExcelMediaMessage(phoneNumbers, message, countryCode, fileSaved.path);
                closeLoadingSwal();
                }else{
                    closeLoadingSwal();
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'No hay números seleccionados',
                        showConfirmButton: false,
                        timer: 1500
                    });
                }

            }else{
                closeLoadingSwal();
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'No se ha podido guardar el archivo',
                    showConfirmButton: false,
                    timer: 1500
                });
            }
        } catch (error) {
            closeLoadingSwal();
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Error al guardar el archivo',
                showConfirmButton: false,
                timer: 1500
            });
        }                
    }
 
});



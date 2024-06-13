import { excelPhones } from '../../public/assets/js/datatables.js';

export const sendMessage = async (messageData) => {
    try {
        const response = await axios.post('../php/dashboard/routes.php', messageData);
        if (response.data._data.id.id) {
            Swal.fire({
                icon: 'success',
                title: 'Mensaje enviado',
                text: response.data.message,
                showConfirmButton: false,
                timer: 1500
            }).then(() => {
                $("#sendWhatsApp")[0].reset();
            });
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: response.data.message,
                showConfirmButton: false,
                timer: 1500
            });
        }
    } catch (error) {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: error.response ? error.response.data.message : 'Unknown error',
            showConfirmButton: false,
            timer: 3000
        });
    }
}

export const uploadExcel = async (excel) => {
    try {
        const response = await axios.post('../php/excel/routes.php', excel);
        if (response.data.success) {
            Swal.fire({
                icon: 'success',
                title: 'Excel subido',
                text: response.data.message,
                showConfirmButton: false,
                timer: 1500
            }).then(() => {
                $("#sendExcelData")[0].reset();
                
                excelPhones(response.data.data);

            });
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: response.data.message,
                showConfirmButton: false,
                timer: 1500
            });
        }
    } catch (error) {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: error.response ? error.response.data.message : 'Unknown error',
            showConfirmButton: false,
            timer: 3000
        });
    }
}

export const sendExcelMessage = async (phoneNumbers, message, countryCode) => {
    try {
        const response = await axios.post('../php/excel/multiple.php', {
            phoneNumbers: phoneNumbers,
            message: message,
            countryCode: countryCode,
            action: 'sendExcelMessage'
        });
        if (response.data.success) {
            Swal.fire({
                icon: 'success',
                title: 'Mensajes enviado',
                text: response.data.message,
                showConfirmButton: false,
                timer: 1500
            }).then(() => {
                $("#phonesExcel").DataTable().clear().destroy();
                $("#clearexcel").css("display", "none");
                $("#sendExcelMen").css("display", "none");
                $("#messageExcel").css("display", "none");
                $("#messageExcelArea").val('');
                $("#mediaSpace").css("display", "none");
            });
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: response.data.message,
                showConfirmButton: false,
                timer: 1500
            });
        }
    } catch (error) {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: error.response ? error.response.data.message : 'Unknown error',
            showConfirmButton: false,
            timer: 3000
        });
    }
}

export const sendExcelMediaMessage = async (phoneNumbers, message, countryCode, path) => {
    try {
        const response = await axios.post('../php/excel/media.php', {
            phoneNumbers: phoneNumbers,
            message: message,
            countryCode: countryCode,
            path: path,
            action: 'sendExcelMessageMedia'
        });
        if (response.data.success) {
            Swal.fire({
                icon: 'success',
                title: 'Mensajes enviado',
                text: response.data.message,
                showConfirmButton: false,
                timer: 1500
            }).then(() => {
                $("#phonesExcel").DataTable().clear().destroy();
                $("#clearexcel").css("display", "none");
                $("#sendExcelMen").css("display", "none");
                $("#messageExcel").css("display", "none");
                $("#mediaSpace").css("display", "none");
            });
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: response.data.message,
                showConfirmButton: false,
                timer: 1500
            });
        }
    } catch (error) {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: error.response ? error.response.data.message : 'Unknown error',
            showConfirmButton: false,
            timer: 3000
        });
    }
}


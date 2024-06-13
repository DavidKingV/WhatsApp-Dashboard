$("#message, #messageExcelArea").on("input", function(event) {
    event.preventDefault();
    let cursorPosition = $(this).prop('selectionStart');

    // Obtener el valor actual del input
    let inputValue = $(this).val();

    // Función para convertir solo la primera letra a mayúscula
    function capitalizeWords(string) {
        if (!string) return string; // Maneja cadenas vacías o nulas
            return string.charAt(0).toUpperCase() + string.slice(1);
    }

    // Convertir el valor del input con la primera letra en mayúscula
    let capitalizedValue = capitalizeWords(inputValue);

    // Establecer el valor modificado en el input
    $(this).val(capitalizedValue);
    
    // Restaurar la posición del cursor después de la modificación
    $(this).prop('selectionStart', cursorPosition);
    $(this).prop('selectionEnd', cursorPosition);
});


$("#sendWhatsApp").validate({
    rules: {
        message: {
            required: true,
            minlength: 1,
            maxlength: 255
        },
        countryCode: {
            required: true,
            valueNotEquals: "0"
        },
        phoneNumber: {
            required: true,
            number: true,
            minlength: 10,
            maxlength: 10
        }
    },
    messages: {
        message: {
            required: "Por favor, escribe un mensaje",
            minlength: "El mensaje debe tener al menos 2 caracteres",
            maxlength: "El mensaje no puede exceder los 255 caracteres"
        },
        countryCode: {
            required: "Por favor, selecciona un código de país"
        },
        phoneNumber: {
            required: "Por favor, escribe un número de teléfono",
            number: "Por favor, escribe solo números",
            minlength: "El número de teléfono debe tener 10 dígitos",
            maxlength: "El número de teléfono debe tener 10 dígitos"
        }
    },
    errorPlacement: function(error, element) {
        var elementId = element.attr("id");
        error.insertAfter($("#" + elementId + "-error")); // Coloca el error después de la etiqueta de error personalizada
    }
});

$("#sendExcelData").validate({
    rules: {
        excel: {
            required: true,
            extension: "xlsx|xls|xlsm|csv"
        }
    },
    messages: {
        excel: {
            required: "Por favor, selecciona un archivo",
            extension: "Por favor, selecciona un archivo Excel"
        }
    },
    errorPlacement: function(error, element) {
        var elementId = element.attr("id");
        error.insertAfter($("#" + elementId + "-error")); // Coloca el error después de la etiqueta de error personalizada
    }
});


$.validator.addMethod("valueNotEquals", function(value, element, arg){
    return arg !== value;
}, "Por favor, selecciona una opción");
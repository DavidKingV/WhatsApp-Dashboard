const Login = async (userData) => {
    try {
        const response = await axios.post('../php/login/routes.php', userData);
        if (response.data.status === 'success') {
            // Redirect to the dashboard
            window.location.href = 'dashboard.php';
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
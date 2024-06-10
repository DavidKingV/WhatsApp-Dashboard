$("#loginForm").on("submit", async function(e) {
    e.preventDefault();
    
    let userData = $(this).serialize();
    const action = 'login';

    userData += `&action=${action}`;
    console.log(userData);

    await Login(userData);
});
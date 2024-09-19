const customerLoginForm = document.getElementById('customer_login');

const loginForm = document.querySelector('.login');
const registerForm = document.querySelector('.register');

if (customerLoginForm) {
    const loginHeader = customerLoginForm.querySelector ('.col-1').querySelector('h2');
    const registerHeader = customerLoginForm.querySelector ('.col-2').querySelector('h2');

    registerForm.classList.add("d-none");

    loginHeader.addEventListener('click', function (e) {
        registerForm.classList.add("d-none");
        loginForm.classList.remove("d-none");
    })

    registerHeader.addEventListener('click', function (e) {
        registerForm.classList.remove("d-none");
        loginForm.classList.add("d-none");
    })

    console.log(loginForm);
    console.log(registerForm);
    console.log(loginHeader);
    console.log(registerHeader);
}

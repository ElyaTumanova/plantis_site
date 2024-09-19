const customerLoginForm = document.getElementById('customer_login');

const loginForm = document.querySelector('.login');
const registerForm = document.querySelector('.register');

if (customerLoginForm) {
    const loginHeader = customerLoginForm.querySelector ('.col-1').querySelector('h2');
    const registerHeader = customerLoginForm.querySelector ('.col-2').querySelector('h2');

    console.log(loginForm);
    console.log(registerForm);
    console.log(loginHeader);
    console.log(registerHeader);
}

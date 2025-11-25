document.addEventListener('DOMContentLoaded', function() {
    // Example: Simple client-side validation for a form (e.g., registration)
    const registerForm = document.querySelector('.register-container form'); // More specific selector if needed
    if (registerForm) {
        registerForm.addEventListener('submit', function(event) {
            const nome = document.getElementById('nome').value.trim();
            const email = document.getElementById('email').value.trim();
            const senha = document.getElementById('senha').value;
            const confirmeSenha = document.getElementById('confirme_senha').value;

            if (nome === '' || email === '' || senha === '' || confirmeSenha === '') {
                alert('Todos os campos são obrigatórios!');
                event.preventDefault(); // Stop form submission
                return;
            }

            if (senha !== confirmeSenha) {
                alert('As senhas não coincidem!');
                event.preventDefault();
                return;
            }

            const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailPattern.test(email)) {
                alert('Formato de e-mail inválido!');
                event.preventDefault();
                return;
            }
        });
    }

    const menuButton = document.querySelector('header .menu-button');
    const navUl = document.querySelector('header nav ul');
    if (menuButton && navUl) {
        menuButton.addEventListener('click', function() {
            navUl.style.display = navUl.style.display === 'flex' || navUl.style.display === '' ? 'none' : 'flex';
            // You might want to style this better for mobile, this is a very basic toggle
        });
    }
});
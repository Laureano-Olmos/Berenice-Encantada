// Obtiene los modales
var loginModal = document.getElementById("loginModal");
var registerModal = document.getElementById("registerModal");

// Obtiene los botones que abren los modales
var loginBtn = document.getElementById("loginBtn");
var registerBtn = document.getElementById("registerBtn");

// Obtiene los elementos <span> que cierran los modales
var closeLogin = document.getElementById("closeLogin");
var closeRegister = document.getElementById("closeRegister");

// Abre el modal de login
loginBtn.onclick = function() {
    loginModal.style.display = "block";
}

// Abre el modal de registro
registerBtn.onclick = function() {
    registerModal.style.display = "block";
}

// Cierra el modal de login
closeLogin.onclick = function() {
    loginModal.style.display = "none";
}

// Cierra el modal de registro
closeRegister.onclick = function() {
    registerModal.style.display = "none";
}

// Cierra el modal si el usuario hace clic fuera de él
window.onclick = function(event) {
    if (event.target == loginModal) {
        loginModal.style.display = "none";
    }
    if (event.target == registerModal) {
        registerModal.style.display = "none";
    }
}

document.querySelectorAll('form').forEach(function(form) {
    form.addEventListener('submit', function(event) {
        event.preventDefault(); // Evita el comportamiento predeterminado de enviar el formulario
        let formData = new FormData(form);
        
        fetch(form.action, {
            method: form.method,
            body: formData
        }).then(response => {
            if (response.ok) {
                alert('Producto agregado al carrito.');
            } else {
                alert('Error al agregar el producto.');
            }
        });
    });
});

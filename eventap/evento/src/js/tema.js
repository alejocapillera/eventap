function changeTheme(theme) {
    document.body.setAttribute('data-theme', theme);
    localStorage.setItem('theme', theme); // Guardar el tema en el almacenamiento local
}

// Obtener el tema almacenado del almacenamiento local
const storedTheme = localStorage.getItem('theme');
if (storedTheme) {
    changeTheme(storedTheme); // Aplicar el tema almacenado
}

// Event listeners para los botones
document.getElementById('light-theme-btn').addEventListener('click', function() {
    changeTheme('light');
    document.getElementById('theme-switch').checked = false; // Desactivar el switch
});

document.getElementById('dark-theme-btn').addEventListener('click', function() {
    changeTheme('dark');
    document.getElementById('theme-switch').checked = true; // Activar el switch
});

document.getElementById('custom-theme-btn').addEventListener('click', function() {
    changeTheme('custom');
    document.getElementById('theme-switch').checked = false; // Desactivar el switch
});

// Event listener para el switch de tema claro/oscuro
document.getElementById('theme-switch').addEventListener('change', function() {
    if (this.checked) {
        changeTheme('dark');
    } else {
        changeTheme('light');
    }
});
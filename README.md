Aplicación de Notas "ToDo"
Este proyecto es una aplicación para gestionar notas, desarrollada con Laravel y Vue.js. Permite a los usuarios registrar, listar, editar y eliminar sus notas de forma sencilla, además de autenticarse para asegurar la privacidad de su información.

Características

Backend (Laravel)
Framework: Laravel 8 o superior.
API REST con autenticación usando personal tokens o JWT (Laravel Fortify o Passport recomendado).

Funciones principales:
Crear, editar, listar y eliminar notas.
Ordenar notas por fecha de creación o vencimiento.
Comandos Artisan personalizados para gestionar las notas desde la consola.
Validación de datos para asegurar la integridad.
Migraciones para la base de datos (sin incluir archivos SQL).

Frontend (Vue.js)
Framework: Vue 2.7 o superior.
Vue Router para manejar las rutas.
Vuex para la gestión del estado global.

Vistas incluidas:
Inicio de sesión y registro.
Pantalla principal para ver y administrar notas.
Formulario para la creación y edición de notas.
Peticiones AJAX mediante Axios para interactuar con el backend.
Validación de datos en el frontend para mejorar la experiencia del usuario.


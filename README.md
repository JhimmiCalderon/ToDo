# Aplicación de Notas "ToDo" 

Este proyecto es una aplicación de gestión de notas desarrollada con **Laravel** y **Vue.js**, siguiendo buenas prácticas de desarrollo. Permite a los usuarios gestionar sus notas de manera eficiente, ofreciendo funcionalidades de autenticación, creación, edición, listado y eliminación de notas.

## Características

### Backend (Laravel)
- **Framework:** Laravel 8+
- **API REST** con autenticación mediante tokens personales o JWT (Laravel Fortify o Passport).
- **CRUD** de notas (Creación, Edición, Listado, Eliminación).
- Listado de notas con opción de ordenamiento por **fecha de creación** o **fecha de vencimiento**.
- Comandos Artisan para gestionar notas desde la consola.
- Validación de datos.
- Migraciones para la base de datos (sin archivos SQL).

### Frontend (Vue.js)
- **Framework:** Vue 2.7+
- Manejo de rutas con **Vue Router**.
- Gestión de estado con **Vuex**.
- Pantallas:
  - Inicio de sesión y registro.
  - Pantalla principal con listado de notas.
  - Formulario para crear y editar notas.
- Validación de formularios en el frontend.
- Peticiones AJAX con **Axios**.

## Requisitos
- **PHP 7.3+** con Composer.
- **Node.js 14+** con npm o yarn.
- **MySQL 8** o **PostgreSQL 12** como motor de base de datos.

## Instalación

1. Clona el repositorio:
   ```bash
   git clone https://github.com/tu-usuario/tu-repositorio.git
   cd tu-repositorio


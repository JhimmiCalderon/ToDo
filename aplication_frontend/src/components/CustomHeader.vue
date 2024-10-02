<template>
    <div>
      <nav class="navbar navbar-dark bg-dark">
        <div class="container-fluid">
          <a class="navbar-brand" href="#">
            <img src="@/assets/logo.png" alt="Logo" width="80" height="24" class="d-inline-block align-text-top">
            ToDo
            
          </a>
          
          <!-- Botón de logout -->
          <button @click="logout" class="btn btn-outline-light"><i class="fa-solid fa-right-from-bracket"></i></button>
        </div>
      </nav>
    </div>
  </template>
  
  <script>
  import axios from 'axios';
  export default {
    methods: {
      // Método para cerrar sesión
      logout() {
         // Obtiene el token JWT almacenado en localStorage
        const token = localStorage.getItem('token');
        if (!token) {
          // Si no se encuentra un token, muestra un error en la consola y sale del método
          console.error('No token found');

          return;
          
        }
         // Realiza una solicitud POST a la API para cerrar sesión
        axios.post('http://localhost:8000/api/auth/logout', {}, { // El segundo parámetro es un objeto vacío (para el cuerpo)
             // El segundo parámetro es un objeto vacío ya que no se envía información en el cuerpo de la solicitud
          headers: {
             Authorization: `Bearer ${token}`
           }
          })
            .then(response => {
            console.log('Logout successful:', response.data.message);
             // Elimina el token del localStorage
            localStorage.removeItem('token');
           
             // Redirige al usuario a la página principal ("/")
             this.$router.push('/');
             window.location.reload(true);
            
            })
                .catch(error => {
            console.error('Error during logout:', error.response);
        });
      }
    }
    
  };
  </script>
  
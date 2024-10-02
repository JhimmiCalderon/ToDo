<template>
  <div class="about">
    <div class="wrapper fadeInDown">
      <div id="formContent">
        <!-- Icono -->
        <div class="fadeIn first">
          <img src="@/assets/logo.png" id="icon" alt="User Icon" />
          <h1>Register</h1>
          
        </div>

        <!-- Formulario de registro -->
        <form v-on:submit.prevent="register">
          
          <input 
            type="text"
            id="first_name"
            class="fadeIn second"
            name="first_name"
            placeholder="Firstname" 
            v-model="first_name"
          />
          <input 
            type="text"
            id="last_name"
            class="fadeIn second"
            name="last_name"
            placeholder="Lastname"
            v-model="last_name" 
          />
          <input 
            type="text" 
            id="email" 
            class="fadeIn second" 
            name="email" 
            placeholder="Email" 
            v-model="email" 
          />
          <input 
            type="password" 
            id="password"
            class="fadeIn third"
            name="password"
            placeholder="Password"
            v-model="password"  
          />
          <!-- Mostrar Error -->
        <div class="alert alert-danger" role="alert" v-if="error">
          {{ error }} <!-- se obtiene del estado de Vuex -->
        </div>   
        <!-- Mostrar Mensaje de Éxito -->

          <input 
            type="submit" 
            class="fadeIn fourth" 
            value="Register" 
          />
        </form>
       
      </div>
    </div>
  </div>
</template>

<script>
import { mapState } from 'vuex'; // Asegúrate de importar mapState


export default {
  name: "RegisterView",
  data() {
    return {
      first_name: "",
      last_name: "",
      email: "",
      password: ""
    };
  },
  computed: {
    ...mapState({
      error: state => state.error, // Mapea el estado de error de Vuex
      errorMessage: state => state.errorMessage, // Mapea el mensaje de error de Vuex
      successMessage: state => state.successMessage
    })
  },
  methods: {
    goToRegister() {
      this.$router.push('/register'); // La navegación sigue igual
    },
    register() {
      // Validación para asegurar que todos los campos estén llenos
      if (!this.first_name || !this.last_name || !this.email || !this.password) {
        this.$store.commit('setError', "Todos los campos son obligatorios."); // Enviar error a Vuex
        return;
      }

      // Crear el objeto JSON para la solicitud
      const json = {
        first_name: this.first_name,
        last_name: this.last_name,
        email: this.email,
        password: this.password
      };

      // Ahora se usa Vuex para gestionar la registración
      this.$store.dispatch('register', json)
        .then(() => {
          // Redirigir al usuario después de un registro exitoso
          this.$router.push("/");
        })
        .catch(error => {
          // Manejo de errores se realiza en Vuex
          if (error.response && error.response.data.error) {
            this.$store.commit('setError', error.response.data.error); // Enviar error desde la respuesta de la API a Vuex
          } else {
            this.$store.commit('setError', "Ocurrió un error al registrar al usuario en el Email."); // Mensaje de error genérico
          }
        });
    }
  }
};
</script>

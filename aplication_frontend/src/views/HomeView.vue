<template>
  <div class="home">
    <div class="wrapper fadeInDown">
      <div id="formContent">
       
        <div class="fadeIn first">
          <img src="@/assets/logo.png" id="icon" alt="User Icon" />
          <h1>Login</h1>
        </div>

        <!--Formulario del login-->
        <form v-on:submit.prevent="login">
          <input
            type="text"
            id="login"
            class="fadeIn second"
            name="login"
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
          <input
            type="submit"
            class="fadeIn fourth"
            value="Log In"
            :disabled="!isFormValid" 
          />
          <input
            type="button"
            class="fadeIn fourth"
            value="Register"
            @click="goToRegister"
          />
        </form>

        <!-- Mostrar Error -->
        <div class="alert alert-danger" role="alert" v-if="error">
          {{ error }}
           <!-- se obtiene del estado de Vuex -->
        </div>

        <!-- Mensaje de campos vacíos -->
        <div v-if="!isFormValid" class="alert alert-warning" role="alert">
          Por favor, complete todos los campos. <!-- Mensaje de advertencia -->
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'HomeView',
  data() {
    return {
      email: '',
      password: '',
    };
  },
  computed: {
    // Acceder al error desde Vuex
    error() {
      return this.$store.getters.getError; // Obtener el error desde Vuex en lugar de localmente
    },
    // Computed property para validar el formulario
    isFormValid() {
      return this.email.trim() !== '' && this.password.trim() !== '';
    }
  },
  methods: {
    goToRegister() {
      this.$router.push('/register'); // La navegación sigue igual
    },
    login() {
      const credentials = {
        email: this.email,
        password: this.password
      };
      // Ahora se usa Vuex para gestionar la autenticación
      this.$store.dispatch('login', credentials)
        .then(() => {
          // Redirigir al usuario después de un login exitoso
          this.$router.push('/notes/id');
        })
        .catch(() => {
          // Los errores se manejan en Vuex, no es necesario gestionar el error aquí
        });
    }
  }
};
</script>
  


<style>
/* Establece el tamaño y el margen del documento */
html, body {
  height: 100%; /* Asegura que ambos elementos ocupen toda la altura */
  margin: 0;
  padding: 0;
  background-color: #ffffff; /* El color de fondo que prefieras */
}

/* Estilo de enlaces */
a {
  color: #2d2d2b;
  display:inline-block;
  text-decoration: none;
  font-weight: 400;
}

/* Estilo del título principal h1 */
h1 {
  font-family: 'Poppins', sans-serif; /* Tipografía moderna */
  font-size: 0.8rem; /* Tamaño grande */
  text-transform: uppercase; /* Todas las letras en mayúsculas */
  font-weight: 500; /* Negrita */
  color: #383232; /* Color del texto */
  background: linear-gradient(90deg, #e8e6e6, #242627); /* Degradado en el texto */
  -webkit-background-clip: text; /* Clip para degradado */
  -webkit-text-fill-color: transparent; /* Texto transparente para ver el degradado */
  text-align: center; /* Centrado */
  letter-spacing: 2px; /* Espaciado entre letras */
  text-shadow: 4px 4px 10px rgba(0, 0, 0, 0.4); /* Sombra sutil del texto */
  margin-bottom: 20px; /* Espaciado debajo */
  animation: fadeIn 2s ease-in-out; /* Animación de entrada */
}

/* Animación de entrada para el título */
@keyframes fadeIn {
  0% {
    opacity: 0;
    transform: translateY(-50px);
  }
  100% {
    opacity: 1;
    transform: translateY(0);
  }
  /* Para el Tamaño */
}
#icon {
  width: 300px;
}


/* Estilo del subtítulo  */
h2 {
  text-align: center;
  font-size: 16px;
  font-weight: 600;
  text-transform: uppercase;
  display:inline-block;
  margin: 40px 8px 10px 8px; 
  color: #cccccc;
}

/* Contenedor principal que alinea el contenido */
.wrapper {
  display: flex;
  align-items: center;
  flex-direction: column; 
  justify-content: center;
  width: 100%;
  min-height: 100%;
  padding: 30px;
}

/* Estilo del contenido del formulario */
#formContent {
  -webkit-border-radius: 10px 10px 10px 10px;
  border-radius: 10px 10px 10px 10px;
  background: #fff;
  padding: 30px;
  width: 90%;
  max-width: 450px;
  position: relative;
  padding: 0px;
  -webkit-box-shadow: 0 30px 60px 0 rgba(0,0,0,0.3);
  box-shadow: 0 30px 60px 0 rgba(0,0,0,0.3);
  text-align: center;
}

/* Estilo del pie del formulario */
#formFooter {
  background-color: #f6f6f6;
  border-top: 1px solid #dce8f1;
  padding: 25px;
  text-align: center;
  -webkit-border-radius: 0 0 10px 10px;
  border-radius: 0 0 10px 10px;
}

/* Estilo para h2 inactivo y activo */
h2.inactive {
  color: #cccccc;
}

h2.active {
  color: #0d0d0d;
  border-bottom: 2px solid #050505;
}

/* Estilo de botones de entrada */
input[type=button], input[type=submit], input[type=reset]  {
  background-color: #272424;
  border: none;
  color: white;
  padding: 15px 70px;
  text-align: center;
  text-decoration: none;
  display: inline-block;
  text-transform: uppercase;
  font-size: 15px;
  -webkit-box-shadow: 0 10px 30px 0 rgba(51, 58, 61, 0.4);
  box-shadow: 0 10px 30px 0 rgba(55, 57, 58, 0.4);
  -webkit-border-radius: 5px 5px 5px 5px;
  border-radius: 5px 5px 5px 5px;
  margin:  20px 10px 20px;
  -webkit-transition: all 0.3s ease-in-out;
  -moz-transition: all 0.3s ease-in-out;
  -ms-transition: all 0.3s ease-in-out;
  -o-transition: all 0.3s ease-in-out;
  transition: all 0.3s ease-in-out;
}

/* Efecto hover para botones */
input[type=button]:hover, input[type=submit]:hover, input[type=reset]:hover  {
  background-color: #464040;
}

/* Efecto al hacer clic en botones */
input[type=button]:active, input[type=submit]:active, input[type=reset]:active  {
  -moz-transform: scale(0.95);
  -webkit-transform: scale(0.95);
  -o-transform: scale(0.95);
  -ms-transform: scale(0.95);
  transform: scale(0.95);
}

/* Estilo de campos de texto */
input[type=text], input[type=password] {
  background-color: #f6f6f6;
  border: none;
  color: #0d0d0d;
  padding: 8px 32px;
  text-align: center;
  text-decoration: none;
  display: inline-block;
  font-size: 16px;
  margin: 5px;
  width: 85%;
  border: 2px solid #f6f6f6;
  -webkit-transition: all 0.5s ease-in-out;
  -moz-transition: all 0.5s ease-in-out;
  -ms-transition: all 0.5s ease-in-out;
  -o-transition: all 0.5s ease-in-out;
  transition: all 0.5s ease-in-out;
  -webkit-border-radius: 5px 5px 5px 5px;
  border-radius: 5px 5px 5px 5px;
}

/* Efecto de enfoque para campos de texto */
input[type=text]:focus, input[type=password]:focus {
  background-color: #fff;
  border-bottom: 2px solid #202324;
}

/* Estilo de marcador de posición en campos de texto */
input[type=text]:placeholder, input[type=password]:placeholder {
  color: #cccccc;
}

/* Animaciones de entrada para diferentes efectos */
.fadeInDown {
  -webkit-animation-name: fadeInDown;
  animation-name: fadeInDown;
  -webkit-animation-duration: 1s;
  animation-duration: 1s;
  -webkit-animation-fill-mode: both;
  animation-fill-mode: both;
}

@-webkit-keyframes fadeInDown {
  0% {
    opacity: 0;
    -webkit-transform: translate3d(0, -100%, 0);
    transform: translate3d(0, -100%, 0);
  }
  100% {
    opacity: 1;
    -webkit-transform: none;
    transform: none;
  }
}

@keyframes fadeInDown {
  0% {
    opacity: 0;
    -webkit-transform: translate3d(0, -100%, 0);
    transform: translate3d(0, -100%, 0);
  }
  100% {
    opacity: 1;
    -webkit-transform: none;
    transform: none;
  }
}

/* Animaciones de desvanecimiento */
@-webkit-keyframes fadeIn { from { opacity:0; } to { opacity:1; } }
@-moz-keyframes fadeIn { from { opacity:0; } to { opacity:1; } }
@keyframes fadeIn { from { opacity:0; } to { opacity:1; } }

.fadeIn {
  opacity:0;
  -webkit-animation:fadeIn ease-in 1;
  -moz-animation:fadeIn ease-in 1;
  animation:fadeIn ease-in 1;

  -webkit-animation-fill-mode:forwards;
  -moz-animation-fill-mode:forwards;
  animation-fill-mode:forwards;

  -webkit-animation-duration:1s;
  -moz-animation-duration:1s;
  animation-duration:1s;
}

/* Animaciones de desvanecimiento con demora */
.fadeIn.first {
  -webkit-animation-delay: 0.4s;
  -moz-animation-delay: 0.4s;
  animation-delay: 0.4s;
}

.fadeIn.second {
  -webkit-animation-delay: 0.6s;
  -moz-animation-delay: 0.6s;
  animation-delay: 0.6s;
}

.fadeIn.third {
  -webkit-animation-delay: 0.8s;
  -moz-animation-delay: 0.8s;
  animation-delay: 0.8s;
}

/* Estilo para un contenedor adicional */
#content {
  margin: 0;
  padding: 0;
  display: flex;
  justify-content: center;
  flex-direction: column;
}
</style>

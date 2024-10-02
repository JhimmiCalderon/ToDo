import { createStore } from 'vuex'; // Solo importar createStore de vuex
import axios from 'axios';

const store = createStore({
  state: {
    error: null // Estado para almacenar errores
  },
  mutations: {
    setError(state, errorMessage) { // Mutación para establecer el error
      state.error = errorMessage;
    }, 
    CLEAR_ERROR(state) {
      state.error = null; // Limpiar error
    },
    SET_SUCCESS_MESSAGE(state, message) {
      state.successMessage = message; // Establecer mensaje de éxito
    },
    CLEAR_SUCCESS_MESSAGE(state) {
      state.successMessage = null; // Limpiar mensaje de éxito
    },
  },
  actions: {  
    register({ commit }, json) {
      // Limpia cualquier mensaje de error o éxito previo antes de iniciar el registro
      commit('CLEAR_ERROR');
      commit('CLEAR_SUCCESS_MESSAGE'); // Asegúrate de tener esta mutación en tu store
    
      return axios
        .post("http://127.0.0.1:8000/api/auth/register", json)
        .then(response => {
          // Si el registro es exitoso, establece el mensaje de éxito
          commit('SET_SUCCESS_MESSAGE', "¡Registro exitoso!"); // Establece el mensaje de éxito
    
          // Limpia el mensaje de éxito después de 5 segundos (5000 ms)
          setTimeout(() => {
            commit('CLEAR_SUCCESS_MESSAGE'); // Limpia el mensaje de éxito
          }, 3000);
        })
        .catch(error => {
          // Manejo de errores
          if (error.response && error.response.data.error) {
            commit('SET_ERROR', error.response.data.error); // Establece el error en el store
          } else {
            commit('SET_ERROR',  "Ocurrió un error al registrar al usuario."); // Mensaje de error genérico
          }
          throw error; // Lanza el error para que sea manejado en el componente
        });
    },
    // Otros actions...
   
    login({ commit }, credentials) { // Acción para manejar el inicio de sesión
      return axios
        .post('http://127.0.0.1:8000/api/auth/login', credentials)
        .then(response => {
          if (response.status === 200 && response.data.access_token) {
            localStorage.setItem('token', response.data.access_token);
          }
        })
        .catch(error => {
          if (error.response && error.response.status === 401) {
            commit('setError', error.response.data.error); // Commitear el error si la respuesta es 401
          }
        });
    }
  },
  getters: {
    getError(state) { // Getter para acceder al error
      return state.error;
    }
  }
});

export default store; // Exportar la tienda

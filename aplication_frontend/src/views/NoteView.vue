<template>
  <div>
    <CustomHeader />

     <!-- Mensaje de bienvenida Para saber quien esta autenticado -->
     <h3 v-if="user" style="text-align: center; font-size: 1.25em;">
        Bienvenido, {{ user.first_name }} {{ user.last_name }}. 
        <a>Esta es su aplicación de gestión de notas "ToDo". Organice sus tareas, 
          mantenga el control y sea más productivo. ¡Úsela para llevar sus ideas 
          y proyectos al siguiente nivel!</a>
      </h3>

   

    <!-- Selectores para el criterio de ordenación y el orden -->
    <div class="sort-options">
       <!-- Botón para crear una nueva nota -->
    <button @click="openCreateModal" class="create-note-btn"><i class="fa-solid fa-note-sticky"></i>  Nueva nota</button>
      <label for="sort_by"> Ordenar por: </label>
      <select v-model="sortBy" id="sort_by">
        <option value="created_at">Fecha de creación</option>
        <option value="due_date">Fecha de vencimiento</option>
      </select>

      <label for="sort_order"> Orden:</label>
      <select v-model="sortOrder" id="sort_order">
        <option value="asc">Ascendente</option>
        <option value="desc">Descendente</option>
      </select>

      <!-- Botón para aplicar la ordenación -->
      <button @click="loadNotes" class="create-note-btn"><i class="fa-solid fa-magnifying-glass"></i></button>
    </div>

    <div class="card-container">
      <!-- Bucle sobre las notas -->
      <div v-for="note in notes" :key="note.id" class="card">
        <div class="card-icons">
          <span class="tag-icon" @click="openTagModal(note)" title="Agregar etiqueta">
            <i class="fa-solid fa-tag"></i>
          </span>

          <span class="edit-icon" @click="openEditModal(note)" title="Editar nota">
            <i class="fa-solid fa-pen"></i>
          </span>

          <span class="delete-icon" @click="deleteNote(note.id)" title="Eliminar nota">
            <i class="fa-solid fa-trash"></i>
          </span>

          <p v-if="note.tag" class="note-tag">{{ note.tag }}</p>
        </div>
       
       
        <div class="view-image-icon" @click="openImageModal(note.image)" title="Ver Imagen"><i class="fa-solid fa-eye"></i></div>

        <br />
         <!-- Card -->
        <div class="card-body">
          <h5 class="card-title">{{ note.title }}</h5>
          <p class="card-text">{{ note.description }}</p>
          <p class="card-text">
            <small class="text-muted">
              Fecha de Vencimiento: {{ new Date(note.due_date).toLocaleString('es-ES', { dateStyle: 'short', timeStyle: 'short' }) }}
            </small>
          </p>

          <p class="card-text">
             <small class="text-muted">
              Creado el: {{ new Date(note.created_at).toLocaleDateString() }}
             </small>
          </p>

        </div>
      </div>
    </div>

    <!-- Modal para crear una nueva nota -->
    <div v-if="showCreateModal" class="modal" @click="closeModal">
      <div class="modal-content create-modal edit-modal" @click.stop>
        <span class="close" @click="closeModal">&times;</span>
        <h3>Nota</h3>
        <form @submit.prevent="createNote">
          <div class="form-group">
            <label for="title">Titulo</label>
            <input type="text" id="title" v-model="newNote.title" required />
          </div>
          <div class="form-group">
            <label for="description">Descripción</label>
            <textarea id="description" v-model="newNote.description" required></textarea>
          </div>
          <div class="form-group">
            <label for="due_date">Fecha de Vencimiento</label>
            <input type="datetime-local" id="due_date" v-model="newNote.due_date" required />
          </div>
          <div class="form-group">
            <label for="image">Imagen Url <small>(opcional)</small></label>
            <input type="text" id="image" v-model="newNote.image" />
          </div>

          <button class="create-note-btn" type="submit"><i class="fa-solid fa-check"></i> Crear</button>
        </form>
      </div>
    </div>

    <!-- Modal para mostrar la imagen -->
    <div v-if="showImageModal" class="modal" @click="closeModal">
      <div class="modal-content edit-modal">
        <span class="close" @click="closeModal">&times;</span>
        <template v-if="currentImage">
          <img :src="currentImage" alt="Note Image" />
        </template>
        <template v-else>
          <p class="no-image-message">No hay imagen para mostrar!!</p>
        </template>
      </div>
    </div>

    <!-- Modal para editar las notas -->
    <div v-if="showEditModal" class="modal" @click="closeModal">
      <div class="modal-content edit-modal" @click.stop>
        <span class="close" @click="closeModal">&times;</span>
        <h3>Editar Notas</h3>
        <form @submit.prevent="updateNote">
          <div class="form-group">
            <label for="title">Titulo</label>
            <input type="text" id="title" v-model="editedNote.title" required />
          </div>
          <div class="form-group">
            <label for="description">Descripción</label>
            <textarea id="description" v-model="editedNote.description" required></textarea>
          </div>
          <div class="form-group">
            <label for="due_date">Fecha de Vencimiento</label>
            <input type="datetime-local" id="due_date" v-model="editedNote.due_date" required />
          </div>
          <button class="create-note-btn" type="submit"><i class="fa-solid fa-check"></i> Guardar</button>
        </form>
      </div>
    </div>

    <!-- Modal para gestionar etiquetas -->
    <div v-if="showTagModal" class="modal">
      <div class="modal-content edit-modal">
        <span @click="closeTagModal" class="close">&times;</span>
        <h3>Etiqueta</h3>
        <input v-model="tag" placeholder="Ingrese su etiqueta" />
        <br>
        <button class="create-note-btn" @click="saveTag"><i class="fa-solid fa-check"></i> Guardar</button>
      </div>
    </div>

    <CustomFooter />
  </div>
</template>

<script>
import CustomHeader from '@/components/CustomHeader.vue';
import CustomFooter from '@/components/CustomFooter.vue';
import axios from 'axios';

export default {
  name: 'NoteView',
  data() {
    return {
      user: null, //Almacena la información
      showImageModal:false, //Controla la visibilidad del modal
      showEditModal: false,
      showCreateModal: false,
      showTagModal: false,
      currentImage: null,
      editedNote: null,
      newNote: { //Objetos para almacenar la información.
        title: '',
        description: '',
        due_date: '',
        tag: '',
        image: ''
      },
      tagNote: null,
      notes: [], // Inicializa la lista de notas
      sortBy: 'created_at', // Criterio de ordenación por defecto
      sortOrder: 'asc' // Orden por defecto
    };
  },
  components: {
  CustomHeader, // Componente para el encabezado personalizado
  CustomFooter  // Componente para el pie de página personalizado
},
mounted() {
  // Hook del ciclo de vida que se ejecuta cuando el componente se monta en el DOM
  this.loadNotes(); // Llama a la función para cargar las notas desde el servidor
  this.getUserInfo(); // Llama a la función para obtener información del usuario autenticado
},
methods: {
  // Aquí se definen los métodos (funciones) que pueden ser utilizados en el componente

    loadNotes() {
      // Obtiene el token de autenticación del almacenamiento local
      let token = localStorage.getItem('token');
      // Realiza una solicitud GET para cargar las notas desde el servidor
      axios.get('http://localhost:8000/api/notes', {
          headers: {
            Authorization: `Bearer ${token}` // Incluye el token en el encabezado de autorización
          },
          //Parámetros para ordenar 
          params: {
            sort_by: this.sortBy,
            sort_order: this.sortOrder
          }
        })
        .then(response => {
          console.log('Notes loaded:', response.data.data);// Muestra las notas cargadas en la consola
          this.notes = response.data.data;// Asigna las notas a la propiedad `notes`
        })
        .catch(error => {
          console.error('Error loading notes:', error.response);
        });
    },
    getUserInfo() {
      let token = localStorage.getItem('token');
      if (token) {
         // Realiza una solicitud POST para obtener la información del usuario autenticado
        axios.post('http://localhost:8000/api/auth/me', {}, {
          headers: {
            Authorization: `Bearer ${token}`,
          }
        })
        .then(response => {
          console.log('User info fetched successfully', response.data.user);
          this.user = response.data.user;// Asigna la información del usuario a la propiedad `user`
        })
        .catch(error => {
          console.error('Error fetching user information', error.response);
        });
      } else {
        console.error('No token found in localStorage.');
      }
    },
    openImageModal(image) {
      // Asigna la imagen seleccionada a `currentImage` y muestra el modal de imagen
      this.currentImage = image;
      this.showImageModal = true;
    },
    closeModal() {
       // Cierra todos los modales abiertos
      this.showImageModal = false;
      this.showEditModal = false;
      this.showCreateModal = false;
      this.showTagModal = false;
    },
    openEditModal(note) {
       // Crea una copia de la nota a editar y abre el modal de edición
      this.editedNote = { ...note };
      this.showEditModal = true;
    },
    updateNote() {
      let token = localStorage.getItem('token');
      // Realiza una solicitud PUT para actualizar la nota editada en el servidor
      axios.put(`http://localhost:8000/api/notes/${this.editedNote.id}`, this.editedNote, {
          headers: {
            Authorization: `Bearer ${token}`
          }
        })
        .then(response => {
          console.log('Note updated:', response.data.data);
          let index = this.notes.findIndex(note => note.id === this.editedNote.id);
          if (index !== -1) {
            this.notes.splice(index, 1, response.data.data);
          }
          this.showEditModal = false;
        })
        .catch(error => {
          console.error('Error updating note:', error.response);
        });
    },
    openTagModal(note) {
      // Guarda la nota actual y carga la etiqueta si existe, luego abre el modal de etiquetas
      this.note = note; // Guarda la nota actual
      this.tag = note.tag || ''; // Carga la etiqueta si existe
      this.showTagModal = true; // Abre el modal
    },
    closeTagModal() {
      // Cierra el modal de etiquetas y limpia la etiqueta
      this.showTagModal = false; // Cierra el modal
      this.tag = ''; // Limpia la etiqueta
    },
    saveTag() {
  let token = localStorage.getItem('token');
  
  // Crea un nuevo objeto con todos los datos de la nota
  const updatedNote = {
    ...this.note, // Copia todos los datos existentes
    tag: this.tag // Actualiza la etiqueta con el nuevo valor
  };

  axios.put(`http://localhost:8000/api/notes/${this.note.id}`, updatedNote, {
    headers: {
      Authorization: `Bearer ${token}`
    }
  })
  .then(response => {
    console.log('Tag saved:', response.data.data);
    let index = this.notes.findIndex(note => note.id === this.note.id);
    if (index !== -1) {
      // Actualiza la nota en la lista con la respuesta del servidor
      this.notes[index] = response.data.data; 
    }
    this.closeTagModal(); // Cierra el modal
  })
  .catch(error => {
    console.error('Error saving tag:', error.response.data);
    alert("Error saving tag:");
  });
},

    openCreateModal() {
      this.showCreateModal = true; // Abre el modal de creación
    },
    createNote() {
      let token = localStorage.getItem('token');
      axios
        .post('http://localhost:8000/api/notes', this.newNote, {
          headers: {
            Authorization: `Bearer ${token}`
          }
        })
        .then(response => {
          console.log('Note created:', response.data.data); // Ver en consola la nueva nota creada
          this.notes.push(response.data.data);
          this.showCreateModal = false;
          this.newNote = { title: '', description: '', due_date: '', image: '' }; // Restablece newNote
        })
        .catch(error => {
          console.error('Error creating note:', error.response); // Imprimir errores
        });
    },
    deleteNote(noteId) {
      axios
        .delete(`http://localhost:8000/api/notes/${noteId}`, {
          headers: {
            Authorization: `Bearer ${localStorage.getItem('token')}`
          }
        })
        .then(response => {
          console.log('Note deleted:', noteId); // Ver en consola la nota eliminada
          this.notes = this.notes.filter(note => note.id !== noteId);
        })
        .catch(error => {
          console.error('Error deleting note:', error.response); // Imprimir errores
        });
    }
  }
};
</script>


<style scoped>

.create-note-btn:hover {
  background-color: #363937;
}

.card-container {
  display: flex;
  flex-wrap: wrap;
  gap: 20px;
  justify-content: space-around;
  padding: 20px;
  margin-top: 20px;
  margin-bottom: 20px;
}

.card {
  width: 18rem;
  position: relative;
  border: 1px solid #ddd;
  border-radius: 10px;
  box-shadow: 2px 2px 5px rgba(0, 0, 0, 0.1);
  padding: 10px;
}

.card-img-top {
  width: 100%;
  height: 150px;
  object-fit: cover;
  border-radius: 10px 10px 0 0;
}

.card-body {
  padding: 15px;
}

.card-title {
  font-size: 1.2rem;
}

.card-text {
  font-size: 0.9rem;
}

.card-icons {
  position: absolute;
  top: 10px;
  right: 10px;
}

.delete-icon,
.edit-icon {
  cursor: pointer;
  margin-left: 10px;
}

.view-image-icon {
  cursor: pointer;
  position: absolute;
  top: 10px;
  left: 10px;
}

.modal {
  position: fixed;
  z-index: 1;
  left: 0;
  top: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0, 0, 0, 0.8);
  display: flex;
  justify-content: center;
  align-items: center;
}

.modal-content {
  background-color: white;
  padding: 20px;
  border-radius: 10px;
  position: relative;
}

.modal-content img {
  width: 100%;
  max-width: 500px;
  height: auto;
}

.close {
  position: absolute;
  top: 10px;
  right: 10px;
  font-size: 24px;
  cursor: pointer;
}

.edit-modal {
  width: 400px;
  max-width: 90%;
}

.form-group {
  margin-bottom: 15px;
}

.form-group label {
  display: block;
  font-weight: bold;
}

.form-group input,
.form-group textarea {
  width: 100%;
  padding: 10px;
  border: 1px solid #ddd;
  border-radius: 5px;
}

button {
  padding: 10px 20px;
  background-color: rgba(26, 26, 26, 0.733)
}
.sort-options {
  display: flex;
  flex-direction: row; 
  gap: 10px; /* Espacio entre los elementos */
  padding: 10px;
  background-color: #f8f9fa; /* Color de fondo suave */
  border-radius: 8px; /* Bordes redondeados */
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1); /* Sombra suave */
}

.create-note-btn,
.apply-btn {
  background-color: #121313; /* Color del botón */
  color: white; /* Color del texto */
  border: none; /* Sin borde */
  border-radius: 5px; /* Bordes redondeados */
  padding: 5px 10px; /* Reducir el espaciado interno */
  cursor: pointer; /* Cambiar cursor al pasar sobre el botón */
  transition: background-color 0.3s; /* Transición suave */
  font-size: 0.8em; /* Tamaño de fuente más pequeño */
}

.create-note-btn:hover,
.apply-btn:hover {
  background-color: #272829; /* Color más oscuro al pasar el ratón */
}

.select-container {
  display: flex;
  flex-direction: column;
  flex-basis: 100px; /* Establecer un ancho específico si es necesario */
}

.sort-label {
  font-weight: bold; /* Hacer la etiqueta más destacada */
  margin-bottom: 2px; /* Espacio entre la etiqueta y el select */
}

.sort-select {
  padding: 5px; /* Reducir el espaciado interno para que se vea más pequeño */
  border-radius: 5px; /* Bordes redondeados */
  border: 1px solid #ccc; /* Borde suave */
  font-size: 0.8em; /* Tamaño de fuente más pequeño */
  transition: border-color 0.3s; /* Suavizar el cambio de color del borde */
}

.sort-select:focus {
  border-color: #272728; /* Color del borde al enfocar */
  outline: none; /* Quitar el contorno predeterminado */
}
.note-tag {
  display: inline-block; /* Para que el recuadro se ajuste al contenido */
  padding: 2px 6px; /* Reducir el espaciado interno */
  border: 1px solid #ccc; /* Borde suave */
  border-radius: 10px; /* Bordes redondeados más pequeños */
  background-color: #f0f0f0; /* Color de fondo */
  font-size: 0.7em; /* Tamaño de fuente más pequeño */
  color: #333; /* Color del texto */
  margin: 5px 0; /* Espaciado externo */
  margin-left: 20px; /* Corre el recuadro hacia la derecha */
}
.no-image-message {
  color: #333; /* Color más oscuro */
  font-weight: bold; /* Hacer el texto más grueso */
  font-size: 18px; /* Puedes ajustar el tamaño según lo necesites */
  text-align: center; /* Centrar el texto */
}



</style>

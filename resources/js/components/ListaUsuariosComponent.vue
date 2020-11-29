<template>
  <div>
    <v-card>
      <v-card-title>
        Usuarios
        <v-spacer></v-spacer>
        <v-text-field
          v-model="search"
          append-icon="mdi-magnify"
          label="Buscar"
          single-line
          hide-details
        ></v-text-field>
      </v-card-title>
      <v-data-table :headers="headers" :items="usuarios" :search="search" >
        <template v-slot:[`item.actions`]="{ item }">
          <v-tooltip bottom>
            <template v-slot:activator="{ on, attrs }">
              <v-icon
                v-bind="attrs"
                v-on="on"
                class="mr-2"
                @click="editItem(item)"
                color="success"
              >mdi-pencil</v-icon>
            </template>
            <span>Editar</span>
          </v-tooltip>

          <v-tooltip bottom>
            <template v-slot:activator="{ on, attrs }">
              <v-icon
                v-bind="attrs"
                v-on="on"
                @click="[eliminarUsuarioModal = true, usuarioEliminar = item]"
                color="error"
              >mdi-delete</v-icon>
            </template>
            <span>Eliminar</span>
          </v-tooltip>

          <v-tooltip bottom>
            <template v-slot:activator="{ on, attrs }">
              <v-icon
                v-bind="attrs"
                v-on="on"
                right
                @click="gestionarRelaciones(item)"
                color="primary"
              >mdi-account-group</v-icon>
            </template>
            <span>Relaciones</span>
          </v-tooltip>
        </template>
      </v-data-table>
    </v-card>

    <v-dialog v-model="editarUsuarioModal" max-width="600px">
      <editar-usuario :usuario="usuarioEditar"></editar-usuario>
    </v-dialog>

    <v-dialog v-model="eliminarUsuarioModal" max-width="320">
      <v-card>
        <v-card-title class="headline">Desea borrar el usuario?</v-card-title>
        <v-card-text>Este usuario no podra participar de ningun torneo, no saldrá en el ranking pero seguirá quedando registro de sus participaciones en torneos. ¿Desea continuar?.</v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn color="green darken-1" outlined @click="[eliminarUsuarioModal = false]">CANCELAR</v-btn>
          <v-btn color="error" @click="[deleteItem(),eliminarUsuarioModal = false]">BORRAR</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>

    <v-dialog v-model="usuarioRelacionesModal" id="adddd" max-width="700px">
      <relaciones-usuario :usuario="usuarioRelaciones"></relaciones-usuario>
    </v-dialog>
  </div>
</template>

<script>
export default {
  data() {
    return {
      usuarios: [],
      search: "",
      headers: [
        { text: "Nombre", value: "nombre"},
        { text: "Apellido", value: "apellido" },
        { text: "Mail", value: "mail", sortable: false },
        { text: "Telefono", value: "telefono", sortable: false },
        { text: "Fecha de alta", value: "fechaAlta", sortable: true },
        { text: "Acciones", value: "actions", sortable: false },
      ],
      usuarioEditar: [],
      editarUsuarioModal: false,
      usuarioEliminar: [],
      eliminarUsuarioModal: false,
      usuarioRelaciones: [],
      usuarioRelacionesModal: false,
    };
  },

  methods: {
    deleteItem() {
      let me = this;

      axios
        .delete(`/usuario/${me.usuarioEliminar.id}`)
        .then(function (res) {
          me.created();
          me.usuarioEliminar = [];
        })
        .catch(function (error) {
          console.log(error);
        });
    },

    editItem(item) {
      this.usuarioEditar = item;
      this.editarUsuarioModal = true;
    },

    gestionarRelaciones(item) {
      this.usuarioRelaciones = [];
      this.usuarioRelaciones = item;
      this.usuarioRelacionesModal = true;
    },

    created() {
      axios.get("/usuario").then((res) => {
        this.usuarios = res.data;
        this.usuarios.forEach(usuario =>{
          usuario.fechaAlta = this.darFormatoFecha(usuario.created_at)
        })

      })
      .catch(error => console.log(error));
    },
    darFormatoFecha(fecha) {
            if (!fecha) return null;
            fecha = fecha.substr(0,10);
            console.log(fecha);
            const [anio, mes, dia] = fecha.split("-");
            return `${dia}/${mes}/${anio}`;
        }
  },

  mounted() {
    this.created();
  },
};
</script>

<template>
  <div>
    <v-card>
      <v-card-title>

        <v-checkbox
          v-model="verSocios"
          @click="filtrar()"
          hide-details
          label="Ver Socios"
        ></v-checkbox>
        
        <v-spacer></v-spacer>

        <v-checkbox
          v-model="verNoSocios"
          @click="filtrar()"
          hide-details
          label="Ver No Socios"
        ></v-checkbox>

        <v-spacer></v-spacer>
        
        <v-text-field
          v-model="search"
          append-icon="mdi-magnify"
          label="Buscar"
          single-line
          hide-details
        ></v-text-field>
      </v-card-title>
      
      <v-data-table :headers="headers" :items="usuariosFiltrados" :search="search"> <!-- :custom-filter="filtrarPorSocio" -->
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
      <editar-usuario :usuario="usuarioEditar" @reFiltrar = 'reFiltrar = $event'></editar-usuario>
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
  name: 'vm',
  data() {
    return {
      reFiltrar: false,
      verSocios: true,
      verNoSocios: false,
      usuarios: [],
      usuariosFiltrados: [],
      search: "",
      headers: [
        { text: "Apellido", value: "apellido" },
        { text: "Nombre", value: "nombre"},
        { text: "DNI", value: "dni"},
        { text: "Mail", value: "mail", sortable: true, filterable: false},
        { text: "Telefono", value: "telefono", sortable: false, filterable: false},
        { text: "Fecha de alta", value: "fechaAlta", sortable: true, filterable: false},
        { text: "Acciones", value: "actions", sortable: false, filterable: false},
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

    async created() {
        await axios.get("/usuario").then((res) => {
        this.usuarios = res.data;
        this.usuarios.forEach(usuario =>{
          usuario.fechaAlta = this.darFormatoFecha(usuario.created_at);
        })

      })
      .catch(error => console.log(error));
      this.filtrar();
    },
    darFormatoFecha(fecha) {
            if (!fecha) return null;
            fecha = fecha.substr(0,10);
            const [anio, mes, dia] = fecha.split("-");
            return `${dia}/${mes}/${anio}`;
        },

    /*filtrarPorSocio(value, search, items) {
      
      let inName = RegExp(search,'i').test(items.nombre);
      let inLastname = RegExp(search,'i').test(items.apellido);

      //if (items.socio==false && this.verNoSocios==true ||items.socio==true && this.verSocios==true){condiciones_socio_noSocio.push(this,items);}
      if (items.socio==false && this.verNoSocios==true ||items.socio==true && this.verSocios==true){return true;}
      else return false;

      /*let condiciones_socio_noSocio = items.filter(unItem => {
          if (unItem.socio==false && this.verNoSocios==true || unItem.socio==true && this.verSocios==true) {
            return true;}
          else return false;
        });
        
       console.log();

      return inName || inLastname;
    },*/

  

    filtrar(){
      this.usuariosFiltrados=[];

      this.usuarios.forEach(usuario => {
        if((usuario.socio==false && this.verNoSocios==true) || (usuario.socio==true && this.verSocios==true)) {this.usuariosFiltrados.push(usuario);}
      });
    },


  },

      watch: {
            reFiltrar : function(){
                this.filtrar();
                this.reFiltrar=false;
            }
  },



  mounted() {
    this.created();
  },
};
</script>
<!-- Comment -->
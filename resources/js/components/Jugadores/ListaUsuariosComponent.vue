<template>
  <v-container fluid>
    <div class="d-flex mx-auto mb-9" style="justify-content: center">
      <h2 v-if="isListaSocios">Lista socios</h2>
      <h2 v-else>Listado de todos los jugadores</h2>
    </div>
    <v-card>
      <div class="mx-2">
        <v-text-field v-model="search" append-icon="mdi-magnify" label="Buscar" single-line hide-details></v-text-field>
      </div>

      <v-data-table :headers="headers" :items="usuarios" 
      :options.sync="options"
      :server-items-length="totalUsuarios" 
      :search="search"
      >
        <!-- :custom-filter="filtrarPorSocio" -->
        <template v-slot:[`item.actions`]="{ item }">
          <v-tooltip bottom>
            <template v-slot:activator="{ on, attrs }">
              <v-icon v-bind="attrs" v-on="on" class="mr-1" @click="editItem(item)" color="success">mdi-pencil</v-icon>
            </template>
            <span>Editar</span>
          </v-tooltip>
          <v-tooltip bottom>
            <template v-slot:activator="{ on, attrs }">
              <v-icon v-bind="attrs" v-on="on" class="mr-4" right @click="gestionarRelaciones(item)"
                color="primary">mdi-account-group</v-icon>
            </template>
            <span>Relaciones</span>
          </v-tooltip>
          <v-btn class="" color="primary" small @click="mostrarDetalleCuotasAdeudadas(item)">ver cuotas</v-btn>
        </template>

        <template v-slot:[`header.isSocio`]="{ header }">
          {{ header.text }}
          <v-tooltip bottom>
            <template v-slot:activator="{ on, attrs }">
              <v-icon style="margin-top: 0px" v-bind="attrs" v-on="on">mdi-help-circle-outline</v-icon>
            </template>
            <span>
              <v-icon color="blue"> mdi-star </v-icon> Socio
              <v-icon color="blue"> mdi-star-outline </v-icon> Socio Inactivo
              <v-icon> mdi-star-off-outline </v-icon> No Socio
            </span>
          </v-tooltip>
        </template>

        <template v-slot:[`item.isSocio`]="{ item }">
          <v-icon :color="elegirColorIcono(item)">
            {{ elegirIcono(item) }}
          </v-icon>
        </template>

        <template v-slot:[`item.cuotasAdeudadas`]="{ item }">
          {{ item.cuotasAdeudadas }}
        </template>
      </v-data-table>

      <!-- dialogs -->
      <v-dialog v-model="detalleCuotasAdeudadasModal">
        <detalle-cuotas-usuario :usuario="usuarioVerDetalleCuotas"></detalle-cuotas-usuario>
      </v-dialog>

      <v-dialog v-model="editarUsuarioModal" max-width="600px">
        <editar-usuario :usuario="usuarioEditar" @reFiltrar="reFiltrar = $event"></editar-usuario>
      </v-dialog>

      <v-dialog v-model="eliminarUsuarioModal" max-width="400">
        <v-card>
          <v-card-title class="headline">Desea borrar el usuario?</v-card-title>
          <v-card-text>Este usuario no podra participar de ningun torneo, no saldrá en el
            ranking pero seguirá quedando registro de sus participaciones en
            torneos. ¿Desea continuar?.</v-card-text>
          <v-container>
            <v-textarea outlined v-model="motivoBaja" label="Motivo de baja" hint="Debe especificar el motivo de baja"
              counter></v-textarea>
          </v-container>

          <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn color="green darken-1" outlined @click="[(eliminarUsuarioModal = false)]">CANCELAR</v-btn>
            <v-btn color="error" @click="[deleteItem()]">BORRAR</v-btn>
          </v-card-actions>
        </v-card>
      </v-dialog>

      <v-dialog v-model="usuarioRelacionesModal" id="adddd" max-width="700px">
        <relaciones-usuario :usuario="usuarioRelaciones"></relaciones-usuario>
      </v-dialog>
    </v-card>
  </v-container>
</template>

<script>
import { mapActions } from "vuex";
export default {
  props: {
    isListaSocios: Boolean,
  },
  name: "vm",
  data() {
    return {
    //data-table
      options: {},
      totalUsuarios: 0,
      reFiltrar: false,
      verSocios: true,
      verNoSocios: false,
      usuarios: [],
      usuariosFiltrados: [],
      search: "",
      headers: [
        { text: "Apellido", value: "apellido", width: '100px' },
        { text: "Nombre", value: "nombre", width: '100px' },
        { text: "DNI", value: "dni", width: '100px' },
        { text: "Mail", value: "mail", sortable: true, filterable: false },
        {
          text: "Telefono",
          value: "telefono",
          sortable: false,
          filterable: false,
        },
        {
          text: "Fecha de alta",
          value: "fechaAlta",
          sortable: true,
          filterable: false,
          width: '130px'
        },
        {
          text: "Cuotas adeudadas",
          value: "cuotasAdeudadas",
          sortable: true,
          sort: (a, b) => {
            return a - b;
          },
          width: '160px'
        },
        {
          text: "Socio",
          value: "isSocio",
          sortable: false,
          filterable: false,
          width: '100px'
        },
        {
          text: "Acciones",
          value: "actions",
          sortable: false,
          filterable: false,
        },
      ],
      usuarioEditar: [],
      usuarioVerDetalleCuotas: null,
      editarUsuarioModal: false,
      usuarioEliminar: [],
      motivoBaja: "",
      eliminarUsuarioModal: false,
      detalleCuotasAdeudadasModal: false,
      usuarioRelaciones: [],
      usuarioRelacionesModal: false,
    };
  },

  methods: {
    async getUsuarios() {
      const params = this.crearParametrosPaginado();

      await axios
        .get("/usuario", {params})
        .then((res) => {
          this.usuarios = res.data.usuarios.data;
          this.totalUsuarios = parseInt(res.data.usuarios.total);
          this.usuarios.forEach((usuario) => {
            usuario.fechaAlta = this.darFormatoFecha(usuario.created_at);
            usuario.cuotasAdeudadas = this.calcularCuotasAdeudadas(usuario);
          });
          if (this.isListaSocios) {
            this.verSocios = true;
            this.verNoSocios = false;
          } else {
            this.verSocios = false;
            this.verNoSocios = true;
          }
        })
        .catch((error) => console.log(error));
    },
    crearParametrosPaginado() {
  const params = new URLSearchParams([
    ['perPage', this.options.itemsPerPage],
    ['page', this.options.page],
    ['socio', this.isListaSocios],
    ['orderBy', this.options.sortBy],
    ['orderByDesc', this.options.sortDesc],
  ]);

  if (this.search && this.search.length >= 3) {
    params.append("search", this.search);
  }

  return params;
},
    //TODO hay que arreglar el tema de las cuotas
    ...mapActions(["callSnackbar"]),
    async deleteItem() {
      let me = this;

      if (this.motivoBaja.length >= 5) {
        try {
          const resp = await axios.delete(`/usuario/${me.usuarioEliminar.id}`, {
            data: { motivo: this.motivoBaja },
          });
          console.log(resp);
          me.created();
          me.usuarioEliminar = [];
          this.eliminarUsuarioModal = false;
          this.motivoBaja = "";
        } catch (error) {
          console.log(error);
        }
      } else {
        this.eliminarUsuarioModal = true;
        this.callSnackbar([
          "El motivo debe ser de al menos 5 caracteres",
          "error",
        ]);
      }
    },
    mostrarDetalleCuotasAdeudadas(item) {
      this.$router.push({
        path: `/usuarios/pagos`,
        query: { usuario: item.id },
      });
      // this.usuarioVerDetalleCuotas = item;
      // this.detalleCuotasAdeudadasModal = true;
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
    elegirIcono(item) {
      if (item.socio.socio && item.socio.activo) return "mdi-star";
      if (item.socio.socio && !item.socio.activo) return "mdi-star-outline";
      return "mdi-star-off-outline";
    },
    elegirColorIcono(item) {
      if (item.socio.socio && item.socio.activo) return "blue";
      if (item.socio.socio && !item.socio.activo) return "blue";
      return "black";
    },
    calcularCuotasAdeudadas(item) {
      if (item.cuotas == null || item.cuotas.length < 1) {
        return 0;
      }

      const cuotasOrdenadas = item.cuotas.sort((a, b) => {
        const dateA = new Date(a.periodo);
        const dateB = new Date(b.periodo);
        return dateA - dateB;
      });
      return cuotasOrdenadas
        .slice(cuotasOrdenadas.findLastIndex((cuota) => cuota.pago != null))
        .filter((cuota) => cuota.pago == null).length;
    },


    darFormatoFecha(fecha) {
      if (!fecha) return null;
      fecha = fecha.substr(0, 10);
      const [anio, mes, dia] = fecha.split("-");
      return `${dia}/${mes}/${anio}`;
    },
    // filtrar() {
    //   this.usuariosFiltrados = [];
    //   this.usuarios.forEach((usuario) => {
    //     if (!this.isListaSocios) {
    //       this.usuariosFiltrados.push(usuario);
    //     } else if (usuario.socio.socio) {
    //       this.usuariosFiltrados.push(usuario);
    //     }
    //   });
    // },
  },

  watch: {
    reFiltrar: function () {
      this.filtrar();
      this.reFiltrar = false;
    },
    options: {
        handler () {
          console.log('optionas:', this.options);
          this.getUsuarios();
        },
        deep: true,
      },
      search: {
    handler() {
      this.options.page = 1;
      this.getUsuarios();
    },
    deep: true,
  },
  },

};
</script>
<!-- Comment -->
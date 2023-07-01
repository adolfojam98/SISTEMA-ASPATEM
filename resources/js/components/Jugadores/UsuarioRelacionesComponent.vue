<template>
  <v-card>
    <v-container grid-list-xs>
      <h2 class="ma-5 mt-2">
        <center>
          Gestionar relaciones del usuario: {{ usuario.nombre }}
          {{ usuario.apellido }}
        </center>
      </h2>

      <div class="d-flex" style="justify-content: space-around">
        <div style="width: 250px">
          <v-autocomplete
            v-model="relacionadoCon"
            :items="usuarios"
            :item-text="nombreCompleto"
            item-value="id"
            dense
            label="Relacionado con"
          ></v-autocomplete>
        </div>

        <div style="width: 250px">
          <v-autocomplete
            v-model="relacion"
            :items="tipos"
            label="Tipo de relacion"
            dense
          ></v-autocomplete>
        </div>
      </div>

      <center>
        <v-btn color="primary" @click.prevent="agregarRelacion()"
          >Agregar relacion</v-btn
        >
      </center>

      <relaciones-usuario-lista
        :usuario="usuario"
        :nuevaRelacion="nuevaRelacion"
      ></relaciones-usuario-lista>
    </v-container>
  </v-card>
</template>

<script>
import { mapActions } from "vuex";
export default {
  props: ["usuario"],

  data() {
    return {
      relacion: null,
      relacionadoCon: null,
      usuarios: [],
      tipos: ["Amistad", "Familiar", "Referido"],

      exist: Boolean,
      nuevaRelacion: [],
    };
  },

  methods: {
    ...mapActions(["callSnackbar"]),
    nombreCompleto: (item) => item.apellido + " " + item.nombre,

    posibles() {
      axios
        .get(`/usuario/${this.usuario.id}/relacionables`)
        .then((res) => {
          this.usuarios = res.data;
        })
        .catch((e) =>
          this.callSnackbar(["Error al traer los usuarios", "error"])
        );
    },

    async agregarRelacion() {
      try {
        const existeRelacion = await axios.get("/usuario/relacion/existe", {
          params: {
            id_socio_A: this.usuario.id,
            id_socio_B: this.relacionadoCon,
          },
        });

        if (!existeRelacion.data) {
          const nuevaRelacion = await axios.post("/usuario/relacion", {
            id_socio_A: this.usuario.id,
            id_socio_B: this.relacionadoCon,
            relacion: this.relacion,
          });
          this.callSnackbar(["Relacion agregada correctamente", "success"]);
          this.nuevaRelacion = nuevaRelacion.data;
        } else {
          this.callSnackbar(["Esta relación ya existe", "warning"]);
        }
      } catch (e) {
        this.callSnackbar([
          "Error al guardar relacion, verifique datos cargados",
          "error",
        ]);
      }
    },
  },
  watch: {
    usuario: function () {
      this.posibles();
    },
  },

  mounted() {
    this.posibles();
  },
};
</script>

<template>
  <v-container>
    <v-card>
      <v-btn class="primary mt-2 ml-2" @click="$router.go(-1)">Volver</v-btn>
      <v-card-title class="justify-center">
        <center>
          <p>
            <b>{{ fecha_nombre }}</b>
          </p>
        </center>
      </v-card-title>
      <hr />
      <v-data-table :headers="headers" :items="ranking" :search="search">
        <template v-slot:[`item.puntos`]="{ item }">
          <p class="mt-4">{{ item.puntos }} ({{ item.puntos_ganados }})</p>
        </template>
      </v-data-table>
    </v-card>
    <v-btn class="mt-2 mb-2 primary" @click="getFechaExcel()"> Exportar Fecha </v-btn>
  </v-container>
</template>

<script>
export default {
  props: [],

  data() {
    return {
      ranking: [],
      fecha_nombre: null,
      fechaId: this.$route.params.id,
      search: "",
      headers: [
        { text: "Nombre", value: "nombre" },
        { text: "Apellido", value: "apellido" },
        { text: "Dni", value: "dni" },
        { text: "Puntos", value: "puntos" },
        { text: "Categoria", value: "categoria" },
      ],
    };
  },

  methods: {
    getFecha() {
      axios.get(`/torneo/fecha/${this.fechaId}`).then((res) => {
        this.fecha_nombre = res.data.fecha_nombre;
        this.ranking = res.data.ranking;
      });
    },
    getFechaExcel() {
        window.open(`/export-fecha/${this.fechaId}`,"_self");
    }
  },

  created() {
    this.getFecha();
  },
};
</script>
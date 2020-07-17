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
      <v-data-table :headers="headers" :items="usuarios" :search="search"></v-data-table>
    </v-card>
  </div>
</template>

<script>
export default {
  data() {
    return {
      usuarios: [],
      search: "",
      headers: [
        {
          text: "Usuarios",
          align: "start",
          sortable: false,
          value: "nombre"
        },
        { text: "Apellido", value: "apellido" },
        { text: "Mail", value: "mail" },
        { text: "Telefono", value: "telefono" },
        { text: "Puntos", value: "puntos" }
      ]
    };
  },
  methods: {},
  created() {
    axios.get("/usuario/mostrar").then(res => {
      this.usuarios = res.data;
    });
  }
};
</script>

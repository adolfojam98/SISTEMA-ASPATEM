<template>
  <v-simple-table>
    <template v-slot:default>
      <thead>
        <tr>
          <th class="text-left">Usuario</th>
          <th class="text-left">Tipo de Relacion</th>
          <th class="text-left">Eliminar</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="item in relaciones" :key="item.id">
          <td>{{ item.usuario.nombre }} , {{item.usuario.apellido}}</td>
          <td>{{ item.relacion }}</td>
          <td>
            <v-icon right @click="eliminarRelacion(item)" color="error">mdi-account-group</v-icon>
          </td>
        </tr>
      </tbody>
    </template>
  </v-simple-table>
</template>


<script>
export default {
  props: ["usuario"],

  data() {
    return {
      relaciones: [],
    };
  },
  methods: {
    relacionesExistentes() {
      axios.get(`/usuario/${this.usuario.id}/relaciones`).then((res) => {
        this.relaciones = res.data;
      });
    },

    eliminarRelacion(relacion) {
      axios
        .delete("usuario/relacion", {
          relacion,
        })
        .then();
    },
  },
  watch: {
    usuario: function () {
      this.relacionesExistentes();
    },
  },

  created() {
    this.relacionesExistentes();
  },
};
</script>
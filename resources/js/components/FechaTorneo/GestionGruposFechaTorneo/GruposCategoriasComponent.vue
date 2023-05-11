<template>
  <div>
    <div class="text-center">
      <v-menu offset-y>
        <template v-slot:activator="{ on, attrs }">
          <v-btn color="primary" dark v-bind="attrs" v-on="on">
            CATEGORIAS
          </v-btn>
        </template>
        <v-list>
          <v-list-item-group>
            <v-list-item @click="abrirModalCategoria(item)" v-for="(item, index) in categorias" :key="index">
              <v-list-item-title>{{ item.nombre }}</v-list-item-title>
            </v-list-item>
          </v-list-item-group>
        </v-list>
      </v-menu>
    </div>


    <!-- MODAL CATEGORIA -->
    <div v-if="categoriaSeleccionada">
      <v-row justify="center">
        <v-dialog v-model="modalPartidosCategoria" persistent fullscreen hide-overlay
          transition="dialog-bottom-transition">

          <v-card>
            <v-toolbar dark color="primary">
              <v-btn icon dark @click="modalPartidosCategoria = false">
                <v-icon>mdi-close</v-icon>
              </v-btn>
              <v-toolbar-title>Partidos categoria : {{ categoriaSeleccionada.nombre }}</v-toolbar-title>
              <v-spacer></v-spacer>
            </v-toolbar>
            <partidos-categoria :categoria="categoriaSeleccionada"></partidos-categoria>
          </v-card>
        </v-dialog>
      </v-row>
    </div>

  </div>
</template>

<script>
export default {
  props: ["categorias", "listaJugadores"],
  data() {
    return {
      categoriaSeleccionada: null,
      modalPartidosCategoria: false
    }
  }, methods: {

     abrirModalCategoria(categoria) {
      console.log(categoria);
      this.categoriaSeleccionada = categoria;
      console.log(categoria);
      console.log('abriendoModalCategoria');
      this.modalPartidosCategoria = true;
    },
 

  },
}
</script>
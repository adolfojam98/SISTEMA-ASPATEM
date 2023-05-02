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
  props: ["categorias"],
  data() {
    return {
      categoriaSeleccionada: null,
      modalPartidosCategoria: false
    }
  }, methods: {

    async abrirModalCategoria(categoria) {
      console.log(categoria);
      const res = await axios.get(`/fechas/${categoria.fecha_id}/categoria/${categoria.id}/partidos`);
      const partidos = res.data.body;
      if (partidos.length > 0) {
        // this.mapearPartidosDeCategoria(partidos);
      }
      this.categoriaSeleccionada = categoria
      console.log(categoria);
      console.log('abriendoModalCategoria');
      this.modalPartidosCategoria = true;
    },
    mapearPartidosDeCategoria(partidos) {
      //TODO hay que revisar esta parte
      partidos.forEach(partido => {
        if (partido.grupo) {
          console.log('partido fase de grupo:',partido)
          if(this.categoriaSeleccionada.listaGrupos.length){
            this.categoriaSeleccionada.listaGrupos.push({
              nombre: partido.grupo.nombre,
              jugadoresDelGrupo: [],
              partidos: []
            });
          }
        
          const grupoPartido = this.categoriaSeleccionada.listaGrupos.find(grupo => grupo.nombre == partido.grupo.nombre);
          if(!grupoPartido){
            this.categoriaSeleccionada.listaGrupos.push({
              nombre: partido.grupo.nombre,
              jugadoresDelGrupo: [],
              partidos: []
            });
            grupoPartido.push({
              
            })
          }



        } else {
          console.log('partido llaves: ', partido)
        }

      });
    }
  },
}
</script>
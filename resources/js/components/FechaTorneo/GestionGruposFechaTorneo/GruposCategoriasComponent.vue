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

    async abrirModalCategoria(categoria) {
      console.log(categoria);
      const res = await axios.get(`/fechas/${categoria.fecha_id}/categoria/${categoria.id}/partidos`);
      const partidos = res.data.body;
      this.categoriaSeleccionada = categoria
      if (partidos.length > 0) {
        this.mapearPartidosDeCategoria(partidos);
      }
      console.log(categoria);
      console.log('abriendoModalCategoria');
      this.modalPartidosCategoria = true;
    },
    mapearPartidosDeCategoria(partidos) {

      partidos.forEach((partido) => {
        partido.setsJugador1 = partido.jugadores.jugador1.sets;
        partido.setsJugador2 = partido.jugadores.jugador2.sets;
        partido.jugadores.jugador1 = this.listaJugadores.find(j => j.usuario_id == partido.jugadores.jugador1.id);
        partido.jugadores.jugador2 = this.listaJugadores.find(j => j.usuario_id == partido.jugadores.jugador2.id);
      });



      const partidosAgrupados = partidos.reduce((grupos, partido) => {
        console.log('->partido unico:', partido);
        if (partido.grupo) {
          const nuevoPartido = {
            "id": partido.id,
            "jugador1": partido.jugadores.jugador1,
            "jugador2": partido.jugadores.jugador2,
            "setsJugador1": partido.setsJugador1,
            "setsJugador2": partido.setsJugador2
          }

          const nombreGrupo = partido.grupo.nombre;
          const grupoExistente = grupos.find((grupo) => grupo.nombre === nombreGrupo);

          if (!grupoExistente) {
            console.log('nuevo grupo');
            const nuevoGrupo = { nombre: nombreGrupo, partidos: [nuevoPartido], jugadoresDelGrupo: [partido.jugadores.jugador1, partido.jugadores.jugador2] };
            grupos.push(nuevoGrupo);
          } else {
            grupoExistente.partidos.push(nuevoPartido);

            // verificamos si el jugador ya existe en el array jugadoresDelGrupo antes de agregarlo
            if (!grupoExistente.jugadoresDelGrupo.some(jugador => jugador.usuario_id === partido.jugadores.jugador1.usuario_id)) {
              grupoExistente.jugadoresDelGrupo.push(partido.jugadores.jugador1);
            }

            if (!grupoExistente.jugadoresDelGrupo.some(jugador => jugador.usuario_id === partido.jugadores.jugador2.usuario_id)) {
              grupoExistente.jugadoresDelGrupo.push(partido.jugadores.jugador2);
            }
          }
        }

        return grupos;
      }, []);

      if (partidosAgrupados) {
        this.categoriaSeleccionada.listaGrupos = partidosAgrupados;
      }
    }
  },
}
</script>
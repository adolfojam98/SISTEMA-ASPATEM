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
    mapearJugadoresDePartido(partido, listaJugadores) {
      const { jugador1, jugador2 } = partido.jugadores;
      partido.setsJugador1 = jugador1.sets;
      partido.setsJugador2 = jugador2.sets;
      partido.jugadores.jugador1 = listaJugadores.find(jugador => jugador.usuario_id == jugador1.id);
      partido.jugadores.jugador2 = listaJugadores.find(jugador => jugador.usuario_id == jugador2.id);
    },

    mapearPartidosDeCategoria(partidos) {
      for (const partido of partidos) {
        this.mapearJugadoresDePartido(partido, this.listaJugadores);
      }

      const gruposDePartidos = partidos.reduce((grupos, partido) => {
        if (partido.grupo) {
          const { id, jugadores, setsJugador1, setsJugador2 } = partido;
          const { nombre } = partido.grupo;
          const nuevoPartido = { id, jugador1: jugadores.jugador1, jugador2: jugadores.jugador2, setsJugador1, setsJugador2 };

          const grupoExistente = grupos.find(grupo => grupo.nombre === nombre);

          if (!grupoExistente) {
            const nuevoGrupo = { nombre, partidos: [nuevoPartido], jugadoresDelGrupo: [jugadores.jugador1, jugadores.jugador2] };
            grupos.push(nuevoGrupo);
          } else {
            grupoExistente.partidos.push(nuevoPartido);

            if (!grupoExistente.jugadoresDelGrupo.some(jugador => jugador.usuario_id === jugadores.jugador1.usuario_id)) {
              grupoExistente.jugadoresDelGrupo.push(jugadores.jugador1);
            }

            if (!grupoExistente.jugadoresDelGrupo.some(jugador => jugador.usuario_id === jugadores.jugador2.usuario_id)) {
              grupoExistente.jugadoresDelGrupo.push(jugadores.jugador2);
            }
          }
        }

        return grupos;
      }, []);

      if (gruposDePartidos.length > 0) {
        this.categoriaSeleccionada.listaGrupos = gruposDePartidos;
      }
    }
  },
}
</script>
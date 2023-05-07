<template>
  <div>
    <div v-if="categoria.jugadoresAnotados">

      <v-form v-model="valid" lazy-validation v-if="!gruposGenerados">
        <v-col>
          <v-text-field label="Cantidad de Grupos" v-model="categoria.cantidadGrupos" :rules="cantidadGruposRules"
            required class="mb-0 ml-2"></v-text-field>
          <p class="text--secondary d-flex justify-end">
            Jugadores anotados: {{ categoria.jugadoresAnotados.length }}
          </p>
          <p class="text--secondary d-flex justify-end"
            v-if="categoria.cantidadGrupos && categoria.cantidadGrupos % 1 == 0">
            Cantidad minima de jugadores: {{ categoria.cantidadGrupos * 3 }}
          </p>
          <v-switch v-model="categoria.gruposConEliminatoria" label="Fase de grupos con eliminatoria"
            class="ml-2 mt-0"></v-switch>

          <v-btn class="ml-2 mr-4" dark :disabled="!valid" @click="[generarGrupos()]" color="primary">Generar
            grupos</v-btn>
        </v-col>
      </v-form>
      <v-divider></v-divider>

      <div v-if="gruposGenerados && !llavesGeneradas" class="mt-3">
        <v-btn class='ml-7' @click="[deshacerGrupos()]" color="primary">Deshacer
          grupos</v-btn>

        <partidos-fase-grupos :categoria="categoria" @generar-llaves="generarLLavess"></partidos-fase-grupos>


      </div>
      <div v-if="llavesGeneradas">
        <resultados-grupos :categoria="categoria">
        </resultados-grupos>
        <partidos-fase-llaves :categoria="categoria"></partidos-fase-llaves>
      </div>
      <br>
      <v-btn color="success" @click='guardarFecha()'>guardar fecha</v-btn>
    </div>
  </div>
</template>

<script>
import { mapActions } from "vuex";
export default {
  props: ["categoria"],
  data() {
    return {
      valid: true,
      llavesGeneradas: false,
      gruposGenerados: false,
      numeroGrupos: 0,
      cantidadGruposRules: [
        (v) => !!v || "Cantidad de grupos requerido",
        (v) => /^([0-9]*)?[0-9]+$/.test(v) || "Deben ser solo numeros enteros",
      ],

    }
  },
  created() {
    this.recargarVariables();
  },
  watch: {
    categoria: function () {
      this.recargarVariables();
    }
  },
  methods: {
    ...mapActions(["callSnackbar"]),
    recargarVariables() {
      this.gruposGenerados = this.categoria.listaGrupos.length ? true : false;
      this.llavesGeneradas = this.categoria.partidosLlaves.length ? true : false;
    },

    generarGrupos() {
      const jugadoresAgrupados = this.agruparJugadores(this.categoria.jugadoresAnotados, this.categoria.cantidadGrupos);
      if (this.categoria.jugadoresAnotados.length >= this.categoria.cantidadGrupos * 3) {
        const grupos = this.crearGrupos(jugadoresAgrupados);
        this.categoria.listaGrupos = this.generarPartidosGrupos(grupos);
        console.log(this.categoria.listaGrupos)
        this.gruposGenerados = true;
        this.callSnackbar(["Grupos generados con éxito", "success"]);
      } else {
        this.callSnackbar(["La cantidad de jugadores es insuficiente para la cantidad de grupos", "warning"]);
      }
    },

    agruparJugadores(jugadores, cantidadGrupos) {
      const jugadoresOrdenados = jugadores.sort((a, b) => b.puntos - a.puntos);
      const cantidadJugadoresPorGrupo = Math.ceil(jugadores.length / cantidadGrupos);
      const grupos = Array.from({ length: cantidadGrupos }, () => []);

      let index = 0;
      let reverse = false;

      for (let i = 0; i < jugadoresOrdenados.length; i++) {
        grupos[index].push(jugadoresOrdenados[i]);

        if (reverse) {
          index--;
          if (index < 0) {
            index++;
            reverse = false;
          }
        } else {
          index++;
          if (index >= cantidadGrupos) {
            index--;
            reverse = true;
          }
        }
      }
      return grupos;
    },

    crearGrupos(jugadoresAgrupados) {
      const letras = "ABCDEFGHIJKLMNOPQRSTUVWXYZ".split("");
      return jugadoresAgrupados.map((jugadores, indice) => ({
        nombre: letras[indice],
        jugadoresDelGrupo: jugadores,
        partidos: [],
      }));
    },
    generarPartidosGrupos(grupos) {
      console.log("Ejecución generarPartidosGrupos");

      grupos.forEach((grupo) => {
        for (let i = 0; i < grupo.jugadoresDelGrupo.length; i++) {
          for (let j = i + 1; j < grupo.jugadoresDelGrupo.length; j++) {
            const partido = {
              id: grupo.partidos.length + 1,
              jugador1: grupo.jugadoresDelGrupo[i],
              jugador2: grupo.jugadoresDelGrupo[j],
              setsJugador1: null,
              setsJugador2: null,
            };
            grupo.partidos.push(partido);
          }
        }
      });
      return grupos;
    },
    deshacerGrupos() {
      console.log("Ejecución deshacerGrupos");
      this.categoria.listaGrupos = [];
      this.gruposGenerados = false;
    },
    generarLLavess(prop) {
      if (!this.validarPartidos()) {
        return;
      }
      console.log('generando llaves componente padre...')
      this.llavesGeneradas = true;
    },
    validarPartidos() {
      const setsCargados = this.categoria.listaGrupos.every(grupo => grupo.partidos.every(partido => partido.setsJugador1 !== null && partido.setsJugador2 !== null));
      const hayEmpates = this.categoria.listaGrupos.some(grupo => grupo.partidos.some(partido => partido.setsJugador1 == partido.setsJugador2));

      if (!setsCargados) {
        this.callSnackbar(["Falta cargar al menos un partido", "warning"]);
        return false;
      }

      if (hayEmpates) {
        this.callSnackbar(["Hay al menos un partido empatado", "warning"]);
        return false;
      }

      return true;
    },
    deshacerLlaves(categoria) {
      console.log("Ejecución deshacerLlaves");
      categoria.partidosLlaves = [];
      categoria.llavesGeneradas = false;
    },
    mostrarNotificacion(mensaje) {
      console.log(`Notificación: ${mensaje[0]} (${mensaje[1]})`);
    },
    guardarFecha() {
      console.log('guardando fecha....')
      const partidosRequest = this.transformarPartidosRequest();
      axios.post(`/fechas/${this.categoria.fecha_id}/categoria/${this.categoria.id}`, {
        'partidos': partidosRequest
      }).then(res => {
        console.log(res)
      });
    },

    transformarPartidosRequest() {
      let partidos = [];

      this.categoria.listaGrupos.forEach(grupo => {
        console.log(grupo)
        grupo.partidos.forEach(partido => {
          partidos.push({
            "fase": "grupos",
            "grupo_nombre": grupo.nombre,
            "id_jugador1": partido.jugador1.usuario_id,
            "id_jugador2": partido.jugador2.usuario_id,
            "set_jugador1": partido.setsJugador1,
            "set_jugador2": partido.setsJugador2
          });
        });

      });
      this.categoria.partidosLlaves.forEach(partido => {
        partidos.push({
          "id": partido.id,
          "fase": partido.fase,
          "id_jugador1": partido.jugador1.usuario_id,
          "id_jugador2": partido.jugador2.usuario_id,
          "set_jugador1": partido.setsJugador1,
          "set_jugador2": partido.setsJugador2,
          "sig_partido_id": partido.idPartidoPadre
        });
      })


      return partidos;


    }

  },
}

</script>
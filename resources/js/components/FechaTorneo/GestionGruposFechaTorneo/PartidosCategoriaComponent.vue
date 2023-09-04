<template>
  <div>
    <v-container>
    <div v-if="categoria.jugadoresAnotados">

      <v-form v-model="valid" lazy-validation v-if="!gruposGenerados">
        <v-col>
          <v-text-field label="Cantidad de Grupos" v-model="categoria.cantidadGrupos" :rules="cantidadGruposRules"
            required class="mb-0 ml-2" style="width: 250px" ></v-text-field>
          <p class="text--secondary ml-2">
            Jugadores anotados: {{ categoria.jugadoresAnotados.length }}
          </p>
          <p class="text--secondary ml-2"
            v-if="categoria.cantidadGrupos && categoria.cantidadGrupos % 1 == 0">
            Cantidad minima de jugadores: {{ categoria.cantidadGrupos * 3 }}
          </p>

          <v-btn class="ml-2 mr-4" dark :disabled="!valid" @click="[generarGrupos()]" color="primary">Generar
            grupos</v-btn>
        </v-col>
      </v-form>

      <div v-if="gruposGenerados && !llavesGeneradas" class="mt-3 ml-1">
        <v-btn class='ml-4' @click="[confirmModalDeshacerGrupos = true]" color="primary">Deshacer
          grupos</v-btn>
          
        <partidos-fase-grupos :categoria="categoria" @generar-llaves="generarLLavess"></partidos-fase-grupos>


      </div>
      <div v-if="llavesGeneradas" class="mt-3">
        <v-btn class='ml-5' @click="[confirmModalDeshacerLlaves = true]" color="primary">Deshacer
          llaves</v-btn>
        <resultados-grupos v-if="llavesGeneradas" :categoria="categoria">
        </resultados-grupos>
        <partidos-fase-llaves :categoria="categoria"></partidos-fase-llaves>
      </div>
      <br>

         <v-btn  class="mx-5" color="success" @click='guardarFecha()'>Guardar partidos</v-btn>
    </div>

    <!-- DIALOGS -->

    <v-dialog persist v-model="confirmModalDeshacerLlaves" max-width="290">
      <v-card>
        <v-card-title>
          Confirmación
        </v-card-title>
        <v-card-text>Esta acción no puede revertirse. </v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn color="error" @click="confirmModalDeshacerLlaves = false">
            CANCELAR
          </v-btn>
          <v-btn color="primary" @click="[confirmModalDeshacerLlaves = false, deshacerLlaves()]">
            ACEPTAR
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>

    
    <v-dialog persist v-model="confirmModalDeshacerGrupos" max-width="290">
      <v-card>
        <v-card-title>
          Confirmación
        </v-card-title>
        <v-card-text>Esta acción no puede revertirse. </v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn color="error"  @click="confirmModalDeshacerGrupos = false">
            CANCELAR
          </v-btn>
          <v-btn color="primary"  @click="[confirmModalDeshacerGrupos = false, deshacerGrupos()]">
            ACEPTAR
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>

  </v-container>
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
      confirmModalDeshacerLlaves: false,
      confirmModalDeshacerGrupos : false,
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
      const setsCargados = this.categoria.listaGrupos.every(grupo => grupo.partidos.every(partido => parseInt(partido.setsJugador1) >=0 && parseInt(partido.setsJugador2) >=0));
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
    deshacerLlaves() {
      console.log("Ejecución deshacerLlaves");
      this.categoria.partidosLlaves = [];
      this.llavesGeneradas = false;
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
        this.callSnackbar(["Categoria guardada correctamente", "success"]);
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
            "id_jugador1": partido.jugador1?.usuario_id,
            "id_jugador2": partido.jugador2?.usuario_id,
            "set_jugador1": partido.setsJugador1,
            "set_jugador2": partido.setsJugador2
          });
        });

      });
      this.categoria.partidosLlaves.forEach(partido => {
        partidos.push({
          "id": partido.id,
          "fase": partido.fase,
          "id_jugador1": partido.jugador1?.usuario_id,
          "id_jugador2": partido.jugador2?.usuario_id,
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
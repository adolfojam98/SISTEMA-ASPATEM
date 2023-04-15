<template>
  <div>
    <div v-if="categoria.jugadoresAnotados">

      <v-form v-model="valid" lazy-validation>
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

          <v-btn class="ml-2 mr-4" dark :disabled="!valid" @click="[generarGrupos()]" color="blue">Generar
            grupos</v-btn>
        </v-col>
      </v-form>
      <v-divider></v-divider>

      <div v-if="gruposGenerados && !llavesGeneradas">
        <partidos-fase-grupos :categoria="categoria" @generar-llaves="generarLLavess"></partidos-fase-grupos>

        


      </div>
      <div  v-if="llavesGeneradas"> 
      <resultados-grupos :categoria="categoria">
      </resultados-grupos>
      <partidos-fase-llaves :categoria="categoria"></partidos-fase-llaves>
      </div>
     
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
      gruposGenerados: false,
      llavesGeneradas: false,
      numeroGrupos: 0,
      cantidadGruposRules: [
        (v) => !!v || "Cantidad de grupos requerido",
        (v) => /^([0-9]*)?[0-9]+$/.test(v) || "Deben ser solo numeros enteros",
      ],

    }
  },
  methods: {
    ...mapActions(["callSnackbar"]),

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
    deshacerGrupos(categoria) {
      console.log("Ejecución deshacerGrupos");
      categoria.listaGrupos = [];
      categoria.gruposGenerados = false;
    },
    generarLLavess(prop) {
      console.log('generando llaves componente padre...')
      this.llavesGeneradas = true;
    },
    deshacerLlaves(categoria) {
      console.log("Ejecución deshacerLlaves");
      categoria.partidosLlaves = [];
      categoria.llavesGeneradas = false;
    },
    mostrarNotificacion(mensaje) {
      console.log(`Notificación: ${mensaje[0]} (${mensaje[1]})`);
    }

  },
}

</script>
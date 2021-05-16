<template>
    <div>
        <v-card class="mb-12" min-height="390">
            <v-row>
                <v-col cols="8" md="4">
                    <v-container>
                <agregar-jugador-torneo-component></agregar-jugador-torneo-component>
                        <importar-jugadores
                            @nuevosJugadores="nuevosJugadores = $event"
                        ></importar-jugadores>
                       
                    </v-container>
                </v-col>

                <v-col>
                   <tabla-jugadores-torneo-component></tabla-jugadores-torneo-component>
                </v-col>
            </v-row>
        </v-card>
        <v-btn color="primary" @click="setStep(4)" >
            Continuar
        </v-btn>
    </div>
</template>

<script>
import { mapMutations, mapState } from 'vuex';
import AgregarJugadorTorneoComponent from './AgregarJugadorTorneoComponent.vue';
import TablaJugadoresTorneoComponent from './TablaJugadoresTorneoComponent.vue';
export default {
    data() {
        return {
            nuevosJugadores:[]
        }
    },
  components: { AgregarJugadorTorneoComponent, TablaJugadoresTorneoComponent },
  
  computed: {
      ...mapState('CrerTorneo',['listaJugadores'])
  },
  methods: {
      ...mapMutations('CrearTorneo',['setStep'])
  },
   watch: {
        nuevosJugadores: function() {
            console.log(this.nuevosJugadores);
            
        }
    }
  
  };
</script>

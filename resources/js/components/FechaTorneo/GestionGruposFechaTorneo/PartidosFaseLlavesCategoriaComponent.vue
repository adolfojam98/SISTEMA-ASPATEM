<template>
    <div>
        <div class="d-flex">
            <div v-for="fase in FASES_REVERSE">
                <v-col v-if="faseTienePartidos(fase)">
                    <v-row>
                        <v-col>
                            <h3>{{ fase }}</h3>
                        </v-col>
                    </v-row>
                    <div v-for="partido in partidos" :key="partido.id">

                        <!-- NODOS HOJAS -->
                        <div v-if="esNodoHoja(partido)">
                            <v-card outlined flat v-if="partido.fase == fase">
                                <div>
                                    <v-card-text>
                                        <v-row>
                                            <v-col>
                                                <v-autocomplete :items="jugadoresDisponibles(partido)"
                                                    :item-text="nombreCompletoJugador" return-object 
                                                    v-model="partido.jugador1" ></v-autocomplete>

                                                <v-text-field label="Sets" v-model="partido.setsJugador1" 
                                                    type="number"></v-text-field>
                                            </v-col>
                                            <v-col>
                                                <v-autocomplete :items="jugadoresDisponibles(partido)"
                                                    :item-text="nombreCompletoJugador" return-object  
                                                    v-model="partido.jugador2"></v-autocomplete>
                                                <v-text-field label="Sets" v-model="partido.setsJugador2"
                                                    type="number"></v-text-field>
                                            </v-col>
                                        </v-row>
                                    </v-card-text>
                                </div>
                            </v-card>

                        </div>

                        <!-- NODOS COMUNES -->
                        <div v-if="!esNodoHoja(partido)">
                            <v-card outlined flat v-if="partido.fase == fase">
                                <div>
                                    <v-card-text>
                                        <v-row>
                                            <v-col>
                                                <p>{{ partido.jugador1?.nombre }} {{ partido.jugador1?.apellido }}
                                                </p>
                                                <v-text-field label="Sets" v-model="partido.setsJugador1"
                                                    type="number"></v-text-field>
                                            </v-col>
                                            <v-col>
                                                <p>{{ partido.jugador2?.nombre }} {{ partido.jugador2?.apellido }}
                                                </p>
                                                <v-text-field label="Sets" v-model="partido.setsJugador2"
                                                    type="number"></v-text-field>
                                            </v-col>
                                        </v-row>
                                    </v-card-text>
                                </div>
                            </v-card>
                        </div>
                    </div>
                </v-col>
            </div>

        </div>

    </div>
</template>


<script>

export default {
    props: ["categoria"],
    data: () => ({
        FASES: {
            1: 'final',
            2: 'semis',
            3: 'cuartos',
            4: 'octavos',
            5: 'dieciseisavos',
            6: 'treintaidosavos'
        },
        partidos: []
    }),
    created() {
        const partidosss = this.generarPartidos(8);
        for (const fase of Object.values(this.FASES)) {
            this.partidos.push(...partidosss[fase]);
        }

    },
    methods: {
        jugadoresDisponibles(partido) {
            const jugadoresSeleccionados = this.partidos
                .filter(p => p.jugador1 || p.jugador2)
                .map(p => p.jugador1?.usuario_id || p.jugador2?.usuario_id);
            return this.categoria.jugadoresAnotados.filter(jugador => (partido.jugador1?.usuario_id == jugador.usuario_id ||partido.jugador2?.usuario_id == jugador.usuario_id ) || !jugadoresSeleccionados.includes(jugador.usuario_id) );
        },
        nombreCompletoJugador(jugador) {
            return `${jugador.nombre} ${jugador.apellido}`;
        },
        esNodoHoja(partido) {
            return !this.partidos.some(p => p.idPartidoPadre == partido.id);
        },
        faseTienePartidos(fase) {
            return this.partidos.some(partido => partido.fase == fase);
        },
        generarPartidos(nroJugadores) {
            let partidosLlaves = [];
            let rondas = Math.ceil(Math.log2(nroJugadores));
            let idPartidoActual = 1;
            partidosLlaves[this.FASES[1]] = [];
            // Crear la final
            const final = {
                id: idPartidoActual,
                jugador1: null,
                jugador2: null,
                setsJugador1: null,
                setsJugador2: null,
                idPartidoPadre: null,
                fase: this.FASES[1]
            };
            partidosLlaves[this.FASES[1]].push(final);
            idPartidoActual++;
            for (let ronda = 2; ronda <= rondas; ronda++) {
                partidosLlaves[this.FASES[ronda]] = [];
                const partidosFaseAnterior = partidosLlaves[this.FASES[ronda - 1]];

                partidosFaseAnterior.forEach(partidoAnterior => {
                    let nuevoPartido1 = {
                        id: idPartidoActual,
                        jugador1: null,
                        jugador2: null,
                        setsJugador1: null,
                        setsJugador2: null,
                        idPartidoPadre: partidoAnterior.id,
                        fase: this.FASES[ronda]
                    };
                    partidosLlaves[this.FASES[ronda]].push(nuevoPartido1);
                    idPartidoActual++;

                    let nuevoPartido2 = {
                        id: idPartidoActual,
                        jugador1: null,
                        jugador2: null,
                        setsJugador1: null,
                        setsJugador2: null,
                        idPartidoPadre: partidoAnterior.id,
                        fase: this.FASES[ronda]
                    };
                    partidosLlaves[this.FASES[ronda]].push(nuevoPartido2);
                    idPartidoActual++;
                });
            }

            return partidosLlaves;
        },

        generarId() {
            // Creamos un timestamp con precisión de milisegundos
            const timestamp = new Date().getTime().toString(16);

            // Generamos un número aleatorio de 8 dígitos
            const randomNumber = Math.floor(Math.random() * 100000000).toString(16);

            // Combinamos los dos valores anteriores para crear el ID único
            const uniqueId = timestamp + randomNumber;

            return uniqueId;
        }


    },
    computed: {
        FASES_REVERSE() {
            return Object.values(this.FASES).reverse();
        },

      
    },

};
</script>
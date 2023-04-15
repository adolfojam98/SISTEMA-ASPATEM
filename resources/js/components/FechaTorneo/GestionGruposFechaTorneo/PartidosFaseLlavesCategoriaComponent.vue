<template>
    <div>
        {{ partidos }}
        <v-btn color="success" @click="generarPartidos(16)">text</v-btn>
    </div>
</template>


<script>
import { timeouts } from 'retry';

export default {
    props: ["categoria"],
    data: () => ({
            FASES: {
                1: 'Final',
                2: 'semis',
                3: 'cuartos',
                4: 'octavos',
                5: 'dieciseisavos',
                6: 'treintaydosavos'

            },
            partidos: []
        }),
    created() {
        
        this.partidos = this.generarPartidos(23);
     
    },
    methods: {
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
                idPartidoPadre: null
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
                        idPartidoPadre: partidoAnterior.id
                    };
                    partidosLlaves[this.FASES[ronda]].push(nuevoPartido1);
                    idPartidoActual++;

                    let nuevoPartido2 = {
                        id: idPartidoActual,
                        jugador1: null,
                        jugador2: null,
                        setsJugador1: null,
                        setsJugador2: null,
                        idPartidoPadre: partidoAnterior.id
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


    }
};
</script>
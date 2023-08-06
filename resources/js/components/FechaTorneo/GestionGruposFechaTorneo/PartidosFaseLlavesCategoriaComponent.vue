<template>
    <div>
        <div class="d-flex">
            <div v-for="fase in FASES_REVERSE" v-bind:key="fase" class="align-self-center">
                <v-col v-if="faseTienePartidos(fase)" class="d-flex flex-column">
                    <v-row>
                        <v-col>
                            <h3>{{ fase }}</h3>
                        </v-col>
                    </v-row>
                    <div v-for="partido in categoria.partidosLlaves" :key="partido.id">

                        <!-- NODOS HOJAS -->
                        <div class="ma-2" v-if="partido.fase == fase">

                            <v-card outlined flat>
                                <div>
                                    <v-card-text>
                                        <v-row>
                                            <v-col>
                                                <v-autocomplete :disabled="tienePartidoAnterior(partido, 1)"
                                                    :items="jugadoresDisponibles(partido.jugador1)"
                                                    :item-text="nombreCompletoJugador" return-object clearable
                                                    v-model="partido.jugador1"></v-autocomplete>
                                                    <v-autocomplete :disabled="tienePartidoAnterior(partido, 2)"
                                                    :items="jugadoresDisponibles(partido.jugador2)"
                                                    :item-text="nombreCompletoJugador" return-object clearable
                                                    v-model="partido.jugador2"></v-autocomplete>
                                                
                                            </v-col>
                                            <v-col>
                                                <v-text-field label="Sets" v-model="partido.setsJugador1"
                                                    @change="asingarGanadorASiguientePartido(partido)"
                                                    type="number"></v-text-field>
                                                <v-text-field label="Sets" v-model="partido.setsJugador2"
                                                    @change="asingarGanadorASiguientePartido(partido)"
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
    }),
    created() {
        if (!this.categoria.partidosLlaves.length) {
            const partidos = this.generarPartidos(this.categoria.jugadoresAnotados.length);
            for (const fase of Object.values(this.FASES)) {
                this.categoria.partidosLlaves.push(...partidos[fase]);
            }
        }
    },
    methods: {
        tienePartidoAnterior(partido, nroJugador) {
            //recibe el partido y el nro de jugador(1 o 2)
            //dependiendo de cuantos partidos tiene como hijos,
            //devuelve si se oculta o no.
            const partidosAsociados = this.categoria.partidosLlaves.filter(p => p.idPartidoPadre == partido.id);
            return partidosAsociados.length >= nroJugador;
        },
        asingarGanadorASiguientePartido(partido) {
            if (!(partido.jugador1 && partido.jugador2)) {
                return;
            }

            if (!(partido.setsJugador1 && partido.setsJugador2)) {
                return;
            }
            //saco el jugador ganador del partido
            const jugadorGanador = partido.setsJugador1 > partido.setsJugador2 ? partido.jugador1 : partido.jugador2;
            //saco el siguiente partido de la fase siguiente
            const partidoSiguiente = this.categoria.partidosLlaves.find(p => p.id == partido.idPartidoPadre);
            if (!partidoSiguiente) {
                return;
            }
            //saco los 2 partidos que apuntan al mismo partido
            const partidosAsociados = this.categoria.partidosLlaves.filter(p => p.idPartidoPadre == partidoSiguiente.id);

            //si tiene solo uno es porque el otro jugador no juega la llave de ajuste, le reservamos el primer lugar
            if(partidosAsociados.length == 1) {
                partidoSiguiente.jugador2 = jugadorGanador;
                return
            }

            if (partidosAsociados[0] == partido) {
                partidoSiguiente.jugador1 = jugadorGanador;
            }

            if (partidosAsociados[1] == partido) {
                partidoSiguiente.jugador2 = jugadorGanador;
            }

        },
        jugadoresDisponibles(jugadorPartido) {
            let jugadoresSeleccionados = this.categoria.jugadoresAnotados.filter(jugador => {
                return !this.categoria.partidosLlaves.some(p => (p.jugador1 && p.jugador1.usuario_id == jugador.usuario_id) || (p.jugador2 && p.jugador2.usuario_id == jugador.usuario_id));
            })
            if (jugadorPartido) {
                jugadoresSeleccionados.push(jugadorPartido);
            }
            return jugadoresSeleccionados;
        },

        nombreCompletoJugador(jugador) {
            return `${jugador.nombre} ${jugador.apellido}`;
        },
        esNodoHoja(partido) {
            return !this.categoria.partidosLlaves.some(p => p.idPartidoPadre == partido.id);
        },

        faseTienePartidos(fase) {
            return this.categoria.partidosLlaves.some(partido => partido.fase == fase);
        },
        calcularNumeroDeRondas(nroJugadores) {
            let nroRondas = Math.ceil(Math.log2(nroJugadores));
            const jugadoresLlavesPerfectas = Math.pow(2, Math.floor(Math.log2(nroJugadores)));
            if (nroJugadores != jugadoresLlavesPerfectas) {
                nroRondas--;
            }
            return nroRondas
        },

        organizarPartidosLlaves(partidosLlaves) {
            let gruposConJugadoresOrdenados = [];

            this.categoria.listaGrupos.forEach(grupo => {
                gruposConJugadoresOrdenados.push({ ...grupo, jugadoresOrdenados: this.getJugadoresOrdenadosPorGrupo(grupo) });
            });

            const todosLosJugadoresOrdenados = this.getJugadoresOrdenadosFromGrupos(gruposConJugadoresOrdenados);
            const nroJugadoresLlavesPerfectas = Math.pow(2, Math.floor(Math.log2(todosLosJugadoresOrdenados.length)));
            const nroJugadoresLlavesAjustes = (todosLosJugadoresOrdenados.length - nroJugadoresLlavesPerfectas) * 2

            let jugadoresParaLlavesSinSerpenteo = []
            let jugadoresParaLlavesConSerpenteo = todosLosJugadoresOrdenados
            let jugadoresParaLlavesDeAjuste = []

            if(nroJugadoresLlavesAjustes && this.categoria.gruposConEliminatoria) {
                // separamos a los jugadores entre los que hay que serpenear, los que no y los de ajuste
                jugadoresParaLlavesSinSerpenteo = todosLosJugadoresOrdenados.slice(0, nroJugadoresLlavesAjustes / 2); // esto siempre va a ser par (son los que van solos debido a que tienen que esperar un partido de ajuste)
                jugadoresParaLlavesConSerpenteo = todosLosJugadoresOrdenados.slice(nroJugadoresLlavesAjustes / 2, todosLosJugadoresOrdenados.length - nroJugadoresLlavesAjustes) //esto son lo otros que no van solo ni en ajuste
                jugadoresParaLlavesDeAjuste = todosLosJugadoresOrdenados.slice(todosLosJugadoresOrdenados.length - nroJugadoresLlavesAjustes, todosLosJugadoresOrdenados.length) //estos son los que van en la llave de ajustes (tambien serpenteados pero por separado)
            } else {
                jugadoresParaLlavesConSerpenteo = todosLosJugadoresOrdenados.slice(0, nroJugadoresLlavesPerfectas)
            }

            //serpenteamos...
            jugadoresParaLlavesConSerpenteo = this.serpenteoArray(jugadoresParaLlavesConSerpenteo)
            jugadoresParaLlavesDeAjuste = this.serpenteoArray(jugadoresParaLlavesDeAjuste).reverse()

            // obtenemos las rondas y asignamos los partidos 
            const rondas = this.calcularNumeroDeRondas(todosLosJugadoresOrdenados.length);
            const partidosAsignados = this.asignarJugadoresPartidos(partidosLlaves, jugadoresParaLlavesSinSerpenteo, jugadoresParaLlavesConSerpenteo, jugadoresParaLlavesDeAjuste, rondas)

            return partidosAsignados;
        },

        asignarJugadoresPartidos(partidosLlaves, jugadoresParaLlavesSinSerpenteo, jugadoresParaLlavesConSerpenteo, jugadoresParaLlavesDeAjuste, rondas) {
            const primeraFase = this.FASES[rondas]
            const faseDeAjuste = this.FASES[rondas+1]

            while(jugadoresParaLlavesSinSerpenteo.length > 0) {
                let jugadorSinSerpenteo = jugadoresParaLlavesSinSerpenteo.shift()
                let partidoPrimeraFase = this.getPrimerPartidoDisponible(partidosLlaves, primeraFase)
                partidoPrimeraFase.jugador1 = jugadorSinSerpenteo

                //ahora modificamos el partido de ajuste que apunta a este
                let jugadorConSerpenteo1 = jugadoresParaLlavesDeAjuste.shift()
                let jugadorConSerpenteo2 = jugadoresParaLlavesDeAjuste.shift()
                let partido = this.getPrimerPartidoDisponible(partidosLlaves, faseDeAjuste); //primero se intenta meter en el de ajuste

                partido.jugador1 = jugadorConSerpenteo1
                partido.jugador2 = jugadorConSerpenteo2
                partido.idPartidoPadre = partidoPrimeraFase.id
            }

            while(jugadoresParaLlavesConSerpenteo.length > 0) {
                let jugadorConSerpenteo1 = jugadoresParaLlavesConSerpenteo.shift()
                let jugadorConSerpenteo2 = jugadoresParaLlavesConSerpenteo.shift()

                let partido = partido = this.getPrimerPartidoDisponible(partidosLlaves, primeraFase);

                partido.jugador1 = jugadorConSerpenteo1
                partido.jugador2 = jugadorConSerpenteo2
            }

            return partidosLlaves;
        },

        obtenerElementosRepetidos(arr) {
            return arr.filter((item, index) => arr.indexOf(item) !== index);
        },

        getPrimerPartidoDisponible(partidosLlaves, fase) {
            partidosLlaves[fase] = this.ordenarPartidosParaAsignacion(partidosLlaves[fase])
            const partido = partidosLlaves[fase]?.find((partido) => !partido.jugador1 && !partido.jugador2);
            partidosLlaves[fase] = this.ordenarPartidosParaAsignacion(partidosLlaves[fase])

            return partido
        },

        serpenteoArray(arr) {
            const result = [];
            let left = 0;
            let right = arr.length - 1;

            while (left < right) {
                result.push(arr[left]);
                result.push(arr[right]);
                left++;
                right--;
            }

            if (left === right) {
                result.push(arr[left]);
            }

            return result;
        },
        
        ordenarPartidosParaAsignacion(arr) { //ordena/vuelve a como estaban antes los partidos, asi se hacen bien las asignaciones de los jugadores
            let A = [arr[0]]
            let B = [arr[1]]
            let direccionA = false
            let posicion = arr.length -1

            while(posicion > 1) {console.log('entra')
                if(direccionA) {
                    B.push(arr[posicion])
                    A.push(arr[posicion-1])
                } else {
                    A.push(arr[posicion])
                    B.push(arr[posicion-1])
                }
                
                direccionA = !direccionA
                posicion = posicion-2
            }

            return [...A, ...B.reverse()]
        },

        getJugadoresOrdenadosFromGrupos(gruposConJugadoresOrdenados) {
            //devuelve todos los jugadores de los grupos en una lista ordenada por posicion del jugador en el grupo - grupo
            const jugadoresOrdenados = [];

            const maxLength = gruposConJugadoresOrdenados.reduce((max, grupo) => { //el largo del array de jugadores mas largo
                const lengthActual = grupo.jugadoresOrdenados.length;
                return lengthActual > max ? lengthActual : max;
            }, 0);

            let j = 0;

            while(j <= maxLength) {
                for (let i = 0; i < gruposConJugadoresOrdenados.length; i++) {
                    const grupo = gruposConJugadoresOrdenados[i];
                    
                    if(grupo.jugadoresOrdenados.length > j) {
                        const jugador = grupo.jugadoresOrdenados[j]
                        jugadoresOrdenados.push(jugador)
                    }
                }
                j++;
            }

            return jugadoresOrdenados;
        },

        getJugadoresOrdenadosPorGrupo(grupo) {
            let jugadores = grupo.jugadoresDelGrupo.map(jugador => {
                return { ...jugador, partidosGanados: 0 };
            });

            grupo.partidos.forEach(partido => {
                const idGanador = this.getIdGanadorPartido(partido);
                let jugadorGanador = jugadores.find(jugador => jugador.usuario_id == idGanador);
                jugadorGanador.partidosGanados++;
            });

            // ordenamos
            jugadores.sort((jugadorA, jugadorB) => {
            // ordenar por partidosGanados (mayor a menor)
                if (jugadorB.partidosGanados !== jugadorA.partidosGanados) {
                    return jugadorB.partidosGanados - jugadorA.partidosGanados;
                } 
                else {
                    let setGanadosJugadorA = this.getSetGanados(jugadorA, grupo.partidos);
                    let setGanadosJugadorB = this.getSetGanados(jugadorB, grupo.partidos);

                    if(setGanadosJugadorA != setGanadosJugadorB){ // si tienen la misma cantidad de partidosGanados, ordenamos por cantidad de set ganados
                        return setGanadosJugadorB - setGanadosJugadorA;
                    }
                    else {
                        // si tienen la misma cantidad de partidosGanados y setsGanados, ordenamos por el resultado del partido entre ellos
                        const partidoEntreJugadores = grupo.partidos.find(partido =>
                            (partido.jugador1.usuario_id === jugadorA.usuario_id && partido.jugador2.usuario_id === jugadorB.usuario_id) ||
                            (partido.jugador1.usuario_id === jugadorB.usuario_id && partido.jugador2.usuario_id === jugadorA.usuario_id)
                        );

                        // ordenamos por resultado del partido (mayor a menor)
                        return this.getIdGanadorPartido(partidoEntreJugadores) === jugadorA.usuario_id ? -1 : 1;
                    }
                }
            });

            return jugadores
        },

        getSetGanados(jugador, partidos) {
            let totalSetsGanados = 0
            partidos.forEach(partido => {
                if(jugador.usuario_id == partido.jugador1.usuario_id) {
                    totalSetsGanados += parseInt(partido.setsJugador1)
                }

                if(jugador.usuario_id == partido.jugador2.usuario_id) {
                    totalSetsGanados += parseInt(partido.setsJugador2)
                }
            });

            return totalSetsGanados;
        },

        getIdGanadorPartido(partido) {
            const setsJugador1 = parseInt(partido.setsJugador1);
            const setsJugador2 = parseInt(partido.setsJugador2);

            if(setsJugador1 > setsJugador2) {
                return partido.jugador1.usuario_id;
            } else {
                return partido.jugador2.usuario_id;
            }
        },

        generarPartidos(nroJugadores) {
            let partidosLlaves = [];
            let rondas = this.calcularNumeroDeRondas(nroJugadores);
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
            if (this.categoria.gruposConEliminatoria) {
                partidosLlaves[this.FASES[rondas + 1]] = [];
                // Calcula la potencia de 2 más cercana y menor al número dado
                const jugadoresLlavesPerfectas = Math.pow(2, Math.floor(Math.log2(nroJugadores)));
                // Calcula la diferencia entre el número y la potencia de 2 más cercana y menor
                let jugadoresLlavesAjustes = nroJugadores - jugadoresLlavesPerfectas;

                const partidosFaseAnterior = partidosLlaves[this.FASES[rondas]];

                while (jugadoresLlavesAjustes > 0) {
                    console.log(jugadoresLlavesAjustes);
                    partidosFaseAnterior.forEach(partidoAnterior => {
                        if (jugadoresLlavesAjustes > 0) {
                            let nuevoPartido = {
                                id: idPartidoActual,
                                jugador1: null,
                                jugador2: null,
                                setsJugador1: null,
                                setsJugador2: null,
                                idPartidoPadre: partidoAnterior.id,
                                fase: this.FASES[rondas + 1]
                            };
                            partidosLlaves[this.FASES[rondas + 1]].push(nuevoPartido);
                            idPartidoActual++;
                            jugadoresLlavesAjustes = jugadoresLlavesAjustes - 1;
                        }

                    })
                }
            }

            partidosLlaves = this.organizarPartidosLlaves(partidosLlaves);
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
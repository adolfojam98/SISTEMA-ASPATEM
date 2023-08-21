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
                        <div class="ma-2" v-if="partido.fase == fase">
                            <v-card outlined flat max-width="374">
                                <div class="d-flex container-group_player">
                                    <div class="my-auto mr-1 container-group_position">
                                        <span v-if="partido?.jugador1?.posicionGrupo"> {{partido?.jugador1?.posicionGrupo}}: </span>
                                    </div>

                                    <div class="justify-space-between my-auto container-group_player_info">
                                        <v-autocomplete
                                        :disabled="tienePartidoAnterior(partido, 2)"
                                        :items="jugadoresDisponibles(partido.jugador1)"
                                        :item-text="nombreCompletoJugador"
                                        return-object
                                        clearable
                                        v-model="partido.jugador1"
                                        ></v-autocomplete>
                                    </div>

                                    <div class="my-auto ml-2">
                                        <v-text-field
                                        label="Sets"
                                        v-model="partido.setsJugador1"
                                        @change="asingarGanadorASiguientePartido(partido)"
                                        type="number"
                                        style="width: 36px"
                                        ></v-text-field>
                                    </div>
                                </div>

                                <div class="d-flex container-group_player">
                                     <div class="my-auto container-group_position">
                                        <span v-if="partido?.jugador2?.posicionGrupo"> {{partido?.jugador2?.posicionGrupo}}: </span>
                                    </div>

                                    <div class="justify-space-between my-auto container-group_player_info">
                                        <v-autocomplete
                                        :disabled="
                                            tienePartidoAnterior(partido, 1) ||
                                            tienePartidoAnterior(partido, 2)
                                        "
                                        :items="jugadoresDisponibles(partido.jugador2)"
                                        :item-text="nombreCompletoJugador"
                                        return-object
                                        clearable
                                        v-model="partido.jugador2"
                                        ></v-autocomplete>
                                    </div>

                                    <div class="my-auto ml-2">
                                        <v-text-field
                                        label="Sets"
                                        v-model="partido.setsJugador2"
                                        @change="asingarGanadorASiguientePartido(partido)"
                                        type="number"
                                        style="width: 36px"
                                        ></v-text-field>
                                    </div>
                                </div>
                            </v-card>
                        </div>
                    </div>
                </v-col>
            </div>
        </div>
    </div>
</template>

<style scoped>
    .text-truncate {
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
    }

    .align-center {
    align-items: center;
    }

    .my-auto {
    margin: auto 0;
    }

    .mb-none {
    margin-bottom: 0;
    }

    .container-group_player {
    display: flex;
    justify-content: space-between;
    margin-right: 16px;
    margin-left: 16px;
    }

    .container-group_player .container-group_player_info {
    text-overflow: ellipsis;
    max-width: 80%;
    }

    .container-group_player .input-sets {
    width: 36px;
    }

    .container-group_position {
    width: 55px;
    padding-bottom: 6px;
    }

    .v-text-field__details {
    display: none;
    }

    .max-w-80 {
    max-width: 80%;
    }
</style>


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

            if(partido.setsJugador1 == partido.setsJugador2){
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

            //ordenamos cada grupo
            this.categoria.listaGrupos.forEach(grupo => {
                gruposConJugadoresOrdenados.push({ ...grupo, jugadoresOrdenados: this.getJugadoresOrdenadosPorGrupo(grupo) });
            });

            //ordenamos todos los grupos juntos
            const todosLosJugadoresOrdenados = this.getJugadoresOrdenadosFromGrupos(gruposConJugadoresOrdenados);

            const nroJugadores = todosLosJugadoresOrdenados.length;
            const nroJugadoresLlavesPerfectas = Math.pow(2, Math.floor(Math.log2(nroJugadores)));
            const nroPartidosLlavesAjustes = nroJugadores - nroJugadoresLlavesPerfectas;
            const nroJugadoresLlavesAjustes = nroPartidosLlavesAjustes * 2;

            let jugadoresParaLlavesPrimeraFase = [];
            let jugadoresParaLlavesDeAjuste = [];

            if(nroJugadoresLlavesAjustes && this.categoria.gruposConEliminatoria) {
                jugadoresParaLlavesPrimeraFase = todosLosJugadoresOrdenados.slice(0, nroJugadores - nroJugadoresLlavesAjustes)
                jugadoresParaLlavesDeAjuste = todosLosJugadoresOrdenados.slice(nroJugadores - nroJugadoresLlavesAjustes, nroJugadores)
                //serpenteamos los de ajuste
                jugadoresParaLlavesDeAjuste = this.serpenteoArray(jugadoresParaLlavesDeAjuste).reverse()
            } else {
                jugadoresParaLlavesPrimeraFase = todosLosJugadoresOrdenados.slice(0, nroJugadoresLlavesPerfectas)
            }

            // obtenemos las rondas y asignamos los partidos 
            const rondas = this.calcularNumeroDeRondas(nroJugadores);
            const partidosAsignados = this.asignarJugadoresPartidos(partidosLlaves, jugadoresParaLlavesPrimeraFase, jugadoresParaLlavesDeAjuste, rondas)

            return partidosAsignados;
        },

        asignarJugadoresPartidos(partidosLlaves, jugadoresParaLlavesPrimeraFase, jugadoresParaLlavesDeAjuste, rondas) {
            const primeraFase = this.FASES[rondas];
            const faseDeAjuste = this.FASES[rondas+1];

            let indexPartidosPrimeraFase = 0;
            let direccionPositiva = true;

            while(jugadoresParaLlavesPrimeraFase.length) {
                if(indexPartidosPrimeraFase == partidosLlaves[primeraFase].length) {
                    direccionPositiva = false;
                    indexPartidosPrimeraFase--
                }

                partidosLlaves[primeraFase] = this.ordenarPartidosParaAsignacion(partidosLlaves[primeraFase])
                let partido = partidosLlaves[primeraFase][indexPartidosPrimeraFase]
                partidosLlaves[primeraFase] = this.ordenarPartidosParaAsignacion(partidosLlaves[primeraFase])

                let jugador = jugadoresParaLlavesPrimeraFase.shift();

                if(partido) { //si falla por algun motivo, que no se rompa
                    if(direccionPositiva) {
                        partido.jugador1 = jugador;

                        if(jugadoresParaLlavesDeAjuste?.length > 1) {
                            partidosLlaves[faseDeAjuste] = this.ordenarPartidosParaAsignacion(partidosLlaves[faseDeAjuste])
                            let partidoAjuste = this.getPrimerPartidoDisponible(partidosLlaves, faseDeAjuste);
                            partidosLlaves[faseDeAjuste] = this.ordenarPartidosParaAsignacion(partidosLlaves[faseDeAjuste])

                            if(partidoAjuste) {
                                let jugador1 = jugadoresParaLlavesDeAjuste.shift();
                                let jugador2 = jugadoresParaLlavesDeAjuste.shift();

                                partidoAjuste.jugador1 = jugador1;
                                partidoAjuste.jugador2 = jugador2;
                            }
                        }
                    }
                    else {
                        partido.jugador2 = jugador;
                    }
                }

                indexPartidosPrimeraFase += direccionPositiva ? 1 : -1;
            }

            while(jugadoresParaLlavesPrimeraFase.length) {
                if(indexPartidosPrimeraFase == partidosLlaves[primeraFase].length) {
                    direccionPositiva = false;
                    indexPartidosPrimeraFase--
                }

                partidosLlaves[primeraFase] = this.ordenarPartidosParaAsignacion(partidosLlaves[primeraFase])
                let partido = partidosLlaves[primeraFase][indexPartidosPrimeraFase]
                partidosLlaves[primeraFase] = this.ordenarPartidosParaAsignacion(partidosLlaves[primeraFase])

                let jugador = jugadoresParaLlavesPrimeraFase.shift();

                if(partido) { //si falla por algun motivo, que no se rompa
                    if(direccionPositiva) {
                        partido.jugador1 = jugador;

                        if(jugadoresParaLlavesDeAjuste?.length > 1) {
                            partidosLlaves[faseDeAjuste] = this.ordenarPartidosParaAsignacion(partidosLlaves[faseDeAjuste])
                            let partidoAjuste = this.getPrimerPartidoDisponible(partidosLlaves, faseDeAjuste);
                            partidosLlaves[faseDeAjuste] = this.ordenarPartidosParaAsignacion(partidosLlaves[faseDeAjuste])

                            if(partidoAjuste) {
                                let jugador1 = jugadoresParaLlavesDeAjuste.shift();
                                let jugador2 = jugadoresParaLlavesDeAjuste.shift();

                                partidoAjuste.jugador1 = jugador1;
                                partidoAjuste.jugador2 = jugador2;
                            }
                        }
                    }
                    else {
                        partido.jugador2 = jugador;
                    }
                }

                indexPartidosPrimeraFase += direccionPositiva ? 1 : -1;
            }

            while(jugadoresParaLlavesDeAjuste?.length > 1) {
                let partidoAjuste = this.getPrimerPartidoDisponible(partidosLlaves, faseDeAjuste);

                if(partidoAjuste) {
                    let jugador1 = jugadoresParaLlavesDeAjuste.shift();
                    let jugador2 = jugadoresParaLlavesDeAjuste.shift();

                    partidoAjuste.jugador1 = jugador1;
                    partidoAjuste.jugador2 = jugador2;
                }
            }

            if(partidosLlaves[faseDeAjuste]) {
                // recorremos todos los partidos de primera fase y asignamos bien los padres
                let indexPartidoAjuste = 0;
                partidosLlaves[primeraFase] = this.ordenarPartidosParaAsignacion(partidosLlaves[primeraFase])
                partidosLlaves[faseDeAjuste] = this.ordenarPartidosParaAsignacion(partidosLlaves[faseDeAjuste])

                for (let index = 0; index < partidosLlaves[primeraFase].length; index++) {
                    if(indexPartidoAjuste < partidosLlaves[faseDeAjuste].length) {
                        const partidoPrimeraFase = partidosLlaves[primeraFase][index]
                        let partidoAjuste1 = partidosLlaves[faseDeAjuste][indexPartidoAjuste]
                        let partidoAjuste2 = partidosLlaves[faseDeAjuste][indexPartidoAjuste+1]

                        if(partidoPrimeraFase.jugador1) {
                            if(partidoPrimeraFase) {
                                partidoAjuste1.idPartidoPadre = partidoPrimeraFase.id
                                indexPartidoAjuste += 1
                            }
                        }
                        else if(partidoPrimeraFase) {
                            partidoAjuste1.idPartidoPadre = partidoPrimeraFase.id
                            partidoAjuste2.idPartidoPadre = partidoPrimeraFase.id
                            indexPartidoAjuste += 2
                        }
                    }
                }

                partidosLlaves[primeraFase] = this.ordenarPartidosParaAsignacion(partidosLlaves[primeraFase])
                partidosLlaves[faseDeAjuste] = this.ordenarPartidosParaAsignacion(partidosLlaves[faseDeAjuste])
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

            if(partido == undefined) {
                return null
            }

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

            while(posicion > 1) {
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

            return this.eliminarDuplicados([...A, ...B.reverse()])
        },

        eliminarDuplicados(arr) {
            return arr.filter((objeto, index, self) =>
                index === self.findIndex(item => item.id === objeto.id)
            );
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
                        let jugador = grupo.jugadoresOrdenados[j]
                        jugador.posicionGrupo = grupo.nombre + '' + (j+1) //{grupo} {posicion}
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
                console.log(this.FASES[ronda])
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
                                idPartidoPadre: null, //partidoAnterior.id
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
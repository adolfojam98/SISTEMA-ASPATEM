<template>
    <div>
        <v-toolbar>
            <template>
                <v-tabs v-model="tab" align-with-title>
                    <v-tabs-slider color="yellow"></v-tabs-slider>

                    <v-tab v-for="item in listaCategorias" :key="item.id">
                        {{ item.nombre }}
                    </v-tab>
                </v-tabs>
            </template>
        </v-toolbar>

        <v-tabs-items v-model="tab">
            <v-tab-item v-for="item in listaCategorias" :key="item.id">
                <v-card flat color="#546E7A" class="rounded-0">
                    <v-container v-if="gruposGenerados(item)">
                        <v-row>
                            <v-form v-model="valid" lazy-validation>
                                <v-col>
                                    <v-text-field
                                        label="Cantidad de Grupos"
                                        v-model="item.cantidadGrupos"
                                        :rules="cantidadGruposRules"
                                        required
                                        dark
                                        class="mb-0 ml-2"
                                    ></v-text-field>

                                    <v-switch
                                        v-model="item.gruposConEliminatoria"
                                        label="Fase de grupos con eliminatoria"
                                        dark
                                        class="ml-2 mt-0"
                                    ></v-switch>

                                    <v-btn
                                        class="ml-2 mr-4"
                                        dark
                                        :disabled="!valid"
                                        @click="[generarGrupos(item)]"
                                        color="blue"
                                        >Generar grupos</v-btn
                                    >
                                </v-col>
                            </v-form>

                            <v-col>
                                <v-simple-table dark>
                                    <template v-slot:default>
                                        <thead>
                                            <tr>
                                                <th class="text-left">
                                                    Apellido
                                                </th>
                                                <th class="text-left">
                                                    Nombre
                                                </th>
                                                <th class="text-left">
                                                    DNI
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr
                                                v-for="jugador in item.jugadoresAnotados"
                                                :key="jugador.id"
                                            >
                                                <td>{{ jugador.apellido }}</td>
                                                <td>{{ jugador.nombre }}</td>
                                                <td>{{ jugador.dni }}</td>
                                            </tr>
                                        </tbody>
                                    </template>
                                </v-simple-table>
                            </v-col>
                        </v-row>
                    </v-container>

                    <div v-else>
                        <v-btn
                            class="ml-6 mt-6"
                            dark
                            @click="deshacerGrupos(item)"
                            color="blue"
                            >Deshacer grupos</v-btn
                        >

                        <v-card
                            v-for="grupo in item.listaGrupos"
                            :key="grupo.nombre"
                            flat
                            color="#546E7A"
                            class="rounded-0"
                        >
                            <v-col>
                                <v-card
                                    color="#90A4AE"
                                    class="mt-6 mb-6 ml-12 mr-12"
                                >
                                    <v-container
                                        v-for="partido in grupo.partidos"
                                        :key="partido.id"
                                    >
                                        <v-flex d-flex class="mb-0">
                                            <v-tooltip bottom>
                                                <template
                                                    v-slot:activator="{
                                                        on,
                                                        attrs
                                                    }"
                                                >
                                                    <h3 class="mt-2">
                                                        {{
                                                            partido.jugador1
                                                                .apellido
                                                        }}
                                                        <v-icon
                                                            class="mb-1"
                                                            v-bind="attrs"
                                                            v-on="on"
                                                            >mdi-account-question</v-icon
                                                        >
                                                    </h3>
                                                </template>
                                                <span
                                                    >{{
                                                        partido.jugador1.nombre
                                                    }}
                                                    -
                                                    {{
                                                        partido.jugador1.dni
                                                    }}</span
                                                >
                                            </v-tooltip>

                                            <v-select
                                                class="ml-4 mr-2"
                                                :items="cantidadSets"
                                                v-model="partido.setsJugador1"
                                                solo
                                                dense
                                            ></v-select>

                                            <v-select
                                                class="ml-2 mr-4"
                                                :items="cantidadSets"
                                                v-model="partido.setsJugador2"
                                                dense
                                                solo
                                            ></v-select>

                                            <v-tooltip bottom>
                                                <template
                                                    v-slot:activator="{
                                                        on,
                                                        attrs
                                                    }"
                                                >
                                                    <h3 class="mt-2">
                                                        {{
                                                            partido.jugador2
                                                                .apellido
                                                        }}
                                                        <v-icon
                                                            class="mb-1"
                                                            v-bind="attrs"
                                                            v-on="on"
                                                            >mdi-account-question</v-icon
                                                        >
                                                    </h3>
                                                </template>
                                                <span
                                                    >{{
                                                        partido.jugador2.nombre
                                                    }}
                                                    -
                                                    {{
                                                        partido.jugador2.dni
                                                    }}</span
                                                >
                                            </v-tooltip>
                                        </v-flex>
                                    </v-container>
                                </v-card>
                            </v-col>
                        </v-card>

                        <center>
                            <v-btn
                                class="center mb-6"
                                dark
                                @click="item.llavesGeneradas = true"
                                color="blue"
                            >
                                Generar llaves
                            </v-btn>
                        </center>

                        <generar-llaves
                            v-if="item.llavesGeneradas"
                            :categoria="item"
                        ></generar-llaves>
                    </div>
                </v-card>
            </v-tab-item>
        </v-tabs-items>

        <v-snackbar v-model="snackbar" timeout="3000">
            <div v-text="message"></div>

            <template v-slot:action="{ attrs }">
                <v-btn
                    color="blue"
                    text
                    v-bind="attrs"
                    @click="snackbar = false"
                >
                    Cerrar
                </v-btn>
            </template>
        </v-snackbar>
    </div>
</template>

<script>
import { mapMutations, mapState } from "vuex";
export default {
    data() {
        return {
            tab: null,
            valid: false,
            snackbar: false,
            message: "",
            cantidadSets: [0, 1, 2, 3, 4, 5, 6, 7],
            cantidadGruposRules: [
                v => !!v || "Cantidad de grupos requerido",
                v =>
                    /^([0-9]*)?[0-9]+$/.test(v) ||
                    "Deben ser solo numeros enteros"
            ]
        };
    },

    computed: {
        ...mapState("crearFecha", ["listaCategorias"])
    },
    methods: {
        gruposGenerados(categoria) {
            //TODO falta
            return !categoria.gruposGenerados;
        },
        generarGrupos(categoria) {
            //TODO falta
            console.log("Ejecucionn generarGrupos");
            if (
                categoria.jugadoresAnotados.length /
                    parseInt(categoria.cantidadGrupos) >=
                3
            ) {
                var letras = [
                    "A",
                    "B",
                    "C",
                    "D",
                    "E",
                    "F",
                    "G",
                    "H",
                    "I",
                    "J",
                    "K",
                    "L",
                    "M",
                    "N",
                    "O",
                    "P",
                    "Q",
                    "R",
                    "S",
                    "T",
                    "U",
                    "V",
                    "W",
                    "X",
                    "Y",
                    "Z"
                ];
                for (var i = 0; i < categoria.cantidadGrupos; i++) {
                    var unGrupo = {
                        nombre: letras[i],
                        jugadoresDelGrupo: [],
                        partidos: []
                    };
                    categoria.listaGrupos.push(unGrupo);
                }

                categoria.jugadoresAnotados = categoria.jugadoresAnotados.sort(
                    (a, b) => b.pivot.puntos - a.pivot.puntos
                );

                categoria.listaGrupos.forEach(function(
                    grupo,
                    indiceGrupo,
                    array1
                ) {
                    categoria.jugadoresAnotados.forEach(function(
                        jugador,
                        indiceJugador,
                        array2
                    ) {
                        if (
                            indiceJugador %
                                parseInt(categoria.cantidadGrupos) ==
                            indiceGrupo
                        ) {
                            grupo.jugadoresDelGrupo.push(jugador);
                        }
                    });
                });

                this.generarPartidosGrupos(categoria.listaGrupos);
                categoria.gruposGenerados = true;
                this.message = "Grupos generados con exito";
                this.snackbar = true;
            } else {
                this.message =
                    "La cantidad de jugadores es insuficiente para la cantidad de grupos";
                this.snackbar = true;
            }
        },
        deshacerGrupos(categoria) {
            //falta
            console.log("Ejecucion deshacerGrupos");
            categoria.gruposGenerados = false;
            categoria.listaGrupos = [];
        },
        generarPartidosGrupos(grupos) {
            console.log("generarPartidosGrupos");
            var IDPartido = 0;
            grupos.forEach(grupo => {
                for (var i = 0; i < grupo.jugadoresDelGrupo.length; i++) {
                    for (var j = 0; j < grupo.jugadoresDelGrupo.length; j++) {
                        if (j > i) {
                            var partido = {
                                id: IDPartido,
                                jugador1: grupo.jugadoresDelGrupo[i],
                                jugador2: grupo.jugadoresDelGrupo[j],
                                setsJugador1: null,
                                setsJugador2: null
                            };
                            IDPartido += 1;
                            grupo.partidos.push(partido);
                        }
                    }
                }
            });
        }
    }
};
</script>

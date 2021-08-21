<template>
    <div>
        <v-row>
            <v-col>
                <template>
                    <v-data-table
                        dense
                        :headers="headers"
                        :items="listaJugadores"
                        item-key="dni"
                        class="elevation-1 mr-2 ml-2"
                        :items-per-page="15"
                    >
                        <template v-slot:[`item.actions`]="{ item }">
                            <v-tooltip bottom>
                                <template v-slot:activator="{ on, attrs }">
                                    <v-icon
                                        class="ml-2 mr-4"
                                        v-bind="attrs"
                                        v-on="on"
                                        @click="[agregarEnSuCategoria(item)]"
                                        color="green"
                                        >=</v-icon
                                    >
                                </template>

                                <span>{{ calcularCategoria(item) }}</span>
                            </v-tooltip>

                            <v-tooltip bottom>
                                <template v-slot:activator="{ on, attrs }">
                                    <v-icon
                                        class="mt-1"
                                        v-bind="attrs"
                                        v-on="on"
                                        @click="
                                            [agregarEnLaCategoriaSuperior(item)]
                                        "
                                        color="primary"
                                        >^</v-icon
                                    >
                                </template>
                                <span>{{
                                    calcularCategoriaSuperior(item)
                                }}</span>
                            </v-tooltip>
                        </template>
                        
                        <template v-slot:[`item.pivot.puntos`]="{ item }">
                            <input type="number"
                            id="soloNumeros"
                            class="soloNumeros"
                            style="width:80px;"
                            v-model.number=item.pivot.puntos
                            >
                        </template>
                    </v-data-table>
                </template>

                <v-btn
                    block
                    v-if="!nuevoJugador"
                    @click="changeBooleanValueNuevoJugador"
                >
                    <v-icon></v-icon>
                    Agregar jugador a la lista
                </v-btn>
            </v-col>
        </v-row>

        <v-form v-if="nuevoJugador" v-model="valid" lazy-validation>
            <v-text-field
                :value="apellidoJugador"
                @input="setApellidoJugador"
                :rules="aynRules"
                class="subheading font-weight-bold"
                label="Apellido del jugador"
                required
            ></v-text-field>
            <v-text-field
                :value="nombreJugador"
                @input="setNombreJugador"
                :rules="aynRules"
                class="subheading font-weight-bold"
                label="Nombre del jugador"
                required
            ></v-text-field>
            <v-text-field
                :value="dniJugador"
                @input="setDniJugador"
                :rules="dniRules"
                class="subheading font-weight-bold"
                label="DNI del jugador"
                required
            ></v-text-field>
            <v-text-field
                :value="puntosJugador"
                @input="setPuntosJugador"
                class="subheading font-weight-bold"
                label="Puntos del jugador"
                :rules="puntosRules"
                required
            ></v-text-field>

            <v-btn
                block
                class="rounded-pill"
                color="primary"
                :disabled="!valid"
                @click="[agregarJugador()]"
                >Agregar</v-btn
            >

            <v-btn
                block
                color="error"
                class="rounded-pill mt-3 mb-3"
                dark
                @click="limpiarNuevoJugador()"
                >Cancelar</v-btn
            >
        </v-form>
    </div>
</template>

<script>
import { mapActions, mapMutations, mapState } from "vuex";
export default {
    data() {
        return {
            
            valid: false,
            headers: [
                { text: "Apellido", value: "apellido" },
                { text: "Nombre", value: "nombre" },
                { text: "DNI", value: "dni" },
                { text: "Puntos", value: "pivot.puntos"},
                {
                    text: "Categorias",
                    value: "actions",
                    sortable: false,
                    filterable: false
                },
                { text: "Monto", value: "montoPagado" }
            ],
            aynRules: [
                v => !!v || "Campo obligatorio",
                v =>
                    /^([A-Za-z][A-Za-z]*([ \t\n\r\f]?[A-Za-z])*)+$/.test(v) ||
                    "Nombre invalido",
                v => v.length <= 30 || "Demasiado largo"
            ],
            dniRules: [
                v => !!v || "Campo obligatorio",
                v => /^([0-9]*)+$/.test(v) || "Solo numeros",
                v =>
                    (!!v && v.length == 8) ||
                    "El DNI debe estar compuesto por 8 numeros"
            ],
            puntosRules: [
                v => !!v || "Puntos requeridos",
                v =>
                    /^([0-9]*)?[0-9]+$/.test(v) ||
                    "Los puntos deben ser numeros enteros"
            ]
        };
    },
    computed: {
        ...mapState("crearFecha", [
            "listaJugadores",
            "listaCategorias",
            "torneoSeleccionado"
        ]),
        ...mapState("jugadores", [
            "nuevoJugador",
            "apellidoJugador",
            "nombreJugador",
            "dniJugador",
            "puntosJugador"
        ]),
    },
    methods: {
       ...mapActions(['callSnackbar']),
        ...mapMutations("jugadores", [
            "changeBooleanValueNuevoJugador",
            "setApellidoJugador",
            "setNombreJugador",
            "setDniJugador",
            "setPuntosJugador"
        ]),
        ...mapMutations("crearFecha", [
            "pushJugadorCategoria",
            "spliceJugadorCategoria",
            "pushJugador"
        ]),
        
        ...mapActions("crearFecha",["calcularMonto"]),
       
        agregarEnSuCategoria(item) {
            let categorias = this.listaCategorias;
            let me = this;
            categorias.forEach(function(categoria, indexCategoria) {

                if (
                    item.pivot.puntos >= categoria.puntos_minimos &&
                    item.pivot.puntos <= categoria.puntos_maximos
                ) {
                    if(!categoria.gruposGenerados){

                        var indice = categoria.jugadoresAnotados.indexOf(item);
                        if (indice === -1) {
                            categoria.jugadoresAnotados.push(item);
                            //me.pushJugadorCategoria({ item, indexCategoria });
                            me.callSnackbar(['Jugador anotado en su categoria','info'])
                        } else {
                            categoria.jugadoresAnotados.splice(indice,1);
                            //me.spliceJugadorCategoria({ indice, indexCategoria });
                        
                                me.callSnackbar(['El Jugador ya no esta anotado en su categoria','info'])
            
                        }
                    }else me.callSnackbar(['Los grupos de esta categoria ya han sido generados','warning'])
                }
            });
            this.calcularMonto();
        },
        limpiarNuevoJugador() {
            this.changeBooleanValueNuevoJugador();
            this.setApellidoJugador("");
            this.setNombreJugador("");
            this.setDniJugador(null);
            this.setPuntosJugador(null);
        },

        agregarJugador() {
            this.changeBooleanValueNuevoJugador();

            var jugadorExiste = false;
            this.listaJugadores.forEach(jugador => {
                if (jugador.dni == parseInt(this.dniJugador)) {
                    jugadorExiste = true;
                }
            });
            if (!jugadorExiste) {
                var jugador = [];
                jugador.push({
                    apellido: this.apellidoJugador,
                    nombre: this.nombreJugador,
                    dni: parseInt(this.dniJugador),
                    pivot: { puntos: parseInt(this.puntosJugador) },
                    montoPagado: 0
                });

                axios
                    .post("/jugadores", {
                        id_torneo: this.torneoSeleccionado.id,
                        jugadores: jugador
                    })
                    .then(res => {
                        console.log(res.data);
                        this.pushJugador(jugador.pop());
                       this.callSnackbar(['Jugador anotado  exitosamente','success'])
                    });

            
            } else {
                
                   this.callSnackbar(["Ya hay un jugador con el dni que intenta ingresar",'warning']) ;
                
            }
            this.setApellidoJugador("");
            this.setNombreJugador("");
            this.setDniJugador(null);
            this.setPuntosJugador(null);
        },

        calcularCategoria: function(item) {
            var mensaje = "";
            this.listaCategorias.forEach(categoria => {
                if (
                    item.pivot.puntos >= categoria.puntos_minimos &&
                    item.pivot.puntos <= categoria.puntos_maximos
                ) {
                    if (!categoria.jugadoresAnotados.includes(item)) {
                        mensaje = "Agregar a la categoria: " + categoria.nombre;
                    } else {
                        mensaje = "Quitar de la categoria: " + categoria.nombre;
                    }
                }
            });
            return mensaje;
        },
        calcularCategoria: function(item) {
            let mensaje = "";
            this.listaCategorias.forEach(categoria => {
                if (
                    item.pivot.puntos >= categoria.puntos_minimos &&
                    item.pivot.puntos <= categoria.puntos_maximos
                ) {
                    if (!categoria.jugadoresAnotados.includes(item)) {
                        mensaje = "Agregar a la categoria: " + categoria.nombre;
                    } else {
                        mensaje = "Quitar de la categoria: " + categoria.nombre;
                    }
                }
            });
            return mensaje;
        },
        agregarEnLaCategoriaSuperior(item) {
            var entrarEnElSiguiente = false;
            this.listaCategorias.forEach(categoria => {
                if (entrarEnElSiguiente) {
                    if(!categoria.gruposGenerados){
                        var indice = categoria.jugadoresAnotados.indexOf(item);

                        if (indice === -1) {
                            categoria.jugadoresAnotados.push(item);
                            this.calcularMonto();
                            this.callSnackbar(["Jugador anotado en la categoria inmediata superior a la suya",'info'])
                        
                        
                                "Jugador anotado en la categoria inmediata superior a la suya";
                        
                        } else {
                            categoria.jugadoresAnotados.splice(indice, 1);
                            this.calcularMonto();
                            this.callSnackbar(["El jugador ya no esta anotado en la categoria superior a la suya",'info'])
                            
                        }
                        entrarEnElSiguiente = false;
                    }else this.callSnackbar(['Los grupos de esta categoria ya han sido generados','warning'])
                }
                if (
                    item.pivot.puntos >= categoria.puntos_minimos &&
                    item.pivot.puntos <= categoria.puntos_maximos
                ) {
                    entrarEnElSiguiente = true;
                }else entrarEnElSiguiente = false;
            });
            if (entrarEnElSiguiente) {
                this.callSnackbar(["No hay una categoria superior",'warning'])
            }
        },
        calcularCategoriaSuperior(item) {
            let entrarEnElSiguiente = false;
            let mensaje = "No hay una categoria superior";
            this.listaCategorias.forEach(categoria => {
                if (entrarEnElSiguiente) {
                    var indice = categoria.jugadoresAnotados.indexOf(item);

                    if (indice === -1) {
                        mensaje =
                            "Agregar en la categoria superior: " +
                            categoria.nombre;
                    } else {
                        mensaje =
                            "Quitar de la categoria superior: " +
                            categoria.nombre;
                    }
                    entrarEnElSiguiente = false;
                }
                if (
                    item.pivot.puntos >= categoria.puntos_minimos &&
                    item.pivot.puntos <= categoria.puntos_maximos
                ) {
                    entrarEnElSiguiente = true;
                }
            });
            return mensaje;
        },
        eliminarJugador(item) {
            var indice = this.listaJugadores.indexOf(item);
            this.listaJugadores.splice(indice, 1);
        },
    }
};
</script>

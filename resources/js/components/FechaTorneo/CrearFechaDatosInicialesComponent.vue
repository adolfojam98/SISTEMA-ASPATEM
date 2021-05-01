<template>
    <div>
        <v-select
            :value="torneoSeleccionado"
            @input="setTorneoSeleccionado"
            :items="torneos"
            item-text="nombre"
            return-object
            filled
            label="Seleccione el torneo"
            class="subheading font-weight-bold"
        ></v-select>

        <v-card elevation="4" class="rounded-sm">
            <v-text-field
                dense
                filled
                :value="nombreFecha"
                @input="setNombreFecha"
                class="subheading font-weight-bold"
                label="Nombre de la fecha"
                :rules="nombreFechaRules"
                required
            ></v-text-field>

            <v-row>
                <v-col cols="12" md="6">
                    <v-text-field
                        :value="montoSociosUnaCategoria"
                        @input="setMontoSociosUnaCategoria"
                        :rules="montoRules"
                        class="mr-2 ml-2"
                       outlined
                        label="Monto socios que juegan una categoria"
                        prefix="$"
                        
                    ></v-text-field>

                    <v-text-field
                        :value="montoSociosDosCategorias"
                        @input="setMontoSociosDosCategorias"
                        :rules="montoRules"
                        class="mr-2 ml-2"
                        outlined
                        label="Monto socios que juegan dos categorias"
                        prefix="$"
                    ></v-text-field>
                </v-col>

                <v-col cols="12" md="6">
                    <v-text-field
                        :value="montoNoSociosUnaCategoria"
                        @input="setMontoNoSociosUnaCategoria"
                        :rules="montoRules"
                        class="mr-2 ml-2"
                        outlined
                        label="Monto no socios que juegan una categoria"
                        prefix="$"
                    ></v-text-field>

                    <v-text-field
                        :value="montoNoSociosDosCategorias"
                        @input="setMontoNoSociosDosCategorias"
                        :rules="montoRules"
                        class="mr-2 ml-2"
                        outlined
                        label="Monto no socios que juegan dos categorias"
                        prefix="$"
                    ></v-text-field>
                </v-col>
            </v-row>
        </v-card>
    </div>
</template>

<script>
import { mapState, mapMutations, mapActions } from "vuex";
export default {
    data() {
        return {
            nombreFechaRules: [
                v => !!v || "Nombre requerido",
                v =>
                    /^(([A-Za-z0-9]*([/])*[ \t\n\r\f]?[A-Za-z0-9])*)+$/.test(
                        v
                    ) || "Nombre invalido",
                v => v.length <= 30 || "Demasiado largo"
            ],
            montoRules: [
                v => !!v || "Monto requerido",
                v =>
                    /^(([0-9]*)([.][0-9]([0-9])*)*)+$/.test(v) ||
                    "Monto no valido "
            ]
        };
    },
    computed: {
        ...mapState("crearFecha", [
            "torneoSeleccionado",
            "nombreFecha",
            "torneos",
            "montoSociosUnaCategoria",

            "montoSociosDosCategorias",
            "montoNoSociosUnaCategoria",
            "montoNoSociosDosCategorias"
        ])
    },
    methods: {
        ...mapMutations("crearFecha", [
            "setTorneoSeleccionado",
            "setNombreFecha",
            "setMontoSociosUnaCategoria",
            "setMontoSociosDosCategorias",
            "setMontoNoSociosUnaCategoria",
            "setMontoNoSociosDosCategorias",
            "setListaCategorias",
            "setListaJugadores"
        ]),
        traerJugadoresTorneo() {
            let me = this;
            axios
                .get(`/torneos/${this.torneoSeleccionado.id}/jugadores`)
                .then(res => {
                    console.log(res.data);
                    this.setListaJugadores(res.data);
                });
        },
        ...mapActions("crearFecha",['calcularMonto']),
        traerCategorias() {
            let me = this;
            axios
                .get(`/torneos/${this.torneoSeleccionado.id}/categorias`)
                .then(res => {
                    //this.$store.commit('setListaCategorias', res.data);
                    res.data.forEach(categoria => {
                        Object.defineProperty(categoria, "jugadoresAnotados", {
                            value: [],
                            writable: true,
                            configurable: true,
                            enumerable: true
                        });
                        Object.defineProperty(
                            categoria,
                            "gruposConEliminatoria",
                            {
                                value: false,
                                writable: true,
                                configurable: true,
                                enumerable: true
                            }
                        );
                        Object.defineProperty(categoria, "cantidadGrupos", {
                            writable: true,
                            configurable: true,
                            enumerable: true
                        });
                        Object.defineProperty(categoria, "listaGrupos", {
                            value: [],
                            writable: true,
                            configurable: true,
                            enumerable: true
                        });
                        //Object.defineProperty(categoria,'gruposGenerados', {value: false, writable:true, configurable:true, enumerable:true});
                        this.$set(categoria, "gruposGenerados", false);
                        this.$set(categoria, "llavesGeneradas", false);
                    });
                    this.setListaCategorias(res.data);
                });
        },
        
    },
    watch: {
        torneoSeleccionado() {
            console.log(" watcher torneoSeleccionado");
            this.traerJugadoresTorneo();
            this.traerCategorias();
        },
        listaCategorias() {
            console.log(" watcher listaCategorias");
            this.calcularMonto();
        },
        montoSociosUnaCategoria() {
            console.log(" watcher montoSociosUnaCategoria");
            this.calcularMonto();
        },
        montoSociosDosCategorias() {
            console.log(" watcher montoSociosDosCategorias");
            this.calcularMonto();
        },
        montoNoSociosUnaCategoria() {
            console.log(" watcher montoNoSociosUnaCategoria");
            this.calcularMonto();
        },
        montoNoSociosDosCategorias() {
            console.log(" watcher montoNoSociosDosCategorias");
            this.calcularMonto();
        }
    }
};
</script>

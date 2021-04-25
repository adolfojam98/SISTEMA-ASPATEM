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
                        solo
                        label="Monto socios que juegan una categoria"
                        prefix="$"
                    ></v-text-field>

                    <v-text-field
                        :value="montoSociosDosCategorias"
                        @input="setMontoSociosDosCategorias"
                        :rules="montoRules"
                        class="mr-2 ml-2"
                        solo
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
                        solo
                        label="Monto no socios que juegan una categoria"
                        prefix="$"
                    ></v-text-field>

                    <v-text-field
                        :value="montoNoSociosDosCategorias"
                        @input="setMontoNoSociosDosCategorias"
                        :rules="montoRules"
                        class="mr-2 ml-2"
                        solo
                        label="Monto no socios que juegan dos categorias"
                        prefix="$"
                    ></v-text-field>
                </v-col>
            </v-row>
        </v-card>
    </div>
</template>

<script>
import { mapState, mapMutations } from "vuex";
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
            "setMontoNoSociosDosCategorias"
        ])
    }
};
</script>

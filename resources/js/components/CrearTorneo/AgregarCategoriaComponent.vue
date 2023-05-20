<template>
    <div>
        <v-card class="mb-0">
            <v-card-title class="mb-0 justify-center" >Nueva categoria</v-card-title>
            <v-divider></v-divider>
            <v-form v-model="valid" ref="form">
                <v-card-text>
                    <v-text-field v-model="nombreNuevaCategoria" :rules="nombreCategoriaRules"
                        label="Nombre de la Categoria" required></v-text-field>
                    <v-text-field v-model="puntosMinimos" :rules="puntosRules" label="Puntos Minimos de la Categoria"
                        required v-on:keyup.enter="[agregarCategoria(), resetValidate()]"></v-text-field>
                </v-card-text>

                <v-btn block class="mb-0" color="primary" @click="[agregarCategoria(), resetValidate()]"
                    :disabled="!valid">Agregar</v-btn>
            </v-form>
        </v-card>
    </div>
</template>

<script>
import { mapActions, mapState } from "vuex";
export default {
    data() {
        return {
            valid: false,
            nombreNuevaCategoria: "",
            puntosMinimos: null,

            //RULES
            nombreCategoriaRules: [
                (v) => !!v || "Nombre requerido",
                (v) =>
                    /^([A-Za-z]([A-Za-z0-9]*[ \t\n\r\f]?[A-Za-z0-9])*)+$/.test(v) ||
                    "Nombre invalido",
                (v) => v.length <= 30 || "Demasiado largo",
            ],
            puntosRules: [
                (v) => !!v || "Puntos requeridos",
                (v) =>
                    /^([0-9]*)?[0-9]+$/.test(v) || "Los puntos deben ser numeros enteros",
            ]
        };
    },
    computed: {
        ...mapState("CrearTorneo", ["arrayCategorias"]),
    },
    methods: {
        ...mapActions(["callSnackbar"]),
        resetValidate() {
            this.$refs.form.resetValidation();
        },
        existeNombreCategoria() {
            return this.arrayCategorias.some(
                (categoria) => categoria.nombre === this.nombreNuevaCategoria
            );
        },
        resetearFormulario(){
            this.nombreNuevaCategoria = "";
            this.puntosMinimos = null;
        },
        agregarCategoria() {
            if (this.existeNombreCategoria()) {
                this.callSnackbar(["Ya existe una categoria con este nombre", "error"]);
                return;
            }
            const categoria = {
                nombre: this.nombreNuevaCategoria,
                puntosMinimo: parseInt(this.puntosMinimos),
                puntosMaximo: 9999999,
            };

            if (this.arrayCategorias.length === 0) {
                this.arrayCategorias.push(categoria);
                this.resetearFormulario();
                return;
            }

            for (let i = 0; i < this.arrayCategorias.length; i++) {
                if (
                    parseInt(this.arrayCategorias[i].puntosMinimo) ==
                    parseInt(this.puntosMinimos)
                ) {
                    this.callSnackbar([
                        "Ya existe una categoria con estos puntos minimos",
                        "error",
                    ]);
                    break;
                }

                if (
                    parseInt(this.arrayCategorias[i].puntosMinimo) >
                    parseInt(this.puntosMinimos) &&
                    i == 0
                ) {
                    categoria.puntosMaximo =
                        parseInt(this.arrayCategorias[i].puntosMinimo) - 1;
                    this.arrayCategorias.splice(i, 0, categoria);
                    break;
                } else {
                    if (
                        parseInt(this.arrayCategorias[i].puntosMinimo) >
                        parseInt(this.puntosMinimos)
                    ) {
                        if (this.arrayCategorias.length > i) {
                            if (this.arrayCategorias.length > 1) {
                                this.arrayCategorias[--i].puntosMaximo =
                                    parseInt(this.puntosMinimos) - 1;
                                i++;
                            }
                            categoria.puntosMaximo =
                                parseInt(this.arrayCategorias[i].puntosMinimo) - 1;
                        }

                        if (i + 1 == this.arrayCategorias.length) {
                            this.arrayCategorias[i].puntosMaximo = 9999999;
                        }

                        this.arrayCategorias.splice(i, 0, categoria);
                        break;
                    }
                }

                if (
                    parseInt(this.arrayCategorias[i].puntosMinimo) <
                    parseInt(this.puntosMinimos) &&
                    this.arrayCategorias.length === i + 1
                ) {
                    this.arrayCategorias[i--].puntosMaximo =
                        parseInt(this.puntosMinimos) - 1;
                    i++;

                    this.arrayCategorias.push(categoria);
                    break;
                }
            }
         this.resetearFormulario();
        },
    },
};
</script>


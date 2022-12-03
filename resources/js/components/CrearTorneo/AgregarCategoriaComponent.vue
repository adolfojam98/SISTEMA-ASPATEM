<template>
    <div>
        <v-card elevation="4" class="mb-0">
        <v-card-title elevation="4" class=" mb-0">Nueva categoria</v-card-title>

        <v-divider></v-divider>
        <v-form v-model="valid" ref="form">
            <v-card-text>

            <v-text-field
          
                v-model="nombreNuevaCategoria"
                :rules="nombreCategoriaRules"
                label="Nombre de la Categoria"
                required
            ></v-text-field>
            <v-text-field
                v-model="puntosMinimos"
                :rules="puntosRules"
                label="Puntos Minimos de la Categoria"
                required
                v-on:keyup.enter="[agregarCategoria(),resetValidate()]"
            ></v-text-field>
            </v-card-text>
            

                <v-btn
                    block
                    class="mb-0"
                    color="primary"
                    @click="[agregarCategoria(), resetValidate()]"
                    :disabled="!valid"
                    >Agregar</v-btn
                >
            
        
            <!-- <v-card-actions>
                
                <v-btn
                    block
                    color="error"
                    class="rounded-pill"
                    @click="
                        [(puntosMinimos = null), (nombreNuevaCategoria = ''),resetValidate()]
                    "
                    >Cancelar</v-btn
                >
            </v-card-actions> -->

        </v-form>
        </v-card>
    </div>
</template>

<script>
import { mapState } from "vuex";
export default {
    data() {
        return {
            valid: false,
            message: "",
            snackbar: false,
            nombreNuevaCategoria: "",
            puntosMinimos: null,

            //RULES
            nombreCategoriaRules: [
                v => !!v || "Nombre requerido",
                v =>
                    /^([A-Za-z]([A-Za-z0-9]*[ \t\n\r\f]?[A-Za-z0-9])*)+$/.test(
                        v
                    ) || "Nombre invalido",
                v => v.length <= 30 || "Demasiado largo"
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
        ...mapState("CrearTorneo", ["arrayCategorias"])
    },
    methods: {
        resetValidate() {
            this.$refs.form.resetValidation();
        },
        agregarCategoria() {
            console.log(this.arrayCategorias.length);
            let nombreExistente = false;
            this.arrayCategorias.forEach(categoria => {
                if (categoria.nombre === this.nombreNuevaCategoria) {
                    nombreExistente = true;
                }
            });

            if (nombreExistente === false) {
                var categoria = {
                    nombre: this.nombreNuevaCategoria,
                    puntosMinimo: parseInt(this.puntosMinimos),
                    puntosMaximo: 9999999
                };

                if (this.arrayCategorias.length === 0) {
                    this.arrayCategorias.push(categoria);
                } else {
                    for (var i = 0; i < this.arrayCategorias.length; i++) {
                        if (
                            parseInt(this.arrayCategorias[i].puntosMinimo) ==
                            parseInt(this.puntosMinimos)
                        ) {
                            this.message =
                                "Ya existe una categoria con estos puntos minimos";
                            this.snackbar = true;
                            break;
                        }

                        if (
                            parseInt(this.arrayCategorias[i].puntosMinimo) >
                                parseInt(this.puntosMinimos) &&
                            i == 0
                        ) {
                            categoria.puntosMaximo =
                                parseInt(this.arrayCategorias[i].puntosMinimo) -
                                1;
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
                                        parseInt(
                                            this.arrayCategorias[i].puntosMinimo
                                        ) - 1;
                                }

                                if (i + 1 == this.arrayCategorias.length) {
                                    this.arrayCategorias[
                                        i
                                    ].puntosMaximo = 9999999;
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
                }
                this.nombreNuevaCategoria = "";
                this.puntosMinimos = null;
            } else {
                this.message = "Ya existe una categoria con este nombre";
                this.snackbar = true;
            }
        }
    }
};
</script>

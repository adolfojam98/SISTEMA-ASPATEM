
<template>
    <div>
        <v-card class="mb-0">
            <v-card-title class="mb-0 justify-center">Nueva categoria</v-card-title>
            <v-divider></v-divider>
            <v-form v-model="valid" ref="form">
                <v-card-text>
                    <v-text-field v-model="nombreNuevaCategoria" :rules="nombreCategoriaRules"
                        label="Nombre de la Categoria" required></v-text-field>
                    <v-text-field v-model="puntosMinimos" :rules="puntosRules" label="Puntos Minimos de la Categoria"
                        required v-on:keyup.enter="[resetValidate()]"></v-text-field>
                    <v-text-field v-model="puntosBase" :rules="puntosRules" label="Puntos base de la Categoria" required
                        v-on:keyup.enter="[agregarCategoria(), resetValidate()]"></v-text-field>
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
            puntosBase: null,

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
        resetearFormulario() {
            this.nombreNuevaCategoria = "";
            this.puntosMinimos = null;
            this.puntosBase = null;
        },
        agregarCategoria() {
            if (this.existeNombreCategoria()) {
                this.callSnackbar(["Ya existe una categoria con este nombre", "error"]);
                return;
            }



            const nuevaCategoria = {
                nombre: this.nombreNuevaCategoria,
                puntosMinimo: parseInt(this.puntosMinimos),
                puntosMaximo: 9999999,
                puntosBase: this.puntosBase
            };

            try {
                this.insertarCategoriaOrdenada(nuevaCategoria);
            } catch (error) {
                this.callSnackbar([error.message, "error"]);
                return;
            }

            this.resetearFormulario();
        },
        validarPuntosCategorias(categoria){
            if(categoria.puntosMinimo > this.puntosBase){
                throw new Error("No se permite poner puntos base menor a puntos minimos")
            }
            if (categoria.puntosBase < categoria.puntosMinimo || categoria.puntosBase > categoria.puntosMaximo) {
                throw new Error("Los punto base no estan dentro del intervalo permitido");
            }

            for (const categoriaActual of this.arrayCategorias) {
        if (
            categoria.puntosMinimo <= categoriaActual.puntosBase &&
            categoria.puntosMaximo >= categoriaActual.puntosBase
        ) {
            throw new Error("Ya existe una categoría con puntos base dentro del intervalo.");
        }
    }
        },

        insertarCategoriaOrdenada(nuevaCategoria) {

            this.validarPuntosCategorias(nuevaCategoria);
            let insertIndex = 0;
            for (let i = 0; i < this.arrayCategorias.length; i++) {
                const categoriaActual = this.arrayCategorias[i];

                if (categoriaActual.puntosMinimo === nuevaCategoria.puntosMinimo) {
                    throw new Error("Ya existe una categoria con estos puntos minimos");
                }

                if (categoriaActual.puntosMinimo > nuevaCategoria.puntosMinimo) {
                    nuevaCategoria.puntosMaximo = categoriaActual.puntosMinimo - 1;
                    this.validarPuntosCategorias(nuevaCategoria);
                    this.arrayCategorias.splice(insertIndex, 0, nuevaCategoria);
                    return;
                }

                if (i + 1 === this.arrayCategorias.length) {
                    categoriaActual.puntosMaximo = 9999999;
                }

                insertIndex++;
            }
            this.validarPuntosCategorias(nuevaCategoria);

           

            this.arrayCategorias.push(nuevaCategoria);
        },

    },
};
</script>


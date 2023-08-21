
<template>
  <v-row>
    <v-dialog v-model="dialog"  max-width="600px">
      <template v-slot:activator="{ on, attrs }">
        <v-btn color="success" v-bind="attrs" v-on="on">
          Nuevo jugador
        </v-btn>
      </template>
      <v-card>
        <h2 class="pa-3"> <center>Agregar nuevo Jugador</center></h2>
        <v-form v-model="valid" ref="form">
          <v-text-field class="ml-2 mr-2" v-model="apellidoJugador" :rules="aynRules" label="Apellido del jugador"
            required></v-text-field>
          <v-text-field class="ml-2 mr-2" v-model="nombreJugador" :rules="aynRules" label="Nombre del jugador"
            required></v-text-field>
          <v-text-field class="ml-2 mr-2" v-model="dniJugador" :rules="dniRules" label="DNI del jugador"
            required></v-text-field>
            <v-select label="Seleccione una categoria" :items="categorias" item-text="nombre" v-model="categoriaJugadorSeleccionada" return-object></v-select>

          <v-text-field class="ml-2 mr-2" v-model="categoriaJugador" label="Puntos del jugador" :rules="puntosRules"
            required></v-text-field>
        </v-form>

        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn @click="dialog = false">
            CANCELAR
          </v-btn>
          <v-btn :disabled="!valid" class="success" @click="[ guardarJugador(), dialog = false]">
            AGREGAR
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </v-row>
</template>

<script>
import { mapState } from 'vuex';
export default {
  props: ['categorias'],
  data() {
    return {
      dialog: false,
      resetForm: false,
      valid: false,
      nombreJugador: "",
      apellidoJugador: "",
      dniJugador: null,
      puntosJugador: null,
      categoriaJugadorSeleccionada: null,

      //RULES
      aynRules: [
        (v) => !!v || "Campo obligatorio",
        (v) =>
          /^([A-Za-z][A-Za-z]*([ \t\n\r\f]?[A-Za-z])*)+$/.test(v) ||
          "Nombre invalido",
        (v) => (!v || v.length <= 30) || "Demasiado largo",
      ],
      dniRules: [
        (v) => !!v || "Campo obligatorio",
        (v) => /^([0-9]*)+$/.test(v) || "Solo numeros",
        (v) =>
          (!!v && v.length == 8) || "El DNI debe estar compuesto por 8 numeros",
      ],
      puntosRules: [
        (v) => !!v || "Puntos requeridos",
        (v) =>
          /^([0-9]*)?[0-9]+$/.test(v) || "Los puntos deben ser numeros enteros",
      ],
    };
  },
  methods: {
    guardarJugador() {      
      this.$emit('agregar-jugador', {
        nombre: this.nombreJugador,
        apellido: this.apellidoJugador,
        dni: this.dniJugador,
        puntos: this.categoriaJugadorSeleccionada.puntosBase,
      });      
      this.$refs.form.reset();
    },
  },
}
</script>
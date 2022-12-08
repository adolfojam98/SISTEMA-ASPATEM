<template>
  <div>
      <v-form v-model="valid" v-on:submit.prevent>
        <v-col cols="6">
          <v-text-field
            :value="nombreTorneo"
            label="Nombre del Torneo"
            :rules="nombreTorneoRules"
            required
            placeholder="Ej: Torneo de Maestros"
            solo
            @input="setNombreTorneo"
          ></v-text-field>
        </v-col>
      </v-form>
  </div>
</template>

<script>
import { mapMutations, mapState } from "vuex";
export default {
  data() {
    return {
      nombreTorneoRules: [
        (v) => !!v || "Nombre requerido",
        (v) =>
          /^([A-Za-z]([A-Za-z0-9]*[ \t\n\r\f]?[A-Za-z0-9])*)+$/.test(v) ||
          "Nombre invalido",
        (v) => v.length <= 30 || "Demasiado largo",
      ],
    };
  },
  computed: {
    ...mapState("CrearTorneo", ["nombreTorneo", "e6"]),
  },
  methods: {
    ...mapMutations("CrearTorneo", ["setNombreTorneo", "setStep"]),
  },
};
</script>

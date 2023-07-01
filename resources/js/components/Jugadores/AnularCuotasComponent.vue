<template>
    <v-row justify="center">
      <v-dialog
        v-model="dialog"

        max-width="600"
      >
        <template v-slot:activator="{ on, attrs }">
          <v-btn
            color="primary"
            dark
            v-bind="attrs"
            v-on="on"
          >
            Anular cuotas impagas
          </v-btn>
        </template>
        <v-card>
            <h2 class="pa-3"> <center>Desea anular las cuotas impagas del socio?</center></h2>
          <v-card-text>Esta acción anulará las cuotas por lo que el socio no poseerá mas deuda, deberá colocar una observación:

       <v-textarea
          name="input-7-1"
          label="Observación"
          v-model="observacion"
          rows="2"
        ></v-textarea>

          </v-card-text>
          <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn
              color="error"
              @click="dialog = false"
            >
              Cancelar
            </v-btn>
            <v-btn
              @click="[dialog = false, anularTodasLasCuotas()]"
            >
              ANULAR
            </v-btn>
          </v-card-actions>
        </v-card>
      </v-dialog>
    </v-row>
  </template>


<script>
  export default {
    data () {
      return {
        dialog: false,
        observacion: "" 
      }
    },
    methods: {
          anularTodasLasCuotas () {
            this.$emit('anularTodasLasCuotas')
          }
        }
  }
</script>
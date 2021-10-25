<template>
  <div>
    <v-container grid-list-xs>
      <v-form ref="form" lazy-validation v-model="valid">
        <v-container>
          <v-text-field
            :value="asunto"
            @input="setAsunto"
            label="Asunto"
            required
            :rules="ruleRequired"
          ></v-text-field>

          <v-text-field
            :value="titulo"
            @input="setTitulo"
            label="Titulo"
            required
            :rules="ruleRequired"
          ></v-text-field>

          <v-text-field
            :value="subtitulo"
            @input="setSubtitulo"
            label="Subtitulo (Opcional)"
          ></v-text-field>

          <v-textarea
            type="text"
            :value="mensaje"
            @input="setMensaje"
            label="Mensaje"
            required
            :rules="ruleRequired"
          ></v-textarea>

          <v-btn @click.prevent="sendEmail()" block large depressed color="primary">Enviar</v-btn>
        </v-container>
      </v-form>
    </v-container>
  </div>
</template>

<script>
import { mapState, mapMutations, mapActions } from "vuex";
export default {
  data() {
    return {
        valid : false,
        ruleRequired : [v => !!v || 'Campo obligatorio']
    };
  },

  computed: {
    ...mapState("avisos", ["asunto", "titulo", "subtitulo", "mensaje"]),

    store() {
      return this.$store.state;
    },
  },

  methods: {
    ...mapMutations("avisos", [
      "setAsunto",
      "setTitulo",
      "setSubtitulo",
      "setMensaje",
    ]),
    ...mapActions(["callSnackbar"]),

    sendEmail(e) {

        if(this.asunto != '' && this.titulo != '' && this.mensaje != '') {
            axios.post('/send-email',{
                asunto: this.asunto,
                titulo: this.titulo,
                subtitulo: this.subtitulo,
                mensaje: this.mensaje
            })
            .then((res) => {
                this.callSnackbar([
                    "Aviso enviado correctamente",
                    "success"
                ]);
            })
            .catch((e) => 
                this.callSnackbar([
                    "Error al enviar email",
                    "error"
                ])
            )
        } else {
            this.callSnackbar([
                "Debe completar todos los campos",
                "error"
            ])
        }
    },

  },
};
</script>
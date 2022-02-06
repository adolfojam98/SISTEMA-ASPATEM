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

          <!-- <v-textarea
            type="text"
            :value="mensaje"
            @input="setMensaje"
            label="Mensaje"
            required
            :rules="ruleRequired"
          ></v-textarea> -->

          <tiptap-vuetify
            class="mb-5"
            placeholder="Mensaje…"
            :value="mensaje"
            :extensions="extensions"
            @input="setMensaje"
            required
            :rules="ruleRequired"
            :toolbar-attributes="{ color: 'grey lighten-4' }"
          />

          <v-btn
            @click.prevent="sendEmail()"
            block
            large
            depressed
            color="primary"
            >Enviar</v-btn
          >
        </v-container>
      </v-form>
    </v-container>
  </div>
</template>

<script>
import { mapState, mapMutations, mapActions } from "vuex";
import {
  TiptapVuetify,
  Heading,
  Bold,
  Italic,
  Strike,
  Underline,
  Code,
  Image,
  Paragraph,
  BulletList,
  OrderedList,
  ListItem,
  Link,
  Blockquote,
  HardBreak,
  HorizontalRule,
  History,
  Table,
  TableCell,
  TableHeader,
  TableRow,
} from "tiptap-vuetify";
export default {
  components: { TiptapVuetify },
  data() {
    return {
      valid: false,
      ruleRequired: [(v) => !!v || "Campo obligatorio"],

      extensions: [
        Paragraph,
        [
          Heading,
          {
            options: {
              levels: [1, 2, 3],
            },
          },
        ],
        Bold,
        Italic,
        Underline,
        Strike,
        Blockquote,
        ListItem,
        BulletList,
        OrderedList,
        HorizontalRule,
        Link,
        HardBreak,
        [
          Table,
          {
            options: {
              resizable: true,
            },
          },
        ],
        TableCell,
        TableHeader,
        TableRow,
        History,
      ],
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
      if (this.asunto != "" && this.titulo != "" && this.mensaje != "") {
        axios
          .post(
            "/send-email",
            {
              asunto: this.asunto,
              titulo: this.titulo,
              subtitulo: this.subtitulo,
              mensaje: this.mensaje,
            },
            { headers: window.axios.defaults.headers.common }
          )
          .then((res) => {
            this.callSnackbar(["Aviso enviado correctamente", "success"]);
          })
          .catch((e) => this.callSnackbar(["Error al enviar email", "error"]));
      } else {
        this.callSnackbar(["Debe completar todos los campos", "error"]);
      }
    },
  },
};
</script>
<template>
  <v-container>
    <v-card>
        <v-btn class="primary mt-2 ml-2" @click="$router.go(-1)">back</v-btn>
      <v-card-title class="justify-center">
          <center>
          <p>
              <b>{{fechaId}}</b>
          </p>
        </center>
      </v-card-title>
    </v-card>
  </v-container>
</template>

<script>
export default {
  props: [],

  data() {
    return {
      fechaId: this.$route.params.id,
    };
  },

  created() {
    //console.log(this.$route);
  },
};
</script>
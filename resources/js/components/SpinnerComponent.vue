
<template>
  <div v-if="isLoading">
    <v-overlay>
      <center>
        <v-progress-circular
          indeterminate
          color="blue"
          :size="70"
          :width="7"
        ></v-progress-circular>
      </center>
    </v-overlay>
  </div>
</template>

<script>  
import { mapState } from 'vuex';
export default {

  data() {
    return {
      URL_STATUS: "/session/status",
      requestTimer: null,
    };
  },
  methods: {
  
  },
  computed: {
    ...mapState(['isLoading']),
  },

};
</script>

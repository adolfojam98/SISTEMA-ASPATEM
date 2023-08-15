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
export default {
  data() {
    return {
      isLoading: false,
      URL_STATUS: "/session/status",
      requestTimer: null,
    };
  },
  methods: {
    enableInterceptor() {
      // this.axiosInterceptor = window.axios.interceptors.request.use(
      //   (config) => {
      //     if (!(config.url === this.URL_STATUS)) {
      //       // Iniciar el timer al hacer la petición
      //       this.requestTimer = setTimeout(() => {
      //         this.isLoading = true;
      //       }, 500);
      //     }

      //     return config;
      //   },
      //   (error) => {
      //     this.isLoading = false;
      //     // Limpiar el timer en caso de error
      //     clearTimeout(this.requestTimer);
      //     return Promise.reject(error);
      //   }
      // );

      // window.axios.interceptors.response.use(
      //   (response) => {
      //     // Limpiar el timer al recibir la respuesta
      //     clearTimeout(this.requestTimer);
      //     this.isLoading = false;
      //     return response;
      //   },
      //   (error) => {
      //     // Limpiar el timer en caso de error
      //     clearTimeout(this.requestTimer);
      //     this.isLoading = false;
      //     return Promise.reject(error);
      //   }
      // );
    },
  },
  created() {
    this.enableInterceptor();
  },
};
</script>

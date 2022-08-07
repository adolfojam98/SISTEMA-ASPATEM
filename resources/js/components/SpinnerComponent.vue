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
    };
  },
  methods: {
    enableInterceptor() {
      this.axiosInterceptor = window.axios.interceptors.request.use(
        (config) => {
          if (!config.url == this.URL_STATUS) {
            setTimeout(() => {
              this.isLoading = true;
            }, 2000);
          }

          return config;
        },
        (error) => {
          this.isLoading = false;
          return Promise.reject(error);
        }
      );

      window.axios.interceptors.response.use(
        (response) => {
          this.isLoading = false;
          return response;
        },
        function (error) {
          this.isLoading = false;
          return Promise.reject(error);
        }
      );
    },
  },
  created() {
    this.enableInterceptor();
  },
};
</script>
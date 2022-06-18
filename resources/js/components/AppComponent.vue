<template>
  <div
    class="hover"
    @mouseenter="getSessionStatus()"
    @mouseleave="getSessionStatus()"
    @click="getSessionStatus()"
  >
    <v-app id="inspire">
      <v-navigation-drawer v-model="drawer" app color="#CFD8DC">
        <v-list
          class="hover"
          @mouseenter="getSessionStatus()"
          @mouseleave="getSessionStatus()"
          @click="getSessionStatus()"
        >
          <v-list-item link :to="{ name: 'home' }">
            <v-list-item-action>
              <v-icon>mdi-home</v-icon>
            </v-list-item-action>
            <v-list-item-content>
              <v-list-item-title>Home</v-list-item-title>
            </v-list-item-content>
          </v-list-item>

          <v-list-group no-action prepend-icon="mdi-account">
            <template v-slot:activator>
              <v-list-item-title>Socios</v-list-item-title>
            </template>

            <v-list-item link :to="{ name: 'agregar' }">
              <v-list-item-title>Agregar socio</v-list-item-title>
              <v-list-item-icon>
                <v-icon>mdi-account-plus</v-icon>
              </v-list-item-icon>
            </v-list-item>

            <v-list-item link :to="{ name: 'lista' }">
              <v-list-item-title>Lista socios</v-list-item-title>
              <v-list-item-icon>
                <v-icon>mdi-account-group</v-icon>
              </v-list-item-icon>
            </v-list-item>

            <v-list-item link :to="{ name: 'pagos' }">
              <v-list-item-title>Pagos</v-list-item-title>
              <v-list-item-icon>
                <v-icon>mdi-currency-usd</v-icon>
              </v-list-item-icon>
            </v-list-item>
          </v-list-group>

          <v-list-group no-action prepend-icon="mdi-tournament">
            <template v-slot:activator>
              <v-list-item-title>Torneos</v-list-item-title>
            </template>

            <v-list-item link :to="{ name: 'gestion-torneos' }">
            <v-list-item-title>Gestion torneos</v-list-item-title>
            <v-list-item-icon>
              <v-icon>mdi-trophy</v-icon>
            </v-list-item-icon>
          </v-list-item>

          <v-list-item link :to="{ name: 'historial-jugador' }">
            <v-list-item-title>Historial por jugador</v-list-item-title>
            <v-list-item-icon>
              <v-icon>mdi-content-paste</v-icon>
            </v-list-item-icon>
          </v-list-item>

          <v-list-item link :to="{ name: 'crear-torneos' }">
            <v-list-item-title>Nuevo torneo</v-list-item-title>
            <v-list-item-icon>
              <v-icon small>mdi-trophy</v-icon>
              <v-icon small>mdi-plus</v-icon>
            </v-list-item-icon>
          </v-list-item>

            <v-list-item link :to="{ name: 'crear-fecha' }">
              <v-list-item-title>Nueva fecha</v-list-item-title>
              <v-list-item-icon>
                <v-icon>mdi-plus</v-icon>
              </v-list-item-icon>
            </v-list-item>
          </v-list-group>

          <v-list-item link :to="{ name: 'ingresos' }">
            <v-list-item-action>
              <v-icon>mdi-currency-usd</v-icon>
            </v-list-item-action>
            <v-list-item-content>
              <v-list-item-title> Ingresos </v-list-item-title>
            </v-list-item-content>
          </v-list-item>

          <v-list-item link :to="{ name: 'avisos' }">
          <v-list-item-action>
            <v-icon>mdi-email-send-outline</v-icon>
          </v-list-item-action>
          <v-list-item-content>
            <v-list-item-title> Avisos </v-list-item-title>
          </v-list-item-content>
        </v-list-item>


<v-list-group no-action prepend-icon="mdi-tournament">
            <template v-slot:activator>
              <v-list-item-title>configuracion</v-list-item-title>
            </template>

            <v-list-item link :to="{ name: 'configuracion' }">
            <v-list-item-title>Ajustes</v-list-item-title>
            <v-list-item-icon>
              <v-icon>mdi-trophy</v-icon>
            </v-list-item-icon>
          </v-list-item>
           <v-list-item link :to="{ name: 'ajustes-cuotas' }">
            <v-list-item-title>Cuotas</v-list-item-title>
            <v-list-item-icon>
              <v-icon>mdi-trophy</v-icon>
            </v-list-item-icon>
          </v-list-item>
</v-list-group>


          <v-list-item link :to="{ name: 'configuracion' }">
            <v-list-item-action>
              <v-icon>mdi-wrench</v-icon>
            </v-list-item-action>
            <v-list-item-content>
              <v-list-item-title> Configuraciones </v-list-item-title>
            </v-list-item-content>
          </v-list-item>

          <v-list-item @click="salir()">
            <v-list-item-action>
              <v-icon>mdi-close-box</v-icon>
            </v-list-item-action>
            <v-list-item-content>
              <v-list-item-title> Salir </v-list-item-title>
            </v-list-item-content>
          </v-list-item>
        </v-list>
      </v-navigation-drawer>

      <v-app-bar app color="#1A237E" dark>
        <v-app-bar-nav-icon @click.stop="drawer = !drawer"></v-app-bar-nav-icon>
        <v-toolbar-title> ASPATEM </v-toolbar-title>
      </v-app-bar>

      <v-main
        class="hover"
        @mouseenter="getSessionStatus()"
        @mouseleave="getSessionStatus()"
        @click="getSessionStatus()"
      >
        <v-container fluid>
          <router-view :key="$route.fullPath"></router-view>
          <snackbar></snackbar>
        </v-container>
      </v-main>

      <v-footer
        color="#1A237E"
        app
        class="hover"
        @mouseenter="getSessionStatus()"
        @mouseleave="getSessionStatus()"
        @click="getSessionStatus()"
      >
        <span class="white--text">&copy; {{ new Date().getFullYear() }}</span>
      </v-footer>

      <modal-login :isOpen="showModalLogin" :getSessionStatus="getSessionStatus"/>
      
    </v-app>
  </div>
</template>

<script>
export default {
  props: {
    source: String,
  },

  data: () => ({
    drawer: null,
    showModalLogin: false,
    loggingOut: false
  }),

  created() {
    //this.getSessionStatus();
    setInterval(() => {
      //this.getSessionStatus();
    }, 5000);
  },

  methods: {
    async salir() {
      this.showModalLogin = false
      this.loggingOut = true

      window.location.replace('/login');
      await axios.post("/logout");

      this.showModalLogin = false
      this.loggingOut = false
    },

    async getSessionStatus() {
      let res = await axios.get("/session/status")
      this.showModalLogin = (res.data || window.location.href.includes('/login') || this.loggingOut) ? false : true
    },
  },
};
</script>
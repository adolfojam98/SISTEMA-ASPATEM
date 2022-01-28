<template>
  <v-app id="inspire">
    <v-navigation-drawer
      v-model="drawer"
      app
      color=#CFD8DC
    >
      <v-list>
        <v-list-item link :to="{name: 'home'}">
          <v-list-item-action>
            <v-icon>mdi-home</v-icon>
          </v-list-item-action>
          <v-list-item-content>
            <v-list-item-title>Home</v-list-item-title>
          </v-list-item-content>
          
        </v-list-item>
       

<v-list-group
          no-action
          prepend-icon="mdi-account"
        >
        <template v-slot:activator>
          <v-list-item-title>Socios</v-list-item-title>
        </template>

          <v-list-item
            link :to="{name: 'agregar'}" 
          > 
          
            <v-list-item-title >Agregar socio</v-list-item-title>
            <v-list-item-icon>
            <v-icon>mdi-account-plus</v-icon> 
            </v-list-item-icon>
          </v-list-item>

          <v-list-item
            link :to="{name: 'lista'}" 
          >
            <v-list-item-title >Lista socios</v-list-item-title>
            <v-list-item-icon>
              <v-icon>mdi-account-group</v-icon>
            </v-list-item-icon>
          </v-list-item>
        

          <v-list-item
            link :to="{name: 'pagos'}" 
          >
            <v-list-item-title >Pagos</v-list-item-title>
            <v-list-item-icon>
              <v-icon>mdi-currency-usd</v-icon>
            </v-list-item-icon>
          </v-list-item>
</v-list-group>


        <v-list-group
          no-action
          prepend-icon="mdi-tournament">
          <template v-slot:activator>
            <v-list-item-title>Torneos</v-list-item-title>
          </template>

              <v-list-item
            link :to="{name: 'gestion-torneos'}"
          >
            <v-list-item-title>Gestion torneos</v-list-item-title>
            <v-list-item-icon>
              <v-icon>mdi-chart-histogram</v-icon>
            </v-list-item-icon>
          </v-list-item>

              <v-list-item
            link :to="{name: 'crear-torneos'}"
          >
            <v-list-item-title>Nuevo torneo</v-list-item-title>
            <v-list-item-icon>
              <v-icon>mdi-trophy</v-icon>
            </v-list-item-icon>
          </v-list-item>

          <v-list-item
            link :to="{name: 'crear-fecha'}"
          >
            <v-list-item-title>Nueva fecha</v-list-item-title>
            <v-list-item-icon>
              <v-icon>mdi-plus</v-icon>
            </v-list-item-icon>
          </v-list-item>
            


        </v-list-group>

        <v-list-item link :to="{name:'ingresos'}">
          <v-list-item-action>
            <v-icon>mdi-currency-usd</v-icon>
          </v-list-item-action>
          <v-list-item-content>
            <v-list-item-title>
              Ingresos
              </v-list-item-title>
          </v-list-item-content>
        </v-list-item>


        <v-list-item link :to="{name:'configuracion'}">
          <v-list-item-action>
            <v-icon>mdi-wrench</v-icon>
          </v-list-item-action>
          <v-list-item-content>
            <v-list-item-title>
              Configuracion
              </v-list-item-title>
          </v-list-item-content>
        </v-list-item>

<v-list-item @click="salir()">
          <v-list-item-action>
            <v-icon>mdi-close-box</v-icon>
          </v-list-item-action>
          <v-list-item-content>
            <v-list-item-title>
              Salir
              </v-list-item-title>
          </v-list-item-content>
        </v-list-item>
  
        
      </v-list>
    </v-navigation-drawer>

    <v-app-bar
      app
      color=#1A237E
      dark
    >
      <v-app-bar-nav-icon @click.stop="drawer = !drawer"></v-app-bar-nav-icon>
      <v-toolbar-title> ASPATEM </v-toolbar-title>
    </v-app-bar>

    <v-main>
    <v-container fluid>
      <router-view :key="$route.fullPath"></router-view>
     <snackbar></snackbar>
   </v-container>
    </v-main>
    <v-footer
      color=#1A237E
      app
    >
      <span class="white--text">&copy; {{ new Date().getFullYear() }}</span>
    </v-footer>
   
  </v-app>
</template>

<script>

  export default {
    props: {
      source: String,
    },
        
    data: () => ({
      drawer: null,
    }),
    methods: {
     async salir(){
       await axios.post('/logout');
      window.location.href = '/login'
       
      }
    },
    
  }
</script>
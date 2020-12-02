<template>
  <v-app id="inspire">
    <v-navigation-drawer
      v-model="drawer"
      app
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

        <!-- <v-list-group>
          <template v-slot:activator>
            <v-list-item-action>
              <v-icon>mdi-account</v-icon>
            </v-list-item-action>
            <v-list-item-title >Socios</v-list-item-title>
          </template>
      
          <v-list-item link :to="{name: 'agregar'}">
            <v-list-item-content>
              <v-list-item-title><center>Agregar nuevo socio</center></v-list-item-title>
            </v-list-item-content>
          </v-list-item>

          <v-list-item link :to="{name: 'lista'}">
            <v-list-item-content>
              <v-list-item-title><center>Ver lista de socios</center></v-list-item-title>
            </v-list-item-content>
          </v-list-item>


        </v-list-group> -->

        



        




          

        <v-list-item link :to="{name:'torneos'}">
          <v-list-item-action>
            <v-icon>mdi-battery-charging-30</v-icon>
          </v-list-item-action>
          <v-list-item-content>
            <v-list-item-title>
              torneos
              </v-list-item-title>
          </v-list-item-content>
        </v-list-item>

        <v-list-item link :to="{name:'ranking'}">
          <v-list-item-action>
            <v-icon>mdi-account</v-icon>
          </v-list-item-action>
          <v-list-item-content>
            <v-list-item-title>
              ranking
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


  
        
      </v-list>
    </v-navigation-drawer>

    <v-app-bar
      app
      color="indigo"
      dark
    >
      <v-app-bar-nav-icon @click.stop="drawer = !drawer"></v-app-bar-nav-icon>
      <v-toolbar-title>ASPATEM</v-toolbar-title>
    </v-app-bar>

    <v-main>
    <v-container fluid>
      <router-view :key="$route.fullPath"></router-view>
   
   </v-container>
    </v-main>
    <v-footer
      color="indigo"
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
  }
</script>
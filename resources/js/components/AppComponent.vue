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
       

        <v-list-item link :to="{name:'usuarios'}">
          <v-list-item-action>
            <v-icon>mdi-email</v-icon>
          </v-list-item-action>
          <v-list-item-content>
            <v-list-item-title>
              Usuarios
              </v-list-item-title>
          </v-list-item-content>
        </v-list-item>

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
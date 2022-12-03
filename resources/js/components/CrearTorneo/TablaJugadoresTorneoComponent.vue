<template>
  <div>
    <template>
      <v-data-table
        dense
        :headers="headers"
        :items="listaJugadores"
        item-key="dni"
        class="elevation-1 mr-2"
        :items-per-page="5"
      >
        <template v-slot:[`item.actions`]="{ item }">
          <v-tooltip bottom>
            <template v-slot:activator="{ on, attrs }">
              <v-icon
                class="ml-4"
                v-bind="attrs"
                v-on="on"
                @click="eliminarJugador(item)"
                color="error"
                >mdi-delete</v-icon
              >
            </template>
            <span>Eliminar</span>
          </v-tooltip>
        </template>
      </v-data-table>
    </template>
    <div v-if="listaJugadores.length > 0">
      <p class="font-weight-medium">
     Jugadores nuevos: {{cantidadJugadoresNuevos}}
    </p>
     <p class="font-weight-medium">
     Jugadores existentes: {{cantidadJugadoresExistentes}}
    </p>
     <p class="font-weight-medium">
     Socios: {{cantidadJugadoresSocios}}
    </p>
    </div>
   
  </div>
</template>


<script>
import { mapMutations, mapState } from "vuex";
export default {
  data() {
    return {
      headers: [
        { text: "Apellido", value: "apellido" },
        { text: "Nombre", value: "nombre" },
        { text: "DNI", value: "dni" },
        { text: "Puntos", value: "puntos" },
        {
          text: "Eliminar",
          value: "actions",
          sortable: false,
          filterable: false,
        },
      ],
    };
  },
  computed: {
    ...mapState("CrearTorneo", ["listaJugadores"]),
    cantidadJugadoresNuevos(){
      return this.listaJugadores.filter((jugador)=> jugador.id === undefined).length;
    },
    cantidadJugadoresExistentes(){
       return this.listaJugadores.filter((jugador)=> jugador.id !== undefined).length;  
    },
    cantidadJugadoresSocios(){
       return this.listaJugadores.filter((jugador)=>  jugador.socio?.socio !== undefined ? jugador.socio.socio : jugador.socio === '1').length;  
    }
  },
  methods: {
    ...mapMutations("CrearTorneo", ["eliminarJugador"]),
  },
};
</script>
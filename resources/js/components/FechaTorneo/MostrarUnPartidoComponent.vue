<template>
    <div>
        <v-row>
        <v-card class="mt-3 mr-3 mb-3" width="255px" height="135px" elevation="6">
              <center><h4>{{partido.id}}</h4></center>
              <v-divider></v-divider>

                <div v-if="esNodoHoja(partido.id)">
                    <v-row><v-col cols="12" md="8">
                    <v-select
                    single-line
                    class="ml-2 mb-0"
                    @input="[calcularSiguienteJugador()]"
                    v-model="partido.jugador1"
                    :items="jugadores"
                    :item-text="nombreCompleto"
                    return-object
                    dense
                    ></v-select>
                    </v-col>

                    <v-col  cols="12" md="4">
                    <v-select
                    single-line
                    class="mr-2 mb-0"
                    @input="[calcularSiguienteJugador()]"
                    dense
                    v-model="partido.set1"
                    :items=[0,1,2,3,4,5,6,7]
                    type="number"
                    ></v-select>
                    </v-col></v-row>

                    <v-row><v-col cols="12" md="8">
                    <v-select
                    single-line
                    class="ml-2 mt-0"
                    @input="[calcularSiguienteJugador()]"
                    v-model="partido.jugador2"
                    :items="jugadores"
                    :item-text="nombreCompleto"
                    return-object
                    dense
                    ></v-select>
                    </v-col>

                    <v-col  cols="12" md="4">
                    <v-select
                    single-line
                    class="mr-2 mt-0"
                    @input="[calcularSiguienteJugador()]"
                    dense
                    v-model="partido.set2"
                    :items=[0,1,2,3,4,5,6,7]
                    type="number"
                    ></v-select>
                    </v-col></v-row>
                </div>

                <div v-if="esMedioNodoHoja(partido.id)">
                <v-row><v-col cols="12" md="8">
                    <v-select
                    single-line
                    class="ml-2 mb-0"
                    @input="[calcularSiguienteJugador()]"
                    v-model="partido.jugador1"
                    :items="jugadores"
                    :item-text="nombreCompleto"
                    return-object
                    dense
                    ></v-select>
                    </v-col>

                    <v-col  cols="12" md="4">
                    <v-select
                    single-line
                    class="mr-2 mb-0"
                    @input="[calcularSiguienteJugador()]"
                    dense
                    v-model="partido.set1"
                    :items=[0,1,2,3,4,5,6,7]
                    type="number"
                    ></v-select>
                    </v-col></v-row>

                    <v-row><v-col cols="12" md="8">
                    <v-select
                    single-line
                    class="ml-2 mt-0"
                    @input="[calcularSiguienteJugador()]"
                    v-model="partido.jugador2"
                    :items="jugadores"
                    :item-text="nombreCompleto"
                    return-object
                    dense
                    disabled
                    ></v-select>
                    </v-col>

                    <v-col  cols="12" md="4">
                    <v-select
                    single-line
                    class="mr-2 mt-0"
                    @input="[calcularSiguienteJugador()]"
                    dense
                    v-model="partido.set2"
                    :items=[0,1,2,3,4,5,6,7]
                    type="number"
                    ></v-select>
                    </v-col></v-row>
                <div v-if="partido.jugador2 == null"> - </div>
                </div>

                <div v-if="!esNodoHoja(partido.id) && !esMedioNodoHoja(partido.id)">
                    <v-row><v-col cols="12" md="8">
                    <v-select
                    single-line
                    class="ml-2 mb-0"
                    @input="[calcularSiguienteJugador()]"
                    v-model="partido.jugador1"
                    :items="jugadores"
                    :item-text="nombreCompleto"
                    return-object
                    dense
                    disabled
                    ></v-select>
                    </v-col>

                    <v-col  cols="12" md="4">
                    <v-select
                    single-line
                    class="mr-2 mb-0"
                    @input="[calcularSiguienteJugador()]"
                    dense
                    v-model="partido.set1"
                    :items=[0,1,2,3,4,5,6,7]
                    type="number"
                    ></v-select>
                    </v-col></v-row>

                    <v-row><v-col cols="12" md="8">
                    <v-select
                    single-line
                    class="ml-2 mt-0"
                    @input="[calcularSiguienteJugador()]"
                    v-model="partido.jugador2"
                    :items="jugadores"
                    :item-text="nombreCompleto"
                    return-object
                    dense
                    disabled
                    ></v-select>
                    </v-col>

                    <v-col  cols="12" md="4">
                    <v-select
                    single-line
                    class="mr-2 mt-0"
                    @input="[calcularSiguienteJugador()]"
                    dense
                    v-model="partido.set2"
                    :items=[0,1,2,3,4,5,6,7]
                    type="number"
                    ></v-select>
                    </v-col></v-row>
                </div>
          </v-card>
          </v-row>
    </div>
</template>


<script>
export default {
  props: ["partido", "cantidadPartidos", "nodosHojasID", "nodosMediasHojasID", "posiblesSigsPartidos", "jugadores", "partidos"],

    methods: {
        nombreCompleto: item => item.apellido + " " + item.nombre,

        esNodoHoja(id){//TODO hay dos partidos que apuntan a este
        console.log(this.nodosHojasID);
            return this.nodosHojasID.includes(id);
        },

        esMedioNodoHoja(id){//TODO solo un partidos que apuntan a este
            return this.nodosMediasHojasID.includes(id);
        },

        calcularSiguienteJugador(){
            let me = this;

            this.partidos.forEach(partido => {
                if(partido.jugador1 != null && partido.jugador2 != null && partido.set1 != null && partido.set2 != null && partido.sigPartidoID != null){
                    //TODO calculamos el ganador primero...
                    if(partido.set1 > partido.set2){ var ganador = partido.jugador1;}
                    else if(partido.set2 > partido.set1){ var ganador = partido.jugador2;}

                    //TODO calculamos donde va el ganador y lo asignamos
                    var sigPartido = me.partidos.find(unPartido => unPartido.id == partido.sigPartidoID);
                    if(me.nodosMediasHojasID.includes(sigPartido.id)){sigPartido.jugador2 = ganador;}
                    else if(sigPartido.jugador1 == partido.jugador1 || sigPartido.jugador1 == partido.jugador2){sigPartido.jugador1 = ganador;}
                    else if(sigPartido.jugador2 == partido.jugador1 || sigPartido.jugador2 == partido.jugador2){sigPartido.jugador2 = ganador;}
                    else if(sigPartido.jugador1 == null){sigPartido.jugador1 = ganador;}
                    else if(sigPartido.jugador2 == null){sigPartido.jugador2 = ganador;}
                }
            });
        }
    },
};
</script>
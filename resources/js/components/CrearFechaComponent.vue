<template>
  <div>
      <v-card
      color="#546E7A"
      >
        <v-container>
            <v-select
                v-model="torneoSeleccionado"
                dark
                background-color="#90A4AE"
                :items="torneos"
                item-text="nombre"
                return-object
                filled
                label="Seleccione el torneo"
                class="subheading font-weight-bold"
            ></v-select>

            <v-card
                color="#90A4AE"
                elevation="4"
                class="rounded-sm"
                dark
                >
                  <v-text-field
                  dense
                  dark
                    filled
                    color="#212121"
                    v-model="nombreFecha"
                    class="subheading font-weight-bold"
                    label="Nombre de la fecha"
                    :rules="nombreFechaRules"
                    required
                  ></v-text-field>

                <v-row>
                <v-col cols="12" md="6">

                <v-text-field
                    :rules="montoRules"
                    class="mr-2 ml-2"
                    solo
                    label="Monto socios que juegan una categoria"
                    prefix="$"
                    v-model="montoSociosUnaCategoria"
                ></v-text-field>

                <v-text-field
                    :rules="montoRules"
                    class="mr-2 ml-2"
                    solo
                    label="Monto socios que juegan dos categorias"
                    prefix="$"
                    v-model="montoSocioDosCategorias"
                ></v-text-field>

                </v-col>

                <v-col cols="12" md="6">

                <v-text-field
                    :rules="montoRules"
                    class="mr-2 ml-2"
                    solo
                    label="Monto no socios que juegan una categoria"
                    prefix="$"
                    v-model="montoNoSociosUnaCategoria"
                ></v-text-field>

                <v-text-field
                    :rules="montoRules"
                    class="mr-2 ml-2"
                    solo
                    label="Monto no socios que juegan dos categorias"
                    prefix="$"
                    v-model="montoNoSociosDosCategorias"
                ></v-text-field>

                </v-col>
                </v-row>

            </v-card>

            <v-container></v-container>

            <v-card
                color="#90A4AE"
                elevation="4"
                class="rounded-sm"
                dark
                >


                <v-row>
                    <v-col>

                        <template>
                            <v-data-table
                            dense
                            :headers="headers"
                            :items="listaJugadores"
                            item-key="dni"
                            class="elevation-1 mr-2 ml-2"
                            dark
                            :items-per-page="5"
                            >

                            
                            <template v-slot:[`item.actions`]="{ item }">
                                
                                <v-tooltip bottom>
                                <template v-slot:activator="{ on, attrs }">
                                    <v-icon
                                    class="ml-2 mr-4"
                                    v-bind="attrs"
                                    v-on="on"
                                    @click="[agregarEnSuCategoria(item)]"
                                    color="green"
                                    >=</v-icon
                                    >
                                </template>
                                <span>Anotar en su categoria</span>
                                </v-tooltip>

                                <v-tooltip bottom>
                                <template v-slot:activator="{ on, attrs }">
                                    <v-icon
                                    class="mt-1"
                                    v-bind="attrs"
                                    v-on="on"
                                    @click="[agregarEnLaCategoriaSuperior(item)]"
                                    color="primary"
                                    >^</v-icon
                                    >
                                </template>
                                <span>Anotar en la categoria superior</span>
                                </v-tooltip>
                                
                            </template>
                            


                            <template v-slot:[`item.action`]="{ item }">
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


                    </v-col>
                </v-row>

            </v-card>

        </v-container>
      </v-card>


    <v-snackbar v-model="snackbar" timeout="3000">
      <div v-text="message"></div>

      <template v-slot:action="{ attrs }">
        <v-btn color="blue" text v-bind="attrs" @click="snackbar = false">
          Cerrar
        </v-btn>
      </template>
    </v-snackbar>

  </div>
</template>


<script>    //En la tabla poner para que cada jugador se pueda anotar en su categoria(1), en la suya y la superior (2) o en ninguna (3)
            //En algun lugar que ingrese la cantidad de participantes por grupos y que categorias son con eliminatoria y cuales no (en la fase de grupos)
export default {
data: () => ({
torneoSeleccionado : "",
torneos : [],
nombreFecha : "",
montoSociosUnaCategoria : null,
montoSocioDosCategorias : null,
montoNoSociosUnaCategoria : null,
montoNoSociosDosCategorias : null,
listaJugadores : [],
listaCategorias : [],
message: "",
snackbar : false,



nombreFechaRules: [
      (v) => !!v || "Nombre requerido",
      (v) =>
        /^(([A-Za-z0-9]*([/])*[ \t\n\r\f]?[A-Za-z0-9])*)+$/.test(v) ||
        "Nombre invalido",
      (v) => v.length <= 30 || "Demasiado largo",
    ],

montoRules: [
      (v) => !!v || "Monto requerido",
      (v) => /^(([0-9]*)([.][0-9]([0-9])*)*)+$/.test(v) || "Monto no valido ",
    ],



    headers: [
      { text: "Apellido", value: "apellido" },
      { text: "Nombre", value: "nombre" },
      { text: "DNI", value: "dni" },
      { text: "Puntos", value: "pivot.puntos" },
      {
        text: "Categorias",
        value: "actions",
        sortable: false,
        filterable: false,
      },
      {
        text: "Eliminar",
        value: "action",
        sortable: false,
        filterable: false,
      },
    ],




}),


methods: {
    
    traerJugadoresTorneo(){
        let me = this;
        axios.get(`/torneos/${me.torneoSeleccionado.id}/jugadores`)
        .then(
            res => {this.listaJugadores = res.data;}
        )
    },

    traerCategorias(){
        let me = this;
        axios.get(`/torneos/${me.torneoSeleccionado.id}/categorias`)
        .then(
            res => {this.listaCategorias = res.data;

            this.listaCategorias.forEach(categoria => {
                Object.defineProperty(categoria,'jugadoresAnotados', {value: [], writable:true, configurable:true, enumerable:true});
                });
            }
        )
    },

    eliminarJugador(indice) {
      this.listaJugadores.splice(indice, 1);
    },

    agregarEnSuCategoria(item){
        this.listaCategorias.forEach(categoria => {
            if(item.pivot.puntos >= categoria.puntos_minimos && item.pivot.puntos <= categoria.puntos_maximos){
                var indice = categoria.jugadoresAnotados.indexOf(item);

                if(indice === -1){
                    categoria.jugadoresAnotados.push(item);
                    this.message="Jugador anotado en su categoria";
                    this.snackbar = true;
                    }
                
                else{
                    categoria.jugadoresAnotados.splice(indice,1);
                    this.message="El jugador ya no esta anotado en su categoria";
                    this.snackbar = true;
                }
            }
        });
    },

    agregarEnLaCategoriaSuperior(item){
        var entrarEnElSiguiente=false;
        this.listaCategorias.forEach(categoria => {

            if(entrarEnElSiguiente){
                    var indice = categoria.jugadoresAnotados.indexOf(item);
                    
                    if(indice === -1){
                        categoria.jugadoresAnotados.push(item);
                        this.message="Jugador anotado en la categoria inmediata superior a la suya";
                        this.snackbar = true;
                        }
                    
                    else{
                        categoria.jugadoresAnotados.splice(indice,1);
                        this.message="El jugador ya no esta anotado en la categoria superior a la suya";
                        this.snackbar = true;
                    }
                    entrarEnElSiguiente=false;
                }

            if(item.pivot.puntos >= categoria.puntos_minimos && item.pivot.puntos <= categoria.puntos_maximos){
                entrarEnElSiguiente=true;
                }

        });

        if(entrarEnElSiguiente){
            this.message="No hay una categoria superior"
            this.snackbar=true;
            }
    },



},

watch : {
    torneoSeleccionado : function(){
        this.traerJugadoresTorneo();
        this.traerCategorias();
        },
    },

created() {
        axios.get("/torneos").then(res => {
            this.torneos = res.data;
        });
    }


}
</script>

<style>

</style>
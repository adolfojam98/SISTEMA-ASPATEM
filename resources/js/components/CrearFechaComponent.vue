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
                    v-model="montoSociosDosCategorias"
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
                                    >=</v-icon>
                                </template>
                                <span>{{ calcularCategoria(item) }}</span>
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
                                <span>{{ calcularCategoriaSuperior(item) }}</span>
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


                <v-col cols="12" md="6">
                <v-btn
                block
                  v-if="!nuevoJugador"
                  dark
                  color="#212121"
                  @click="[(nuevoJugador = !nuevoJugador)]"
                >
                  <v-icon></v-icon>
                  Agregar jugador a la lista
                </v-btn>

                <v-form v-if="nuevoJugador" v-model="valid" lazy-validation>
                  <v-text-field
                  dark
                    color="#212121"
                    v-model="apellidoJugador"
                    :rules="aynRules"
                    class="subheading font-weight-bold"
                    label="Apellido del jugador"
                    required
                  ></v-text-field>
                  <v-text-field
                  dark
                    color="#212121"
                    v-model="nombreJugador"
                    :rules="aynRules"
                    class="subheading font-weight-bold"
                    label="Nombre del jugador"
                    required
                  ></v-text-field>
                  <v-text-field
                  dark
                    color="#212121"
                    v-model="dniJugador"
                    :rules="dniRules"
                    class="subheading font-weight-bold"
                    label="DNI del jugador"
                    required
                  ></v-text-field>
                  <v-text-field
                  dark
                    color="#212121"
                    v-model="puntosJugador"
                    class="subheading font-weight-bold"
                    label="Puntos del jugador"
                    :rules="puntosRules"
                    required
                  ></v-text-field>

                  <v-btn
                    dark
                    block
                    class="rounded-pill"
                    color="primary"
                    v-model="nuevoJugador"
                    :disabled="!valid"
                    @click="
                      [((nuevoJugador = !nuevoJugador), agregarJugador())]
                    "
                    >Agregar</v-btn
                  >

                  <v-btn
                  block
                    color="error"
                    class="rounded-pill mt-3 mb-3"
                    dark
                    v-model="nuevoJugador"
                    @click="
                      [
                        ((apellidoJugador = ''),
                        (nombreJugador = ''),
                        (dniJugador = null),
                        (puntosJugador = null),
                        (nuevoJugador = false)),
                      ]
                    "
                    >Cancelar</v-btn
                  >
                </v-form>
                </v-col>

            </v-card>

        </v-container>
      </v-card>

      <v-container></v-container>

            <v-card
                v-if="listaCategorias.length > 0"
                
                dark
                >
                <v-toolbar>
                    <template>
                        <v-tabs
                        v-model="tab"
                        align-with-title
                        >
                        <v-tabs-slider color="yellow"></v-tabs-slider>

                            <v-tab
                                v-for="item in listaCategorias"
                                :key="item.id"
                            >
                                {{ item.nombre }}
                            </v-tab>

                        </v-tabs>
                    </template>
                </v-toolbar>

                <v-tabs-items
                v-model="tab"
                >
                    <v-tab-item
                        v-for="item in listaCategorias"
                        :key="item.id"
                    >
                    

                        <v-card
                            flat
                            color="#546E7A"
                            class="rounded-0"
                            >
                                <v-container>
                                    <v-form v-model="valid" lazy-validation>
                                    
                                
                                    <v-col cols="12" md="4">
                                        <v-text-field
                                        label="Cantidad de Grupos"
                                        v-model="item.cantidadGrupos"
                                        :rules="cantidadGruposRules"
                                        required
                                        dark
                                        class="mb-0"
                                        ></v-text-field>
                                    </v-col>
                                

                            
                                    <v-switch
                                    v-model="item.gruposConEliminatoria"
                                    label="Fase de grupos con eliminatoria"
                                    dark
                                    class="ml-2 mt-0"
                                    ></v-switch>

                                    </v-form>
                                
                                        <v-btn
                                        class="ml-2 mr-4"
                                        dark
                                        :disabled="!valid"
                                        @click="[generarGrupos(item)]"
                                        color="blue"
                                        >Generar grupos</v-btn>
                                        
                                    </v-container>
                                    
                            

                                <v-card
                                v-for="jugadores in item.jugadoresAnotados"
                                :key="jugadores.id"
                                flat
                                color="#90A4AE"
                                class="rounded-0"
                                >
                                    <v-card-text>{{jugadores}}</v-card-text>
                                </v-card>
                            </v-card>

                    </v-tab-item>
                </v-tabs-items>

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
valid: false,
torneos : [],
nombreFecha : "",
montoSociosUnaCategoria : null,
montoSociosDosCategorias : null,
montoNoSociosUnaCategoria : null,
montoNoSociosDosCategorias : null,
apellidoJugador: "",
nombreJugador: "",
dniJugador: null,
puntosJugador: null,
nuevoJugador: false,
listaJugadores : [],
listaCategorias : [],
message: "",
snackbar : false,
tab: null,



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

cantidadGruposRules: [
      (v) => !!v || "Cantidad de grupos requerido",
      (v) =>
        /^([0-9]*)?[0-9]+$/.test(v) || "Deben ser solo numeros enteros",
    ],

aynRules: [
      (v) => !!v || "Campo obligatorio",
      (v) =>
        /^([A-Za-z][A-Za-z]*([ \t\n\r\f]?[A-Za-z])*)+$/.test(v) || "Nombre invalido",
      (v) => v.length <= 30 || "Demasiado largo",
    ],

dniRules: [
      (v) => !!v || "Campo obligatorio",
      (v) => /^([0-9]*)+$/.test(v) || "Solo numeros",
      (v) =>
        (!!v && v.length == 8) || "El DNI debe estar compuesto por 8 numeros",
    ],
puntosRules: [
      (v) => !!v || "Puntos requeridos",
      (v) =>
        /^([0-9]*)?[0-9]+$/.test(v) || "Los puntos deben ser numeros enteros",
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
      { text: "Monto", value: "montoPagado"},
      {
        text: "Eliminar",
        value: "action",
        sortable: false,
        filterable: false,
      },
    ],




}),


methods: {
    calcularCategoria : function(item){
        var mensaje="";
        this.listaCategorias.forEach(categoria => {
            
            if(item.pivot.puntos >= categoria.puntos_minimos && item.pivot.puntos <= categoria.puntos_maximos){

                if(!categoria.jugadoresAnotados.includes(item)){
                    mensaje="Agregar a la categoria: "+categoria.nombre
                    }
                else{
                    mensaje="Quitar de la categoria: "+categoria.nombre
                }
               
            }

        });
         return mensaje;
    },

    agregarJugador() {
      var jugadorExiste = false;

        this.listaJugadores.forEach(jugador => {
            if(jugador.dni == parseInt(this.dniJugador)){jugadorExiste = true;}
        });

    if(!jugadorExiste){

      var jugador = {
        apellido: this.apellidoJugador,
        nombre: this.nombreJugador,
        dni: parseInt(this.dniJugador),
        pivot: {puntos: parseInt(this.puntosJugador)},
      };

      this.listaJugadores.push(jugador);
    
    }else{
        this.message = "Ya hay un jugador con el dni que intenta ingresar";
        this.snackbar = true;
    }

      this.apellidoJugador = "";
      this.nombreJugador = "";
      this.dniJugador = null;
      this.puntosJugador = null;

    },

    calcularCategoriaSuperior(item){
        var entrarEnElSiguiente=false;
        var mensaje="No hay una categoria superior";
        this.listaCategorias.forEach(categoria => {

            if(entrarEnElSiguiente){
                    var indice = categoria.jugadoresAnotados.indexOf(item);
                    
                    if(indice === -1){
                        mensaje="Agregar en la categoria superior: "+categoria.nombre;
                        }
                    
                    else{
                        mensaje="Quitar de la categoria superior: "+categoria.nombre;
                    }
                    entrarEnElSiguiente=false;
                }

            if(item.pivot.puntos >= categoria.puntos_minimos && item.pivot.puntos <= categoria.puntos_maximos){
                entrarEnElSiguiente=true;
                }
            
        });

        return mensaje;
    },


    
    traerJugadoresTorneo(){
        let me = this;
        axios.get(`/torneos/${me.torneoSeleccionado.id}/jugadores`)
        .then(
            res => {
                console.log(res.data)
                this.listaJugadores = res.data;}
        )
    },

    traerCategorias(){
        let me = this;
        axios.get(`/torneos/${me.torneoSeleccionado.id}/categorias`)
        .then(
            res => {this.listaCategorias = res.data;

            this.listaCategorias.forEach(categoria => {
                Object.defineProperty(categoria,'jugadoresAnotados', {value: [], writable:true, configurable:true, enumerable:true});
                Object.defineProperty(categoria,'gruposConEliminatoria', {value: false, writable:true, configurable:true, enumerable:true});
                Object.defineProperty(categoria,'cantidadGrupos', {writable:true, configurable:true, enumerable:true});
                });
            }
        )
    },

    eliminarJugador(item) {
        var indice = this.listaJugadores.indexOf(item);
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
        this.calcularMonto();
    },

    agregarEnLaCategoriaSuperior(item){
        var entrarEnElSiguiente=false;
        this.listaCategorias.forEach(categoria => {

            if(entrarEnElSiguiente){
                    var indice = categoria.jugadoresAnotados.indexOf(item);
                    
                    if(indice === -1){
                        categoria.jugadoresAnotados.push(item);
                        this.calcularMonto();
                        this.message="Jugador anotado en la categoria inmediata superior a la suya";
                        this.snackbar = true;
                        }
                    
                    else{
                        categoria.jugadoresAnotados.splice(indice,1);
                        this.calcularMonto();
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

    calcularMonto(){
        this.listaJugadores.forEach(jugador => {
        var anotadoEnCategorias = 0;
        this.listaCategorias.forEach(categoria => {
                if(categoria.jugadoresAnotados.includes(jugador)){
                    anotadoEnCategorias++;
                }
        })

        if(parseInt(this.montoSociosUnaCategoria) >= 0 && parseInt(this.montoSociosDosCategorias) >= 0 && parseInt(this.montoNoSociosUnaCategoria) >= 0 && parseInt(this.montoNoSociosDosCategorias) >= 0){
            if(anotadoEnCategorias == 0){ jugador.montoPagado = 0; }
            else if(anotadoEnCategorias == 1 && jugador.socio == 1){ jugador.montoPagado =  this.montoSociosUnaCategoria;}
                else if(anotadoEnCategorias == 2 && jugador.socio == 1){ jugador.montoPagado =  this.montoSociosDosCategorias;}
                    else if(anotadoEnCategorias == 1 && jugador.socio == 0){ jugador.montoPagado =  this.montoNoSociosUnaCategoria;}
                        else if(anotadoEnCategorias == 2 && jugador.socio == 0){ jugador.montoPagado =  this.montoNoSociosDosCategorias;}
        }
        else{
            this.message="Debe definir los montos para poder calcular la columna 'Montos'";
            this.snackbar=true;
        }
        })
    },

    agregarPropiedadMonto(){
        this.listaJugadores.forEach(jugador => {
            Object.defineProperty(jugador,'montoPagado', {value: 0, writable:true, configurable:true, enumerable:true});
        });
    },

    generarGrupos(item){
        if((item.jugadoresAnotados.length/item.cantidadGrupos)>=3){

        }
        else{
            this.snackbar=true;
            this.message="La cantidad de jugadores es insuficiente para la cantidad de grupos";
        }
    }



},

watch : {
    torneoSeleccionado : function(){
        this.traerJugadoresTorneo();
        this.traerCategorias();
        this.agregarPropiedadMonto();
        },

    listaCategorias : function(){
        this.calcularMonto();
        },

    montoSociosUnaCategoria : function(){
        this.calcularMonto();
        },

    montoSociosDosCategorias : function(){
        this.calcularMonto();
        },

    montoNoSociosUnaCategoria : function(){
        this.calcularMonto();
        },

    montoNoSociosDosCategorias : function(){
        this.calcularMonto();
        },

    },

created() {
        axios.get("/torneos").then(res => {
            this.torneos = res.data;
        });
    },
    


}
</script>

<style>

</style>
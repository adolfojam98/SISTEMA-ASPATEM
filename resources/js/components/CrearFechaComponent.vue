<template>
  <div>
      <v-card
      color="indigo lighten-3"
      >
        <v-container>
            <datos-fecha></datos-fecha>

           

            <v-card
                elevation="4"
                class="rounded-sm"
                >
                <jugadores-fecha></jugadores-fecha>


                
                

               
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
                                <v-container
                                v-if='(gruposGenerados(item))'
                                >
                                <v-row>
                                    <v-form v-model="valid" lazy-validation>
                                    
                                
                                    <v-col>
                                        <v-text-field
                                        label="Cantidad de Grupos"
                                        v-model="item.cantidadGrupos"
                                        :rules="cantidadGruposRules"
                                        required
                                        dark
                                        class="mb-0 ml-2"
                                        ></v-text-field>
                                    
                                

                            
                                    <v-switch
                                    v-model="item.gruposConEliminatoria"
                                    label="Fase de grupos con eliminatoria"
                                    dark
                                    class="ml-2 mt-0"
                                    ></v-switch>

                                    
                                
                                        <v-btn
                                        class="ml-2 mr-4"
                                        dark
                                        :disabled="!valid"
                                        @click="[generarGrupos(item)]"
                                        color="blue"
                                        >Generar grupos</v-btn>

                                        </v-col>

                                        </v-form>


                                    <v-col>
                                        <v-simple-table dark>
                                            <template v-slot:default>
                                            <thead>
                                                <tr>
                                                <th class="text-left">
                                                    Apellido
                                                </th>
                                                <th class="text-left">
                                                    Nombre
                                                </th>
                                                <th class="text-left">
                                                    DNI
                                                </th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr
                                                v-for="jugador in item.jugadoresAnotados" :key="jugador.id"
                                                >
                                                <td>{{ jugador.apellido }}</td>
                                                <td>{{ jugador.nombre }}</td>
                                                <td>{{ jugador.dni }}</td>
                                                </tr>
                                            </tbody>
                                            </template>
                                        </v-simple-table>
                                    </v-col>
                                        </v-row>
                                    </v-container>
                                    

                                <div v-else>

                                    <v-btn
                                    class="ml-6 mt-6"
                                    dark
                                    @click="deshacerGrupos(item)"
                                    color="blue"
                                    >Deshacer grupos</v-btn>

                                <v-card
                                v-for="grupo in item.listaGrupos"
                                :key="grupo.nombre"
                                flat
                                color="#546E7A"
                                class="rounded-0"
                                >
                                    
                                    
                                <v-col>
                                    <v-card
                                    color="#90A4AE"
                                    class="mt-6 mb-6 ml-12 mr-12"
                                    >

                                        <v-container 
                                        v-for="partido in grupo.partidos"
                                        :key="partido.id"
                                        >
                                            
                                            <v-flex d-flex class="mb-0">
                                                
                                                <v-tooltip bottom>
                                                    <template v-slot:activator="{ on, attrs }">
                                                        <h3 class="mt-2">{{partido.jugador1.apellido}}
                                                            <v-icon
                                                            class="mb-1"
                                                            v-bind="attrs"
                                                            v-on="on"
                                                            >mdi-account-question</v-icon>
                                                        </h3>
                                                    </template>
                                                    <span>{{partido.jugador1.nombre}} - {{partido.jugador1.dni}}</span>
                                                </v-tooltip>
                                                
                                                <v-select
                                                class="ml-4 mr-2"
                                                :items="cantidadSets"
                                                v-model="partido.setsJugador1"
                                                solo
                                                dense
                                                ></v-select>
                                            
                                                <v-select
                                                class="ml-2 mr-4"
                                                :items="cantidadSets"
                                                v-model="partido.setsJugador2"
                                                dense
                                                solo
                                                ></v-select>

                                                <v-tooltip bottom>
                                                    <template v-slot:activator="{ on, attrs }">
                                                        <h3 class="mt-2">{{partido.jugador2.apellido}}
                                                            <v-icon
                                                            class="mb-1"
                                                            v-bind="attrs"
                                                            v-on="on"
                                                            >mdi-account-question</v-icon>
                                                        </h3>
                                                    </template>
                                                    <span>{{partido.jugador2.nombre}} - {{partido.jugador2.dni}}</span>
                                                </v-tooltip>
                                                
                                            </v-flex>

                                        </v-container>
                                    </v-card>
                                </v-col>
                                </v-card>

                            <center>
                                <v-btn
                                    class="center mb-6"
                                    dark
                                    @click="item.llavesGeneradas = true"
                                    color="blue">
                                    Generar llaves
                                </v-btn>
                            </center>


                                <generar-llaves v-if="item.llavesGeneradas" :categoria="item"></generar-llaves>


                                </div>
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


<script>
import { mapGetters, mapSetter, mapState, mapMutations, mapActions } from 'vuex';
export default {
data: () => ({
//torneoSeleccionado : "",
valid: false,
message: "",
snackbar : false,
tab: null,
cantidadSets: [0,1,2,3,4,5,6,7],
jugadores: [],
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

   
}),
computed: {
    store() {
      return this.$store;
    },
    
    ...mapState('jugadores',['apellidoJugador','nombreJugador','dniJugador','puntosJugador','montoPagado','nuevoJugador']),
    ...mapState('crearFecha',['torneoSeleccionado','nombreFecha','listaJugadores','listaCategorias','montoSociosUnaCategoria',
                'montoSociosDosCategorias','montoNoSociosUnaCategoria','montoNoSociosDosCategorias','torneos'])
},
methods: {
    ...mapMutations('jugadores',['setApellidoJugador', 'setNombreJugador', 'setDniJugador', 'setPuntosJugador',
        'changeBooleanValueNuevoJugador'
]),
    ...mapMutations('crearFecha',['setTorneoSeleccionado','setNombreFecha','setMontoSociosUnaCategoria','setMontoSociosDosCategorias','setMontoNoSociosUnaCategoria',
        'setMontoNoSociosDosCategorias','setListaJugadores','setTorneos','setListaCategorias','pushJugador','pushJugadorCategoria','spliceJugadorCategoria','setMontoPagadoJugador']),
   
   limpiarNuevoJugador(){
        this.changeBooleanValueNuevoJugador()
        this.setApellidoJugador("")
        this.setNombreJugador("")
        this.setDniJugador(null)
        this.setPuntosJugador(null)
    },
    calcularCategoria : function(item){
        let mensaje="";
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
//TODO: Agregar relacion de los jugadores que se agregan desd el boton agregar jugadores
    agregarJugador() { 
        this.changeBooleanValueNuevoJugador()
        
        var jugadorExiste = false;
        this.listaJugadores.forEach(jugador => {
            if(jugador.dni == parseInt(this.dniJugador)){jugadorExiste = true;}
        });
    if(!jugadorExiste){
      var jugador = []
      jugador.push({
        apellido: this.apellidoJugador,
        nombre: this.nombreJugador,
        dni: parseInt(this.dniJugador),
        pivot: {puntos: parseInt(this.puntosJugador)},
        montoPagado: 0,
      });
      
    axios.post('/jugadores',{
        id_torneo : this.torneoSeleccionado.id,
        jugadores : jugador
    }).then(res =>{
       console.log(res.data)
    this.pushJugador(jugador.pop())
    })
        
    
    
    }else{
        this.message = "Ya hay un jugador con el dni que intenta ingresar";
        this.snackbar = true;
    }
        this.setApellidoJugador("")
        this.setNombreJugador("")
        this.setDniJugador(null)
        this.setPuntosJugador(null)
    },
    calcularCategoriaSuperior(item){
        let entrarEnElSiguiente=false;
        let mensaje="No hay una categoria superior";
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
        axios.get(`/torneos/${this.torneoSeleccionado.id}/jugadores`)
        .then(
            res => {
                console.log(res.data)
                this.setListaJugadores(res.data);
                }
        )
    },
    traerCategorias(){
        let me = this;
        axios.get(`/torneos/${this.torneoSeleccionado.id}/categorias`)
        .then(
            res => {//this.$store.commit('setListaCategorias', res.data);
            res.data.forEach(categoria => {
                Object.defineProperty(categoria,'jugadoresAnotados', {value: [], writable:true, configurable:true, enumerable:true});
                Object.defineProperty(categoria,'gruposConEliminatoria', {value: false, writable:true, configurable:true, enumerable:true});
                Object.defineProperty(categoria,'cantidadGrupos', {writable:true, configurable:true, enumerable:true});
                Object.defineProperty(categoria,'listaGrupos', {value: [], writable:true, configurable:true, enumerable:true});
                //Object.defineProperty(categoria,'gruposGenerados', {value: false, writable:true, configurable:true, enumerable:true});
                this.$set(categoria, 'gruposGenerados', false);
                this.$set(categoria, 'llavesGeneradas', false);
                });
                this.setListaCategorias(res.data);
            }
        )
    },
    eliminarJugador(item) {
        var indice = this.listaJugadores.indexOf(item);
        this.listaJugadores.splice(indice, 1);
    },
    
    agregarEnSuCategoria(item){
        let categorias = this.listaCategorias;
        let me = this;
        categorias.forEach( function (categoria, indexCategoria) { 
            if(item.pivot.puntos >= categoria.puntos_minimos && item.pivot.puntos <= categoria.puntos_maximos){
                var indice = categoria.jugadoresAnotados.indexOf(item);
                if(indice === -1){
                    //categoria.jugadoresAnotados.push(item);
                    me.pushJugadorCategoria({item, indexCategoria});
                    me.message="Jugador anotado en su categoria";
                    me.snackbar = true;
                    }
                
                else{
                    //categoria.jugadoresAnotados.splice(indice,1);
                    me.spliceJugadorCategoria({indice, indexCategoria});
                    me.message="El jugador ya no esta anotado en su categoria";
                    me.snackbar = true;
                }
            }
        });
        this.calcularMonto()
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
    calcularMonto(){//desuso pronto
        
    },
    generarGrupos(categoria){//falta
        console.log('Ejecucionn generarGrupos')
        if((categoria.jugadoresAnotados.length/parseInt(categoria.cantidadGrupos))>=3){
            var letras = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z'];
            for(var i = 0; i < categoria.cantidadGrupos; i++){
                var unGrupo = {
                    nombre : letras[i],
                    jugadoresDelGrupo : [],
                    partidos : []
                };
                categoria.listaGrupos.push(unGrupo);
            }
            
            categoria.jugadoresAnotados = categoria.jugadoresAnotados.sort((a, b) => b.pivot.puntos - a.pivot.puntos);
            
            categoria.listaGrupos.forEach(function(grupo, indiceGrupo, array1){
                categoria.jugadoresAnotados.forEach(function(jugador, indiceJugador, array2){
                    if(indiceJugador%parseInt(categoria.cantidadGrupos) == indiceGrupo){grupo.jugadoresDelGrupo.push(jugador);}
                })
            })
            
            this.generarPartidosGrupos(categoria.listaGrupos);
            categoria.gruposGenerados = true;
            this.message="Grupos generados con exito";
            this.snackbar=true;
            
        }
        else{
            this.message="La cantidad de jugadores es insuficiente para la cantidad de grupos";
            this.snackbar=true;
        }
    },
    generarPartidosGrupos(grupos){
        console.log("generarPartidosGrupos");
        var IDPartido = 0;
        grupos.forEach(grupo => {
            for(var i = 0; i < grupo.jugadoresDelGrupo.length; i++){
                for( var j = 0; j < grupo.jugadoresDelGrupo.length; j++){
                    if(j > i){
                        var partido = {
                            id : IDPartido,
                            jugador1 : grupo.jugadoresDelGrupo[i],
                            jugador2 : grupo.jugadoresDelGrupo[j],
                            setsJugador1 : null,
                            setsJugador2 : null,
                        }
                        IDPartido+=1;
                        grupo.partidos.push(partido);
                    }
                }
            }
        });
    },
    gruposGenerados(categoria){//falta
        return !categoria.gruposGenerados;
    },
    deshacerGrupos(categoria){//falta
        console.log('Ejecucion deshacerGrupos')
        categoria.gruposGenerados = false;
        categoria.listaGrupos = [];
    },
},
watch : {
    torneoSeleccionado(){
        console.log(" watcher torneoSeleccionado")
        this.traerJugadoresTorneo();
        this.traerCategorias();
        },
    listaCategorias(){
        console.log(" watcher listaCategorias")
        this.calcularMonto();
        },
    montoSociosUnaCategoria(){
        console.log(" watcher montoSociosUnaCategoria")
        this.calcularMonto();
        },
    montoSociosDosCategorias(){
        console.log(" watcher montoSociosDosCategorias")
        this.calcularMonto();
        },
    montoNoSociosUnaCategoria() {
        console.log(" watcher montoNoSociosUnaCategoria")
        this.calcularMonto()
        },
    montoNoSociosDosCategorias(){
        console.log(" watcher montoNoSociosDosCategorias")
        this.calcularMonto()
        },
    },
created() {
        axios.get("/torneos").then(res => {
            this.setTorneos(res.data)
        });
    },
    
}
</script>

<style>
</style>
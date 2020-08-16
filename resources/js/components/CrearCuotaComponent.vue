<template>
    <div>
        <v-card>
            <v-form v-model="valid" ref="form" lazy-validation>
                <v-col>
                    <v-select
                    v-model="mes"
                    :items="meses"
                    item-text = "nombre"
                    item-value="id"
                    filled
                    label="Mes"
                    ></v-select>

                    <v-select
                    v-model="anio"
                    :items="anios"
                    filled
                    label="Año"
                    ></v-select>

                    <v-btn
                        depressed
                        color="primary"
                        :disabled="!valid"
                        @click.prevent="generarCuota"
                        >Generer cuota
                    </v-btn>
                </v-col>
            </v-form>
        </v-card>

        <v-snackbar v-model="snackbar" timeout="3000">
            <div
            v-text="message">
            </div>

            <template v-slot:action="{ attrs }">
                <v-btn
                    color="blue"
                    text
                    v-bind="attrs"
                    @click="snackbar = false"
                >
                    Cerrar
                </v-btn>
            </template>
        </v-snackbar>


    </div>
</template>


<script>
  export default {

      props : ["usuarioID"],

    data() { 
    return {
        snackbar:false,
        message:"",
        valid: false,
        mes:null,
        anio:new Date().getFullYear(),
        meses: [
            {'id' : 1,  "nombre" : "Enero"},
            {'id' : 2,  "nombre" : "Febrero"},
            {'id' : 3,  "nombre" : "Marzo"},
            {'id' : 4,  "nombre" : "Abril"},
            {'id' : 5,  "nombre" : "Mayo"},
            {'id' : 6,  "nombre" : "Junio"},
            {'id' : 7,  "nombre" : "Julio"},
            {'id' : 8,  "nombre" : "Agosto"},
            {'id' : 9,  "nombre" : "Septiembre"},
            {'id' : 10, "nombre" : "Octubre"},
            {'id' : 11, "nombre" : "Noviembre"},
            {'id' : 12, "nombre" : "Diciembre"},

        ],
        anios: [new Date().getFullYear(),new Date().getFullYear()-1],
        }
    },

    methods:{

       generarCuota(){
           axios.post('/generarCuota',{
               mes : this.mes,
               anio : this.anio,
               usuario_id : this.usuarioID,
           })
           .then((res)=>{
               console.log(res.data.message);
               this.message=res.data.message;
               this.snackbar=true;
           })

           
       }
       
       
    },
   






  }
</script>
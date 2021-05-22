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
                        >Generar cuota
                    </v-btn>
                </v-col>
            </v-form>
        </v-card>


    </div>
</template>


<script>
import { mapActions } from 'vuex';
  export default {

      props : ["usuarioID"],

    data() { 
    return {
        
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
        anios: [new Date().getFullYear()+1,new Date().getFullYear(),new Date().getFullYear()-1],
        }
    },

    methods:{
        ...mapActions(['callSnackbar']),

      async generarCuota(){//TODO revisar el pago de cuotas
           try{
 const nuevaCuota = await axios.post('/generarCuota',{
               mes : this.mes,
               anio : this.anio,
               usuario_id : this.usuarioID,
           });
    this.callSnackbar([nuevaCuota.data.message ,'primary'])

           }catch(e){
               this.callSnackbar(['Error al generar cuota' + e,'error'])
           }
               
          


        //    .then((res)=>{
        //        this.callSanckbar([res.data.message,'success'])
        //        console.log(res.data.message);
               
            
               this.$emit("recargarCuotas", true)
        //    })
        //    .catch(error =>{
            //    console.log('error' + error)
               //this.callSanckbar(['Error al generar la cuota','error'])
        //    })

           
       }
       
       
    },
   






  }
</script>
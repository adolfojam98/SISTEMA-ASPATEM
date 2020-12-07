<template>
    <div>
        
        <div>
          
             <input
                id = "file-excel"
                type="file"
                @change="excelExport"
                accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet"
                style="display: none;"
            /> 
          
            <label for="file-excel" class="subir text-button" style="background-color: #212121;border-radius: 5px">    
          
          
     SELECCIONE UN EXCEL


   </label>          
       
            
        </div>
        <div>
 <!-- <v-simple-table
    
    >
      <template v-slot:default>
        
        <tbody>
          <tr v-for="fila in excelData" :key="fila">
            <td v-for="celda in fila" :key="celda">{{celda}}</td>
           
          </tr>
        </tbody>
      </template>
    </v-simple-table> -->
    
        </div>
    </div>
</template>

<script>
import XLSX from "xlsx";
export default {
    data() {
        return {
            excelData: [],
            prueba :[],
            json : []
        };
    },
    methods: {
        async excelExport (e) {
            


            
            if (e.target.files.length > 0) {
        try {
            
            let file = e.target.files[0];
            let reader = new FileReader();
    
            reader.onload =  async function (e) {
                
                let data = new Uint8Array(e.target.result);
                let workbook =  XLSX.read(data, { type: "array" });
                let worksheet = workbook.Sheets[workbook.SheetNames[0]];
                this.excelData = XLSX.utils.sheet_to_json(worksheet, { header: 1 });
               
                this.armarJson(this.excelData)
                
                

                
                

               
               
            }.bind(this);
            reader.readAsArrayBuffer(file);  

            
        } catch (exception) {
           console.log(exception)
        }
    } else {
        toast("No files found", { type: "error" });
    }
  

        },

        armarJson(data){
            let cabecera = data.shift();
            if(cabecera[0] == 'Apellido' && cabecera[1] == 'Nombre' && cabecera[2] == 'DNI' && cabecera[3] == 'Puntos'){
                
                let json = []
                data.forEach(participante => {
                    let participanteJson = {
                        "apellido"  : participante[0],
                        "nombre"    : participante[1],
                        "dni"       : participante[2],
                        "puntos"    : participante[3]
                    }
                    json.push(participanteJson)
                });
                console.log('Emitiendo')
                 this.$emit("nuevosJugadores", json);
                
                
                
            }else{
                console.log('se mandaron mal los aegumentos')
            }
        }
    }
};
</script>


<style>
 .subir{
    padding: 9px 89px;
    /* background: #5E35B1; */
    color:#fff;
    border:0px solid #fff;
}
 
.subir:hover{
    color:#fff;
    /* background: #5E35B1; */
    cursor:pointer
}
</style>
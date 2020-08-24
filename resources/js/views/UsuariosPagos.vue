<template>
    <div>
        <v-container>
            <v-autocomplete
                v-model="usuarioSeleccionado"
                :items="usuarios"
                :item-text="nombreCompleto"
                return-object

                filled
                label="Ingrese el nombre del socio"
            ></v-autocomplete>
            <v-row>
                <v-col>
                    <v-btn @click="buscarCuotasUsuario" large color="primary">
                        Buscar
                    </v-btn>
                </v-col>

                <v-col>
                    <div v-show="busco">
                        <v-btn
                            @click="[(CrearCuotaModal = true)]"
                            large
                            color="teal lighten-3"
                            class="white--text"
                        >
                            Nueva cuota
                        </v-btn>
                    </div>
                </v-col>
            </v-row>

            
        </v-container>

        <v-container>
            <template>
                <v-simple-table>
                    <template v-slot:default>
                        <thead>
                            <tr v-if="cuotasUsuario != ''">
                                <th class="text-left">Mes</th>
                                <th class="text-left">Año</th>
                                <th class="text-left">Monto</th>
                                <th class="text-left"></th>
                            </tr>
                        </thead>
                        <tbody
                            v-for="(cuota, index) in cuotasUsuario"
                            :key="index"
                        >
                            <tr
                                v-if="cuota.fechaPago"
                                @click="
                                    [
                                        (infoCuotaPaga = !infoCuotaPaga),
                                        (cuotaActual = cuota)
                                    ]
                                "
                            >
                                <td>{{ cuota.mes }}</td>
                                <td>{{ cuota.anio }}</td>
                                <td>${{ cuota.importe }}</td>
                                <td>
                                    <div class="text-center">
                                        <v-chip color="success" dark>
                                            Pagado
                                        </v-chip>
                                    </div>
                                </td>
                            </tr>

                            <tr
                                v-else
                                @click="
                                    [
                                        (pagoCuota = !pagoCuota),
                                        ,
                                        (cuotaActual = cuota)
                                    ]
                                "
                            >
                                <td>{{ cuota.mes }}</td>
                                <td>{{ cuota.anio }}</td>
                                <td>${{ cuota.importe }}</td>
                                <td>
                                    <div class="text-center">
                                        <v-chip color="error" dark>
                                            Pagar
                                        </v-chip>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </template>
                </v-simple-table>
            </template>
        </v-container>
        <v-dialog v-model="CrearCuotaModal" max-width="600px">
                <crear-cuota :usuarioID="usuarioSeleccionado.id" @recargarCuotas = 'recargarCuotas = $event'></crear-cuota>
            </v-dialog>

        <v-dialog v-model="infoCuotaPaga" max-width="350px">
            <info-cuota-paga :usuario = "usuarioSeleccionado" :cuota = "cuotaActual"></info-cuota-paga>
        </v-dialog>

        <v-dialog v-model="pagoCuota" max-width="400px">
           <pago-cuota :cuota = 'cuotaActual' :usuario = 'usuarioSeleccionado' @recargarCuotas = 'recargarCuotas = $event'></pago-cuota>
        </v-dialog>

  <v-snackbar v-model="snackbar" timeout="3000">
            Cuota pagada

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
    data() {
        return {
            cuotaActual: "",
            usuarioSeleccionado: "",
            usuarios: [],
            cuotasUsuario: '',
            infoCuotaPaga: false,
            pagoCuota: false,
            CrearCuotaModal: false,
            busco: false,
            recargarCuotas :false,
            snackbar: false,

            
        };
    },
    methods: {
        nombreCompleto: item => item.apellido + " " + item.nombre,
        buscarCuotasUsuario() {
            if (this.usuarioSeleccionado != "") {
                this.cuotasUsuario = [];
                axios
                    .get(`/usuario/${this.usuarioSeleccionado.id}/cuotas`)
                    .then(res => {
                        this.cuotasUsuario = res.data;
                        console.log(res.data);
                        this.busco = true;
                    });
            }
        },
        

       
    },
    watch: {
            recargarCuotas : function(){
                
                this.buscarCuotasUsuario()
                this.pagoCuota = false
                this.snackbar = true
                this.recargarCuotas = false
            }
            
        },
    created() {
        axios.get("/usuario").then(res => {
            this.usuarios = res.data;
        });
    }
};
</script>

<template>
    <div>
        <h1>Ruta pagos</h1>
        <v-container>
            <v-autocomplete
                v-model="usuarioSeleccionado"
                :items="usuarios"
                :item-text="nombreCompleto"
                item-value="id"
                filled
                label="Ingrese el nombre del socio"
            ></v-autocomplete>
            <div class="my-2">
                <v-btn @click="buscarCuotasUsuario" large color="primary">
                    Buscar
                </v-btn>
                
                <div v-show='busco'>
                <v-btn @click="[CrearCuotaModal = true]"
                    large color="primary">
                    Nueva cuota
                </v-btn>
                </div>

            </div>

            <v-dialog v-model="CrearCuotaModal" max-width="600px">
            <crear-cuota :usuarioID="usuarioSeleccionado"></crear-cuota>
            </v-dialog>
            
        </v-container>

        <v-container>
            <template>
                <v-simple-table>
                    <template v-slot:default>
                        <thead>
                            <tr>
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
                                        (infoCuotaPaga = !infoPagoCuota),
                                        (cuotaActual = cuota)
                                    ]
                                "
                            >
                                <td>{{ cuota.mes }}</td>
                                <td>{{ cuota.anio }}</td>
                                <td>{{ cuota.importe }}</td>
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
                                <td>{{ cuota.importe }}</td>
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

        <v-dialog v-model="infoCuotaPaga" max-width="600px">
            <v-card>
                <h1>
                    Aca pone lo de las cuotas que estan pagadas
                </h1>
            </v-card>
        </v-dialog>

        <v-dialog v-model="pagoCuota" max-width="600px">
            <v-card>
                <h1>
                    Aca pones lo de para persistir un pago de cuota
                </h1>
            </v-card>
        </v-dialog>
    </div>
</template>

<script>
export default {
    data() {
        return {
            usuarioSeleccionado: "",
            usuarios: [],
            cuotasUsuario: [],
            infoCuotaPaga: false,
            pagoCuota: false,
            CrearCuotaModal: false,
            busco:false,
            
        };
    },
    methods: {
        nombreCompleto: item => item.apellido + " " + item.nombre,
        buscarCuotasUsuario() {
            this.cuotasUsuario = [];
            axios
                .get(`/usuario/${this.usuarioSeleccionado}/cuotas`)
                .then(res => {
                    this.cuotasUsuario = res.data;
                    console.log(res.data);
                    this.busco = true;
                });
        },
       
    },
    created() {
        axios.get("/usuario").then(res => {
            this.usuarios = res.data;
        });
    }
};
</script>

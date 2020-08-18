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

            <v-dialog v-model="CrearCuotaModal" max-width="600px">
                <crear-cuota :usuarioID="usuarioSeleccionado.id"></crear-cuota>
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

        <v-dialog v-model="infoCuotaPaga" max-width="500px">
            <info-cuota-paga :usuario = "usuarioSeleccionado" :cuota = "cuotaActual"></info-cuota-paga>
        </v-dialog>

        <v-dialog v-model="pagoCuota" max-width="250px">
            <v-card>
                <v-card
                    class="pa-2"
                    outlined
                    style="background-color: lightgrey;"
                    tile
                >
                    <h1 style="color:blue"><center>ASPATEM</center></h1>
                </v-card>

                <v-text-field
                    v-model="importePersonalizado"
                    label="Monto personalizado(opcional)"
                    :rules="importeRules"
                    prefix="$"
                    required
                ></v-text-field>

                <center>
                    <v-btn @click="pagarCuota" large color="primary">
                        Pagar cuota
                    </v-btn>
                </center>
            </v-card>
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
            cuotasUsuario: [],
            infoCuotaPaga: false,
            pagoCuota: false,
            CrearCuotaModal: false,
            busco: false,
            importePersonalizado: null,
            snackbar: false,

            importeRules: [
                v => !!v || "Importe requerido",
                v => v >= 0 || "Importe no valido"
            ]
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

        pagarCuota() {
            axios
                .put("/pagarCuota", {
                    importe: this.importePersonalizado,
                    id: this.cuotaActual.id
                })
                .then(
                    (this.importePersonalizado = null),
                    this.buscarCuotasUsuario(),
                    (this.snackbar = true),
                    (this.pagoCuota = false)
                );
        }
    },
    created() {
        axios.get("/usuario").then(res => {
            this.usuarios = res.data;
        });
    }
};
</script>

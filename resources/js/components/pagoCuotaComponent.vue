<template>
    <div>
        <v-card>
            <div
                class="pa-2"
                outlined
                style="background-color: lightgrey;"
                tile
            >
                <h1 style="color:blue"><center>ASPATEM</center></h1>
            </div>

            <div class="ml-3 mt-3">
                <div class="text-h6">Usuario</div>
                <div class="text-body-1 ml-1">
                    {{ usuario.nombre }} {{ usuario.apellido }}
                </div>

                <v-divider class="mt-3"></v-divider>

                <div class="text-h6">Mes al que corresponde</div>
                <div class="ml-1 text-body-1">
                    {{ cuota.mes }} del {{ cuota.anio }}
                </div>

                <v-divider class="mt-3"></v-divider>

                <div class="text-h6">Importe de la cuota</div>

                <div class="text-body-1">
                    <v-row
                        dense
                        justify="space-between"
                        class="white"
                        no-gutters
                    >
                        <v-col cols="6">
                            <v-text-field
                                label="Ingresar monto"
                                prefix="$"
                                outlined
                                flat
                                solo
                                dense
                                v-model="cuota.importe"
                                :disabled="editarMonto"
                                :rules="importeRules"
                            ></v-text-field>
                        </v-col>
                        <v-col cols="5">
                            <v-tooltip bottom>
                                <template v-slot:activator="{ on, attrs }">
                                    <v-btn
                                        color="teal lighten-3"
                                        fab
                                        small
                                        dark
                                    >
                                        <v-icon
                                            v-bind="attrs"
                                            v-on="on"
                                            @click="editarMonto = !editarMonto"
                                        >
                                            mdi-border-color</v-icon
                                        >
                                    </v-btn>
                                </template>
                                <span>Editar monto</span>
                            </v-tooltip>
                        </v-col>
                    </v-row>

                    <div v-if="!editarMonto">
<v-divider></v-divider> 
                    <v-row dense  no-gutters  >
                        
                        <v-col cols = '11'>
                            <v-text-field 
                                v-model="observacion"
                                label="Observacion del cambio de monto"
                            ></v-text-field>
                        </v-col>
                    </v-row>
                    </div>
                    
                </div>

                <v-divider></v-divider>

                <div class="text-h6">Fecha de pago</div>

                <v-row no-gutters>
                    <v-col dense cols="7">
                        <v-menu
                            v-model="menuFecha"
                            transition="scale-transition"
                            offset-y
                            min-width="290px"
                        >
                            <template v-slot:activator="{ on, attrs }">
                                <v-text-field
                                    v-model="formatoFecha"
                                    dense
                                    append-icon="mdi-calendar"
                                    outlined
                                    readonly
                                    v-bind="attrs"
                                    v-on="on"
                                ></v-text-field>
                            </template>
                            <v-date-picker
                                v-model="fecha"
                                @input="menuFecha = false"
                            ></v-date-picker>
                        </v-menu>
                    </v-col>
                </v-row>

                <center>
                    <v-btn @click="pagarCuota" large color="primary">
                        Pagar cuota
                    </v-btn>
                </center>
                <br />
            </div>
        </v-card>
        
 
    </div>
</template>

<script>
import { mapActions } from 'vuex';
export default {
    props: ["cuota", "usuario"],
    data() {
        return {
            fecha: new Date().toISOString().substr(0, 10),
            formatoFecha: this.darFormatoFecha(
                new Date().toISOString().substr(0, 10)
            ),
            menuFecha: false,

            importePersonalizado: "",
            importeRules: [
                v => !!v || "Importe requerido",
                v => v >= 0 || "Importe no valido"
            ],
            editarMonto: true,
            observacion : null,
            
        };
    },
    watch: {
        fecha(val) {
            this.formatoFecha = this.darFormatoFecha(this.fecha);
        },
        cuota() {
            this.fecha = new Date().toISOString().substr(0, 10);
            this.editarMonto = true;
        },
        editarMonto(){
            this.observacion = null
        }
    },
    methods: {
        ...mapActions(['callSnackbar']),
        pagarCuota() {
            axios
                .put("/pagarCuota", {
                    importe: this.cuota.importe,
                    fecha: this.fecha,
                    id: this.cuota.id,
                    observacion : this.observacion
                })
                .then(res => {
                    this.importePersonalizado = null;
                    this.callSnackbar(['Cuota Pagada','success'])
                    this.$emit("recargarCuotas", true);
                    
                })
                .catch(error => {
                    console.log(error);
                    this.callSnackbar(['No se pudo realizar el pago. Intente nuevamente', 'error'])
                });
                  

        },

        darFormatoFecha(fecha) {
            if (!fecha) return null;
            console.log(fecha);
            const [anio, mes, dia] = fecha.split("-");
            return `${dia}/${mes}/${anio}`;
        }
    }
};
</script>

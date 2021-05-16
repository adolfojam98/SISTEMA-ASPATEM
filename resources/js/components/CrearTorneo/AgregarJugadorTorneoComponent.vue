<template>
    <div>
        <v-card elevation="4" class="rounded-b-xl">
            <v-card-title>Agregar nuevo Jugador</v-card-title>
            <v-form v-model="valid" lazy-validation>
                <v-text-field
                    
                    v-model="apellidoJugador"
                    :rules="aynRules"
                    
                    label="Apellido del jugador"
                    required
                ></v-text-field>
                <v-text-field
                    
                    v-model="nombreJugador"
                    :rules="aynRules"
                  
                    label="Nombre del jugador"
                    required
                ></v-text-field>
                <v-text-field
                 
                    v-model="dniJugador"
                    :rules="dniRules"
                   
                    label="DNI del jugador"
                    required
                ></v-text-field>
                <v-text-field
                   
                    v-model="puntosJugador"
                  
                    label="Puntos del jugador"
                    :rules="puntosRules"
                    required
                    v-on:keyup.enter="
                        [agregarJugador()]
                    "
                ></v-text-field>

                <v-btn
                    dark
                    block
                    class="rounded-pill mb-2"
                    color="primary"
                
                    
                    :disabled="!valid"
                    @click="
                        [agregarJugador()]
                    "
                    >Agregar</v-btn
                >

                
            </v-form>
        </v-card>
    </div>
</template>

<script>
import { mapActions, mapMutations, mapState } from 'vuex';
export default {
    data() {
        return {
            valid: false,
            nombreJugador : '',
            apellidoJugador:'',
            dniJugador: null,
            puntosJugador: null,

            //RULES
             aynRules: [
            v => !!v || "Campo obligatorio",
            v =>
                /^([A-Za-z][A-Za-z]*([ \t\n\r\f]?[A-Za-z])*)+$/.test(v) ||
                "Nombre invalido",
            v => v.length <= 30 || "Demasiado largo"
        ],
        dniRules: [
            v => !!v || "Campo obligatorio",
            v => /^([0-9]*)+$/.test(v) || "Solo numeros",
            v =>
                (!!v && v.length == 8) ||
                "El DNI debe estar compuesto por 8 numeros"
        ],
         puntosRules: [
            v => !!v || "Puntos requeridos",
            v =>
                /^([0-9]*)?[0-9]+$/.test(v) ||
                "Los puntos deben ser numeros enteros"
        ],
            
        };
    },
    methods: {
        ...mapMutations('CrearTorneo',['pushJugadorTorneo']),
        agregarJugador() {
            let jugador = {
                apellido: this.apellidoJugador,
                nombre: this.nombreJugador,
                dni: this.dniJugador,
                puntos: this.puntosJugador
            };
            console.log(jugador)
            this.pushJugadorTorneo(jugador)
           
            this.apellidoJugador = "";
            this.nombreJugador = "";
            this.dniJugador = null;
            this.puntosJugador = null;
        },
    },
    computed: {
        ...mapState('CrearTorneo', ['listaJugadores'])
    },
};
</script>

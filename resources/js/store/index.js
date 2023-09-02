import Vue from "vue";
import Vuex from "vuex";

import crearFecha from "./crearFecha";
import grupos from "./grupos";
import llaves from "./llaves";
import jugadores from "./jugadores.js";
import CrearTorneo from "./CrearTorneo.js";
import gestionarTorneos from "./gestionarTorneos.js";
import avisos from "./avisos.js";
import historialJugador from "./historialJugador";
import cuotas from "./cuotas";

Vue.use(Vuex);

export default new Vuex.Store({
    strict: false,

    modules: {
        crearFecha,
        jugadores,
        grupos,
        llaves,
        CrearTorneo,
        gestionarTorneos,
        avisos,
        historialJugador,
        cuotas
    },

    state: {
        count: 10,
        snackbar: false,
        message: "",
        colorSnackBar: "",
        isLoading: false,
    },
    getters: {
        getCount(state) {
            return state.count;
        },
        
    },

    mutations: {
        setMessage(state, data) {
            state.message = data;
        },
        setSnackBar(state, data) {
            state.snackbar = data;
        },
        setSnackBarColor(state, data) {
            state.colorSnackBar = data;
        },
        setIsLoading(state, data){
            state.isLoading = data;
        }

    },

    actions: {
        callSnackbar(action,[mensaje, color]) {
           if(color == null){
             color = 'black'
            }
            action.commit("setMessage", mensaje);
            action.commit("setSnackBar", true);
            action.commit("setSnackBarColor", color);
            setTimeout(function(){
                action.commit('setSnackBar',false)
            },5000);
        },
        showSpinner(action){
            action.commit("setIsLoading", true);
        },
        hideSpinner(action){
            action.commit("setIsLoading", false);
        }
    }
});

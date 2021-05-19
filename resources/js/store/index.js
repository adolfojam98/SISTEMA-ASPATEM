import Vue from "vue";
import Vuex from "vuex";

import crearFecha from "./crearFecha";
import grupos from "./grupos";
import llaves from "./llaves";
import jugadores from "./jugadores.js";
import CrearTorneo from "./CrearTorneo.js";

Vue.use(Vuex);

export default new Vuex.Store({
    strict: false,

    modules: {
        crearFecha,
        jugadores,
        grupos,
        llaves,
        CrearTorneo
    },

    state: {
        count: 10,
        snackbar: false,
        message: "",
        colorSnackBar: ""
    },
    getters: {
        getCount(state) {
            return state.count;
        }
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
        }
    },

    actions: {
    callSnackBar(action,[nombre,color]) {
            // if(color == null){
            //    color = 'success'
            // }
            action.commit("setMessage", nombre);
            action.commit("setSnackBar", true);
            action.commit("setSnackBarColor", color);
            setTimeout(function(){
                action.commit('setSnackBar',false)
            },5000);
        }
    }
});

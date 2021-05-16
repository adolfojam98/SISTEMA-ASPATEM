import Vue from 'vue'
import Vuex from 'vuex'

import crearFecha from './crearFecha'
import grupos from './grupos'
import llaves from './llaves'
import jugadores from './jugadores.js'
import CrearTorneo from './CrearTorneo.js'

Vue.use(Vuex)


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
        count: 10
    },
    getters:{
        getCount(state){
            return state.count;
        }
    },

    mutations: {
        sumar(state){
            state.count++;
        },
        restar(state){
            state.count--;
        },
    },

    actions: {

    },
})
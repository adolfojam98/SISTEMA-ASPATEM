import Vue from 'vue'
import Vuex from 'vuex'


Vue.use(Vuex)


export default new Vuex.Store({

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
        }

    },

    actions: {

    },

    modules: {

    }
})
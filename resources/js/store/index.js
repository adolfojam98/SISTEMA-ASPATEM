import Vue from 'vue'
import Vuex from 'vuex'


Vue.use(Vuex)


export default new Vuex.Store({

    state: {
        count: 0
    },
    getters:{
        getCount(state){
            return state.count;
        }
    },

    mutations: {
        setCount(state){
            state.count++;
        }
    },

    actions: {

    },

    modules: {

    }
})
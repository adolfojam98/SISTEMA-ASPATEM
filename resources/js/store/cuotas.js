
export default {
    namespaced: true,
    strict: false,

    state: {
        tipoCuotasDetalles : []
    },

    mutations: {
        setTipoCuotasDetalles(state, data) {
            state.tipoCuotasDetalles = data;
        }
    },
    getters: {

    }
};


export default{
    strict: false,
    modules: {
    },
    namespaced: true,

    state: {
        torneos: [],
        fechas: [],
        torneoSeleccionado: null,
    },

    mutations: {
        setTorneos(state,data){
            state.torneos = data;
        },
        setTorneoSeleccionado(state, data){
            state.torneoSeleccionado = data;
        },
        setFechas(state,data){
            state.fechas = data
        },
    }
}
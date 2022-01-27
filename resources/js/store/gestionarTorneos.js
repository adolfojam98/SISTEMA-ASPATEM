
export default{
    strict: false,
    modules: {
    },
    namespaced: true,

    state: {
        torneos: [],
        fechas: [],
        torneoSeleccionado: null,
        infoGraficas: {
            data: null,
            labels: [],
            series: []
        },
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
        setInfoGraficas(state,data){
            state.infoGraficas = {...state.infoGraficas, ...data}
        }
    }
}
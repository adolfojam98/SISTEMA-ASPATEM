
export default{
    strict: false,
    modules: {
    },
    namespaced: true,

    state: {
        jugadorSeleccionado : ''
    },

    mutations: {
        setjugadorSeleccionado(state,data){
            state.jugadorSeleccionado = data;
        },
    }
}
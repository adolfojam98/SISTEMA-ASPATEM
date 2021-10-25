
export default{
    strict: false,
    modules: {
    },
    namespaced: true,

    state: {
        asunto: '',
        titulo: '',
        subtitulo: '',
        mensaje: ''
    },

    mutations: {
        setAsunto(state,data){
            state.asunto = data;
        },
        setTitulo(state, data){
            state.titulo = data;
        },
        setSubtitulo(state,data){
            state.subtitulo = data
        },
        setMensaje(state,data){
            state.mensaje = data
        },
    }
}
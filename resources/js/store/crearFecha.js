//fecha

import { data } from "jquery";
import { mapMutations, mapState } from "vuex";

export default {
    modules: {
    },
    namespaced: true,

    state: {
        torneoSeleccionado: null,
        nombreFecha: "",
        listaJugadores: [],
        listaCategorias: [],
        montoSociosUnaCategoria: null,
        montoSociosDosCategorias: null,
        montoNoSociosUnaCategoria: null,
        montoNoSociosDosCategorias: null,
        torneos: [],
        cargandoStorage: false,
    },

    mutations: {
        setCargandoStorage(state, data) {
            console.log("Me ejecuto")
            console.log(data)
            state.cargandoStorage = data;
        },

        clearFecha(state) {
            state.nombreFecha = "",
            state.listaJugadores = [],
            state.listaCategorias = [],
            state.montoSociosUnaCategoria = null,
            state.montoSociosDosCategorias = null,
            state.montoNoSociosUnaCategoria = null,
            state.montoNoSociosDosCategorias = null,
            state.cargandoStorage = false
        },

        setTorneoSeleccionado( state, data ) {
            state.torneoSeleccionado = data;
         
            console.log("setTorneoSeleccionado")
            console.log(state.cargandoStorage)

            if( !state.cargandoStorage){
                localStorage.crearFecha = JSON.stringify(state);
            }
        },

        setNombreFecha(state, data) {
            state.nombreFecha = data;
            console.log("set nombre fecha")
            console.log(state.cargandoStorage)
            if( !state.cargandoStorage){

                localStorage.crearFecha = JSON.stringify(state);
            } 
        },

        setMontoSociosUnaCategoria(state, data) {
            state.montoSociosUnaCategoria = data;
            console.log("set monto socios una categoria")
            console.log(state.cargandoStorage)
            if( !state.cargandoStorage){

                localStorage.crearFecha = JSON.stringify(state);
            } 
        },

        setMontoSociosDosCategorias(state, data) {
            state.montoSociosDosCategorias = data;
            console.log("set monto socios dos categorias")
            console.log(state.cargandoStorage)
            if( !state.cargandoStorage){

                localStorage.crearFecha = JSON.stringify(state);
            } 
        },

        setMontoNoSociosUnaCategoria(state, data) {
            state.montoNoSociosUnaCategoria = data;
            console.log("set monto no socios una categoria")
            console.log(state.cargandoStorage)
            if( !state.cargandoStorage){

                localStorage.crearFecha = JSON.stringify(state);
            } 
        },

        setMontoNoSociosDosCategorias(state, data) {
            state.montoNoSociosDosCategorias = data;
            console.log("set socios dos categorias")
            console.log(state.cargandoStorage)
            if( !state.cargandoStorage){

                localStorage.crearFecha = JSON.stringify(state);
            } 
        },

        setListaJugadores(state, data) {
            state.listaJugadores = data;
            console.log("setListaJugadores")
            console.log(state.cargandoStorage)
            if( !state.cargandoStorage){

                localStorage.crearFecha = JSON.stringify(state);
            } 
        },

        setTorneos(state, data) {
            state.torneos = data;
            console.log("setTorneos")
            console.log(state.cargandoStorage)
            if( !state.cargandoStorage){

                localStorage.crearFecha = JSON.stringify(state);
            } 
        },

        setListaCategorias(state, data) {
            state.listaCategorias = data;
            console.log("setListaCategorias")
            console.log(state.cargandoStorage)
            if( !state.cargandoStorage){
                localStorage.crearFecha = JSON.stringify(state);
            } 
        },

        pushJugador(state, data) {
            state.listaJugadores.push(data);
            console.log("Hola")
            console.log(state.cargandoStorage)
            if( !state.cargandoStorage){

                localStorage.crearFecha = JSON.stringify(state);
            } 
        },

        pushJugadorCategoria(state, { item, indexCategoria }) {
            state.listaCategorias[indexCategoria].jugadoresAnotados.push(item);
            console.log("Hola")
            console.log(state.cargandoStorage)
            if( !state.cargandoStorage){

                localStorage.crearFecha = JSON.stringify(state);
            } 
        },

        spliceJugadorCategoria(state, { indice, indexCategoria }) {
            state.listaCategorias[indexCategoria].jugadoresAnotados.splice(indice, 1);
            console.log("Hola")
            console.log(state.cargandoStorage)
            if( !state.cargandoStorage){

                localStorage.crearFecha = JSON.stringify(state);
            } 
        },

        setMontoPagadoJugador(state, { indiceJugador, monto }) {
            state.listaJugadores[indiceJugador].montoPagado = monto;
            console.log("Hola")
            console.log(state.cargandoStorage)
            if( !state.cargandoStorage){

                localStorage.crearFecha = JSON.stringify(state);
            } 
        },


    },

    getters: {
        listaJugadores(state) {
            return state.listaJugadores;
        },

        listaCategorias(state) {
            return state.listaCategorias;
        },

        tieneAlgunCampoCompletado(state) {
            if(state.nombreFecha || state.montoSociosUnaCategoria || state.montoSociosDosCategorias || state.montoNoSociosUnaCategoria || state.montoNoSociosDosCategorias) {
                return true
            }

            return false 
        },

        getCurrentTorneo(state) {
            return state.torneoSeleccionado;
        }

    },

    actions: {
        calcularMonto({ commit, state }) {

            state.listaJugadores.forEach(function (jugador, indiceJugador) {

                var anotadoEnCategorias = 0;
                var monto = 0;

                state.listaCategorias.forEach(categoria => {

                    categoria.jugadoresAnotados.forEach(jugadorAnotado => {
                        if(jugadorAnotado.id === jugador.id) {
                            anotadoEnCategorias++;
                        }
                    });

                    // if (categoria.jugadoresAnotados.includes(jugador)) {
                    //     anotadoEnCategorias++;
                    // }
                })

                if (parseInt(state.montoSociosUnaCategoria) >= 0 && parseInt(state.montoSociosDosCategorias) >= 0
                    && parseInt(state.montoNoSociosUnaCategoria) >= 0 && parseInt(state.montoNoSociosDosCategorias) >= 0) {
                    if (anotadoEnCategorias == 0) { jugador.montoPagado = 0; }
                    else if (anotadoEnCategorias == 1 && jugador.socio.socio == 1) { monto = state.montoSociosUnaCategoria; commit('setMontoPagadoJugador', { indiceJugador, monto }); }
                    else if (anotadoEnCategorias == 2 && jugador.socio.socio == 1) { monto = state.montoSociosDosCategorias; commit('setMontoPagadoJugador', { indiceJugador, monto }); }
                    else if (anotadoEnCategorias == 1 && jugador.socio.socio == 0) { monto = state.montoNoSociosUnaCategoria; commit('setMontoPagadoJugador', { indiceJugador, monto }); }
                    else if (anotadoEnCategorias == 2 && jugador.socio.socio == 0) { monto = state.montoNoSociosDosCategorias; commit('setMontoPagadoJugador', { indiceJugador, monto }); }
                }
            })
        },

        setTorneo({ commit, state }, data) {
            commit('setTorneoSeleccionado', data)
            commit('clearFecha')
        },
    }
}
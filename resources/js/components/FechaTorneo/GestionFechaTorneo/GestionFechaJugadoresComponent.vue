<template>
  <div>
    <v-autocomplete return-object :items="torneos" :item-text="nombreTorneo" v-model="torneoSeleccionado"
      label="Buscar Torneo" @change="getFechas()"></v-autocomplete>
    <v-autocomplete return-object :items="fechas" :item-text="nombreFecha" v-model="fechaSeleccionada"
      label="Buscar Fecha"></v-autocomplete>
    <v-btn @click="cargarFecha()">BUSCAR</v-btn>

    <v-data-table dense :headers="headers" :items="listaJugadores" item-key="dni" :items-per-page="15" :search="search">

<template v-slot:[`item.actions`]="{ item }">
          <v-tooltip bottom v-if="mensajeAgregarCategoria(item) !== ''">
            <template v-slot:activator="{ on, attrs }">
              <small class="mt-1" v-bind="attrs" v-on="on" @click="[agregarJugadorEnSuCategoria(item)]" :class="{'add-category': mensajeAgregarCategoria(item).includes('Agregar'), 'remove-category': mensajeAgregarCategoria(item).includes('Quitar')}">{{mensajeAgregarCategoria(item).replace('Quitar de la categoria: ','').replace('Agregar a la categoria: ','')}}</small>                  </template>
            <span>{{ mensajeAgregarCategoria(item) }}</span>
          </v-tooltip>

          <v-tooltip bottom v-if="!mensajeAgregarCategoriaSuperior(item).includes('No hay una categoria superior')">
            <template v-slot:activator="{ on, attrs }">
              <small class="mt-1" v-bind="attrs" v-on="on" @click="[agregarJugadorEnLaCategoriaSuperior(item)]" :class="{'add-category': mensajeAgregarCategoriaSuperior(item).includes('Agregar'), 'remove-category': mensajeAgregarCategoriaSuperior(item).includes('Quitar')}">{{mensajeAgregarCategoriaSuperior(item).replace('Quitar de la categoria superior: ','').replace('Agregar en la categoria superior: ','')}}</small>
            </template>
            <span>{{ mensajeAgregarCategoriaSuperior(item)}}</span>
          </v-tooltip>
        </template>

</v-data-table>
  </div>
</template>


<script>
export default {
  data() {
    return {
      torneos: [],
      torneoSeleccionado: null,
      fechas: [],
      fechaSeleccionada: null,
      search: '',
      listaJugadores: [],
      listaCategorias: [],
      jugadoresAnotados: [],

      headers: [
        { text: "Apellido", value: "apellido" },
        { text: "Nombre", value: "nombre" },
        { text: "DNI", value: "dni" },
        {
          text: "Categorias",
          value: "actions",
          sortable: false,
          filterable: false,
        },
        { text: "Puntos", value: "puntos" },

        // { text: "Monto", value: "montoPagado" },
      ],

    }
  },
  created() {
    axios.get("/torneos").then((res) => {
      this.torneos = res.data;
    });
  },
  methods: {
    nombreTorneo: (item) => item.nombre,
    nombreFecha: (item) => item.nombre,
    getFechas() {
      if (this.torneoSeleccionado) {
        axios.get(`/torneo/${this.torneoSeleccionado.id}/fechas`).then((res) => {
          this.fechas = res.data
        })
        axios.get(`/torneos/${this.torneoSeleccionado.id}/categorias`).then((res) => {
          this.listaCategorias = res.data;
        })
      }
    },

    async cargarFecha() {
      try {
        const [fechaResponse, jugadoresAnotadosResponse] = await Promise.all([
          axios.get(`/fechas/${this.fechaSeleccionada.id}`),
          axios.get(`/fechas/${this.fechaSeleccionada.id}/usuarios`)
        ]);

        this.listaJugadores = fechaResponse.data.resumen_jugadores.ranking;
        this.listaCategorias = fechaResponse.data.resumen_jugadores.categorias;
        const listaJugadoresAnotados = jugadoresAnotadosResponse.data.body;
        this.listaCategorias = this.listaCategorias.map(categoria => {
          return {
            ...categoria,
            jugadoresAnotados: []
          }
        });

        this.agregarJugadoresAnotados(listaJugadoresAnotados);

      } catch (error) {
        console.error(error);
      }
    },


    agregarJugadoresAnotados(listaJugadoresAnotados) {
      this.listaCategorias.forEach(categoria => {
        listaJugadoresAnotados.forEach(jugador => {
          if (categoria.id == jugador.categoria_mayor_id || categoria.id == jugador.categoria_menor_id) {
            const jugadorEncontrado = this.listaJugadores.find(j => j.usuario_id == jugador.usuario_id);
            categoria.jugadoresAnotados.push(jugadorEncontrado);
          }
        });
      });
    },


    getCategoriaJugador(jugador) {
      return this.listaCategorias.find((categoria) => categoria.nombre == jugador.categoria);
    },

    getCategoriaSuperiorJugador(jugador) {
      const categoriaJugador = this.getCategoriaJugador(jugador);
      return this.listaCategorias.find(categoria => parseInt(categoria.puntos_minimos) > parseInt(categoriaJugador.puntos_minimos));
    },


    mensajeAgregarCategoria(jugador) {
      const categoria = this.getCategoriaJugador(jugador);
      if (!categoria) {
        return "No hay una categoria superior";
      }
      if (this.estaAnotadoEnCategoria(categoria, jugador)) {
        return "Agregar a la categoria: " + categoria.nombre;
      } else {
        return "Quitar de la categoria: " + categoria.nombre;
      }
    },

    mensajeAgregarCategoriaSuperior(jugador) {
      const categoria = this.getCategoriaSuperiorJugador(jugador);
      if (!categoria) {
        return "No hay una categoria superior";
      }
      if (this.estaAnotadoEnCategoria(categoria, jugador)) {
        return "Agregar en la categoria superior: " + categoria.nombre;
      } else {
        return "Quitar de la categoria superior: " + categoria.nombre;
      }

    },

    agregarJugadorEnCategoria(infoJugador) {
      axios.post(`/fechas/${this.fechaSeleccionada.id}/usuarios/${infoJugador.id}`, { ...infoJugador }).then(
        
      res => console.log(res))
      ;
    },

    agregarJugadorEnSuCategoria(jugador) {
      const estadoJugador = this.getEstadoJugador(jugador);
      estadoJugador.categoria_menor_id = this.getCategoriaJugador(jugador).id;
      this.agregarJugadorEnCategoria(estadoJugador);

    },

    agregarJugadorEnLaCategoriaSuperior(jugador) {
      console.log('entro aca');
      const estadoJugador = this.getEstadoJugador(jugador);
      estadoJugador.categoria_mayor_id = this.getCategoriaSuperiorJugador(jugador).id;
      this.agregarJugadorEnCategoria(estadoJugador);

    },
    getEstadoJugador(jugador) {
      const categoriaActual = this.getCategoriaJugador(jugador);
      const categoriaSuperior = this.getCategoriaSuperiorJugador(jugador);
      const categoriaActualId = categoriaActual && this.buscarJugadorEnCategoria(jugador, categoriaActual) ? categoriaActual.id : null;
      const categoriaSuperiorId = categoriaSuperior && this.buscarJugadorEnCategoria(jugador, categoriaSuperior) ? categoriaSuperior.id : null;
    console.log(categoriaActual);
    console.log(categoriaSuperior);
    console.log(categoriaActualId);
    console.log(categoriaSuperiorId);
    
      return {
        id: jugador.usuario_id,
        categoria_menor_id: categoriaActualId,
        categoria_mayor_id: categoriaSuperiorId,
        monto: 124
      }
    },


    buscarJugadorEnCategoria(jugador, categoria) {
      return categoria.jugadoresAnotados.find(j => j.usuario_id == jugador.usuario_id);
    },
    calcularCategoria(item) {
      let mensaje = "";
      this.listaCategorias.forEach((categoria) => {

        if (
          parseInt(item.puntos) >= parseInt(categoria.puntos_minimos) &&
          parseInt(item.puntos) <= parseInt(categoria.puntos_maximos)
        ) {
          const indice = this.getIndexjugadorAnotado(categoria, item);
          //if (!categoria.jugadoresAnotados.includes(item)) {
          if (indice == -1) {
            mensaje = "Agregar a la categoria: " + categoria.nombre;
          } else {
            mensaje = "Quitar de la categoria: " + categoria.nombre;
          }
        }
      });
      return mensaje;
    },

    estaAnotadoEnCategoria(categoria, jugador) {
      let indice = -1
      categoria.jugadoresAnotados.forEach(function (elemento, index) {
        if (elemento.usuario_id === jugador.usuario_id) {
          indice = index;
        }
      });
      return indice != -1 ;
    }
  }
}
</script>


<style>
.remove-category {
  cursor: pointer;
  background-color: lightseagreen;
  color: white;
  padding: 4px;
  border-radius: 10px;
}

.add-category {
  cursor: pointer;
  background-color: gray;
  color: white;
  padding: 4px;
  border-radius: 10px;
}
</style>
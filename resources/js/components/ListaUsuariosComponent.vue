<template>
  <div>
    <v-card>
      <v-card-title>
        Usuarios
        <v-spacer></v-spacer>
        <v-text-field
          v-model="search"
          append-icon="mdi-magnify"
          label="Buscar"
          single-line
          hide-details
        ></v-text-field>
      </v-card-title>      
      <v-data-table :headers="headers" :items="usuarios" :search="search">
        <template v-slot:item.actions="{ item }">
            
              <v-icon
                small
                class="mr-2"
                @click="editItem(item)"
              >
                mdi-pencil
              </v-icon>
              <v-icon
                small
                @click="deleteItem(item)"
              >
                mdi-delete
              </v-icon>
            
        </template>

        
      </v-data-table>

      

    </v-card>
  </div>
</template>

<script>
export default {
  data() {
    return {
      usuarios: [],
      search: "",
      headers: [
        {
          text: "Nombre",
          align: "start",
          sortable: true,
          value: "nombre"
        },
        { text: "Apellido", value: "apellido" },
        { text: "Mail", value: "mail" },
        { text: "Telefono", value: "telefono" },
        { text: "Puntos", value: "puntos" },
        { text: 'Actions', value: 'actions', sortable: false }
      ]
    };
  },

  methods: {
    deleteItem(item){
                let me=this
                
                if (confirm('¿Seguro que deseas borrar a este socio?')) {
                    axios.delete(`/usuario/borrar/${item.id}`)
                    .then(function(res){
                     me.created();
                    })
                    .catch(function (error) {
                        console.log(error);
                    }); 
                }
            },

    editItem(item){
      this.$router.push({
      name: "modificar",
      params:{
      usuario: item,
      }
    });
    },

    created() {
      axios.get("/usuario/mostrar").then(res => {
      this.usuarios = res.data;
      });
    },

  },
  
  mounted(){
    this.created();
  }
  

};
</script>

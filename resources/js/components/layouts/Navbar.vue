<template>
  <div>
    <nav class="navbar bg-white p-0 border-bottom">
      <div class="col-12 p-0">
        <ul class="navigation p-0 m-0">
          <li class="nav-item">
            <router-link class="nav-link py-1" :to="'/'">
              <span class="fas fa-home"></span>
            </router-link>
          </li>
          <li class="nav-item" v-for="(cat) in catList" :key="cat.id">
            <router-link
              class="nav-link py-1"
              :to="{name: 'categoria', params: { id: cat.value }}"
            >{{cat.label}}</router-link>
          </li>
        </ul>
      </div>
    </nav>
  </div>
</template>

<script>
export default {
  props: ["ruta"],
  data() {
    return {
      catList: [],
    };
  },
  mounted() {
    this.getAllCategories();
  },
  methods: {
    //   Cargar Categorías Guardadas
    getAllCategories: function () {
      axios
        .get("/categories")
        .then((response) => {
          response.data.forEach((element) => {
            this.catList.push({
              value: element.id,
              label: element.nombre,
            });
          });
          this.catList.shift();
        })
        .catch((error) => {
          //no estas autenticado
          if (error.response.status == 401) {
            // this.app.Toastr.error("Caduco su Sesión");
            // localStorage.removeItem("user-authenticate");
            // this.$router.push("/login");
            console.log(error.response.status);
          }
        });
    },
  },
};
</script>

<style>
.nav-link {
  color: black !important;
  font-weight: 0.8rem;
  font-size: 0.8rem;
  font-family: "Arial Black", Helvetica, sans-serif;
  /* transform: scaleY(0.9); */
}
</style>

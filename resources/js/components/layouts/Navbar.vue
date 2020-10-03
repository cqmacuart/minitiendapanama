<template>
  <div :style="navStyle">
    <nav class="navbar bg-white p-0 border-bottom custom-nav-style">
      <div class="col-12 p-0">
        <ul class="navigation p-0 m-0">
          <li
            class="nav-item custom-item-style"
            v-for="cat in catList"
            :key="cat.id"
          >
            <router-link
              class="nav-link py-1"
              :to="{ name: 'categoria', params: { id: cat.value } }"
              >{{ cat.label }}</router-link
            >
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
      fillColors: {
        //   Cabecera
        nvBgColor: "",
        nvTxColor: "",
        nvBrColor: "",
      },
      isColors: null,
    };
  },
  computed: {
    navStyle() {
      return {
        "--bg-nav-color": this.fillColors.nvBgColor
          ? this.fillColors.nvBgColor
          : "#FFFFFF",
        "--text-item-color": this.fillColors.nvTxColor
          ? this.fillColors.nvTxColor
          : "#000000",
        "--border-item-color": this.fillColors.nvBrColor
          ? this.fillColors.nvBrColor
          : "magenta",
      };
    },
  },
  mounted() {
    this.getAllCategories();
    this.getColorCount();
  },
  methods: {
    getColorCount: function () {
      axios.get("/colors/count").then((response) => {
        this.isColors = response.data;
        if (this.isColors > 0) {
          this.getColorSettings();
        }
      });
    },
    getColorSettings: function () {
      axios.get("/colors").then((response) => {
        response.data.forEach((element) => {
          this.fillColors.nvBgColor = element.nvBgColor;
          this.fillColors.nvTxColor = element.nvTxColor;
          this.fillColors.nvBrColor = element.nvBrColor;
        });
      });
    },
    //   Cargar Categorías Guardadas
    getAllCategories: function () {
      axios
        .get("/categories/page")
        .then((response) => {
          response.data.forEach((element) => {
            this.catList.push({
              value: element.id,
              label: element.nombre,
            });
          });
          this.catList;
          //   this.catList.shift();
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
.custom-nav-style {
  background-color: var(--bg-nav-color) !important;
}
.custom-item-style > a {
  color: var(--text-item-color) !important;
  font-weight: 0.8rem;
  font-size: 0.8rem;
  font-family: "Arial Black", Helvetica, sans-serif;
}
.item-active,
.custom-item-style > a:hover {
  border-bottom: var(--border-item-color) !important;
}
</style>

<template>
  <section class="container col-12 mt-3">
    <div class="col-12 border rounded pt-2 bg-white pb-5">
      <h2 class="m-0 admin-title">Nuevo Producto</h2>
      <hr class="mt-0" />
      <!-- Form -->
      <section class="col-12 d-flex row flex-column flex-sm-row m-auto px-0 px-sm-2">
        <div class="col-12 col-md-4">
          <img src="/img/settings/mobilestore_logo.jpg" alt class="img-fluid" />
        </div>
        <div class="col-12 col-md-8 px-0 px-sm-2">
          <table class="table table-sm">
            <tbody>
              <tr>
                <th
                  class="d-none d-sm-table-cell text-nowrap text-left align-bottom align-sm-middle border-top-0"
                  style="width:1%"
                >Nombre:</th>
                <td class="border-top-0">
                  <input
                    type="text"
                    class="form-control form-control-sm border-0"
                    placeholder="Nombre del Producto"
                    autofocus
                    v-model="fillProducts.productName"
                  />
                </td>
              </tr>
              <tr class="text-left">
                <th
                  class="d-none d-sm-table-cell align-bottom align-sm-middle text-nowrap"
                >Descripción Corta:</th>
                <td>
                  <input
                    type="text"
                    class="form-control form-control-sm border-0"
                    placeholder="Breve descripción del producto"
                    autofocus
                    v-model="fillProducts.productShort"
                  />
                </td>
              </tr>
              <tr class="text-left">
                <th
                  class="d-none d-sm-table-cell align-bottom align-sm-middle text-nowrap"
                >Descripción Larga:</th>
                <td>
                  <textarea
                    name="long_des"
                    id="long_des"
                    rows="2"
                    class="form-control form-control-sm border-0 textarea"
                    placeholder="Descripción extendida"
                    v-model="fillProducts.productLong"
                  ></textarea>
                </td>
              </tr>
              <tr class="text-left">
                <th class="d-none d-sm-table-cell align-bottom align-sm-middle">Categoría:</th>
                <td>
                  <select
                    name="category"
                    id="category"
                    class="custom-select form-control-sm border-0 py-0 pl-1 select text-muted"
                    v-model="fillProducts.productCategory"
                  >
                    <option selected disabled value>
                      <small class="text-muted">Elija una categoría</small>
                    </option>
                    <option v-for="(cat, index) in catList" :key="index" :value="cat.balue">
                      <small class="text-muted" v-text="cat.label"></small>
                    </option>
                  </select>
                </td>
              </tr>
              <tr class="text-left">
                <th class="d-none d-sm-table-cell align-bottom align-sm-middle">Posición:</th>
                <td>
                  <label for class="form-label d-sm-none text-muted ml-2">Posición:</label>
                  <el-input-number
                    v-model="fillProducts.productPosition"
                    controls-position="right"
                    :min="1"
                    size="mini"
                    :class="'col-12 p-0'"
                  ></el-input-number>
                </td>
              </tr>
              <tr class="text-left">
                <th class="d-none d-sm-table-cell align-bottom align-sm-middle">Precio:</th>
                <td>
                  <label for class="form-label d-sm-none text-muted ml-2">Precio:</label>
                  <input
                    type="text"
                    class="form-control form-control-sm text-right"
                    placeholder="0.00"
                    autofocus
                    v-model="fillProducts.productPrice"
                  />
                </td>
              </tr>
              <tr class="text-left">
                <th class="d-none d-sm-table-cell align-bottom align-sm-middle">Estado:</th>
                <td>
                  <select
                    name="status"
                    id="status"
                    class="custom-select form-control-sm border-0 py-0 pl-1 select text-muted"
                    v-model="fillProducts.productStatus"
                  >
                    <option selected disabled value>
                      <small class="text-muted">Estado</small>
                    </option>
                    <option v-for="(state, index) in statusList" :key="index" :value="state.value">
                      <small class="text-muted" v-text="state.label"></small>
                    </option>
                  </select>
                </td>
              </tr>
              <tr class="text-left">
                <th class="d-none d-sm-table-cell align-bottom align-sm-middle">Imagen:</th>
                <td>
                  <div class="custom-file">
                    <input
                      type="file"
                      class="custom-file-input form-control-sm border-0 border-bottom"
                      id="customFile"
                      @change="getImageFile"
                    />
                    <label
                      class="custom-file-label text-left border-0 border-bottom"
                      for="customFile"
                    >Imagen del Producto</label>
                  </div>
                </td>
              </tr>
              <tr>
                <td colspan="2" class="text-right py-3">
                  <button class="btn btn-sm btn-primary" @click.prevent="setProduct">
                    <span class="fas fa-check"></span>
                    Crear
                  </button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </section>
      <!-- End Form -->
    </div>
  </section>
</template>

<script>
export default {
  data() {
    return {
      fillProducts: {
        productName: "",
        productShort: "",
        productLong: "",
        productCategory: "",
        productPosition: "",
        productPrice: "",
        productStatus: "",
        productImageName: "",
      },
      form: new FormData(),
      catList: [],
      statusList: [],
    };
  },
  mounted() {
    this.getCategoryStatus();
    this.getAllCategories();
  },
  methods: {
    //   CONSTRUCTORES >>>
    getCategoryStatus: function () {
      this.statusList = [];
      axios
        .get("/status")
        .then((response) => {
          response.data.forEach((element) => {
            this.statusList.push({
              value: element.id,
              label: element.nombre,
            });
          });
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
    getAllCategories: function () {
      this.catList = [];
      axios
        .get("/categories")
        .then((response) => {
          response.data.forEach((element) => {
            this.catList.push({
              value: element.id,
              label: element.nombre,
            });
          });
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
    // CONSTRUCTORES <<<
    setProduct: function () {
      if (this.fillProducts.productImage) {
        const params = {
          nombre: this.fillProducts.productName,
          short_des: this.fillProducts.productShort,
          long_des: this.fillProducts.productLong,
          estado_id: this.fillProducts.productStatus,
          category_id: this.fillProducts.productCategory,
          position: this.fillProducts.productPosition,
          price: this.fillProducts.productPrice,
          image: this.fillProducts.productImageName,
        };
        axios.post("/products", params).then((response) => {
          if (response.data) {
            this.$router.push("/admin/productos");
          }
        });
      }
    },
    getImageFile: function (e) {
      this.fillProducts.productImage = e.target.files[0];
      this.saveImageFile();
    },
    saveImageFile() {
      this.form.append("file", this.fillProducts.productImage);
      const config = { headers: { "Content-Type": "multipart/form-data" } };
      let url = "/products/saveFile";
      axios.post(url, this.form, config).then((response) => {
        this.fillProducts.productImageName = response.data;
      });
    },
  },
};
</script>

<style>
.el-input-number {
  width: 100%;
}
.textarea {
  resize: none;
}
.select {
  height: 1.4rem;
}
</style>

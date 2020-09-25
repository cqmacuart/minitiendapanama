<template>
  <section class="container col-12 mt-3">
    <div class="col-12 border rounded pt-2 bg-white pb-5">
      <h2 class="m-0 admin-title">Configuración</h2>
      <hr class="mt-0" />
      <!-- Form -->
      <section class="col-12 d-flex row flex-column flex-sm-row m-auto px-0 px-sm-2">
        <div class="col-12 col-md-4">
          <img
            :src="`/img/settings/${fillSetting.image}`"
            alt
            class="img-fluid"
            v-if="fillSetting.image"
          />
        </div>
        <div class="col-12 col-md-8 px-0 px-sm-2">
          <!-- this.mobile -->
          <!-- this.image -->
          <!-- this.currency -->
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
                    placeholder="Nombre de la Tienda"
                    v-model="fillSetting.storename"
                    autofocus
                  />
                </td>
              </tr>
              <tr>
                <th
                  class="d-none d-sm-table-cell text-nowrap text-left align-bottom align-sm-middle"
                  style="width:1%"
                >País:</th>
                <td class>
                  <input
                    type="text"
                    class="form-control form-control-sm border-0"
                    placeholder="País"
                    v-model="fillSetting.country"
                  />
                </td>
              </tr>
              <tr>
                <th
                  class="d-none d-sm-table-cell text-nowrap text-left align-bottom align-sm-middle"
                  style="width:1%"
                >Ciudad:</th>
                <td class>
                  <input
                    type="text"
                    class="form-control form-control-sm border-0"
                    placeholder="Ciudad de Ubicación"
                    v-model="fillSetting.city"
                  />
                </td>
              </tr>
              <tr>
                <th
                  class="d-none d-sm-table-cell text-nowrap text-left align-bottom align-sm-middle"
                  style="width:1%"
                >Dirección:</th>
                <td class>
                  <input
                    type="text"
                    class="form-control form-control-sm border-0"
                    placeholder="Escriba su dirección"
                    v-model="fillSetting.location"
                  />
                </td>
              </tr>
              <tr>
                <th
                  class="d-none d-sm-table-cell text-nowrap text-left align-bottom align-sm-middle"
                  style="width:1%"
                >Teléfono:</th>
                <td class>
                  <input
                    type="text"
                    class="form-control form-control-sm border-0"
                    placeholder="+507 1234 5678"
                    v-model="fillSetting.mobile"
                  />
                </td>
              </tr>
              <tr>
                <th
                  class="d-none d-sm-table-cell text-nowrap text-left align-bottom align-sm-middle"
                  style="width:1%"
                >Moneda:</th>
                <td class>
                  <input
                    type="text"
                    class="form-control form-control-sm border-0"
                    placeholder="USD - EUR - AUS - CAN..."
                    v-model="fillSetting.currency"
                  />
                </td>
              </tr>

              <tr class="text-left">
                <th class="d-none d-sm-table-cell align-bottom align-sm-middle">Imagen:</th>
                <td>
                  <div class="custom-file">
                    <input
                      type="file"
                      class="custom-file-input form-control-sm border-0 border-bottom"
                      name="customFile"
                      id="customFile"
                      @change="getImageFile"
                    />
                    <label
                      class="custom-file-label text-left border-0 border-bottom"
                      for="customFile"
                      v-text="fillSetting.image"
                    ></label>
                  </div>
                </td>
              </tr>
              <tr>
                <td colspan="2" class="text-right py-3">
                  <button class="btn btn-sm btn-primary" @click.prevent="setSettings">
                    <span class="fas fa-check"></span>
                    Actualizar
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
      fillSetting: {
        storeid: "",
        storename: "",
        mobile: "",
        city: "",
        country: "",
        location: "",
        image: "",
        currency: "",
      },
      form: new FormData(),
      settings: [],
    };
  },
  mounted() {
    this.getAllSettings();
  },
  methods: {
    getAllSettings: function () {
      this.settings = [];
      axios
        .get("/admin/settings")
        .then((response) => {
          this.fillSetting.storeid = response.data.id;
          this.fillSetting.storename = response.data.storename;
          this.fillSetting.mobile = response.data.mobile;
          this.fillSetting.location = response.data.location;
          this.fillSetting.image = response.data.image;
          this.fillSetting.currency = response.data.currency;
          this.fillSetting.country = response.data.country;
          this.fillSetting.city = response.data.city;
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
    setSettings: function () {
      const params = {
        storename: this.fillSetting.storename,
        mobile: this.fillSetting.mobile,
        location: this.fillSetting.location,
        image: this.fillSetting.image,
        currency: this.fillSetting.currency,
        country: this.fillSetting.country,
        city: this.fillSetting.city,
      };
      axios
        .put(`/admin/settings/${this.fillSetting.storeid}`, params)
        .then((response) => {
          if (response.data) {
            this.$router.push("/admin");
          }
        });
    },
    getImageFile: function (e) {
      this.fillSetting.image = e.target.files[0];
      this.saveImageFile();
    },
    saveImageFile() {
      this.form.append("file", this.fillSetting.image);
      const config = { headers: { "Content-Type": "multipart/form-data" } };
      let url = "/admin/settings/saveFile";
      axios.post(url, this.form, config).then((response) => {
        this.fillSetting.image = response.data;
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

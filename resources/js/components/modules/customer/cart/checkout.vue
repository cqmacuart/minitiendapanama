<template>
  <div v-if="ok" :style="customStyle">
    <!-- <section class="col-12 p-0 m-0 text-center">
      <img :src="`/img/categories/${categoryImage}`" alt class="img-fluid" v-if="categoryImage" />
    </section>-->
    <div class="col-12 pt-2 pb-1 text-center border-bottom">
      <h5 class="font-weight-bolder customer-title custom-checkout-style">
        Confirmar Pedido
        <span class="fas fa-check-circle fa-2x custom-checkout-style"></span>
      </h5>
    </div>
    <div class="col-12 m-auto p-0 d-flex flex-col flex-sm-row row">
      <section class="container-md p-0 p-sm-3 col-12 col-sm-6">
        <div class="col-12 p-0 bg-white h-100">
          <table class="table table-bordered m-0">
            <tr>
              <th
                class="bg-secondary font-weight-bolder text-white align-middle"
                style="width: 1%"
              >
                Nombre
              </th>
              <td>
                <el-input
                  placeholder="Nombre de Cliente"
                  v-model="nombre"
                  maxlength="20"
                  show-word-limit
                ></el-input>
              </td>
            </tr>
            <tr>
              <th
                class="bg-secondary font-weight-bolder text-white align-middle"
              >
                Ciudad
              </th>
              <td>
                <el-input
                  placeholder="Ciudad"
                  v-model="ciudad"
                  maxlength="20"
                  show-word-limit
                ></el-input>
              </td>
            </tr>
            <tr>
              <th
                class="bg-secondary font-weight-bolder text-white align-middle"
              >
                Dirección
              </th>
              <td>
                <el-input
                  placeholder="Dirección"
                  v-model="direccion"
                  maxlength="120"
                  show-word-limit
                ></el-input>
              </td>
            </tr>
            <tr>
              <th
                class="bg-secondary font-weight-bolder text-white align-middle"
              >
                Celular
              </th>
              <td>
                <el-input
                  placeholder="Teléfono Celular"
                  v-model="celular"
                ></el-input>
              </td>
            </tr>
            <tr>
              <th
                class="bg-secondary font-weight-bolder text-white align-middle"
              >
                Email
              </th>
              <td>
                <el-input
                  placeholder="Email"
                  v-model="email"
                  type="email"
                ></el-input>
              </td>
            </tr>
            <tr>
              <th
                class="bg-secondary font-weight-bolder text-white align-middle"
              >
                Comentarios
              </th>
              <td>
                <el-input
                  placeholder="Comentarios adicionales"
                  v-model="comentario"
                  maxlength="120"
                  show-word-limit
                ></el-input>
              </td>
            </tr>
            <tr>
              <th
                class="bg-secondary font-weight-bolder text-white align-middle"
              >
                Recibir Notificación
              </th>
              <td class="align-middle">
                <el-switch
                  v-model="notificacion"
                  active-text="Si"
                  inactive-text="No"
                ></el-switch>
              </td>
            </tr>
          </table>
        </div>
      </section>
      <section
        class="container-md p-0 p-sm-3 col-12 col-sm-6 pb-3 clearfix mt-2 mt-sm-0"
      >
        <div class="col-12 p-0 bg-white h-100">
          <table class="table table-bordered m-0">
            <caption class="pl-3 text-muted">
              <small>**El valor del domicilio no se encuentra incluído</small>
            </caption>
            <tr>
              <th class="totalize text-black" style="width: 1%">Artículos</th>
              <td class="text-right">
                <label class="totalize" for>{{ totalItems }} Artículo(s)</label>
              </td>
            </tr>
            <tr>
              <th class="totalize text-black">Total</th>
              <td class="text-right">
                <label class="totalize custom-price-style" for
                  >{{ currency }} {{ totalAmount | numeral("0,0.00") }}</label
                >
              </td>
            </tr>
          </table>
          <button
            class="btn btn-success btn-lg float-right mr-4 mb-4"
            @click.prevent="setOrder"
            v-loading.fullscreen.lock="fullscreenLoading"
          >
            Pedir
          </button>
        </div>
      </section>
    </div>
  </div>
</template>

<script>
export default {
  props: ["currency", "ruta"],
  data() {
    return {
      totalItems: 0,
      totalAmount: 0,
      //datos de usuario
      nombre: "",
      ciudad: "",
      direccion: "",
      celular: "",
      email: "",
      comentario: "",
      notificacion: true,
      ok: false,
      fillSetting: {
        storename: "",
        mobile: "",
        city: "",
        country: "",
        image: "",
        currency: "",
      },
      fullscreenLoading: false,
      fillColors: {
        ctBgColor: "",
        ctTxColor: "",
        pPrColor: "",
        pIcColor: "",
      },
      isColors: null,
    };
  },
  mounted() {
    this.getCartTotalItems();
    this.getCartTotalAmount();
    this.getAllSettings();
    this.getColorCount();
  },
  computed: {
    customStyle() {
      //   sb = SHOPPING BAG
      // hm = HOME
      return {
        "--bg-ct-color": this.fillColors.ctBgColor
          ? this.fillColors.ctBgColor
          : "#FFFFFF",
        "--text-ct-color": this.fillColors.ctTxColor
          ? this.fillColors.ctTxColor
          : "magenta",
        "--text-p-color": this.fillColors.pPrColor
          ? this.fillColors.pPrColor
          : "gray",
        "--icon-p-color": this.fillColors.pIcColor
          ? this.fillColors.pIcColor
          : "red",
      };
    },
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
          this.fillColors.ctBgColor = element.ctBgColor;
          this.fillColors.ctTxColor = element.ctTxColor;
          this.fillColors.pPrColor = element.pPrColor;
          this.fillColors.pIcColor = element.pIcColor;
        });
      });
    },
    setOrder: function () {
      this.fullscreenLoading = true;
      if (this.validate()) {
        const params = {
          nombre: this.nombre,
          ciudad: this.ciudad,
          direccion: this.direccion,
          celular: this.celular,
          email: this.email,
          comentario: this.comentario,
          notificacion: this.notificacion,
        };
        axios.post("/orders", params).then((response) => {
          if (response.status == 200) {
            //salvar el token
            window.open(
              `https://wa.me/${this.fillSetting.mobile}?text= Hola, Me gustaría realizar el siguiente pedido: ${response.data.link}`,
              `_blank`
            );
            let token = response.data.serialize;
            const mailparams = {
              pedido: response.data.pedido,
              enlace: response.data.link,
            };
            //establecer parametros de envío de MAIL
            axios.post("/sendingmail/nuevo", mailparams).then((response) => {
              if (sessionStorage.getItem("currCustomer")) {
                sessionStorage.removeItem("currCustomer");
              }
              this.$router.push({
                name: "mipedido",
                params: { serial: token },
              });
              this.fullscreenLoading = false;
            });
          }
        });
      } else {
        this.fullscreenLoading = false;
        this.errors.forEach((element) => {
          this.$toastr.error(element.error, "!Oops", {
            closeButton: true,
            debug: false,
            newestOnTop: false,
            progressBar: true,
            positionClass: "toast-bottom-left",
            preventDuplicates: false,
            onclick: null,
            showDuration: "300",
            hideDuration: "1000",
            timeOut: "3000",
            extendedTimeOut: "3000",
            showEasing: "swing",
            hideEasing: "linear",
            showMethod: "fadeIn",
            hideMethod: "fadeOut",
          });
        });
      }
    },
    getCartTotalItems: function () {
      this.totalItems = 0;
      axios.get(`/cartCount`).then((response) => {
        if (response.data > 0) {
          this.ok = true;
          this.totalItems = response.data;
        } else {
          this.$router.push("/");
        }
      });
    },
    getCartTotalAmount: function () {
      this.totalAmount = 0;
      axios.get("/cart/amount").then((response) => {
        this.totalAmount = response.data;
      });
    },
    getAllSettings: function () {
      this.settings = [];
      axios
        .get("/admin/settings")
        .then((response) => {
          this.fillSetting.storename = response.data.storename;
          this.fillSetting.mobile = response.data.mobile;
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
    validate() {
      let pattern = new RegExp(/^[^a-zA-Z\W][0-9]*\d$/);
      this.errors = [];
      let check = true;
      if (this.nombre.length == 0) {
        this.errors.push({ error: "El nombre es un campo requerido." });
        check = false;
      }
      if (this.ciudad.length == 0) {
        this.errors.push({ error: "La ciudad es un campo requerido" });
        check = false;
      }
      if (this.direccion.length == 0) {
        this.errors.push({ error: "La dirección es un campo requerido" });
        check = false;
      }
      if (this.celular.length == 0) {
        this.errors.push({ error: "El celular es un campo requerido" });
        check = false;
      }
      if (!pattern.test(this.celular)) {
        this.errors.push({ error: "El número de celular no es válido" });
        check = false;
      }
      if (this.email.length == 0) {
        this.errors.push({ error: "El correo es un campo requerido" });
        check = false;
      }
      return check;
    },
  },
};
</script>

<style>
</style>

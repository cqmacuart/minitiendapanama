<template>
  <div v-if="ok" :style="customStyle">
    <!-- <section class="col-12 p-0 m-0 text-center">
      <img
        :src="`/img/categories/${categoryImage}`"
        alt
        class="img-fluid"
        v-if="categoryImage"
      />
    </section> -->
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
            <caption>
              <small
                >Complete los campos obligatorios (<span class="text-danger"
                  >*</span
                >) para realizar su pedido.</small
              >
            </caption>
            <tr>
              <th
                class="bg-secondary font-weight-bolder text-white align-middle"
                style="width: 1%"
              >
                <span>*</span>
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
                <span>*</span>
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
                <span>*</span>
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
                <span>*</span>
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
                <span>*</span>
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
                  placeholder="Comentarios adicionales (opcional)"
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
                <router-link :to="{ name: 'cart' }" class="totalize"
                  >{{ totalItems }} Artículo(s)</router-link
                >
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
          <!-- <button
            class="btn btn-success btn-lg float-right mr-4 mb-4"
            @click.prevent="setOrder"
            v-loading.fullscreen.lock="fullscreenLoading"
          >
            Pedir
          </button> -->

          <div
            v-if="epayco_params.epayco_existed && epayco_params.epayco_param_5"
          >
            <div v-if="validate()">
              <div class="text-right">
                <img
                  src="/img/payment/epayco/epayco.png"
                  @click="epaycoform()"
                  style="cursor: pointer"
                />
              </div>
            </div>
            <div v-else class="text-right">
              <img src="/img/payment/epayco/epayco_gris.png" />
            </div>
          </div>
          <div v-else>
            <small class="text-danger"
              >No hay ningun medio habilitado para efectuar pagos</small
            >
          </div>
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
      //   Pasarelas de pago
      epayco_params: {
        epayco_param_1: "",
        epayco_param_2: "",
        epayco_param_3: "",
        epayco_param_4: "",
        epayco_param_5: false,
        epayco_existed: false,
        epayco_test: false,
      },
    };
  },
  mounted() {
    this.getCartTotalItems();
    this.getCartTotalAmount();
    this.getAllSettings();
    this.getColorCount();
    this.getEpayco();
  },
  computed: {
    csrf() {
      return document
        .querySelector('meta[name="csrf-token"]')
        .getAttribute("content");
    },
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
            this.$toastr.error("Error " + error.response.status);
          }
        });
    },
    validate() {
      let pattern = new RegExp(/^[^a-zA-Z\W][0-9]*\d$/);
      let mailPattern = new RegExp(
        /^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/
      );
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
      if (!mailPattern.test(this.email)) {
        this.errors.push({
          error: "El formato del correo electrónico no es válido",
        });
        check = false;
      }
      return check;
    },
    epaycoform() {
      var handler = ePayco.checkout.configure({
        key: `${this.epayco_params.epayco_param_3}`,
        test: this.epayco_params.epayco_test,
      });

      var data = {
        //Parametros compra (obligatorio)
        name: this.fillSetting.storename,
        description: `Pedido en ${this.fillSetting.storename}`,
        currency: `${this.fillSetting.currency}`,
        amount: this.totalAmount,
        tax_base: "0",
        tax: "0",
        country: "co",
        lang: "es",

        //Onpage="false" - Standard="true"
        external: "false",

        //Atributos opcionales
        extra1: `${this.nombre}`,
        extra2: `${this.ciudad}`,
        extra3: `${this.direccion}`,
        extra4: `${this.celular}`,
        extra5: `${this.email}`,
        extra6: `${this.comentario}`,
        extra7: `${this.notificacion}`,
        response: `${this.ruta}/epayco/apiresponse`,

        //Atributos cliente
        name_billing: `${this.nombre}`,
        email_billing: `${this.email}`,
        address_billing: `${this.direccion}`,
        mobilephone_billing: `${this.celular}`,
      };
      handler.open(data);
    },
    getEpayco() {
      axios.get(`/admin/epayco`).then((response) => {
        if (response.status == 200) {
          this.epayco_params.epayco_param_1 = response.data[1].value;
          this.epayco_params.epayco_param_2 = response.data[2].value;
          this.epayco_params.epayco_param_3 = response.data[3].value;
          this.epayco_params.epayco_param_4 = response.data[4].value;
          this.epayco_params.epayco_param_5 =
            response.data[0].value == 1 ? true : false;
          this.epayco_params.epayco_test =
            response.data[5].value == 1 ? false : true;
          this.epayco_params.epayco_existed = true;
        }
      });
      this.fullscreenLoading = false;
    },
  },
};
</script>

<style>
</style>

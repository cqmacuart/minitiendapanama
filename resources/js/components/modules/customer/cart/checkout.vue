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
                  autocomplete="ON"
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
                  autocomplete="ON"
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
                  autocomplete="ON"
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
                  autocomplete="ON"
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
                  autocomplete="ON"
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

          <!-- VALIDAR DATOS PRIMERO -->
          <div v-if="validate()">
            <!--VERIFICAR COD -->
            <div v-if="cod_params.cod_param_1" class="mb-3">
              <div class="accordion" id="accordionExample">
                <div class="card border-0">
                  <div class="" id="headingOne">
                    <div class="text-center">
                      <img
                        src="/img/payment/cod/codbutton.png"
                        style="cursor: pointer"
                        data-toggle="collapse"
                        data-target="#collapseOne"
                        aria-expanded="true"
                        aria-controls="collapseOne"
                      />
                    </div>
                  </div>
                  <div
                    id="collapseOne"
                    class="collapse"
                    aria-labelledby="headingOne"
                    data-parent="#accordionExample"
                  >
                    <div class="card-body py-1">
                      <p class="m-0 text-muted text-center">
                        <small>Pague al recibir/retirar su pedido</small>
                      </p>
                      <div
                        class="row d-flex flex-column flex-sm-row justify-content-center align-items-center mb-3"
                      >
                        <label
                          class="d-flex m-0 align-items-center px-2 pb-3 pb-sm-0"
                        >
                          <img
                            src="/img/payment/cod/cash_disable.png"
                            v-if="cod_params.cod_choose"
                          />
                          <img src="/img/payment/cod/cash.png" v-else />
                          <el-switch
                            style="display: block; visibility: hidden"
                            v-model="cod_params.cod_choose"
                            active-color="#13ce66"
                            inactive-color="#ff4949"
                          >
                          </el-switch>
                          <img
                            src="/img/payment/cod/dataphone_disable.png"
                            v-if="!cod_params.cod_choose"
                          />
                          <img src="/img/payment/cod/dataphone.png" v-else />
                        </label>
                        <button
                          class="btn btn-success px-2"
                          v-loading.fullscreen.lock="fullscreenLoading"
                          @click.prevent="setCod"
                        >
                          <b>Ordenar</b>
                        </button>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!--COD END -->
            <!--VERIFICAR Paguelofacil -->
            <div class="mb-3" v-if="paguelofacil_params.paguelofacil_param_3">
              <div class="text-center">
                <a
                  :href="`https://secure.paguelofacil.com/LinkDeamon.cfm?CCLW=${paguelofacil_params.paguelofacil_param_1}&CMTN=${totalAmount}&CDSC=${paguelofacil_params.paguelofacil_param_4}&RETURN_URL=${paguelofacil_params.paguelofacil_param_2}&PARM_1=${paguelofacil_params.paguelofacil_param_5}&PARM_2=${paguelofacil_params.paguelofacil_param_6}&PARM_3=${paguelofacil_params.paguelofacil_param_7}&PARM_4=${paguelofacil_params.paguelofacil_param_8}&PARM_5=${paguelofacil_params.paguelofacil_param_9}&PARM_6=${paguelofacil_params.paguelofacil_param_10}&PARM_7=${paguelofacil_params.paguelofacil_param_11}`"
                >
                  <img src="/img/payment/paguelofacil/paguelofacil_on.png" />
                </a>
              </div>
            </div>
            <!-- Paguelofacil END -->
          </div>
          <div v-else class="text-center">
            <small class="text-danger"
              >Complete los datos obligatorios para desplegar medios de
              pago</small
            >
            <div>
              <div class="text-center mb-3" v-if="cod_params.cod_param_1">
                <img src="/img/payment/cod/codbutton_disable.png" />
              </div>
              <div class="text-center mb-3">
                <img src="/img/payment/paguelofacil/paguelofacil_off.png" />
              </div>
            </div>
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
      cod_params: {
        cod_param_1: false,
        cod_choose: false,
      },
      paguelofacil_params: {
        paguelofacil_param_1: null, //CCWL
        paguelofacil_param_2: null, //URL RETURN
        paguelofacil_param_3: false, //ENABLED/DISABLED
        paguelofacil_param_4: `Pago en línea - PagueloFacil`, //Descripción
        // parametros extra
        paguelofacil_param_5: this.nombre,
        paguelofacil_param_6: this.ciudad,
        paguelofacil_param_7: this.direccion,
        paguelofacil_param_8: this.celular,
        paguelofacil_param_9: this.email,
        paguelofacil_param_10: this.comentario,
        paguelofacil_param_11: this.notificacion,
      },
    };
  },
  mounted() {
    this.getCartTotalItems();
    this.getCartTotalAmount();
    this.getAllSettings();
    this.getColorCount();
    this.getCod();
    this.getPaguelofacil();
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
      //settear valores para enlace
      if (check) {
        this.settingPagueloFacil();
      }
      return check;
    },
    getPaguelofacil() {
      axios.get(`/admin/paguelofacil`).then((response) => {
        if (response.status == 200) {
          this.paguelofacil_params.paguelofacil_param_1 =
            response.data[1].value;
          this.paguelofacil_params.paguelofacil_param_2 =
            response.data[2].value;
          this.paguelofacil_params.paguelofacil_param_3 =
            response.data[0].value == 1 ? true : false;
        }
      });
      this.fullscreenLoading = false;
    },
    settingPagueloFacil() {
      this.paguelofacil_params.paguelofacil_param_5 = this.nombre;
      this.paguelofacil_params.paguelofacil_param_6 = this.ciudad;
      this.paguelofacil_params.paguelofacil_param_7 = this.direccion;
      this.paguelofacil_params.paguelofacil_param_8 = this.celular;
      this.paguelofacil_params.paguelofacil_param_9 = this.email;
      this.paguelofacil_params.paguelofacil_param_10 = this.comentario;
      this.paguelofacil_params.paguelofacil_param_11 = this.notificacion;
    },
    getCod() {
      axios.get(`/admin/cod`).then((response) => {
        if (response.status == 200) {
          this.cod_params.cod_param_1 =
            response.data[0].value == 1 ? true : false;
        }
      });
      this.fullscreenLoading = false;
    },
    setCod() {
      this.fullscreenLoading = true;
      const params = {
        nombre: this.nombre,
        ciudad: this.ciudad,
        direccion: this.direccion,
        celular: this.celular,
        email: this.email,
        comentario: this.comentario,
        notificacion: this.notificacion,
        //   datos de transacción
        referencia: null,
        fecha: new Date(),
        respuesta: "Pago Contra Entrega",
        motivo: "Realizado con Pago Contra Entrega",
        banco: null,
        recibo: null,
        total: this.totalAmount,
        moneda: this.fillSetting.currency,
        //Datos adicionales de transaccion
        type_payment: this.cod_params.cod_choose ? "Datafono" : "Efectivo",
        cod_transaction_state: 1,
        errorcode: "00",
        cust_id_cliente: null,
        id_factura: null,
        franchise: null,
        cardnumber: null,
        customer_ip: null,
        medio: "cod",
      };
      axios.post("/orders", params).then((response) => {
        if (response.status == 200) {
          let token = response.data.serialize;
          const mailparams = {
            pedido: response.data.pedido,
            enlace: response.data.link,
          };
          //establecer parametros de envío de MAIL
          axios.post("/sendingmail/nuevo", mailparams).then((response) => {
            this.$router.push({
              name: "cod.response",
              params: { cod_ref: token },
            });
            this.fullscreenLoading = false;
          });
        } else {
          this.fullscreenLoading = false;
        }
      });
    },
  },
};
</script>

<style>
</style>

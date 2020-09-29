<template>
  <div v-if="ok">
    <!-- <section class="col-12 p-0 m-0 text-center">
      <img :src="`/img/categories/${categoryImage}`" alt class="img-fluid" v-if="categoryImage" />
    </section>-->
    <div class="col-12 pt-2 pb-1 text-center border-bottom">
      <h5 class="font-weight-bolder customer-title">
        Confirmar Pedido
        <span class="fas fa-check-circle fa-2x"></span>
      </h5>
    </div>
    <div class="col-12 m-auto p-0 d-flex flex-col flex-sm-row row">
      <section
        class="container-md p-0 p-sm-3 col-12 col-sm-6 order-2 order-sm-1"
      >
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
                <el-input placeholder="Email" v-model="email"></el-input>
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
        class="container-md p-0 p-sm-3 col-12 col-sm-6 pb-3 order-1 order-sm-2 clearfix"
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
                <label
                  class="totalize"
                  for
                  v-text="currency + ' ' + totalAmount.toFixed(2)"
                ></label>
              </td>
            </tr>
          </table>
          <button
            class="btn btn-success btn-lg float-right mr-4 mb-4"
            @click.prevent="setOrder"
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
  props: ["currency"],
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
    };
  },
  mounted() {
    this.getCartTotalItems();
    this.getCartTotalAmount();
  },
  methods: {
    setOrder: function () {
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
          if (sessionStorage.getItem("currCustomer")) {
            sessionStorage.removeItem("currCustomer");
          }
          window.open(
            "https://wa.me/+573184163107?text=Hola, Me gustaría realizar el siguiente pedido: " +
              response.data.link,
            "_blank"
          );
          this.$router.push({
            name: "mipedido",
            params: { serial: response.data.serialize },
          });
        }
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
  },
};
</script>

<style>
.remove-item {
  cursor: pointer;
  position: absolute;
  right: 0;
  top: 3px;
}
.totalize {
  font-family: "Arial Black", Helvetica, sans-serif;
}
</style>

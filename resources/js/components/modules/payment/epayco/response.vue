<template>
  <div v-loading.fullscreen.lock="fullscreenLoading">
    <!-- <section class="col-12 p-0 m-0 text-center">
      <img
        :src="`/img/categories/${categoryImage}`"
        alt
        class="img-fluid"
        v-if="categoryImage"
      />
      <div v-else class="p-3">
        <span class="fas fa-5x fa-spin fa-spinner"></span>
      </div>
    </section> -->
    <div class="col-12 pt-0 pb-1 text-center px-0">
      <h5
        class="font-weight-bolder customer-title custom-category-style py-2"
        v-text="'Epayco'"
      ></h5>
      <small
        class="text-muted py-2"
        v-text="`Referencia de pago: ${ref_payco}`"
      ></small>
    </div>
    <section class="container-md p-0 p-sm-3 col-12 d-flex flex-wrap"></section>
  </div>
</template>

<script>
export default {
  props: ["ruta"],
  data() {
    return {
      fullscreenLoading: true,
      fillColors: {
        //   footer
        sbBgColor: "",
        sbIcColor: "",
        sbScColor: "",
        sbTxColor: "",
        hmBgColor: "",
        hmTxColor: "",
        ctBgColor: "",
        ctTxColor: "",
        pPrColor: "",
        pIcColor: "",
      },
      ref_payco: "dcdad23e7bb8c9932743a33b",
    };
  },
  computed: {
    customStyle() {
      //   sb = SHOPPING BAG
      // hm = HOME
      return {
        "--bg-sb-color": this.fillColors.sbBgColor
          ? this.fillColors.sbBgColor
          : "magenta",
        "--icon-sb-color": this.fillColors.sbIcColor
          ? this.fillColors.sbIcColor
          : "#FFFFFF",
        "--second-sb-color": this.fillColors.sbScColor
          ? this.fillColors.sbScColor
          : "#3490DC",
        "--text-sb-color": this.fillColors.sbTxColor
          ? this.fillColors.sbTxColor
          : "#FFFFFF",
        "--bg-hm-color": this.fillColors.hmBgColor
          ? this.fillColors.hmBgColor
          : "#000000",
        "--text-hm-color": this.fillColors.hmTxColor
          ? this.fillColors.hmTxColor
          : "#FFFFFF",
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
  mounted() {
    this.getColorCount();
    this.getPayInfo();
  },
  methods: {
    getPayInfo() {
      //   this.ref_payco = this.$route.query.ref_payco;
      axios
        .get(
          `https://secure.epayco.co/validation/v1/reference/${this.ref_payco}`
        )
        .then((response) => {
          console.log(response);
        });
    },
    //   ESTILOS
    getColorCount: function () {
      axios.get("/colors/count").then((response) => {
        this.isColors = response.data;
        if (this.isColors > 0) {
          this.getColorSettings();
        }
      });
      return true;
    },
    getColorSettings: function () {
      axios.get("/colors").then((response) => {
        response.data.forEach((element) => {
          this.fillColors.sbBgColor = element.sbBgColor;
          this.fillColors.sbIcColor = element.sbIcColor;
          this.fillColors.sbScColor = element.sbScColor;
          this.fillColors.sbTxColor = element.sbTxColor;
          this.fillColors.hmBgColor = element.hmBgColor;
          this.fillColors.hmTxColor = element.hmTxColor;
          this.fillColors.ctBgColor = element.ctBgColor;
          this.fillColors.ctTxColor = element.ctTxColor;
          this.fillColors.pPrColor = element.pPrColor;
          this.fillColors.pIcColor = element.pIcColor;
        });
      });
      this.fullscreenLoading = false;
    },
  },
};
</script>

<style>
.custom-sb-styles {
  background: var(--bg-sb-color) !important;
  overflow: visible;
  max-width: 55px;
  max-height: 55px;
  border-radius: 50px;
}
.custom-sb-styles .fa-shopping-bag {
  color: var(--icon-sb-color) !important;
}
.custom-sb-styles .fa-layers-bottom-left {
  background-color: var(--second-sb-color) !important;
  border-color: var(--text-sb-color) !important;
  color: var(--text-sb-color) !important;
}
.custom-hm-styles {
  background-color: var(--bg-hm-color) !important;
  color: var(--text-hm-color) !important;
}
.custom-hm-styles .fa-home {
  color: var(--text-hm-color) !important;
}
.custom-category-style {
  background-color: var(--bg-ct-color) !important;
  color: var(--text-ct-color) !important;
}
.custom-price-style {
  color: var(--text-p-color) !important;
}
.custom-addicon-style {
  color: var(--icon-p-color) !important;
}
</style>

<template>
  <div class="col-12 p-0" :style="footerStyle">
    <footer class="fixed-bottom d-none d-md-block col-12 m-auto p-0">
      <div class="bg-dark custom-footer-style">
        <p class="text-center p-0 m-0">
          <small class="text-white">Copyright &copy;</small>
        </p>
      </div>
    </footer>
  </div>
</template>

<script>
export default {
  data() {
    return {
      fillColors: {
        //   footer
        fBgColor: "",
        fTxColor: "",
      },
      isColors: null,
    };
  },
  computed: {
    footerStyle() {
      return {
        "--bg-footer-color": this.fillColors.fBgColor
          ? this.fillColors.fBgColor
          : "#343A40",
        "--text-footer-color": this.fillColors.fTxColor
          ? this.fillColors.fTxColor
          : "#FFFFFF",
      };
    },
  },
  mounted() {
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
          this.fillColors.fBgColor = element.fBgColor;
          this.fillColors.fTxColor = element.fTxColor;
        });
      });
    },
  },
};
</script>

<style>
.custom-footer-style {
  background-color: var(--bg-footer-color) !important;
}
.custom-footer-style > p > small {
  color: var(--text-footer-color) !important;
}
</style>

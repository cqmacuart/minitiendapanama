<template>
  <div>
    <section class="col-12 p-0 m-0 text-center">
      <img
        :src="`/img/categories/${categoryImage}`"
        alt
        class="img-fluid"
        v-if="categoryImage"
      />
    </section>
    <div class="col-12 pt-2 pb-1 text-center">
      <h5 class="font-weight-bolder customer-title" v-text="categoryName"></h5>
    </div>
    <section class="container-md p-0 p-sm-3 col-12 d-flex flex-wrap">
      <div
        class="col-12 col-sm-6 mb-4 mb-sm-1"
        v-for="(product, index) in resultadosPaginados"
        :key="index"
      >
        <div class="card h-100 mb-3 col-12 product-card pt-3 pt-md-0 relative">
          <!-- share icons on each product -->
          <div class="card-icons d-sm-flex flex-column">
            <div class="col-12 p-2 p-lg-3">
              <a
                href
                @click.prevent="
                  dialogVisible = true;
                  setShareInfo(product.value);
                "
              >
                <span class="fas fa-share-alt text-black-50 fa-2x"></span>
              </a>
            </div>
            <div v-if="product.flag" class="col-12 p-2 p-lg-3 mt-4 mt-lg-2">
              <span class="fas fa-shopping-bag fa-2x text-danger"></span>
            </div>
          </div>
          <div class="row no-gutters h-100">
            <div class="col-md-4 d-flex align-items-center text-center">
              <router-link
                :to="{ name: 'producto', params: { id: product.value } }"
              >
                <img
                  :src="`/img/products/${product.image}`"
                  alt
                  class="card-image"
                  v-if="product.image"
                />
              </router-link>
            </div>
            <div class="col-md-8">
              <div class="card-header bg-transparent p-0 p-md-3">
                <h5
                  v-text="product.label"
                  class="overflow-hidden text-nowrap"
                ></h5>
              </div>
              <div class="card-body p-0 p-md-3">
                <small>
                  <p class="card-text" v-text="product.short"></p>
                </small>
                <p class="card-text p-0">
                  <small
                    class="text-muted"
                    v-text="currency + ' ' + product.price"
                  ></small>
                </p>
              </div>
              <div class="card-footer bg-transparent text-nowrap">
                <a
                  class="link p-2 remove-to-cart"
                  @click.prevent="substo(product.value)"
                >
                  <el-button
                    icon="el-icon-minus"
                    circle
                    :class="'shadow-sm'"
                  ></el-button>
                </a>
                <input
                  type="number"
                  class="quantity border-0 text-center text-muted"
                  min="0"
                  :value="product.qty"
                  readonly
                />
                <a
                  class="link p-2 add-to-cart"
                  @click.prevent="addto(product.value)"
                >
                  <el-button
                    icon="el-icon-plus"
                    circle
                    :class="'shadow-sm'"
                  ></el-button>
                  <!-- <span class="fas fa-plus text-muted"></span> -->
                </a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
    <div class="mt-2">
      <nav aria-label="Page navigation example">
        <ul class="pagination justify-content-center">
          <li class="page-item" v-if="pageNumber > 0">
            <a
              class="page-link"
              href="#"
              tabindex="-1"
              aria-disabled="true"
              @click.prevent="prevPage"
              >Previous</a
            >
          </li>
          <li
            class="page-item"
            v-for="(page, index) in paginas"
            :key="index"
            :class="page == pageNumber ? ' active' : ''"
          >
            <a class="page-link" href="#" @click.prevent="selectPage(page)">{{
              page + 1
            }}</a>
          </li>
          <li class="page-item" v-if="pageNumber < pageCount - 1">
            <a class="page-link" href="#" @click.prevent="nextPage">Next</a>
          </li>
        </ul>
      </nav>
    </div>
    <!-- Bolsa esquina derecha -->
    <div class="text-right pr-3">
      <div class="d-inline-block fa-4x bag-icon-box">
        <router-link :to="{ name: 'cart' }">
          <span
            class="fa-layers fa-fw"
            style="
              background: magenta;
              overflow: visible;
              max-width: 55px;
              max-height: 55px;
              border-radius: 50px;
            "
          >
            <span class="fas fa-shopping-bag text-white p-2"></span>
            <span
              class="fa-layers-counter fa-layers-bottom-left bg-primary border"
              v-text="cartCount"
            ></span>
          </span>
        </router-link>
      </div>
    </div>

    <!-- sharing Dialog -->
    <el-dialog
      title="Compartir"
      :visible.sync="dialogVisible"
      width="300px"
      style="z-index: 9999"
      class="p-2"
      :before-close="handleClose"
    >
      <h5 v-text="shareName"></h5>
      <div class="d-flex justify-content-around py-3">
        <ShareNetwork
          class="btn text-white"
          v-for="network in networks"
          :network="network.network"
          :key="network.network"
          :style="{ backgroundColor: network.color }"
          :url="sharing.url"
          :title="sharing.title"
          :description="sharing.description"
          :quote="sharing.quote"
          :hashtags="sharing.hashtags"
          :twitterUser="sharing.twitterUser"
        >
          <i :class="network.icon"></i>
        </ShareNetwork>
      </div>
      <span slot="footer" class="dialog-footer">
        <el-button type="primary" @click="handleClose">Salir</el-button>
      </span>
    </el-dialog>
  </div>
</template>

<script>
export default {
  props: ["ruta", "id", "currency", "filter"],
  data() {
    return {
      shoppingCart: [],
      currentCat: [],
      productList: [],
      categoryName: "",
      categoryImage: "",
      categoryId: 1,
      cartCount: 0,
      dialogVisible: false,
      //   SOCIAL NETWORKS LINKS
      shareName: "",
      sharing: {
        url: "https://news.vuejs.org/issues/180",
        title:
          "Say hi to Vite! A brand new, extremely fast development setup for Vue.",
        description:
          'This week, I’d like to introduce you to "Vite", which means "Fast". It’s a brand new development setup created by Evan You.',
        quote: "The hot reload is so fast it's near instant. - Evan You",
        hashtags: "vuejs,vite,javascript",
        twitterUser: "youyuxi",
      },
      networks: [
        {
          network: "facebook",
          name: "Facebook",
          icon: "fab fah fa-lg fa-facebook-f",
          color: "#1877f2",
        },
        {
          network: "whatsapp",
          name: "Whatsapp",
          icon: "fab fah fa-lg fa-whatsapp",
          color: "#25d366",
        },
        {
          network: "twitter",
          name: "Twitter",
          icon: "fab fah fa-lg fa-twitter",
          color: "#1da1f2",
        },
      ],
      // META VARIABLES
      metaTitle: "",
      metaImage: "",
      metaDescription: "",
      metaRuta: "",
      metaProduct: "",
      //   Paginación
      pageNumber: 0,
      perPage: 6,
    };
  },
  // META INFO
  //   metaInfo() {
  //     return {
  //       title: `My Mobile Store`,
  //       titleTemplate: `%s`,
  //       meta: [
  //         {
  //           name: "description",
  //           content: `${this.metaDescription}`,
  //         },
  //         { property: "og:title", content: `%s | ${this.metaTitle}` },
  //         { property: "og:site_name", content: "My Mobile Store" },
  //         {
  //           property: "og:description",
  //           content: `${this.metaDescription}`,
  //         },
  //         { property: "og:type", content: "article" },
  //         {
  //           property: "og:url",
  //           content: `${this.metaRuta}`,
  //         },
  //         {
  //           property: "og:image",
  //           content: `${this.ruta}/img/products/${this.metaImage}`,
  //           //   content: `${this.ruta}/img/products/${this.metaImage}`,
  //         },
  //       ],
  //     };
  //   },
  // META INFO END
  watch: {
    id: {
      // the callback will be called immediately after the start of the observation
      immediate: true,
      handler(val, oldVal) {
        this.getCurrentCategory();
      },
    },
  },
  computed: {
    productFilter() {
      return this.productList;
    },
    //   metodos de paginacion
    pageCount() {
      let a = this.productList.length,
        b = this.perPage;
      return Math.ceil(a / b);
    },
    resultadosPaginados() {
      let inicio = this.pageNumber * this.perPage,
        fin = inicio + this.perPage;
      return this.productList.slice(inicio, fin);
    },
    paginas() {
      let a = this.productList.length,
        b = this.perPage;
      let pageCount = Math.ceil(a / b);
      let count = 0,
        pageArray = [];
      while (count < pageCount) {
        pageArray.push(count);
        count++;
      }
      return pageArray;
    },
  },
  mounted() {
    this.checkingCartCount();
  },
  methods: {
    //   Shared Info
    setShareInfo(id) {
      const producto = this.productList.find(
        (producto) => producto.value == id
      );
      this.metaTitle = producto.label;
      this.metaImage = producto.image;
      this.metaDescription = producto.short;
      this.metaRuta = this.ruta + `/producto/${id}`;
      this.metaProduct = "id";
      this.shareName = producto.label;
      this.sharing = {
        url: this.ruta + "/producto/" + id,
        title: producto.label,
        description: producto.short,
        quote: this.currency + " " + producto.price,
        media: this.ruta + "/img/products/" + producto.image,
        // hashtags: "vuejs,vite,javascript",
        // twitterUser: "youyuxi",
      };
      //   console.log(this.sharing);
    },
    //   Carts Functions >>>>>
    addto(productId) {
      let me = this;
      axios.get(`/addToCart/${productId}`).then((response) => {
        this.cartCount = response.data.count;
        this.chekingCartProducts(response.data.id, 1);
        const producto = this.productList.find(
          (producto) => producto.value == response.data.id
        );
        if (producto) {
          this.productList.map(function (x, y) {
            if (producto.value == x.value) {
              me.productList[y].flag = true;
              me.productList[y].qty = me.productList[y].qty + 1;
            }
          });
        }
      });
    },
    substo(productId) {
      let me = this;
      axios.get(`/subsToCart/${productId}`).then((response) => {
        this.cartCount = response.data.count;
        this.chekingCartProducts(response.data.id, response.data.qty);
        const producto = this.productList.find(
          (producto) => producto.value == response.data.id
        );
        if (producto) {
          this.productList.map(function (x, y) {
            if (producto.value == x.value) {
              me.productList[y].flag = response.data.qty == 0 ? false : true;
              me.productList[y].qty = response.data.qty;
            }
          });
        }
      });
    },
    chekingCartProducts(id, qty) {
      if (this.shoppingCart.includes(id)) {
        if (qty == 0) {
          this.shoppingCart.splice(this.shoppingCart.indexOf(id), 1);
        }
      } else {
        if (qty > 0) {
          this.shoppingCart.push(parseInt(id));
        }
      }
    },
    checkingCartCount() {
      axios.get(`/cartCount`).then((response) => {
        this.cartCount = response.data;
      });
    },
    //  Carts Functions <<<<<
    getProducts: function (id) {
      this.productList = [];
      let url = this.filter
        ? `/products/filteredBySearch/${this.filter}`
        : `/products/filteredByCategory/${this.categoryId}`;
      axios
        .get(url)
        .then((response) => {
          response.data.forEach((element) => {
            fetch(`/cart/isincart/${element.id}`)
              .then((data) => data.json())
              .then((data) => {
                this.productList.push({
                  value: element.id,
                  label: element.nombre,
                  short: element.short_des,
                  image: element.image,
                  price: element.price,
                  flag: data.is,
                  qty: data.qty,
                });
              });
          });
        })
        .catch((error) => {
          if (error.response.status == 401) {
            // console.log(error.response.status);
          }
        });
    },
    getCurrentCategory: function () {
      this.categoryId = this.id ? this.id : 1;
      if (this.id) {
        axios
          .get(`/categories/${this.categoryId}`)
          .then((response) => {
            this.currentCat = response.data;
            this.categoryName = this.currentCat.nombre;
            this.categoryImage = this.currentCat.image;
          })
          .catch((error) => {
            if (error.response.status == 401) {
              //   console.log(error.response.status);
            }
          });
      } else {
        axios
          .get("/categories")
          .then((response) => {
            this.currentCat = response.data;
            this.categoryName = response.data[0].nombre;
            this.categoryImage = response.data[0].image;
          })
          .catch((error) => {
            if (error.response.status == 401) {
              //   console.log(error.response.status);
            }
          });
      }
      this.getProducts(this.categoryId);
    },
    handleClose() {
      this.dialogVisible = false;
      this.metaTitle = "";
    },
    // Metodos de paginación
    nextPage() {
      this.pageNumber++;
    },
    prevPage() {
      this.pageNumber--;
    },
    selectPage(page) {
      this.pageNumber = page;
    },
    incializarPaginacion() {
      this.pageNumber = 0;
    },
  },
};
</script>

<style>
.el-dialog__body {
  padding: 10px;
}
.product-card {
  transition: all 0.3s ease-out;
}
.product-card:hover {
  transform: scale(1.1, 1.1);
}
.customer-title {
  color: magenta;
}
.bag-icon-box {
  position: fixed;
  bottom: 10px;
  z-index: 20;
}
.card-icons {
  position: absolute;
  right: 0;
  z-index: 9;

  background: rgba(255, 255, 255, 0);
  background: -moz-linear-gradient(
    left,
    rgba(255, 255, 255, 0) 0%,
    rgba(255, 255, 255, 1) 20%,
    rgba(255, 255, 255, 1) 100%
  );
  background: -webkit-gradient(
    left top,
    right top,
    color-stop(0%, rgba(255, 255, 255, 0)),
    color-stop(20%, rgba(255, 255, 255, 1)),
    color-stop(100%, rgba(255, 255, 255, 1))
  );
  background: -webkit-linear-gradient(
    left,
    rgba(255, 255, 255, 0) 0%,
    rgba(255, 255, 255, 1) 20%,
    rgba(255, 255, 255, 1) 100%
  );
  background: -o-linear-gradient(
    left,
    rgba(255, 255, 255, 0) 0%,
    rgba(255, 255, 255, 1) 20%,
    rgba(255, 255, 255, 1) 100%
  );
  background: -ms-linear-gradient(
    left,
    rgba(255, 255, 255, 0) 0%,
    rgba(255, 255, 255, 1) 20%,
    rgba(255, 255, 255, 1) 100%
  );
  background: linear-gradient(
    to right,
    rgba(255, 255, 255, 0) 0%,
    rgba(255, 255, 255, 1) 20%,
    rgba(255, 255, 255, 1) 100%
  );
  filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#ffffff', endColorstr='#ffffff', GradientType=1 );
}
.card-image {
  max-height: 80px !important;
}
.quantity {
  max-width: 50px;
}

/* Chrome, Safari, Edge, Opera */
input::-webkit-outer-spin-button,
input::-webkit-inner-spin-button {
  -webkit-appearance: none;
  margin: 0;
}
.remove-to-cart,
.add-to-cart {
  cursor: pointer;
}

/* Firefox */
input[type="number"] {
  -moz-appearance: textfield;
}
@media (min-width: 1200px) {
  .bag-icon-box {
    right: calc(((100% - 1200px) / 2) + 140px);
  }
}
@media (max-width: 1200px) {
  .bag-icon-box {
    right: calc(((100% - 1200px) / 2) + 140px);
  }
}
@media (max-width: 992px) {
  .bag-icon-box {
    right: 25px;
  }
}
@media (max-width: 767px) {
  .card-icons {
    bottom: 0;
  }
}
@media (max-width: 575px) {
  .product-card:hover {
    transform: scale(0.95);
  }
  .card-icons {
    top: 0;
  }
}
</style>

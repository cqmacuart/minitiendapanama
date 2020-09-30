<template>
  <div>
    <section class="container-md p-0 p-sm-3 col-12 d-flex flex-wrap mt-5">
      <div
        class="col-12 mb-4 mb-sm-1 d-flex flex-wrap"
        v-for="(item, index) in product"
        :key="index"
      >
        <section class="col-12 col-sm-6 p-0 m-0 text-center">
          <img
            :src="`/img/products/${item.image}`"
            alt
            class="img-fluid"
            v-if="item.image"
          />
        </section>
        <section class="col-12 col-sm-6 p-0 m-0 text-center p-3 rounded border">
          <h2
            class="text-muted m-0 text-left font-weight-bolder"
            v-text="item.nombre"
          ></h2>
          <small>
            <p v-text="item.short_des" class="text-muted m-0 text-left"></p>
          </small>
          <hr />
          <p v-text="item.long_des" class="text-justify"></p>
          <hr />
          <h5
            class="font-weight-bolder text-primary text-left"
            v-text="currency + ' ' + item.price"
          ></h5>
          <a class="link p-2 remove-to-cart" @click.prevent="substo(item.id)">
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
            :value="item.qty"
            readonly
          />
          <a class="link p-2 add-to-cart" @click.prevent="addto(item.id)">
            <el-button
              icon="el-icon-plus"
              circle
              :class="'shadow-sm'"
            ></el-button>
            <!-- <span class="fas fa-plus text-muted"></span> -->
          </a>
          <hr />

          <router-link
            :to="{ name: 'cart' }"
            class="btn btn-primary btn-lg col-8 shadow-lg go-to-cart"
          >
            <span class="fas fa-shopping-bag"></span>
            Ir al carrito
          </router-link>
        </section>
      </div>
    </section>
  </div>
</template>

<script>
export default {
  props: ["ruta", "id", "currency"],
  data() {
    return {
      fillSetting: {
        storename: "",
        mobile: "",
        city: "",
        country: "",
        image: "",
        currency: "",
      },
      shoppingCart: [],
      currentCat: [],
      productList: [],
      categoryName: "",
      categoryImage: "",
      categoryId: 1,
      cartCount: 0,
      //
      product: [],
      productName: "",
      productShort: "",
      productImage: "",
    };
  },
  computed: {},
  mounted() {
    this.checkingCartCount();
    this.getCurrenProduct();
    this.getAllSettings();
  },
  methods: {
    getCurrenProduct() {
      axios.get(`/products/${this.id}/edit`).then((response) => {
        fetch(`/cart/isincart/${response.data.id}`)
          .then((data) => data.json())
          .then((data) => {
            this.productName = response.data.nombre;
            this.productShort = response.data.short_des;
            this.productImage = response.data.image;
            this.product.push({
              id: response.data.id,
              nombre: response.data.nombre,
              short_des: response.data.short_des,
              long_des: response.data.long_des,
              image: response.data.image,
              price: response.data.price,
              qty: data.qty,
            });
          });
      });
    },
    //   Carts Functions >>>>>
    addto(productId) {
      let me = this;
      axios.get(`/addToCart/${productId}`).then((response) => {
        this.cartCount = response.data.count;
        this.chekingCartProducts(response.data.id, 1);
        const producto = this.product.find(
          (producto) => producto.id == response.data.id
        );
        if (producto) {
          this.product.map(function (x, y) {
            if (producto.value == x.value) {
              me.product[y].flag = true;
              me.product[y].qty = me.product[y].qty + 1;
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
        const producto = this.product.find(
          (producto) => producto.id == response.data.id
        );
        if (producto) {
          this.product.map(function (x, y) {
            if (producto.value == x.value) {
              me.product[y].flag = response.data.qty == 0 ? false : true;
              me.product[y].qty = response.data.qty;
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
  },
};
</script>

<style>
.product-card,
.go-to-cart {
  transition: all 0.3s ease-out;
}
.product-card:hover,
.go-to-cart:hover {
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
  max-height: 150px;
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

<template>
  <div>
    <div class="d-none bg-light col-12 d-sm-flex justify-content-around px-0 border-bottom">
      <label for class="m-1">
        <span class="fab fa-whatsapp"></span>
        <a href="#" class="badge badge-success">{{mobile}}</a>
      </label>
      <label for class="m-1">
        <span class="fas fa-map-marker-alt"></span>
        {{city}},{{country}}
      </label>
    </div>
    <nav class="navbar bg-white p-0 d-flex flex-column flex-sm-row">
      <div class="d-inline p-2">
        <router-link :to="'/'">
          <img
            :src="`/img/settings/${image}`"
            width="90px"
            :alt="storename"
            class="img-fluid"
            v-if="image"
          />
        </router-link>
      </div>
      <form class="form-inline p-3 p-sm-1" :action="`${ruta}/${inputSearch}`">
        <div class="container h-100">
          <div class="d-flex justify-content-center h-100">
            <div class="searchbar">
              <input
                class="search_input"
                :class="{search_input_active:toogleSearch}"
                type="text"
                name
                placeholder="Buscar Producto..."
                v-model="inputSearch"
              />
              <a
                href="#"
                class="search_icon"
                :class="{search_icon_active:toogleSearch}"
                @click.prevent="toogleSearchBar"
              >
                <i class="fas fa-search"></i>
              </a>
            </div>
          </div>
        </div>
      </form>
    </nav>
  </div>
</template>

<script>
export default {
  props: ["ruta", "city", "country", "image", "storename", "mobile"],
  data() {
    return {
      toogleSearch: false,
      searchReady: false,
      inputSearch: "",
    };
  },
  methods: {
    toogleSearchBar: function () {
      this.toogleSearch = !this.toogleSearch;
    },
  },
};
</script>

<style>
</style>

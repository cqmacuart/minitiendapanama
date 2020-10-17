<template>
  <section class="container col-12 mt-3">
    <div class="col-12 border rounded pt-2 bg-white pb-5">
      <el-tabs v-model="activeName" @tab-click="handleClick">
        <el-tab-pane label="Ajustes Básicos" name="first">
          <h2 class="m-0 admin-title">Información de la tienda</h2>
          <hr class="mt-0" />
          <!-- Form -->
          <section
            class="col-12 d-flex row flex-column flex-sm-row m-auto px-0 px-sm-2"
          >
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
                      style="width: 1%"
                    >
                      Nombre:
                    </th>
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
                      style="width: 1%"
                    >
                      País:
                    </th>
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
                      style="width: 1%"
                    >
                      Ciudad:
                    </th>
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
                      style="width: 1%"
                    >
                      Dirección:
                    </th>
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
                      style="width: 1%"
                    >
                      Email:
                    </th>
                    <td class>
                      <input
                        type="email"
                        class="form-control form-control-sm border-0"
                        placeholder="administracion@example.com"
                        v-model="fillSetting.email"
                      />
                    </td>
                  </tr>
                  <tr>
                    <th
                      class="d-none d-sm-table-cell text-nowrap text-left align-bottom align-sm-middle"
                      style="width: 1%"
                    >
                      Teléfono:
                    </th>
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
                      style="width: 1%"
                    >
                      Moneda:
                    </th>
                    <td class>
                      <input
                        type="text"
                        class="form-control form-control-sm border-0"
                        placeholder="USD - EUR - AUS - CAN (SEGUN ISO 4217)"
                        v-model="fillSetting.currency"
                      />
                    </td>
                  </tr>
                  <tr>
                    <th
                      class="d-none d-sm-table-cell text-nowrap text-left align-bottom align-sm-middle"
                      style="width: 1%"
                    >
                      FB Business:
                    </th>
                    <td class="align-bottom text-nowrap overflow-hidden">
                      <button
                        class="btn btn-sm btn-outline-dark mr-1"
                        v-clipboard:copy="message"
                        v-clipboard:success="onCopy"
                      >
                        <span class="fas fa-copy"></span>
                      </button>
                      <label
                        for=""
                        class="form-label text-muted m-0"
                        style="max-width: 300px"
                        >{{
                          ruta
                        }}/files/fb_business/facebook_business_product_csv.csv</label
                      >
                    </td>
                  </tr>

                  <tr class="text-left">
                    <th
                      class="d-none d-sm-table-cell align-bottom align-sm-middle"
                    >
                      Imagen:
                    </th>
                    <td>
                      <div class="custom-file">
                        <input
                          type="file"
                          class="custom-file-input form-control-sm border-0 border-bottom"
                          name="customFile"
                          id="customFile"
                          v-loading.fullscreen.lock="fullscreenLoading"
                          @change="getImageFile"
                        />
                        <label
                          class="custom-file-label text-left border-0 border-bottom"
                          for="customFile"
                          v-text="
                            fillSetting.image.length
                              ? fillSetting.image
                              : 'Logo del Sistema'
                          "
                        ></label>
                      </div>
                    </td>
                  </tr>
                  <tr>
                    <td colspan="2" class="text-right py-3">
                      <button
                        class="btn btn-sm btn-primary"
                        @click.prevent="setSettings"
                      >
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
        </el-tab-pane>
        <el-tab-pane label="Ajustes Avanzados" name="second">
          <h2 class="m-0 admin-title">Ajustes de colores</h2>
          <hr class="mt-0" />
          <!-- Form -->
          <section
            class="col-12 d-flex row flex-column flex-sm-row m-auto px-0 px-sm-2"
          >
            <div class="col-12 px-0 px-sm-2">
              <!-- CABECERA -->
              <section class="mb-4">
                <hgroup>
                  <h4>Cabecera</h4>
                </hgroup>
                <div class="col-12 row">
                  <div class="col-12 col-sm-3 p-0">
                    <div class="card h-100">
                      <div class="card-body p-1">
                        <div
                          class="d-flex justify-content-between align-items-center"
                        >
                          <label for="">Color de fondo:</label>
                          <el-color-picker
                            v-model="fillColors.hBgColor"
                            show-alpha
                          ></el-color-picker>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="col-12 col-sm-3 p-0">
                    <div class="card h-100">
                      <div class="card-body p-1">
                        <div
                          class="d-flex justify-content-between align-items-center"
                        >
                          <label for="">Color de Texto:</label>
                          <el-color-picker
                            v-model="fillColors.hTxColor"
                            show-alpha
                          ></el-color-picker>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="col-12 col-sm-3 p-0">
                    <div class="card h-100">
                      <div class="card-body p-1">
                        <div
                          class="d-flex justify-content-between align-items-center"
                        >
                          <label for="">Color Secundario:</label>
                          <el-color-picker
                            v-model="fillColors.hScColor"
                            show-alpha
                          ></el-color-picker>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="col-12 col-sm-3 p-0">
                    <div class="card h-100">
                      <div class="card-body p-1">
                        <div
                          class="d-flex justify-content-between align-items-center"
                        >
                          <label for="">Color Terciario:</label>
                          <el-color-picker
                            v-model="fillColors.hTrColor"
                            show-alpha
                          ></el-color-picker>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </section>
              <!-- BARRA DE NAVEGACION -->
              <section class="mb-4">
                <hgroup>
                  <h4>Barra de Navegación</h4>
                </hgroup>
                <div class="col-12 row">
                  <div class="col-12 col-sm-4 p-0">
                    <div class="card">
                      <div class="card-body p-1">
                        <div
                          class="d-flex justify-content-between align-items-center"
                        >
                          <label for="">Color de fondo: </label>
                          <el-color-picker
                            v-model="fillColors.nvBgColor"
                            show-alpha
                          ></el-color-picker>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="col-12 col-sm-4 p-0">
                    <div class="card">
                      <div class="card-body p-1">
                        <div
                          class="d-flex justify-content-between align-items-center"
                        >
                          <label for="">Color de Texto: </label>
                          <el-color-picker
                            v-model="fillColors.nvTxColor"
                            show-alpha
                          ></el-color-picker>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="col-12 col-sm-4 p-0">
                    <div class="card">
                      <div class="card-body p-1">
                        <div
                          class="d-flex justify-content-between align-items-center"
                        >
                          <label for="">Color de Borde: </label>
                          <el-color-picker
                            v-model="fillColors.nvBrColor"
                            show-alpha
                          ></el-color-picker>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </section>
              <!-- PIE DE PAGINA -->
              <section class="mb-4">
                <hgroup>
                  <h4>Pié de Página</h4>
                </hgroup>
                <div class="col-12 row">
                  <div class="col-12 col-sm-4 p-0">
                    <div class="card">
                      <div class="card-body p-1">
                        <div
                          class="d-flex justify-content-between align-items-center"
                        >
                          <label for="">Color de fondo: </label>
                          <el-color-picker
                            v-model="fillColors.fBgColor"
                            show-alpha
                          ></el-color-picker>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="col-12 col-sm-4 p-0">
                    <div class="card">
                      <div class="card-body p-1">
                        <div
                          class="d-flex justify-content-between align-items-center"
                        >
                          <label for="">Color de Texto: </label>
                          <el-color-picker
                            v-model="fillColors.fTxColor"
                            show-alpha
                          ></el-color-picker>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </section>
              <!-- ICONO COMPRAS -->
              <section class="mb-4">
                <hgroup>
                  <h4>Ícono de Compras</h4>
                </hgroup>
                <div class="col-12 row">
                  <div class="col-12 col-sm-3 p-0">
                    <div class="card">
                      <div class="card-body p-1">
                        <div
                          class="d-flex justify-content-between align-items-center"
                        >
                          <label for="">Color de fondo: </label>
                          <el-color-picker
                            v-model="fillColors.sbBgColor"
                            show-alpha
                          ></el-color-picker>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="col-12 col-sm-3 p-0">
                    <div class="card">
                      <div class="card-body p-1">
                        <div
                          class="d-flex justify-content-between align-items-center"
                        >
                          <label for="">Color de Ícono: </label>
                          <el-color-picker
                            v-model="fillColors.sbIcColor"
                            show-alpha
                          ></el-color-picker>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="col-12 col-sm-3 p-0">
                    <div class="card">
                      <div class="card-body p-1">
                        <div
                          class="d-flex justify-content-between align-items-center"
                        >
                          <label for="">Color Secundario: </label>
                          <el-color-picker
                            v-model="fillColors.sbScColor"
                            show-alpha
                          ></el-color-picker>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="col-12 col-sm-3 p-0">
                    <div class="card">
                      <div class="card-body p-1">
                        <div
                          class="d-flex justify-content-between align-items-center"
                        >
                          <label for="">Color de Texto: </label>
                          <el-color-picker
                            v-model="fillColors.sbTxColor"
                            show-alpha
                          ></el-color-picker>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </section>
              <!-- ICONO HOME -->
              <section class="mb-4">
                <hgroup>
                  <h4>Ícono Home</h4>
                </hgroup>
                <div class="col-12 row">
                  <div class="col-12 col-sm-4 p-0">
                    <div class="card">
                      <div class="card-body p-1">
                        <div
                          class="d-flex justify-content-between align-items-center"
                        >
                          <label for="">Color de fondo: </label>
                          <el-color-picker
                            v-model="fillColors.hmBgColor"
                            show-alpha
                          ></el-color-picker>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="col-12 col-sm-4 p-0">
                    <div class="card">
                      <div class="card-body p-1">
                        <div
                          class="d-flex justify-content-between align-items-center"
                        >
                          <label for="">Color de Texto: </label>
                          <el-color-picker
                            v-model="fillColors.hmTxColor"
                            show-alpha
                          ></el-color-picker>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </section>
              <!-- CATEGORIAS -->
              <section class="mb-4">
                <hgroup>
                  <h4>Categorías</h4>
                </hgroup>
                <div class="col-12 row">
                  <div class="col-12 col-sm-4 p-0">
                    <div class="card">
                      <div class="card-body p-1">
                        <div
                          class="d-flex justify-content-between align-items-center"
                        >
                          <label for="">Color de fondo: </label>
                          <el-color-picker
                            v-model="fillColors.ctBgColor"
                            show-alpha
                          ></el-color-picker>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="col-12 col-sm-4 p-0">
                    <div class="card">
                      <div class="card-body p-1">
                        <div
                          class="d-flex justify-content-between align-items-center"
                        >
                          <label for="">Color de Texto: </label>
                          <el-color-picker
                            v-model="fillColors.ctTxColor"
                            show-alpha
                          ></el-color-picker>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </section>
              <!-- PRODUCTOS -->
              <section class="mb-4">
                <hgroup>
                  <h4>Productos</h4>
                </hgroup>
                <div class="col-12 row">
                  <div class="col-12 col-sm-4 p-0">
                    <div class="card">
                      <div class="card-body p-1">
                        <div
                          class="d-flex justify-content-between align-items-center"
                        >
                          <label for="">Color de Precio: </label>
                          <el-color-picker
                            v-model="fillColors.pPrColor"
                            show-alpha
                          ></el-color-picker>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="col-12 col-sm-4 p-0">
                    <div class="card">
                      <div class="card-body p-1">
                        <div
                          class="d-flex justify-content-between align-items-center"
                        >
                          <label for="">Color de Añadido: </label>
                          <el-color-picker
                            v-model="fillColors.pIcColor"
                            show-alpha
                          ></el-color-picker>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </section>
              <hr />
              <div class="col-12 text-right">
                <button
                  class="btn btn-sm"
                  :class="isColors > 0 ? ' btn-primary' : ' btn-success'"
                  @click.prevent="setColors"
                  v-loading.fullscreen.lock="fullscreenLoading"
                >
                  <span class="fas fa-check"></span>
                  <span v-text="isColors > 0 ? 'Establecer' : 'Guardar'"></span>
                </button>
              </div>
            </div>
          </section>
          <!-- End Form -->
        </el-tab-pane>
        <el-tab-pane label="Imagenes PWA" name="third">
          <h2 class="m-0 admin-title">Imagenes de la aplicación</h2>
          <hr />
          <p>
            Las siguientes im&aacute;genes se requieren para la
            personalizaci&oacute;n de los logos mostrados al iniciar la
            aplicaci&oacute;n instalada en la pantalla de su dispositivo.
          </p>
          <small>
            <span class="text-muted"
              >Favor apegarse a las dimensiones requeridas y al formato PNG para
              una mejor experiencia y personalizaci&oacute;n</span
            >
          </small>
          <hr class="mt-0" />
          <!-- Form -->
          <section
            class="col-12 d-flex row flex-column flex-sm-row m-auto px-0 px-sm-2"
          >
            <div class="col-12 col-md-8 px-0 px-sm-2">
              <table class="table table-sm">
                <tbody>
                  <tr class="text-left">
                    <th
                      class="d-none d-sm-table-cell align-bottom align-sm-middle"
                    >
                      icon 72x72 px:
                    </th>
                    <td>
                      <div class="custom-file">
                        <input
                          type="file"
                          class="custom-file-input form-control-sm border-0 border-bottom"
                          name="icon-72x72.png"
                          v-loading.fullscreen.lock="fullscreenLoading"
                          accept="image/png"
                          @change="getResponsiveFile"
                        />
                        <label
                          class="custom-file-label text-left border-0 border-bottom"
                          for="customFile"
                          >'72x72 px'</label
                        >
                      </div>
                    </td>
                  </tr>
                  <!--  -->
                  <tr class="text-left">
                    <th
                      class="d-none d-sm-table-cell align-bottom align-sm-middle"
                    >
                      icon 96x96 px:
                    </th>
                    <td>
                      <div class="custom-file">
                        <input
                          type="file"
                          class="custom-file-input form-control-sm border-0 border-bottom"
                          name="icon-96x96.png"
                          v-loading.fullscreen.lock="fullscreenLoading"
                          accept="image/png"
                          @change="getResponsiveFile"
                        />
                        <label
                          class="custom-file-label text-left border-0 border-bottom"
                          for="customFile"
                          >'96x96 px'</label
                        >
                      </div>
                    </td>
                  </tr>
                  <!--  -->
                  <tr class="text-left">
                    <th
                      class="d-none d-sm-table-cell align-bottom align-sm-middle"
                    >
                      icon 128x128 px:
                    </th>
                    <td>
                      <div class="custom-file">
                        <input
                          type="file"
                          class="custom-file-input form-control-sm border-0 border-bottom"
                          name="icon-128x128.png"
                          v-loading.fullscreen.lock="fullscreenLoading"
                          accept="image/png"
                          @change="getResponsiveFile"
                        />
                        <label
                          class="custom-file-label text-left border-0 border-bottom"
                          for="customFile"
                          >'128x128 px'</label
                        >
                      </div>
                    </td>
                  </tr>
                  <!--  -->
                  <tr class="text-left">
                    <th
                      class="d-none d-sm-table-cell align-bottom align-sm-middle"
                    >
                      icon 144x144 px:
                    </th>
                    <td>
                      <div class="custom-file">
                        <input
                          type="file"
                          class="custom-file-input form-control-sm border-0 border-bottom"
                          name="icon-144x144.png"
                          v-loading.fullscreen.lock="fullscreenLoading"
                          accept="image/png"
                          @change="getResponsiveFile"
                        />
                        <label
                          class="custom-file-label text-left border-0 border-bottom"
                          for="customFile"
                          >'144x144 px'</label
                        >
                      </div>
                    </td>
                  </tr>
                  <!--  -->
                  <tr class="text-left">
                    <th
                      class="d-none d-sm-table-cell align-bottom align-sm-middle"
                    >
                      icon 152x152 px:
                    </th>
                    <td>
                      <div class="custom-file">
                        <input
                          type="file"
                          class="custom-file-input form-control-sm border-0 border-bottom"
                          name="icon-152x152.png"
                          v-loading.fullscreen.lock="fullscreenLoading"
                          accept="image/png"
                          @change="getResponsiveFile"
                        />
                        <label
                          class="custom-file-label text-left border-0 border-bottom"
                          for="customFile"
                          >'152x152 px'</label
                        >
                      </div>
                    </td>
                  </tr>
                  <!--  -->
                  <tr class="text-left">
                    <th
                      class="d-none d-sm-table-cell align-bottom align-sm-middle"
                    >
                      icon 192x192 px:
                    </th>
                    <td>
                      <div class="custom-file">
                        <input
                          type="file"
                          class="custom-file-input form-control-sm border-0 border-bottom"
                          name="icon-192x192.png"
                          v-loading.fullscreen.lock="fullscreenLoading"
                          accept="image/png"
                          @change="getResponsiveFile"
                        />
                        <label
                          class="custom-file-label text-left border-0 border-bottom"
                          for="customFile"
                          >'192x192 px'</label
                        >
                      </div>
                    </td>
                  </tr>
                  <!--  -->
                  <tr class="text-left">
                    <th
                      class="d-none d-sm-table-cell align-bottom align-sm-middle"
                    >
                      icon 384x384 px:
                    </th>
                    <td>
                      <div class="custom-file">
                        <input
                          type="file"
                          class="custom-file-input form-control-sm border-0 border-bottom"
                          name="icon-384x384.png"
                          v-loading.fullscreen.lock="fullscreenLoading"
                          accept="image/png"
                          @change="getResponsiveFile"
                        />
                        <label
                          class="custom-file-label text-left border-0 border-bottom"
                          for="customFile"
                          >'384x384 px'</label
                        >
                      </div>
                    </td>
                  </tr>
                  <!--  -->
                  <tr class="text-left">
                    <th
                      class="d-none d-sm-table-cell align-bottom align-sm-middle"
                    >
                      icon 512x512 px:
                    </th>
                    <td>
                      <div class="custom-file">
                        <input
                          type="file"
                          class="custom-file-input form-control-sm border-0 border-bottom"
                          name="icon-512x512.png"
                          v-loading.fullscreen.lock="fullscreenLoading"
                          accept="image/png"
                          @change="getResponsiveFile"
                        />
                        <label
                          class="custom-file-label text-left border-0 border-bottom"
                          for="customFile"
                          >'512x512 px'</label
                        >
                      </div>
                    </td>
                  </tr>
                  <!--  -->
                  <tr class="text-left">
                    <th
                      class="d-none d-sm-table-cell align-bottom align-sm-middle"
                    >
                      splash 640x1136 px:
                    </th>
                    <td>
                      <div class="custom-file">
                        <input
                          type="file"
                          class="custom-file-input form-control-sm border-0 border-bottom"
                          name="splash-640x1136.png"
                          v-loading.fullscreen.lock="fullscreenLoading"
                          accept="image/png"
                          @change="getResponsiveFile"
                        />
                        <label
                          class="custom-file-label text-left border-0 border-bottom"
                          for="customFile"
                          >'640x1136 px'</label
                        >
                      </div>
                    </td>
                  </tr>
                  <!--  -->
                  <tr class="text-left">
                    <th
                      class="d-none d-sm-table-cell align-bottom align-sm-middle"
                    >
                      splash 750x1334 px:
                    </th>
                    <td>
                      <div class="custom-file">
                        <input
                          type="file"
                          class="custom-file-input form-control-sm border-0 border-bottom"
                          name="splash-750x1334.png"
                          v-loading.fullscreen.lock="fullscreenLoading"
                          accept="image/png"
                          @change="getResponsiveFile"
                        />
                        <label
                          class="custom-file-label text-left border-0 border-bottom"
                          for="customFile"
                          >'750x1334 px'</label
                        >
                      </div>
                    </td>
                  </tr>
                  <!--  -->
                  <tr class="text-left">
                    <th
                      class="d-none d-sm-table-cell align-bottom align-sm-middle"
                    >
                      splash 828x1792 px:
                    </th>
                    <td>
                      <div class="custom-file">
                        <input
                          type="file"
                          class="custom-file-input form-control-sm border-0 border-bottom"
                          name="splash-828x1792.png"
                          v-loading.fullscreen.lock="fullscreenLoading"
                          accept="image/png"
                          @change="getResponsiveFile"
                        />
                        <label
                          class="custom-file-label text-left border-0 border-bottom"
                          for="customFile"
                          >'828x1792 px'</label
                        >
                      </div>
                    </td>
                  </tr>
                  <!--  -->
                  <tr class="text-left">
                    <th
                      class="d-none d-sm-table-cell align-bottom align-sm-middle"
                    >
                      splash 1125x2436 px:
                    </th>
                    <td>
                      <div class="custom-file">
                        <input
                          type="file"
                          class="custom-file-input form-control-sm border-0 border-bottom"
                          name="splash-1125x2436.png"
                          v-loading.fullscreen.lock="fullscreenLoading"
                          accept="image/png"
                          @change="getResponsiveFile"
                        />
                        <label
                          class="custom-file-label text-left border-0 border-bottom"
                          for="customFile"
                          >'1125x2436 px'</label
                        >
                      </div>
                    </td>
                  </tr>
                  <!--  -->
                  <tr class="text-left">
                    <th
                      class="d-none d-sm-table-cell align-bottom align-sm-middle"
                    >
                      splash 1242x2208 px:
                    </th>
                    <td>
                      <div class="custom-file">
                        <input
                          type="file"
                          class="custom-file-input form-control-sm border-0 border-bottom"
                          name="splash-1242x2208.png"
                          v-loading.fullscreen.lock="fullscreenLoading"
                          accept="image/png"
                          @change="getResponsiveFile"
                        />
                        <label
                          class="custom-file-label text-left border-0 border-bottom"
                          for="customFile"
                          >'1242x2208 px'</label
                        >
                      </div>
                    </td>
                  </tr>
                  <!--  -->
                  <tr class="text-left">
                    <th
                      class="d-none d-sm-table-cell align-bottom align-sm-middle"
                    >
                      splash 1242x2688 px:
                    </th>
                    <td>
                      <div class="custom-file">
                        <input
                          type="file"
                          class="custom-file-input form-control-sm border-0 border-bottom"
                          name="splash-1242x2688.png"
                          v-loading.fullscreen.lock="fullscreenLoading"
                          accept="image/png"
                          @change="getResponsiveFile"
                        />
                        <label
                          class="custom-file-label text-left border-0 border-bottom"
                          for="customFile"
                          >'1242x2688 px'</label
                        >
                      </div>
                    </td>
                  </tr>
                  <!--  -->
                  <tr class="text-left">
                    <th
                      class="d-none d-sm-table-cell align-bottom align-sm-middle"
                    >
                      splash 1536x2048 px:
                    </th>
                    <td>
                      <div class="custom-file">
                        <input
                          type="file"
                          class="custom-file-input form-control-sm border-0 border-bottom"
                          name="splash-1536x2048.png"
                          v-loading.fullscreen.lock="fullscreenLoading"
                          accept="image/png"
                          @change="getResponsiveFile"
                        />
                        <label
                          class="custom-file-label text-left border-0 border-bottom"
                          for="customFile"
                          >'1536x2048 px'</label
                        >
                      </div>
                    </td>
                  </tr>
                  <!--  -->
                  <tr class="text-left">
                    <th
                      class="d-none d-sm-table-cell align-bottom align-sm-middle"
                    >
                      splash 1668x2224 px:
                    </th>
                    <td>
                      <div class="custom-file">
                        <input
                          type="file"
                          class="custom-file-input form-control-sm border-0 border-bottom"
                          name="splash-1668x2224.png"
                          v-loading.fullscreen.lock="fullscreenLoading"
                          accept="image/png"
                          @change="getResponsiveFile"
                        />
                        <label
                          class="custom-file-label text-left border-0 border-bottom"
                          for="customFile"
                          >'1668x2224 px'</label
                        >
                      </div>
                    </td>
                  </tr>
                  <!--  -->
                  <tr class="text-left">
                    <th
                      class="d-none d-sm-table-cell align-bottom align-sm-middle"
                    >
                      splash 1668x2388 px:
                    </th>
                    <td>
                      <div class="custom-file">
                        <input
                          type="file"
                          class="custom-file-input form-control-sm border-0 border-bottom"
                          name="splash-1668x2388.png"
                          v-loading.fullscreen.lock="fullscreenLoading"
                          accept="image/png"
                          @change="getResponsiveFile"
                        />
                        <label
                          class="custom-file-label text-left border-0 border-bottom"
                          for="customFile"
                          >'1668x2388 px'</label
                        >
                      </div>
                    </td>
                  </tr>
                  <!--  -->
                  <tr class="text-left">
                    <th
                      class="d-none d-sm-table-cell align-bottom align-sm-middle"
                    >
                      splash 2048x2732 px:
                    </th>
                    <td>
                      <div class="custom-file">
                        <input
                          type="file"
                          class="custom-file-input form-control-sm border-0 border-bottom"
                          name="splash-2048x2732.png"
                          v-loading.fullscreen.lock="fullscreenLoading"
                          accept="image/png"
                          @change="getResponsiveFile"
                        />
                        <label
                          class="custom-file-label text-left border-0 border-bottom"
                          for="customFile"
                          >'2048x2732 px'</label
                        >
                      </div>
                    </td>
                  </tr>
                  <!--  -->
                </tbody>
              </table>
            </div>
          </section>
          <!-- End Form -->
        </el-tab-pane>
        <el-tab-pane label="Pagos" name="fourth">
          <h2 class="m-0 admin-title">Pasarelas y M&eacute;todos de Pago</h2>
          <hr class="mt-0" />
          <!-- Form -->
          <section
            class="col-12 d-flex row flex-column flex-sm-row m-auto px-0 px-sm-2"
          >
            <div class="col-12 px-0 px-sm-2">
              <!-- CABECERA -->
              <section class="mb-4">
                <div>
                  <img :src="`${ruta}/img/payment/cod/cod.png`" alt="COD" />
                  <hr />
                </div>
                <div class="d-flex flex-wrap flex-column flex-sm-row">
                  <div class="col-12 col-sm-6 text-left pt-2 pt-sm-1">
                    <el-switch
                      v-model="cod_params.cod_param_1"
                      active-color="#13ce66"
                      inactive-color="#ff0000"
                      active-text="Habilitar"
                      inactive-text="Deshabilitar"
                      :class="'mt-2'"
                      @change="setCod"
                    >
                    </el-switch>
                  </div>
                </div>
              </section>
              <hr class="border-dark" />
            </div>
            <div class="col-12 px-0 px-sm-2">
              <!-- CABECERA -->
              <section class="mb-4">
                <div>
                  <img
                    :src="`${ruta}/img/payment/epayco/epayco_logo.png`"
                    alt="epayco"
                  />
                  <hr />
                </div>
                <div class="col-12 row">
                  <div class="input-group mb-3 col-12 col-sm-6">
                    <div class="input-group-prepend">
                      <span class="input-group-text">ID_CLIENTE</span>
                    </div>
                    <input
                      type="text"
                      class="form-control"
                      aria-describedby="basic-addon3"
                      v-model="epayco_params.epayco_param_1"
                    />
                  </div>
                  <div class="input-group mb-3 col-12 col-sm-6">
                    <div class="input-group-prepend">
                      <span class="input-group-text">P_KEY</span>
                    </div>
                    <input
                      type="text"
                      class="form-control"
                      aria-describedby="basic-addon3"
                      v-model="epayco_params.epayco_param_2"
                    />
                  </div>
                </div>
                <div class="col-12 row">
                  <div class="input-group mb-3 col-12 col-sm-6">
                    <div class="input-group-prepend">
                      <span class="input-group-text">PUBLIC_KEY</span>
                    </div>
                    <input
                      type="text"
                      class="form-control"
                      aria-describedby="basic-addon3"
                      v-model="epayco_params.epayco_param_3"
                    />
                  </div>
                  <div class="input-group mb-3 col-12 col-sm-6">
                    <div class="input-group-prepend">
                      <span class="input-group-text">PRIVATE_KEY</span>
                    </div>
                    <input
                      type="text"
                      class="form-control"
                      aria-describedby="basic-addon3"
                      v-model="epayco_params.epayco_param_4"
                    />
                  </div>
                </div>
                <div
                  class="d-flex justify-content-around flex-wrap flex-column flex-sm-row"
                >
                  <div class="col-12 col-sm-6 text-center pt-2 pt-sm-1">
                    <el-switch
                      v-model="epayco_params.epayco_param_5"
                      active-color="#13ce66"
                      inactive-color="#ff0000"
                      active-text="Habilitar"
                      inactive-text="Deshabilitar"
                      :class="'mt-2'"
                    >
                    </el-switch>
                  </div>
                  <div class="col-12 col-sm-6 text-center pt-2 pt-sm-1">
                    <el-switch
                      v-model="epayco_params.epayco_param_6"
                      active-color="#13ce66"
                      inactive-color="#ff0000"
                      active-text="Produccion"
                      inactive-text="Pruebas"
                      :class="'mt-2'"
                    >
                    </el-switch>
                  </div>
                </div>
              </section>
              <hr />
              <div class="col-12 text-right">
                <button
                  class="btn btn-sm"
                  :class="isColors > 0 ? ' btn-primary' : ' btn-success'"
                  @click.prevent="setEpayco"
                  v-loading.fullscreen.lock="fullscreenLoading"
                >
                  <span class="fas fa-check"></span>
                  <span v-text="'Guardar'"></span>
                </button>
              </div>
            </div>
          </section>
          <!-- End Form -->
        </el-tab-pane>
      </el-tabs>
    </div>
  </section>
</template>

<script>
export default {
  props: ["ruta"],
  data() {
    return {
      fillSetting: {
        storeid: "",
        storename: "",
        mobile: "",
        email: "",
        city: "",
        country: "",
        location: "",
        image: "",
        currency: "",
      },
      responsiveImgages: {
        _72px: "",
        _96px: "",
        _128px: "",
        _144px: "",
        _152px: "",
        _192px: "",
        _384px: "",
        _512px: "",
        _640px: "",
        _750px: "",
        _828px: "",
        _1125px: "",
        _1242_2208px: "",
        _1242_2688px: "",
        _1536px: "",
        _1668_2224px: "",
        _1668_2388px: "",
        _2048px: "",
      },
      form: new FormData(),
      settings: [],
      fullscreenLoading: false,
      errors: [],
      activeName: "first",
      //   colors vars
      fillColors: {
        colorId: "",
        //   Cabecera
        hBgColor: "",
        hTxColor: "",
        hScColor: "",
        hTrColor: "",
        //   Navegación
        nvBgColor: "",
        nvTxColor: "",
        nvBrColor: "",
        //   Footer
        fBgColor: "",
        fTxColor: "",
        // ICONO DE COMPRAS
        sbBgColor: "",
        sbIcColor: "",
        sbScColor: "",
        sbTxColor: "",
        // ICONO DE HOME
        hmBgColor: "",
        hmTxColor: "",
        //   Categorias
        ctBgColor: "",
        ctTxColor: "",
        //   Productos
        pPrColor: "",
        pIcColor: "",
      },
      isColors: null,
      message: `${this.ruta}/files/fb_business/facebook_business_product_csv.csv`,
      //   payment Methods
      //COD
      cod_params: {
        cod_param_1: false,
      },
      //EPAYCO
      epayco_params: {
        epayco_param_1: "",
        epayco_param_2: "",
        epayco_param_3: "",
        epayco_param_4: "",
        epayco_param_5: false,
        epayco_param_6: false,
      },
    };
  },
  mounted() {
    this.getAllSettings();
    this.getColorCount();
    //PASARELAS DE PAGO
    this.getEpayco();
    this.getCod();
  },
  methods: {
    onCopy: function () {
      this.$toastr.info("Copiado en el portapapeles");
    },
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
          this.fillSetting.email = response.data.email;
        })
        .catch((error) => {
          //no estas autenticado
          if (error.response.status == 401) {
            this.$toastr.error("Error " + error.response.status);
          }
        });
    },
    // CONSTRUCTORES <<<
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
          this.fillColors.colorId = element.id;
          this.fillColors.hBgColor = element.hBgColor;
          this.fillColors.hTxColor = element.hTxColor;
          this.fillColors.hScColor = element.hScColor;
          this.fillColors.hTrColor = element.hTrColor;
          this.fillColors.nvBgColor = element.nvBgColor;
          this.fillColors.nvTxColor = element.nvTxColor;
          this.fillColors.nvBrColor = element.nvBrColor;
          this.fillColors.fBgColor = element.fBgColor;
          this.fillColors.fTxColor = element.fTxColor;
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
    },
    setSettings: function () {
      if (this.validate()) {
        this.fullscreenLoading = true;
        const params = {
          storename: this.fillSetting.storename,
          mobile: this.fillSetting.mobile,
          location: this.fillSetting.location,
          image: this.fillSetting.image,
          currency: this.fillSetting.currency,
          country: this.fillSetting.country,
          city: this.fillSetting.city,
          email: this.fillSetting.email,
        };
        axios
          .put(`/admin/settings/${this.fillSetting.storeid}`, params)
          .then((response) => {
            if (response.data) {
              this.fullscreenLoading = false;
              this.$toastr.success("El sistema se ha actualizado");
              this.$router.push("/admin");
            }
          });
      } else {
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
            timeOut: "5000",
            extendedTimeOut: "5000",
            showEasing: "swing",
            hideEasing: "linear",
            showMethod: "fadeIn",
            hideMethod: "fadeOut",
          });
        });
      }
    },
    getImageFile: function (e) {
      this.fillSetting.image = e.target.files[0];
      this.saveImageFile();
    },
    getResponsiveFile: function (e) {
      if (e.target.files[0].size < 1000000) {
        this.fullscreenLoading = true;
        this.form.append("file", e.target.files[0]);
        this.form.append("text", e.target.name);
        const config = { headers: { "Content-Type": "multipart/form-data" } };
        let url = "/admin/settings/saveResponsiveFile";
        axios.post(url, this.form, config).then((response) => {
          if (response.status == 200) {
            this.$toastr.success(
              "Imagen de " + e.target.name + " añadida con éxito"
            );
          } else {
            this.$toastr.error("Ha ocurrido un error al cargar la imagen");
          }
          this.fullscreenLoading = false;
        });
      } else {
        this.$toastr.error("La imagen no debe exceder 1MB de espacio");
      }
    },
    saveImageFile() {
      this.fullscreenLoading = true;
      this.form.append("file", this.fillSetting.image);
      const config = { headers: { "Content-Type": "multipart/form-data" } };
      let url = "/admin/settings/saveFile";
      axios.post(url, this.form, config).then((response) => {
        this.fillSetting.image = response.data;
        this.fullscreenLoading = false;
      });
    },
    validate() {
      let pattern = new RegExp(/\+[0-9]*/);
      this.errors = [];
      let check = true;
      if (this.fillSetting.storename.length == 0) {
        this.errors.push({ error: "El nombre de la tienda es requerido." });
        check = false;
      }
      if (this.fillSetting.country.length == 0) {
        this.errors.push({ error: "El país es requerido." });
        check = false;
      }
      if (this.fillSetting.city.length == 0) {
        this.errors.push({ error: "La ciudad es requerida." });
        check = false;
      }
      if (this.fillSetting.location.length == 0) {
        this.errors.push({ error: "La dirección es requerida." });
        check = false;
      }
      if (this.fillSetting.mobile.length == 0) {
        this.errors.push({ error: "El teléfono móvil es requerido." });
        check = false;
      }
      if (!pattern.test(this.fillSetting.mobile)) {
        this.errors.push({ error: "El número de teléfono no es válido." });
        check = false;
      }
      if (this.fillSetting.currency.length == 0) {
        this.errors.push({ error: "La moneda es requerida." });
        check = false;
      }
      if (this.fillSetting.image.length == 0) {
        this.errors.push({ error: "El logo de la tienda es necesario." });
        check = false;
      }
      return check;
    },
    handleClick(tab, event) {},
    setColors: function () {
      this.fullscreenLoading = true;
      const params = {
        hBgColor: this.fillColors.hBgColor,
        hTxColor: this.fillColors.hTxColor,
        hScColor: this.fillColors.hScColor,
        hTrColor: this.fillColors.hTrColor,
        nvBgColor: this.fillColors.nvBgColor,
        nvTxColor: this.fillColors.nvTxColor,
        nvBrColor: this.fillColors.nvBrColor,
        fBgColor: this.fillColors.fBgColor,
        fTxColor: this.fillColors.fTxColor,
        sbBgColor: this.fillColors.sbBgColor,
        sbIcColor: this.fillColors.sbIcColor,
        sbScColor: this.fillColors.sbScColor,
        sbTxColor: this.fillColors.sbTxColor,
        hmBgColor: this.fillColors.hmBgColor,
        hmTxColor: this.fillColors.hmTxColor,
        ctBgColor: this.fillColors.ctBgColor,
        ctTxColor: this.fillColors.ctTxColor,
        pPrColor: this.fillColors.pPrColor,
        pIcColor: this.fillColors.pIcColor,
      };
      if (this.isColors > 0) {
        axios
          .put(`/colors/${this.fillColors.colorId}`, params)
          .then((response) => {
            if (response.data) {
              this.$toastr.info("El producto se ha actualizado exitosamente");
              this.fullscreenLoading = false;
            }
          });
      } else {
        axios.post("/colors", params).then((response) => {
          if (response.data) {
            this.$toastr.success("Colores guardados");
            this.getColorCount();
            this.fullscreenLoading = false;
          }
        });
      }
    },
    // PASARELAS DE PAGO Y CONFIGURACIONES
    //COD
    setCod() {
      this.fullscreenLoading = true;
      const params = {
        cod_param_1: this.cod_params.cod_param_1,
      };
      axios.post(`/admin/cod`, params).then((response) => {
        if (response.data) {
          this.$toastr.info("Hecho!!");
          this.fullscreenLoading = false;
        }
      });
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
    // EPAYCO
    setEpayco() {
      this.fullscreenLoading = true;
      const params = {
        epayco_param_1: this.epayco_params.epayco_param_1,
        epayco_param_2: this.epayco_params.epayco_param_2,
        epayco_param_3: this.epayco_params.epayco_param_3,
        epayco_param_4: this.epayco_params.epayco_param_4,
        epayco_param_5: this.epayco_params.epayco_param_5,
        epayco_param_6: this.epayco_params.epayco_param_6,
      };
      axios.post(`/admin/epayco`, params).then((response) => {
        if (response.data) {
          this.$toastr.info("Hecho!!");
          this.fullscreenLoading = false;
        }
      });
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
          this.epayco_params.epayco_param_6 =
            response.data[5].value == 1 ? true : false;
        }
      });
      this.fullscreenLoading = false;
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

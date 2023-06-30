<style scoped>
/* .center {
  line-height: 80vh;
  text-align: center;
}
.center h1 {
  line-height: 1.5;
  display: inline-block;
  vertical-align: middle;
  font-family: Cambria, Cochin, Georgia, Times, 'Times New Roman', serif ;
  font-size: 80px;
  color: rgb(26, 35, 126);
} */
/* img {
    display: block;
} */
img.logo {
  height: calc(100vh - 132px);
  width: 100%;
}

.content-logo {
  width: auto;
  min-height: calc(100vh - 132px);
}

.container {
  padding: 0;
}

</style>
<template>
    <div class="content-logo">
        <!-- <h1>ASPATEM</h1> -->
       <img class="logo" v-bind:src="require('../../../public/images/logo.jpg')" alt="imagen-logo-aspatem">
    </div>
</template>#inspire > div > main > div > div > div.center.container > img
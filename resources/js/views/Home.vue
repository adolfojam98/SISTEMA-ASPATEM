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
.container {
  width: 100vw; 
  height: 100vh; 
  overflow: hidden; 
}

.container img {
  width: 100%;
  height: 85%;
  object-fit: contain; 
}

</style>
<template>
    <div class="center container">
        <!-- <h1>ASPATEM</h1> -->
       <img class="scroll-off" v-bind:src="require('../../../public/images/logo.jpg')" alt="imagen-logo-aspatem">
    </div>
</template>#inspire > div > main > div > div > div.center.container > img
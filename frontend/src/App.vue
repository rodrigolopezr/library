<template>
  <v-app>
    <library-view />
  </v-app>
</template>

<script>
import LibraryView from "./components/LibraryView.vue";

export default {
  name: "App",
  components: {
    LibraryView,
  },
};
</script>

<style>
#app {
  font-family: Avenir, Helvetica, Arial, sans-serif;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
  text-align: center;
  color: #2c3e50;
  margin-top: 60px;
}
</style>

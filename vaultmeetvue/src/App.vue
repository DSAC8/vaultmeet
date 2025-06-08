<template>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com">
  <link href="https://fonts.googleapis.com/css2?family=Handlee&display=swap" rel="stylesheet">


 <div>
  <b-navbar toggleable="sm" type="light" variant="light">
    <b-navbar-toggle target="nav-text-collapse"></b-navbar-toggle>

    <b-navbar-brand>BootstrapVue</b-navbar-brand>

    <b-collapse id="nav-text-collapse" is-nav>
      <b-navbar-nav>
        <b-nav-text>Navbar text</b-nav-text>
      </b-navbar-nav>
    </b-collapse>
  </b-navbar>
</div>
</template>

<script setup>
import { ref } from 'vue'

const menuNyitva = ref(false)
</script>

<style scoped>

* {
  height: 100%;
  background: radial-gradient(ellipse at bottom, #121b27 0%, #050608 100%);
  overflow: hidden;
  font-family: "Handlee", cursive;
  font-weight:500;
  font-style: normal;
  color: #f1be8b;
}
</style>

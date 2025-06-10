<template>
  <div>
    <template v-if="!isAuthenticated">
      <loginView />
    </template>
    <template v-else>
      <link rel="preconnect" href="https://fonts.googleapis.com">
      <link rel="preconnect" href="https://fonts.gstatic.com">
      <link href="https://fonts.googleapis.com/css2?family=Handlee&display=swap" rel="stylesheet">
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

      <nav>
        <router-link to="/"><i class="fa-solid fa-house fa-2xl"></i></router-link> |
        <router-link to="/about"><i class="fa-solid fa-question fa-2xl"></i></router-link> |
       <router-link to="/newevent"><i class="fa-solid fa-plus fa-2xl"></i></router-link> |
        <router-link to="/eventlistview"><i class="fa-solid fa-calendar fa-2xl"></i></router-link> |
       <asd @click="logout" class="btn btn-link" style="color:#f1be8b;"><i class="fa-solid fa-right-from-bracket fa-2xl"></i></asd>
      </nav>
      <router-view />
    </template>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import axios from 'axios'
import loginView from './views/loginView.vue'

const isAuthenticated = ref(localStorage.getItem('isAuthenticated') === 'true')


window.addEventListener('storage', () => {
  isAuthenticated.value = localStorage.getItem('isAuthenticated') === 'true'
})

const logout = async () => {
  try {
    await axios.post('http://localhost:8000/api/logout', {}, { withCredentials: true })
  } catch (e) {
    console.error('Logout failed:', e)
  }
  localStorage.setItem('isAuthenticated', 'false')
  isAuthenticated.value = false
}
</script>

<style >

* {
  
  background: radial-gradient(ellipse at bottom, #121b27 0%, #050608 100%) no-repeat center center fixed;
  font-family: "Handlee", cursive;
  font-weight:500;
  font-style: normal;
  color: #f1be8b;
}

</style>
<style scoped>

nav {
  padding: 30px;
  height: 40px;
}

nav a {
  font-weight: bold;
  color: #2c3e50;
}

nav a.router-link-exact-active {
  color: #42b983;
  
}
</style>
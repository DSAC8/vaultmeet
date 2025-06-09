<template>
  <nav>
    <router-link v-if="!isAuthenticated" to="/login">Login</router-link>
    <template v-else>
      <router-link to="/">Home</router-link> |
      <router-link to="/about">About</router-link> |
      <a href="#" @click.prevent="logout">Logout</a>
    </template>
  </nav>
  <router-view/>
</template>

<script setup>
import { ref } from 'vue'
import axios from 'axios'
import { useRouter } from 'vue-router'

const router = useRouter()
const isAuthenticated = ref(localStorage.getItem('isAuthenticated') === 'true')

const logout = async () => {
  try {
    await axios.post('http://127.0.0.1:8000/api/logout', {}, { withCredentials: true })  
    localStorage.removeItem('isAuthenticated')
    isAuthenticated.value = false
    router.push('/login')  
  } catch (error) {
    console.error('Logout error:', error)
    alert('Hiba a kijelentkezéskor.')
  }
}
</script>



<style>
#app {
  font-family: Avenir, Helvetica, Arial, sans-serif;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
  text-align: center;
  color: #2c3e50;
}

nav {
  padding: 30px;
}

nav a {
  font-weight: bold;
  color: #2c3e50;
}

nav a.router-link-exact-active {
  color: #42b983;
}
</style>

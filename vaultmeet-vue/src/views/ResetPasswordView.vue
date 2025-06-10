<template>
  <div class="reset-password">
    <h2>Új jelszó megadása</h2>
    <form @submit.prevent="resetPassword">
      <input v-model="password" type="password" placeholder="Új jelszó" required />
      <input v-model="password_confirmation" type="password" placeholder="Jelszó megerősítése" required />
      <button type="submit">Jelszó mentése</button>
    </form>
    <p v-if="message">{{ message }}</p>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import axios from 'axios'

const route = useRoute()
const router = useRouter()
const password = ref('')
const password_confirmation = ref('')
const message = ref('')

const resetPassword = async () => {
  try {
    await axios.post('http://localhost:8000/api/reset-password', {
      token: route.query.token,
      password: password.value,
      password_confirmation: password_confirmation.value
    })
    message.value = 'Sikeres jelszócsere! Most már bejelentkezhetsz.'
    setTimeout(() => router.push('/login'), 2000)
  } catch (err) {
    message.value = 'Hiba: ' + (err.response?.data?.message || 'Ismeretlen hiba')
  }
}
</script>
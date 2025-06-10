<template>
  
  <div class="login">
    <h1>Login</h1>
    <form @submit.prevent="login">
      <!-- Email input -->
      <div data-mdb-input-init class="form-outline mb-4">
        <input v-model="email" type="email" id="form2Example1" class="form-control" required />
        <label class="form-label" for="form2Example1">Email cím</label>
      </div>

      <!-- Password input -->
      <div data-mdb-input-init class="form-outline mb-4">
        <input v-model="password" type="password" id="form2Example2" class="form-control" required />
        <label class="form-label" for="form2Example2">Jelszó</label>
      </div>

      <!-- 2 column grid layout for inline styling -->
      <div class="row mb-4">
        <div class="col d-flex justify-content-center">

        </div>
        <div class="col">
          <!-- Simple link -->
          <a href="#" @click.prevent="forgotPassword">Elfelejtett jelszó?</a>
        </div>
      </div>

      <!-- Submit button -->
      <button type="submit" data-mdb-button-init data-mdb-ripple-init
        class="btn btn-primary btn-block mb-4">Bejelentkezés</button>

      <!-- Error message -->
      <p style="color: red;">{{ error }}</p>
    </form>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import axios from 'axios'
import { useRouter } from 'vue-router'

const email = ref('')
const password = ref('')
const error = ref('')

const router = useRouter()

const login = async () => {
  try {
    const res = await axios.post('http://localhost:8000/api/login', {
      email: email.value,
      password: password.value
    }, {
      withCredentials: true
    })

    console.log('Siker:', res.data.message)
    localStorage.setItem('isAuthenticated', 'true')
    window.location.reload()
    router.push('/') 
  } catch (err) {
    error.value = 'Hibás belépési adatok.'
  }
}

const forgotPassword = async () => {
  if (!email.value) {
    error.value = 'Kérlek add meg az email címedet!'
    return
  }
  try {
    await axios.post('http://localhost:8000/api/forgot_password', {
      email: email.value
    })
    error.value = 'Jelszó visszaállítási email elküldve!'
  } catch (err) {
    error.value = 'Hiba történt a jelszó visszaállításakor.'
  }
}
</script>

<style scoped>
.login {
  max-width: 400px;
  margin: auto;
  padding: 1rem;
}
</style>

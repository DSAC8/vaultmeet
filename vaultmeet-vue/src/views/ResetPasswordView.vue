<template>
  <div class="reset-password">
    <h2>Set New Password</h2>
    <form @submit.prevent="resetPassword">
      <input v-model="password" type="password" placeholder="New password" required />
      <input v-model="password_confirmation" type="password" placeholder="Confirm password" required />
      <button type="submit">Save Password</button>
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
    await axios.post('http://localhost:8000/api/pass_update', {
      token: route.query.token,
      password: password.value,
      password_confirmation: password_confirmation.value
    })
    message.value = 'Password changed successfully! You can now log in.'
    setTimeout(() => router.push('/login'), 2000)
  } catch (err) {
    message.value = 'Error: ' + (err.response?.data?.message || 'Unknown error')
  }
}
</script>

<style scoped>
.reset-password {
  max-width: 350px;
  margin: 80px auto 0 auto;
  padding: 2rem 1.5rem 1.5rem 1.5rem;

  border-radius: 14px;
  box-shadow: 0 2px 16px #000a;
  display: flex;
  flex-direction: column;
  align-items: center;
}

.reset-password h2 {
  color: #f1be8b;
  margin-bottom: 1.2rem;
  text-align: center;
  letter-spacing: 1px;
}

.reset-password form {
  width: 100%;
  display: flex;
  flex-direction: column;
  gap: 1rem;
}

.reset-password input {

  color: #f1f1f1;
  border: 1px solid #353945;
  border-radius: 8px;
  padding: 0.6rem 0.9rem;
  font-size: 1rem;
  outline: none;
  transition: border 0.2s;
}

.reset-password input:focus {
  border: 1.5px solid #a259ff;
}

.reset-password button[type="submit"] {
  background: linear-gradient(90deg, #a259ff 0%, #23262f 100%);
  color: #fff;
  border: none;
  border-radius: 8px;
  padding: 0.6rem 0;
  font-size: 1rem;
  font-weight: bold;
  cursor: pointer;
  margin-top: 0.3rem;
  transition: background 0.2s;
  letter-spacing: 1px;
  box-shadow: 0 2px 8px #0004;
}

.reset-password button[type="submit"]:hover {
  background: linear-gradient(90deg, #7c3aed 0%, #181a20 100%);
}

.reset-password p {
  margin-top: 1.2rem;
  color: #f1be8b;
  text-align: center;
}
</style>
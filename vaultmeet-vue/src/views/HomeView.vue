<template>
  <div class="home container py-4">
    <h2 class="mb-4">User Data</h2>
    <div v-if="loading" class="text-secondary">Loading...</div>
    <div v-else>
      <div class="mb-3" style="display: flex; align-items: center; gap: 0.5rem;">
        <label class="form-label" style="margin-bottom: 0;">Name</label>
        <span class="user-pill">{{ user.name }}</span>
      </div>
    </div>

    <form @submit.prevent="sendForgot">
      <div class="mb-2" style="display: flex; align-items: center; gap: 0.5rem;">
        <label class="form-label" style="margin-bottom: 0;">Email</label>
        <span class="user-pill">{{ user.email }}</span>
      </div>
      <button
        type="submit"
        class="btn btn-primary btn-sm"
        :disabled="forgotBtnDisabled"
      >
        Reset password
        <span v-if="forgotBtnDisabled">({{ forgotBtnTimer }})</span>
      </button>
      <div v-if="forgotMsg" class="mt-2 text-success">{{ forgotMsg }}</div>
      <div v-if="forgotError" class="mt-2 text-danger">{{ forgotError }}</div>
    </form>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'

const user = ref({})
const loading = ref(true)

const showForgot = ref(false)
const forgotMsg = ref('')
const forgotError = ref('')
const forgotBtnDisabled = ref(false)
const forgotBtnTimer = ref(10)
let timerInterval = null

const fetchUser = async () => {
  loading.value = true
  try {
    const res = await axios.get('http://localhost:8000/api/user', { withCredentials: true })
    user.value = res.data
  } catch (e) {
    user.value = { name: 'Unknown', email: '-' }
  } finally {
    loading.value = false
  }
}

const sendForgot = async () => {
  forgotMsg.value = ''
  forgotError.value = ''
  forgotBtnDisabled.value = true
  forgotBtnTimer.value = 10

 
  timerInterval = setInterval(() => {
    forgotBtnTimer.value--
    if (forgotBtnTimer.value <= 0) {
      forgotBtnDisabled.value = false
      clearInterval(timerInterval)
    }
  }, 1000)

  try {
    await axios.post('http://localhost:8000/api/forgot_password', {
      email: user.value.email
    })
    forgotMsg.value = 'If this email exists, a reset link has been sent.'
  } catch (e) {
    forgotError.value = e.response?.data?.message || 'An error occurred during password reset.'
  }
}

onMounted(fetchUser)
</script>

<style scoped>
.user-pill {
  display: inline-block;
  padding: 0.35em 1em;
  border: 1.5px solid #a259ff;
  border-radius: 999px;
  color: #f1be8b;
  font-size: 1rem;
  font-weight: 500;
  min-width: 2em;
  width: auto;
  box-sizing: border-box;
  transition: border 0.2s;
  word-break: break-all;
}

.btn.btn-primary.btn-sm {
  border: 1.5px solid #a259ff !important;
  background-color: #3772ff;
  color: #fff;
  transition: border 0.2s, background 0.2s;
}
.btn.btn-primary.btn-sm:disabled {
  opacity: 0.7;
  cursor: not-allowed;
}
.btn.btn-primary.btn-sm:hover:not(:disabled) {
  background-color: #a259ff;
  border-color: #a259ff;
}
</style>

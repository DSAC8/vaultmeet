<template>
  <div class="new-event">
    <h1>Új esemény létrehozása</h1>
    <form @submit.prevent="createEvent">
      <div class="form-group">
        <label for="title">Cím:</label>
        <input type="text" id="title" v-model="event.title" required />
      </div>
      <div class="form-group">
        <label for="description">Leírás:</label>
        <textarea id="description" v-model="event.description" required></textarea>
      </div>
      <div class="form-group">
        <label for="location">Helyszín:</label>
        <input type="text" id="location" v-model="event.location" required />
      </div>
      <div class="form-group">
        <label for="start_time">Kezdés:</label>
        <input type="datetime-local" id="start_time" v-model="event.start_time" required />
      </div>
      <div class="form-group">
        <label for="end_time">Befejezés:</label>
        <input type="datetime-local" id="end_time" v-model="event.end_time" required />
      </div>
      <button type="submit">Esemény létrehozása</button>
    </form>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import axios from 'axios'
import { useRouter } from 'vue-router'

const router = useRouter()

const event = ref({
  title: '',
  description: '',
  location: '',
  start_time: '',
  end_time: ''
})

const createEvent = async () => {
  try {
    await axios.post('http://localhost:8000/api/events', event.value, {
      withCredentials: true
    })
    router.push('/eventlistview') // Átirányítás az eseménylistára
  } catch (error) {
    if (error.response) {
      console.error('Szerver válasz hibával:', error.response.data)
      alert('Hiba: ' + JSON.stringify(error.response.data.errors || error.response.data.message))
    } else {
      console.error('Váratlan hiba:', error)
      alert('Valami nem oké.')
    }
  }
}

</script>

<style scoped>
.new-event {
  max-width: 600px;
  margin: auto;
  padding: 1rem;
}
</style>

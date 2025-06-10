<template>
  <div class="event-list">
    <h2>Események</h2>
    <div v-if="loading" class="loading-spinner"></div>
    <ul v-else-if="events.length">
      <li v-for="event in events" :key="event.id">
        <strong>{{ event.title }}</strong><br>
        <span>{{ event.description }}</span><br>
        <span>Helyszín: {{ event.location }}</span><br>
        <span>Kezdés: {{ event.start_time }}</span><br>
        <span>Befejezés: {{ event.end_time }}</span><br>
        <span>
          Létrehozó:
          <template v-if="event.creator_name">
            {{ event.creator_name }}
          </template>
          <template v-else>
            ismeretlen
          </template>
        </span>
        <div v-if="currentUserId && Number(event.creator_id) === Number(currentUserId)">
          <button @click="deleteEvent(event.id)" style="margin-left: 1rem; color: red;">
            <i class="fas fa-trash"></i>
            <span class="visually-hidden">Törlés</span>
          </button>
          <button @click="updateEvent(event.id)" style="margin-left: 1rem; color: green;">
            <i class="fas fa-edit"></i>
            <span class="visually-hidden">Szerkesztés</span>
          </button>
        </div>
        <div style="font-size:10px;"></div>
        <hr>
      </li>
    </ul>
    <div v-else>Nincs esemény.</div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'

const events = ref([])
const currentUserId = ref()
const loading = ref(true)

const fetchEvents = async () => {
  loading.value = true
  try {
    const res = await axios.get('http://localhost:8000/api/events', {
      withCredentials: true
    })
    events.value = res.data
  } catch (err) {
    events.value = []
  } finally {
    loading.value = false
  }
}

const fetchCurrentUser = async () => {
  try {
    const res = await axios.get('http://localhost:8000/api/user', {
      withCredentials: true
    })
    currentUserId.value = res.data.id
  } catch (err) {
    currentUserId.value = null
  }
}

const updateEvent = async (id) => {
  const newDescription = prompt('Új leírás (description):')
  if (newDescription === null) return
  try {
    await axios.put(`http://localhost:8000/api/events/${id}`, {
      description: newDescription
    }, {
      withCredentials: true
    })
    const event = events.value.find(e => e.id === id)
    if (event) event.description = newDescription
    alert('Leírás frissítve!')
  } catch (err) {
    alert('Hiba történt a frissítés során.')
  }
}

const deleteEvent = async (id) => {
  if (!confirm('Biztosan törlöd ezt az eseményt?')) return
  try {
    await axios.delete(`http://localhost:8000/api/events/${id}`, {
      withCredentials: true
    })
    events.value = events.value.filter(e => e.id !== id)
  } catch (err) {
    alert('Hiba történt a törlés során.')
  }
}

onMounted(() => {
  fetchCurrentUser()
  fetchEvents()
})
</script>

<style scoped>
.event-list {
  max-width: 600px;
  margin: 2rem auto;
  padding: 1rem;
}
.loading-spinner {
  margin: 2rem auto;
  border: 6px solid #eee;
  border-top: 6px solid #42b983;
  border-radius: 50%;
  width: 48px;
  height: 48px;
  animation: spin 1s linear infinite;
}
@keyframes spin {
  0% { transform: rotate(0deg);}
  100% { transform: rotate(360deg);}
}
</style>
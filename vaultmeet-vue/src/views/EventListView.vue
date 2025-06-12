<template>
  <div class="event-list">
    <h2 class="title">Events</h2>
    <div v-if="loading" class="loading-spinner"></div>
    <ul v-else-if="events.length" class="event-ul">
      <li v-for="event in events" :key="event.id" class="event-li">
        <div class="event-header">
          <span class="event-title">{{ event.title }}</span>
          <button
            v-if="currentUserId && Number(event.creator_id) === Number(currentUserId)"
            @click="deleteEvent(event.id)"
            class="icon-btn"
            title="Delete"
          >
            <i class="fas fa-trash"></i>
          </button>
        </div>
        <div class="event-info">
          <span>Location: <b>{{ event.location }}</b></span>
          
          <span>Start: <b>{{ event.start_time }}</b></span>
          <span>End: <b>{{ event.end_time }}</b></span>
          <span>
            Creator:
            <template v-if="event.creator_name">
              {{ event.creator_name }}
            </template>
            <template v-else>
              unknown
            </template>
          </span>
        </div>
        <div v-if="editingId !== event.id" class="event-desc">
          {{ event.description }}
          <button
            v-if="currentUserId && Number(event.creator_id) === Number(currentUserId)"
            @click="startEdit(event)"
            class="icon-btn"
            title="Edit"
          >
            <i class="fas fa-edit"></i>
          </button>
        </div>
        <div v-else class="edit-chatbox">
          <textarea
            v-model="editDescription"
            class="chatbox-input"
            rows="3"
            style="width: 100%; resize: vertical;"
          ></textarea>
          <div class="edit-btns">
            <button @click="saveEdit(event.id)" class="icon-btn save" title="Save">
              <i class="fas fa-check"></i>
            </button>
            <button @click="cancelEdit" class="icon-btn cancel" title="Cancel">
              <i class="fas fa-times"></i>
            </button>
          </div>
        </div>
      </li>
    </ul>
    <div v-else class="no-events">No events.</div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'

const events = ref([])
const currentUserId = ref()
const loading = ref(true)

const editingId = ref(null)
const editDescription = ref('')

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

const startEdit = (event) => {
  editingId.value = event.id
  editDescription.value = event.description
}

const cancelEdit = () => {
  editingId.value = null
  editDescription.value = ''
}

const saveEdit = async (id) => {
  try {
    await axios.put(`http://localhost:8000/api/events/${id}`, {
      description: editDescription.value
    }, {
      withCredentials: true
    })
    const event = events.value.find(e => e.id === id)
    if (event) event.description = editDescription.value
    editingId.value = null
    editDescription.value = ''
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
  border-radius: 14px;
  box-shadow: 0 2px 16px #000a;
  padding: 2rem 1.2rem;
  color: #e7e7e7;

}
.title {
  text-align: center;
  color: #f1be8b;
  margin-bottom: 1.5rem;
  font-size: 1.5rem;
  letter-spacing: 1px;
}
.event-ul {
  list-style: none;
  padding: 0;
  margin: 0;
}
.event-li {
  border-radius: 10px;
  margin-bottom: 1.2rem;
  padding: 1.1rem 1rem 0.7rem 1rem;
  box-shadow: 0 1px 6px #0003;
  border: 1px solid #23262f;
  background: transparent;
}
.event-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
}
.event-title {
  font-size: 1.15rem;
  font-weight: bold;
  color: #f1be8b;
}
.event-info {
  font-size: 0.97rem;
  margin: 0.7rem 0 0.2rem 0;
  color: #b3b3b3;
  display: flex;
  flex-wrap: wrap;
  gap: 1.2rem;
}
.event-desc {
  margin-top: 0.7rem;
  border-radius: 8px;
  padding: 0.7rem 1rem;
  color: #e7e7e7;
  display: flex;
  align-items: center;
  justify-content: space-between;
  background: transparent; 
  border: 1px solid #353945;
}
.edit-chatbox {
  border-radius: 8px;
  padding: 0.7rem 1rem;
  margin-top: 0.7rem;
  box-shadow: 0 1px 4px #0002;
  background: transparent; 
  border: 1px solid #353945;
}
.chatbox-input {
  border: 1px solid #353945;
  border-radius: 8px;
  padding: 0.5rem;
  font-size: 1rem;
  width: 100%;
  box-sizing: border-box;
  background: transparent; 
  color: #e7e7e7;
}
.edit-btns {
  margin-top: 0.5rem;
  display: flex;
  gap: 0.5rem;
}
.icon-btn {
  background: none;
  border: none;
  padding: 0.2rem 0.4rem;
  cursor: pointer;
  font-size: 1.1rem;
  color: #f1be8b;
  vertical-align: middle;
  transition: color 0.2s;
}

.icon-btn:hover {
  color: #fff;
}
.loading-spinner {
  margin: 2rem auto;
  border: 6px solid #23262f;
  border-top: 6px solid #f1be8b;
  border-radius: 50%;
  width: 48px;
  height: 48px;
  animation: spin 1s linear infinite;
  background: transparent;
}
.no-events {
  text-align: center;
  color: #888;
  margin-top: 2rem;
}
@keyframes spin {
  0% { transform: rotate(0deg);}
  100% { transform: rotate(360deg);}
}
</style>

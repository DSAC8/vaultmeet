<template>
  <div class="new-event">
    <h1>Create New Event</h1>
    <form @submit.prevent="createEvent">
      <div class="form-group">
        <label for="title">Title:</label>
        <input type="text" id="title" v-model="event.title" required />
      </div>
      <div class="form-group">
        <label for="description">Description:</label>
        <textarea id="description" v-model="event.description" required></textarea>
      </div>
      <div class="form-group">
        <label for="location">Location:</label>
        <input type="text" id="location" v-model="event.location" required />
      </div>
      <div class="form-group">
        <label for="start_time">Start:</label>
        <input type="datetime-local" id="start_time" v-model="event.start_time" required />
      </div>
      <div class="form-group">
        <label for="end_time">End:</label>
        <input type="datetime-local" id="end_time" v-model="event.end_time" required />
      </div>
      <button type="submit">Create Event</button>
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
    router.push('/eventlistview')
  } catch (error) {
    if (error.response) {
      alert('Error: ' + JSON.stringify(error.response.data.errors || error.response.data.message))
    } else {
      alert('Unexpected error.')
    }
  }
}
</script>

<style scoped>
.new-event {
  max-width: 600px;
  margin: auto;
  padding: 1.2rem;
  
  border-radius: 16px;
  box-shadow: 0 4px 24px #0008;
  color: #f1f1f1;
}

.new-event h1 {
  text-align: center;
  color: #f1be8b;
  margin-bottom: 1.2rem;
  letter-spacing: 1px;
}

.form-group {
  margin-bottom: 1rem;
  display: flex;
  flex-direction: column;
}

label {
  margin-bottom: 0.5rem;
  color: #b3b3b3;
  font-weight: 500;
}

input,
textarea {

  color: #f1f1f1;
  border: 1px solid #353945;
  border-radius: 8px;
  padding: 0.5rem 0.8rem;
  font-size: 0.98rem;
  transition: border 0.2s;
  outline: none;
}

input:focus,
textarea:focus {
  border: 1.5px solid #a259ff;
 
}

button[type="submit"] {
  width: 100%;
  background: linear-gradient(90deg, #a259ff 0%, #23262f 100%);
  color: #fff;
  border: none;
  border-radius: 8px;
  padding: 0.6rem 0;
  font-size: 1rem;
  font-weight: bold;
  cursor: pointer;
  margin-top: 0.7rem;
  transition: background 0.2s;
  box-shadow: 0 2px 8px #0004;
  letter-spacing: 1px;
}

button[type="submit"]:hover {
  background: linear-gradient(90deg, #7c3aed 0%, #181a20 100%);
}

input[type="datetime-local"]::-webkit-calendar-picker-indicator {
  filter: invert(1);
}
</style>

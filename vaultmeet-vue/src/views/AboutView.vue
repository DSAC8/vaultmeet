<template>
  <div class="chat-container" >
    <h1 class="chat-title" >VaultMeet Assistant</h1>
    <div class="chat-box">
      <div v-for="(msg, i) in messages" :key="i" :class="['chat-message', msg.type]">
        <span v-if="msg.type === 'user'" class="user-label">Te:</span>
        <span v-if="msg.type === 'bot'" class="bot-label">Bot:</span>
        {{ msg.text }}
        <button
          v-if="msg.type === 'bot'"
          @click="felolvas(msg.text)"
          class="tts-btn"
          title="Felolvasás"
        >
          <i class="fas fa-volume-up"></i>
        </button>
      </div>
    </div>
    <div class="chat-input-row">
      <input
        v-model="kerdes"
        @keyup.enter="kuldes"
        type="text"
        class="chat-input"
        placeholder="Írj be egy kérdést pl.: Mikor lesz az esemény?"
        ref="inputRef"
      />
      <button @click="kuldes" class="chat-send-btn">Küldés</button>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import axios from 'axios'

const kerdes = ref('')
const messages = ref([])
const inputRef = ref(null)

const kuldes = async () => {
  if (!kerdes.value.trim()) return

  messages.value.push({ type: 'user', text: kerdes.value })

  try {
    const response = await axios.post('http://localhost:8000/api/chat', {
      kerdes: kerdes.value
    })
    messages.value.push({ type: 'bot', text: response.data.valasz })
  } catch (error) {
    messages.value.push({ type: 'bot', text: 'Hiba történt a válasz lekérésekor.' })
  }
  kerdes.value = ''
}

const felolvas = (szoveg) => {
  if ('speechSynthesis' in window) {
    if (window.speechSynthesis.speaking) {
      window.speechSynthesis.cancel()
      return
    }
    const tisztaSzoveg = szoveg.replace(/[\p{Emoji_Presentation}\p{Extended_Pictographic}]/gu, '')
    const utter = new window.SpeechSynthesisUtterance(tisztaSzoveg)
    utter.lang = 'hu-HU'
    window.speechSynthesis.speak(utter)
  } else {
    alert('A böngésződ nem támogatja a szövegfelolvasást.')
  }
}
</script>

<style scoped>
.chat-container {
  max-width: 420px;
  margin: 2rem auto;
  background: #181a20; /* fő háttérszín */
  border-radius: 12px;
  box-shadow: 0 2px 12px #0006;
  padding: 1.2rem;
  display: flex;
  flex-direction: column;
}
.chat-title {
  text-align: center;
  font-size: 1.4rem;
  margin-bottom: 1rem;
  color: #f1be8b;
  letter-spacing: 1px;
  background-color: #181a20; /* egységes háttér */
  padding: 0.7rem 0;
  border-radius: 10px;
  box-shadow: 0 2px 12px #0006;
}
.chat-box {
  min-height: 220px;
  max-height: 320px;
  overflow-y: auto;
  background: #181a20; /* egységes háttér */
  border-radius: 8px;
  padding: 1rem;
  margin-bottom: 1rem;
  box-shadow: none;
  display: flex;
  flex-direction: column;
  gap: 0.5rem;
}
.chat-message {
  padding: 0.5rem 0.75rem;
  border-radius: 16px;
  max-width: 80%;
  word-break: break-word;
  font-size: 1rem;
  display: inline-block;
  position: relative;
}
.chat-message.user {
  align-self: flex-end;
  background: #a259ff; /* lila */
  color: #fff;
}
.chat-message.bot {
  align-self: flex-start;
  background: #23262f;
  color: #f1be8b;
}
.user-label {
  font-weight: bold;
  margin-right: 0.5em;
  color: #f1be8b;
}
.bot-label {
  font-weight: bold;
  color: #3772ff;
  margin-right: 0.5em;
}
.tts-btn {
  background: none;
  border: none;
  color: #f1be8b;
  margin-left: 0.5em;
  cursor: pointer;
  font-size: 1.1em;
  vertical-align: middle;
  transition: color 0.2s, background 0.2s;
  box-shadow: none;
  outline: none;
}
.tts-btn:focus {
  outline: none;
  box-shadow: none;
}
.tts-btn:hover {
  color: #fff;
  background: none;
}
.chat-input-row {
  display: flex;
  gap: 0.5rem;
}
.chat-input {
  flex: 1;
  padding: 0.5rem 0.75rem;
  border: 1px solid #353945;
  border-radius: 8px;
  font-size: 1rem;
  background: #181a20;
  color: #fff;
}
.chat-send-btn {
  background: linear-gradient(90deg, #a259ff 0%, #23262f 100%);
  color: #fff;
  border: none;
  border-radius: 8px;
  padding: 0 1.2rem;
  font-size: 1rem;
  cursor: pointer;
  transition: background 0.2s;
  font-weight: bold;
  letter-spacing: 1px;
  box-shadow: 0 2px 8px #0004;
}
.chat-send-btn:hover {
  background: linear-gradient(90deg, #7c3aed 0%, #181a20 100%);
}
</style>

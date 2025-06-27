<template>
  <div class="container mt-5" style="max-width: 400px;">
    <h2 class="mb-4">Вход в систему</h2>
    <form @submit.prevent="submitForm">
      <div class="mb-3">
        <label for="email" class="form-label">Email</label>
        <input v-model="form.email" type="email" class="form-control" required autofocus>
      </div>

      <div class="mb-3">
        <label for="password" class="form-label">Пароль</label>
        <input v-model="form.password" type="password" class="form-control" required>
      </div>

      <div v-if="error" class="alert alert-danger">
        {{ error }}
      </div>

      <button type="submit" class="btn btn-primary w-100">Войти</button>
    </form>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import axios from 'axios';

const form = ref({
  email: '',
  password: ''
});

const error = ref('');

const submitForm = async () => {
  try {
    error.value = '';
    await axios.get('/sanctum/csrf-cookie');

    const response = await axios.post('/api/login', form.value);
    
    window.location.href = '/dashboard';
  } catch (err) {
    if (err.response?.status === 422) {
      error.value = 'Неверные учетные данные.';
    } else {
      error.value = 'Произошла ошибка. Попробуйте позже.';
    }
  }
}
</script>
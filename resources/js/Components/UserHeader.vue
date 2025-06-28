<template>
  <div class="d-flex justify-content-between align-items-center mb-3">
    <div>
      <h5>Добро пожаловать, {{ user.name }} ({{ user.email }})</h5>
    </div>

    <div class="d-flex align-items-center gap-2">
      <a v-if="currentRoute === '/'" href="/history" class="btn btn-outline-secondary btn-sm">История операций</a>
      <a v-if="currentRoute === '/history'" href="/" class="btn btn-outline-secondary btn-sm">Главная страница</a>

      <form method="POST" action="/api/logout" class="mb-0">
        <input type="hidden" name="_token" :value="csrfToken" />
        <button type="submit" class="btn btn-outline-danger btn-sm">Выйти</button>
      </form>
    </div>
  </div>
</template>

<script setup>

import { computed, toRefs } from 'vue';

const props = defineProps({
  user: {
    type: Object,
    required: true
  },
  csrfToken: {
    type: String,
    required: true
  }
});

const { user, csrfToken } = toRefs(props);
const currentRoute = window.location.pathname;
</script>
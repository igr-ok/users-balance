<template>
  <div class="container mt-4">
    <UserHeader :user="user" :csrfToken="csrfToken" />

    <h2>Текущий баланс: {{ balance }} ₽</h2>

    <h4 class="mt-4">Последние операции</h4>
    <table class="table table-striped">
      <thead>
        <tr>
          <th>Дата</th>
          <th>Описание</th>
          <th>Сумма</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="op in operations" :key="op.id">
          <td>{{ formatDate(op.created_at) }}</td>
          <td>{{ op.description }}</td>
          <td :class="{'text-success': op.type === 'income', 'text-danger': op.type === 'expense'}">
            {{ op.amount }} ₽
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</template>

<script setup>
import { ref, onMounted, onBeforeUnmount } from 'vue';
import UserHeader from '../Components/UserHeader.vue';

const balance = ref(0);
const operations = ref([]);
const user = ref({ name: '', email: '' });
const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

let interval = null;

const fetchData = async () => {
  try {
    const response = await fetch('/dashboard-data');
    const data = await response.json();

    user.value = data.user;
    balance.value = data.balance;
    operations.value = data.operations;
  } catch (error) {
    console.error('Ошибка загрузки данных:', error);
  }
};

const formatDate = (date) => {
  return new Date(date).toLocaleString();
};

onMounted(() => {
  fetchData();
  interval = setInterval(fetchData, 10000);
});

onBeforeUnmount(() => {
  clearInterval(interval);
});
</script>

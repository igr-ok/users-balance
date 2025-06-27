<template>
  <div class="container mt-4">
    <h2>История операций</h2>

    <div class="d-flex mb-3 align-items-center">
      <input
        v-model="search"
        @input="onSearchInput"
        type="text"
        class="form-control me-3"
        placeholder="Поиск по описанию"
      />

      <button class="btn btn-outline-primary" @click="toggleSort">
        Сортировка: {{ sortOrder === 'asc' ? 'По возрастанию' : 'По убыванию' }}
      </button>
    </div>

    <table class="table table-striped">
      <thead>
        <tr>
          <th>Дата</th>
          <th>Тип</th>
          <th>Сумма</th>
          <th>Описание</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="operation in operations.data" :key="operation.id">
          <td>{{ formatDate(operation.created_at) }}</td>
          <td>{{ operation.type === 'credit' ? 'Начисление' : 'Списание' }}</td>
          <td :class="operation.type === 'credit' ? 'text-success' : 'text-danger'">
            {{ operation.amount.toFixed(2) }}
          </td>
          <td>{{ operation.description }}</td>
        </tr>
      </tbody>
    </table>

    <nav v-if="operations.meta && operations.meta.last_page > 1" aria-label="Page navigation">
      <ul class="pagination">
        <li
          class="page-item"
          :class="{ disabled: !operations.links.prev }"
          @click="goToPage(operations.meta.current_page - 1)"
        >
          <a class="page-link" href="#">Назад</a>
        </li>

        <li class="page-item disabled">
          <span class="page-link">
            Страница {{ operations.meta.current_page }} из {{ operations.meta.last_page }}
          </span>
        </li>

        <li
          class="page-item"
          :class="{ disabled: !operations.links.next }"
          @click="goToPage(operations.meta.current_page + 1)"
        >
          <a class="page-link" href="#">Вперёд</a>
        </li>
      </ul>
    </nav>
  </div>
</template>

<script setup>
import { ref, watch, onMounted } from 'vue';
import axios from 'axios';

const operations = ref({ data: [], meta: {}, links: {} });
const search = ref('');
const sortOrder = ref('desc');
const currentPage = ref(1);

const fetchOperations = async (page = 1) => {
  try {
    const response = await axios.get('/api/operations', {
      params: {
        search: search.value,
        sort: sortOrder.value,
        page,
      },
    });
    operations.value = response.data;
    currentPage.value = page;
  } catch (error) {
    console.error('Ошибка загрузки операций:', error);
  }
};


let debounceTimeout;
const onSearchInput = () => {
  clearTimeout(debounceTimeout);
  debounceTimeout = setTimeout(() => {
    currentPage.value = 1;
    fetchOperations(1);
  }, 300);
};

const toggleSort = () => {
  sortOrder.value = sortOrder.value === 'asc' ? 'desc' : 'asc';
  fetchOperations(currentPage.value);
};

const goToPage = (page) => {
  if (page < 1 || page > operations.value.meta.last_page) return;
  fetchOperations(page);
};

const formatDate = (dateStr) => {
  const date = new Date(dateStr);
  return date.toLocaleString();
};

onMounted(() => {
  fetchOperations();
});
</script>

<style scoped>

</style>
<template>
  <div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <div>
        <h5>Добро пожаловать, {{ user.name }} ({{ user.email }})</h5>
      </div>
      <form method="POST" action="/logout">
        <input type="hidden" name="_token" :value="csrfToken">
        <button class="btn btn-outline-danger btn-sm" type="submit">Выйти</button>
      </form>
    </div>

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

<script>
export default {
  name: 'DashboardPage',
  data() {
    return {
      balance: 0,
      operations: [],
      user: {
        name: '',
        email: ''
      },
      csrfToken: document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
      interval: null
    }
  },
  methods: {
    fetchData() {
      fetch('/dashboard-data')
        .then(res => res.json())
        .then(data => {
          this.user = data.user
          this.balance = data.balance
          this.operations = data.operations
        })
    },
    formatDate(date) {
      return new Date(date).toLocaleString()
    }
  },
  mounted() {
    this.fetchData()
    this.interval = setInterval(this.fetchData, 10000)
  },
  beforeUnmount() {
    clearInterval(this.interval)
  }
}
</script>

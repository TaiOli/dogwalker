<template>
  <div class="logs-container">
    <h1>Histórico de Alterações do Sistema</h1>

    <!-- Estado de Carregamento -->
    <div v-if="loading" class="loading">Carregando histórico...</div>

    <!-- Lista de Logs -->
    <div v-else-if="paginationData && paginationData.data.length > 0">
      <ul class="logs-list">
        <li v-for="log in paginationData.data" :key="log.id" class="log-item">
          <div class="log-header">
            <span class="badge">{{ log.action }}</span>
            <span class="date">{{ formatDate(log.created_at) }}</span>
          </div>
          
          <p class="description">{{ log.description }}</p>
          
          <!-- Seção em destaque mostrando quem realizou a alteração -->
          <div class="meta-author">
            <svg class="icon-user" xmlns="http://w3.org" viewBox="0 0 24 24" fill="currentColor" width="16" height="16">
              <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
            </svg>
            <span>
              Alterado por: <strong>{{ log.user?.name || 'Sistema / Ação Automática' }}</strong>
              <span v-if="log.user"> ({{ log.user.email }})</span>
            </span>
          </div>
        </li>
      </ul>

      <!-- Controles de Paginação -->
      <div class="pagination-controls">
        <button 
          :disabled="paginationData.current_page === 1" 
          @click="fetchLogs(paginationData.current_page - 1)"
        >
          Anterior
        </button>
        
        <span>Página {{ paginationData.current_page }} de {{ paginationData.last_page }}</span>
        
        <button 
          :disabled="paginationData.current_page === paginationData.last_page" 
          @click="fetchLogs(paginationData.current_page + 1)"
        >
          Próxima
        </button>
      </div>
    </div>

    <!-- Lista Vazia -->
    <div v-else class="empty">Nenhuma alteração registrada no sistema.</div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue';
import axios from 'axios';

// 1. Interfaces Básicas
interface User {
  id: number;
  name: string;
  email: string;
}

interface Log {
  id: number;
  user_id: number | null;
  action: string;
  description: string;
  created_at: string;
  updated_at: string;
  user: User | null;
}

// 2. Interface Estrutura de Paginação do Laravel
interface LaravelPagination<T> {
  current_page: number;
  data: T[];
  first_page_url: string;
  from: number | null;
  last_page: number;
  last_page_url: string;
  next_page_url: string | null;
  path: string;
  per_page: number;
  prev_page_url: string | null;
  to: number | null;
  total: number;
}

// 3. Estados reativos fortemente tipados
const paginationData = ref<LaravelPagination<Log> | null>(null);
const loading = ref<boolean>(true);

// 4. Busca dados enviando o parâmetro da página correta
const fetchLogs = async (page: number = 1): Promise<void> => {
  loading.value = true;
  try {
    // 🛠 CORREÇÃO AQUI: String corrigida para bater com o seu endpoint public-logs do Laravel
    const response = await axios.get<LaravelPagination<Log>>(`http://127.0.0{page}`);
    paginationData.value = response.data;
  } catch (error) {
    console.error('Erro ao buscar logs paginados:', error);
  } finally {
    loading.value = false;
  }
};

const formatDate = (dateString: string): string => {
  const date = new Date(dateString);
  return date.toLocaleString('pt-BR');
};

onMounted((): void => {
  fetchLogs();
});
</script>

<style scoped>
.logs-container {
  max-width: 800px;
  margin: 20px auto;
  font-family: sans-serif;
  padding: 0 15px;
}
h1 {
  color: #2c3e50;
  font-size: 24px;
  margin-bottom: 20px;
  border-bottom: 2px solid #eaeaea;
  padding-bottom: 10px;
}
.logs-list {
  list-style: none;
  padding: 0;
}
.log-item {
  background: #ffffff;
  border-left: 4px solid #3498db; /* Azul para indicar auditoria/alteração */
  padding: 16px;
  margin-bottom: 12px;
  border-radius: 0 4px 4px 0;
  box-shadow: 0 2px 8px rgba(0,0,0,0.06);
}
.log-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 10px;
}
.badge {
  background: #3498db;
  color: white;
  padding: 4px 10px;
  border-radius: 20px;
  font-size: 11px;
  font-weight: bold;
  text-transform: uppercase;
  letter-spacing: 0.5px;
}
.date {
  color: #7f8c8d;
  font-size: 12px;
}
.description {
  margin: 0 0 12px 0;
  color: #2c3e50;
  font-size: 15px;
  line-height: 1.4;
}

/* Nova estilização focada em quem alterou o registro */
.meta-author {
  display: flex;
  align-items: center;
  background: #f8f9fa;
  padding: 8px 12px;
  border-radius: 4px;
  font-size: 13px;
  color: #576574;
  border: 1px solid #edf2f7;
}
.icon-user {
  margin-right: 8px;
  color: #718096;
}
.meta-author strong {
  color: #2d3748;
}

.loading, .empty {
  text-align: center;
  padding: 40px;
  color: #7f8c8d;
  font-size: 16px;
}

/* Estilos dos botões de paginação */
.pagination-controls {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-top: 25px;
  padding: 15px 0;
  border-top: 1px solid #eaeaea;
}
button {
  background-color: #3498db;
  color: white;
  border: none;
  padding: 10px 20px;
  border-radius: 4px;
  cursor: pointer;
  font-weight: bold;
  transition: background 0.2s;
}
button:hover:not(:disabled) {
  background-color: #2980b9;
}
button:disabled {
  background-color: #cbd5e1;
  cursor: not-allowed;
}
span {
  font-size: 14px;
  color: #64748b;
  font-weight: 500;
}
</style>
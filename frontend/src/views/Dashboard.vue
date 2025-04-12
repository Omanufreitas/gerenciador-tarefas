<template>
  <div class="min-h-screen flex items-center justify-center bg-gray-950 text-white">
    <div class="w-[680px] shadow-md rounded-lg flex flex-col content-container">
      <!-- CONTEÚDO PRINCIPAL -->
      <div class="flex-1 p-4 flex flex-col">
        <div class="flex justify-end mb-2">
          <button @click="handleLogout" class="bg-red-600 hover:bg-red-700 text-white px-2 py-1 rounded text-xs">
            Logout
          </button>
        </div>

        <h1 class="text-3xl font-bold text-center title">
          Minhas Tarefas
        </h1>

        <!-- Filtros + Botões -->
        <div class="mb-3 mt-1 text-xs flex flex-row items-end gap-4">
          <div class="flex flex-col">
            <label>Status:</label>
            <Multiselect v-model="filters.status" :options="[
              { value: 'pendente', label: 'Pendente' },
              { value: 'em_andamento', label: 'Em Andamento' },
              { value: 'concluida', label: 'Concluída' }
            ]" placeholder="Selecione..." class="text-xs w-[150px]" :style="inputStyle" />
          </div>

          <div class="flex flex-col" style="margin-left: 8px !important;">
            <label>Vencimento:</label>
            <Flatpickr v-model="filters.data_vencimento" :config="{ dateFormat: 'Y-m-d', locale: Portuguese }"
              placeholder="Selecione data" class="text-xs w-[130px]" :style="inputStyle" />
          </div>

          <button @click="fetchTasks" class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded text-xs"
            style="margin-left: 12px !important;">
            Filtrar
          </button>
          <button @click="openNewTaskForm"
            class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded text-xs ml-auto">
            Adcionar tarefa
          </button>
        </div>

        <!-- Tabela -->
        <div class="overflow-x-auto rounded border border-gray-700 mb-4"
          style="margin-top: 20px !important; overflow: hidden !important; border-radius: 8px !important;">
          <table class="min-w-full text-xs text-left text-gray-200 bg-gray-900" style="padding-left: 8px;">
            <thead class="bg-gray-800">
              <tr>
                <th class="px-2 py-1 text-left text-sm font-medium text-gray-300 tracking-normal">Título</th>
                <th class="px-2 py-1 text-left text-sm font-medium text-gray-300 tracking-normal">Status</th>
                <th class="px-2 py-1 text-left text-sm font-medium text-gray-300 tracking-normal">Vencimento</th>
                <th class="px-2 py-1 text-center text-sm font-medium text-gray-300 tracking-normal">Ações</th>
              </tr>
            </thead>

            <tbody>
              <tr v-if="tasks.length === 0">
                <td colspan="4" class="text-center text-sm text-gray-400 py-4">
                  Nenhuma tarefa encontrada.
                </td>
              </tr>
              <tr v-else v-for="tarefa in tasks" :key="tarefa.id" class="border-b border-gray-700 hover:bg-gray-800">
                <td class="px-2 py-1 font-medium">{{ tarefa.titulo }}</td>
                <td class="px-2 py-1">
                  <span :class="{
                    'bg-red-500 text-white px-1 py-0.5 rounded text-xs': tarefa.status === 'pendente',
                    'bg-yellow-400 text-black px-1 py-0.5 rounded text-xs': tarefa.status === 'em_andamento',
                    'bg-green-500 text-white px-1 py-0.5 rounded text-xs': tarefa.status === 'concluida'
                  }">
                    {{ tarefa.status }}
                  </span>
                </td>
                <td class="px-2 py-1">{{ formatarData(tarefa.data_vencimento) }}</td>
                <td class="px-2 py-1 text-center">
                  <div class="flex justify-center gap-2">
                    <button @click="openEditTaskForm(tarefa)" class="icon-btn" title="Editar">
                      <PencilIcon class="text-blue-400 hover:text-blue-300" />
                    </button>
                    <button @click="deleteTask(tarefa.id)" class="icon-btn" title="Excluir">
                      <TrashIcon class="text-red-400 hover:text-red-300" />
                    </button>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <!-- Paginação -->
        <div class="pt-2 flex justify-center items-center gap-3 text-xs mt-auto">
          <button @click="changePage(currentPage - 1)" :disabled="currentPage === 1"
            style="margin-right: 12px !important;"
            class="bg-gray-700 px-2 py-1 rounded disabled:opacity-50 text-[12px]">
            Anterior
          </button>
          <button @click="changePage(currentPage + 1)" :disabled="currentPage === totalPages"
            class="bg-gray-700 px-2 py-1 rounded disabled:opacity-50 text-[12px]">
            Próxima
          </button>
        </div>
      </div>
    </div>

    <!-- MODAL DE TAREFA -->
    <div v-if="showTaskForm" class="modal" style="display: flex; align-items: center; justify-content: center;">
      <div class="modal-content" style="background-color: var(--color-panel); color: var(--color-text);
        padding: 2.5rem; width: 100%; max-width: 500px; border-radius: 0.5rem;
        display: flex; flex-direction: column; height: auto; max-height: 80vh; overflow-y: auto;">
        <form @submit.prevent="submitTaskForm">
          <h2 class="modal-title" :style="{ color: 'var(--color-secondary)' }"
            style="margin-bottom: 0.5rem; font-size: 1.25rem; font-weight: bold;">
            {{ isEditing ? 'Editar Tarefa' : 'Nova Tarefa' }}
          </h2>

          <!-- Título -->
          <div class="form-group"
            style="margin-bottom: 1.25rem; display: flex; flex-direction: column; align-items: center;">
            <label style="display: block; margin-bottom: 0.5rem; font-size: 0.875rem; width: 90%;">Título:</label>
            <input type="text" v-model="form.titulo" required placeholder="Digite o título" style="width: 90%; height: 2rem; padding: 0.375rem 1rem;
              border: 2px solid var(--color-primary); background-color: #ffffff0d;
              color: var(--color-text); border-radius: 0.5rem; font-size: 1rem;" />
          </div>

          <!-- Descrição -->
          <div class="form-group"
            style="margin-bottom: 1.25rem; display: flex; flex-direction: column; align-items: center;">
            <label style="display: block; margin-bottom: 0.5rem; font-size: 0.875rem; width: 90%;">Descrição:</label>
            <textarea v-model="form.descricao" placeholder="Digite a descrição"
              style="width: 90%; height: 2.5rem; padding: 0.375rem 1rem; border: 2px solid var(--color-primary);
              background-color: #ffffff0d; color: var(--color-text); border-radius: 0.5rem; font-size: 1rem;"></textarea>
          </div>

          <!-- Data de Vencimento -->
          <div class="form-group"
            style="margin-bottom: 1.25rem; display: flex; flex-direction: column; align-items: center;">
            <label style="display: block; margin-bottom: 0.5rem; font-size: 0.875rem; width: 90%;">Data de
              Vencimento:</label>
            <Flatpickr v-model="form.data_vencimento" :config="{ dateFormat: 'Y-m-d', locale: Portuguese }"
              placeholder="Clique para selecionar" style="width: 98%; height: 1.8rem; padding: 0.375rem 1rem; 
           border: 2px solid var(--color-primary); background-color: #ffffff0d; 
           color: var(--color-text); border-radius: 0.5rem; font-size: 1rem;" />
          </div>


          <!-- Status -->
          <div class="form-group"
            style="margin-bottom: 1rem; display: flex; flex-direction: column; align-items: center;">
            <label
              style="display: block; margin-bottom: 0.25rem !important; font-size: 0.875rem; width: 90%;">Status:</label>
            <select v-model="form.status" class="custom-select-dark" style="width: 98%; height: 2.5rem; padding: 0.375rem 1rem;
              border: 2px solid var(--color-primary); background-color: #ffffff0d;
              color: var(--color-text); border-radius: 0.5rem; font-size: 1rem;">
              <option value="pendente">Pendente</option>
              <option value="em_andamento">Em Andamento</option>
              <option value="concluida">Concluída</option>
            </select>
          </div>

          <!-- Botões -->
          <div class="modal-actions" style="display: flex; justify-content: flex-end; gap: 1rem; margin-top: 1rem;">
            <button type="submit" class="btn-submit" style="padding: 0.5rem 1rem; border-radius: 0.5rem;
              background-color: var(--color-primary); color: #fff; border: none;">
              {{ isEditing ? 'Atualizar' : 'Criar' }}
            </button>
            <button type="button" @click="closeTaskForm" class="btn-cancel" style="padding: 0.5rem 1rem; border-radius: 0.5rem;
              background-color: #6b7280; color: #fff; border: none;">
              Cancelar
            </button>
          </div>
        </form>
      </div>
    </div>
    <!-- MODAL DE FEEDBACK -->
    <div v-if="showFeedbackModal" class="modal" style="z-index: 100;">
      <div class="modal-content" style="background-color: var(--color-panel); color: var(--color-text);
        padding: 2rem; border-radius: 0.5rem; max-width: 400px; text-align: center;">
        <p style="font-size: 1rem;">{{ feedbackMessage }}</p>
      </div>
    </div>

  </div>
</template>

<script>
import axios from 'axios'
import Multiselect from '@vueform/multiselect'
import '@vueform/multiselect/themes/default.css'
import Flatpickr from 'vue-flatpickr-component'
import 'flatpickr/dist/themes/dark.css'
import { Portuguese } from 'flatpickr/dist/l10n/pt.js'
import { PencilIcon, TrashIcon } from '@heroicons/vue/24/solid'


export default {
  name: 'Dashboard',
  components: {
    Multiselect,
    Flatpickr,
    PencilIcon,
    TrashIcon
  },
  data() {
    return {
      tasks: [],
      filters: {
        status: '',
        data_vencimento: ''
      },
      currentPage: 1,
      totalPages: 1,
      showTaskForm: false,
      isEditing: false,
      form: {
        id: null,
        titulo: '',
        descricao: '',
        data_vencimento: '',
        status: 'pendente'
      },
      inputStyle: {
        border: '2px solid var(--color-primary)',
        backgroundColor: 'var(--color-panel)',
        color: 'var(--color-text)',
        padding: '0.25rem 0.5rem',
        height: '2rem',
        fontSize: '0.875rem',
        borderRadius: '0.5rem'
      },
      feedbackMessage: '',
      showFeedbackModal: false
    }
  },
  methods: {
    async fetchTasks(page = 1) {
      try {
        const token = localStorage.getItem('auth_token')
        const response = await axios.get('http://127.0.0.1:8000/api/tarefas', {
          params: { ...this.filters, page },
          headers: { Authorization: `Bearer ${token}` }
        })
        this.tasks = response.data.data
        this.currentPage = response.data.current_page
        this.totalPages = response.data.last_page
      } catch (error) {
        console.error('Erro ao buscar tarefas:', error)
      }
    },
    changePage(page) {
      if (page >= 1 && page <= this.totalPages) {
        this.fetchTasks(page)
      }
    },
    openNewTaskForm() {
      this.isEditing = false
      this.form = {
        id: null,
        titulo: '',
        descricao: '',
        data_vencimento: '',
        status: 'pendente'
      }
      this.showTaskForm = true
    },

    openEditTaskForm(task) {
      this.isEditing = true
      this.form = { ...task }
      this.showTaskForm = true
    },
    closeTaskForm() {
      this.showTaskForm = false
    },
    async submitTaskForm() {
      const token = localStorage.getItem('auth_token')
      try {
        if (this.isEditing) {
          await axios.put(`http://127.0.0.1:8000/api/tarefas/${this.form.id}`, this.form, {
            headers: { Authorization: `Bearer ${token}`, 'Content-Type': 'application/json' }
          })
          this.showFeedback('Tarefa atualizada com sucesso!')
        } else {
          await axios.post('http://127.0.0.1:8000/api/tarefas', this.form, {
            headers: { Authorization: `Bearer ${token}`, 'Content-Type': 'application/json' }
          })
          this.showFeedback('Tarefa criada com sucesso!')
        }
        this.closeTaskForm()
        this.fetchTasks(this.currentPage)
      } catch (error) {
        console.error('Erro ao salvar tarefa:', error)
        this.showFeedback('Erro ao salvar tarefa.')
      }
    },
    formatarData(data) {
      if (!data) return ''
      const [ano, mes, dia] = data.split('-')
      return `${dia}/${mes}/${ano}`
    },
    showFeedback(msg, duration = 2000) {
      this.feedbackMessage = msg
      this.showFeedbackModal = true
      setTimeout(() => {
        this.showFeedbackModal = false
        this.feedbackMessage = ''
      }, duration)
    },
    async deleteTask(taskId) {
      const token = localStorage.getItem('auth_token')
      this.showFeedback('Excluindo tarefa...')
      try {
        await axios.delete(`http://127.0.0.1:8000/api/tarefas/${taskId}`, {
          headers: { Authorization: `Bearer ${token}` }
        })
        this.showFeedback('Tarefa excluída com sucesso!')
        this.fetchTasks(this.currentPage)
      } catch (error) {
        console.error('Erro ao excluir tarefa:', error)
        this.showFeedback('Erro ao excluir tarefa.')
      }
    },
    async handleLogout() {
      const token = localStorage.getItem('auth_token')
      try {
        await axios.post('http://127.0.0.1:8000/api/logout', {}, {
          headers: { Authorization: `Bearer ${token}` }
        })
        localStorage.removeItem('auth_token')
        this.$router.push({ name: 'Login' })
      } catch (error) {
        console.error('Erro ao fazer logout:', error)
      }
    }
  },
  created() {
    this.fetchTasks()
  }
}
</script>

<style scoped>
.content-container {
  background-color: var(--color-panel);
  color: var(--color-text);
  box-sizing: border-box;
  min-height: 500px;
  padding: 1rem;
}

.title {
  color: var(--color-secondary);
  margin-bottom: 1.5rem;
}

::v-deep .flatpickr-input {
  height: 1.6rem !important;
  line-height: 1.5rem !important;
  padding: 0.25rem !important;
}

::v-deep .multiselect-placeholder,
::v-deep .flatpickr-input::placeholder {
  font-size: 0.90rem !important;
  font-family: inherit !important;
  color: inherit !important;
  text-align: center !important;
}

.modal {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: rgba(0, 0, 0, 0.5);
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 50;
}

.modal-title {
  margin-bottom: 1rem;
  font-size: 1.25rem;
  font-weight: bold;
}

.form-group {
  margin-bottom: 1rem;
}

.form-group label {
  display: block;
  margin-bottom: 0.25rem;
  font-size: 0.875rem;
}

.modal-actions {
  display: flex;
  justify-content: flex-end;
  gap: 1rem;
}

.btn-submit {
  background-color: #2563eb;
  color: #fff;
  padding: 0.5rem 1rem;
  border: none;
  border-radius: 0.5rem;
  cursor: pointer;
}

.btn-cancel {
  background-color: #6b7280;
  color: #fff;
  padding: 0.5rem 1rem;
  border: none;
  border-radius: 0.5rem;
  cursor: pointer;
}

.custom-select-dark option {
  background-color: #262626;
  /* mesma cor do painel */
  color: var(--color-text);
}

.custom-select-dark:focus {
  outline: none;
  border-color: var(--color-secondary);
}
</style>

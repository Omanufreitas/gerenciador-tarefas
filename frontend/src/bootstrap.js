// frontend/src/bootstrap.js
import axios from 'axios';

axios.defaults.baseURL = 'http://127.0.0.1:8000/api';
axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

// Se preferir, exporte a instância
export default axios;

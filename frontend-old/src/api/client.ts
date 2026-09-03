import axios from 'axios'

// Create a configured instance of Axios for API requests
export const apiClient = axios.create({
  baseURL: import.meta.env.VITE_API_BASE_URL || '/api',
  timeout: 10000,
  headers: {
    Accept: 'application/ld+json',
    'Content-Type': 'application/ld+json',
  },
})

// Response Interceptor: Global error handling
/*
apiClient.interceptors.response.use(
  (response) => response.data, // Strip Axios wrapper, return raw data directly
  (error) => {
    const status = error.response ? error.response.status : null;

    if (status === 401) {
      // Handle unauthorized error (e.g., redirect to login, clear storage)
      // localStorage.removeItem('auth_token');
      // window.location.href = '/login';
      console.error('Unauthorized access.');
    } else if (status === 500) {
      // Handle server error
      console.error('Server error. Please try again later.');
    }

    return Promise.reject(error);
  }
);
*/

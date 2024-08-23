import axios from 'axios';

export const http = axios.create({
    baseURL: '/api/',
    headers: {
        "Accept": "application/json",
        "Content-Type": "application/json",
    },
    timeout: 3000
});

// Request interceptor to include the token dynamically
http.interceptors.request.use(
    (config) => {
        const token = localStorage.getItem('access_token');
        if (token) {
            config.headers['Authorization'] = `Bearer ${token}`;
        }
        return config;
    },
    (error) => {
        return Promise.reject(error);
    }
);
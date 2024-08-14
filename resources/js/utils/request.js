import axios from "axios";

export const http = axios.create({
    baseURL: '/api/',
    headers: {
        "Accept": "application/json",
        "Content-Type": "application/json",
        "Authorization": `Bearer ${localStorage.getItem('access_token')}`
    },
    timeout: 3000
});
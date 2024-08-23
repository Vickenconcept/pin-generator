
import { createApp } from 'vue';
import 'flowbite';

import GenerateComponent from './components/GenerateComponent.vue';


const app = createApp({});

app.component('generate-component', GenerateComponent);


app.mount('#app');

const accessToken = document.querySelector('#access_token');
localStorage.setItem('access_token', accessToken?.value);
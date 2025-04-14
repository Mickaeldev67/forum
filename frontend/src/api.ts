import axios from 'axios';

const api = axios.create({
  baseURL: 'https://forumsynf-backend.ddev.site/api',
  headers: {
    'Content-Type': 'application/ld+json',
  },
});

export default api;
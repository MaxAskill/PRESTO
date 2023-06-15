import axios from "axios";
import router from "./routes/routes.js"

const axiosClient = axios.create({
  baseURL: `http://192.168.0.7:40/api`,
  headers: {
    'Content-Type': 'application/json',
    'Access-Control-Allow-Origin': '*'
    },
    xsrfCookieName: 'XSRF-TOKEN',
    xsrfHeaderName: 'X-XSRF-TOKEN',
    withCredentials: false

});

axiosClient.interceptors.request.use(config => {
  config.headers.Authorization = `Bearer ${sessionStorage.getItem("Token")}`
  return config;
})

export default axiosClient;
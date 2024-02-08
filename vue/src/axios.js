import axios from "axios";
import router from "./routes/routes.js"

const axiosClient = axios.create({
  baseURL: `http://192.168.0.7:90/api`,
  headers: {
    'Content-Type': 'application/json',
    'Access-Control-Allow-Origin': 'http://119.93.113.254:4040',
  },
  xsrfCookieName: 'XSRF-TOKEN',
  xsrfHeaderName: 'X-XSRF-TOKEN',
  withCredentials: false, // Adjust this based on your needs
});

axiosClient.interceptors.request.use(config => {
  config.headers.Authorization = `Bearer ${sessionStorage.getItem("Token")}`
  return config;
})

export default axiosClient;
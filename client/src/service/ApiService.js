import axios from "axios";

const API_URL = process.env.REACT_APP_API_URL;

export const get = (level = 1) => {
    return axios.get(`${API_URL}?level=${level}`);
}

export const post = (expression, result) => {
    var params = new URLSearchParams();
    params.append('expression', expression);
    params.append('result', result);

    return axios.post(`${API_URL}`, params);
}
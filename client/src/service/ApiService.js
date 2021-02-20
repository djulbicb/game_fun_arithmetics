import axios from "axios";

 const API_URL = "http://localhost/game_fun_arithmetics/api/";
//const API_URL = "/game/game_fun_arithmetics/api/";

export const get = (level = 1) => {
    return axios.get(`${API_URL}?level=${level}`);
}

export const post = (expression, result) => {
    var params = new URLSearchParams();
    params.append('expression', expression);
    params.append('result', result);

    return axios.post(`${API_URL}`, params);
}
import axios from 'axios';

export default {
    get(url) {
        return axios.get(url).then(response => response.data);
    },

    delete(url){
        return axios.delete(url);
    }
}

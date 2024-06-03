import axios from 'src/boot/axios';

export async function isAuthenticated() {
    try {
        const response = await axios.get('/auth-check');
        return response.data.authenticated;
    } catch (error) {
        return false;
    }
}
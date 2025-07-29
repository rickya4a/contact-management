import api from './api';
import type { Contact } from '@/types/contact';

export const contactService = {
    async getContacts(page = 1) {
        const response = await api.get(`/contacts?page=${page}`);
        return response.data;
    },

    async createContact(contact: Contact) {
        const response = await api.post('/contacts', contact);
        return response.data;
    },

    async updateContact(id: number, contact: Contact) {
        const response = await api.put(`/contacts/${id}`, contact);
        return response.data;
    },

    async deleteContact(id: number) {
        await api.delete(`/contacts/${id}`);
    }
};
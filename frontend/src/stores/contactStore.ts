import { defineStore } from 'pinia';
import { contactService } from '@/services/contactService';
import type { Contact, ContactsResponse } from '@/types/contact';

interface State {
    contacts: Contact[];
    loading: boolean;
    error: string | null;
    pagination: {
        currentPage: number;
        lastPage: number;
        total: number;
    };
}

export const useContactStore = defineStore('contact', {
    state: (): State => ({
        contacts: [],
        loading: false,
        error: null,
        pagination: {
            currentPage: 1,
            lastPage: 1,
            total: 0
        }
    }),

    actions: {
        async fetchContacts(page = 1) {
            this.loading = true;
            this.error = null;
            try {
                const response: ContactsResponse = await contactService.getContacts(page);
                this.contacts = response.data;
                this.pagination = {
                    currentPage: response.meta.current_page,
                    lastPage: response.meta.last_page,
                    total: response.meta.total
                };
            } catch (error) {
                this.error = 'Failed to fetch contacts';
                console.error(error);
            } finally {
                this.loading = false;
            }
        },

        async createContact(contact: Contact) {
            this.loading = true;
            this.error = null;
            try {
                await contactService.createContact(contact);
                await this.fetchContacts(this.pagination.currentPage);
            } catch (error) {
                this.error = 'Failed to create contact';
                console.error(error);
                throw error;
            } finally {
                this.loading = false;
            }
        },

        async updateContact(id: number, contact: Contact) {
            this.loading = true;
            this.error = null;
            try {
                await contactService.updateContact(id, contact);
                await this.fetchContacts(this.pagination.currentPage);
            } catch (error) {
                this.error = 'Failed to update contact';
                console.error(error);
                throw error;
            } finally {
                this.loading = false;
            }
        },

        async deleteContact(id: number) {
            this.loading = true;
            this.error = null;
            try {
                await contactService.deleteContact(id);
                await this.fetchContacts(this.pagination.currentPage);
            } catch (error) {
                this.error = 'Failed to delete contact';
                console.error(error);
                throw error;
            } finally {
                this.loading = false;
            }
        }
    }
});
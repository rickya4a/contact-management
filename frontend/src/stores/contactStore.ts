import { defineStore } from 'pinia';
import { contactService } from '@/services/contactService';
import type { Contact, ContactsResponse } from '@/types/contact';

interface State {
    contacts: Contact[];
    loading: boolean;
    error: string | null;
    searchQuery: string;
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
        searchQuery: '',
        pagination: {
            currentPage: 1,
            lastPage: 1,
            total: 0
        }
    }),

    getters: {
        sortedContacts: (state) => {
            if (!state.searchQuery) return state.contacts;

            const searchLower = state.searchQuery.toLowerCase();

            return [...state.contacts].sort((a, b) => {
                const aName = a.name.toLowerCase();
                const bName = b.name.toLowerCase();
                const aEmail = a.email.toLowerCase();
                const bEmail = b.email.toLowerCase();
                const aPhone = a.phone;
                const bPhone = b.phone;

                // Exact matches first
                const aExactMatch = aName === searchLower || aEmail === searchLower || aPhone === searchLower;
                const bExactMatch = bName === searchLower || bEmail === searchLower || bPhone === searchLower;

                if (aExactMatch && !bExactMatch) return -1;
                if (!aExactMatch && bExactMatch) return 1;

                // Starts with search query second
                const aStartsWith = aName.startsWith(searchLower) || aEmail.startsWith(searchLower) || aPhone.startsWith(searchLower);
                const bStartsWith = bName.startsWith(searchLower) || bEmail.startsWith(searchLower) || bPhone.startsWith(searchLower);

                if (aStartsWith && !bStartsWith) return -1;
                if (!aStartsWith && bStartsWith) return 1;

                // Contains search query last
                const aContains = aName.includes(searchLower) || aEmail.includes(searchLower) || aPhone.includes(searchLower);
                const bContains = bName.includes(searchLower) || bEmail.includes(searchLower) || bPhone.includes(searchLower);

                if (aContains && !bContains) return -1;
                if (!aContains && bContains) return 1;

                return 0;
            });
        }
    },

    actions: {
        setSearchQuery(query: string) {
            this.searchQuery = query;
        },

        async fetchContacts(page = 1, search = '') {
            this.loading = true;
            this.error = null;
            try {
                const response: ContactsResponse = await contactService.getContacts(page, search);
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
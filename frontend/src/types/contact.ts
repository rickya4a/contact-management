export interface Contact {
    id?: number;
    name: string;
    email: string;
    phone: string;
}

export interface ContactsResponse {
    data: Contact[];
    meta: {
        current_page: number;
        last_page: number;
        per_page: number;
        total: number;
    };
}
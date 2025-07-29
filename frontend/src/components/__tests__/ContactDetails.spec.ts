import { describe, it, expect } from 'vitest';
import { render, fireEvent } from '@testing-library/vue';
import ContactDetails from '../ContactDetails.vue';

describe('ContactDetails', () => {
  const mockContact = {
    id: 1,
    name: 'John Doe',
    email: 'john@example.com',
    phone: '+1234567890'
  };

  it('renders contact information correctly', () => {
    const { getByText } = render(ContactDetails, {
      props: {
        contact: mockContact
      }
    });

    expect(getByText(mockContact.name)).toBeInTheDocument();
    expect(getByText(mockContact.email)).toBeInTheDocument();
    expect(getByText(mockContact.phone)).toBeInTheDocument();
  });

  it('emits edit event when edit button is clicked', async () => {
    const { getByText, emitted } = render(ContactDetails, {
      props: {
        contact: mockContact
      }
    });

    await fireEvent.click(getByText('Edit'));
    expect(emitted()).toHaveProperty('edit');
  });

  it('emits close event when close button is clicked', async () => {
    const { getByRole, emitted } = render(ContactDetails, {
      props: {
        contact: mockContact
      }
    });

    const closeButton = getByRole('button', { name: /close/i });
    await fireEvent.click(closeButton);
    expect(emitted()).toHaveProperty('close');
  });

  it('shows confirmation dialog before emitting delete event', async () => {
    const { getByText, emitted } = render(ContactDetails, {
      props: {
        contact: mockContact
      }
    });

    // Mock window.confirm
    const confirmSpy = vi.spyOn(window, 'confirm');
    confirmSpy.mockImplementation(() => true);

    await fireEvent.click(getByText('Delete Contact'));

    expect(confirmSpy).toHaveBeenCalled();
    expect(emitted()).toHaveProperty('delete');

    confirmSpy.mockRestore();
  });

  it('does not emit delete event when confirmation is cancelled', async () => {
    const { getByText, emitted } = render(ContactDetails, {
      props: {
        contact: mockContact
      }
    });

    // Mock window.confirm
    const confirmSpy = vi.spyOn(window, 'confirm');
    confirmSpy.mockImplementation(() => false);

    await fireEvent.click(getByText('Delete Contact'));

    expect(confirmSpy).toHaveBeenCalled();
    expect(emitted()).not.toHaveProperty('delete');

    confirmSpy.mockRestore();
  });

  it('renders clickable email and phone links', () => {
    const { getByRole } = render(ContactDetails, {
      props: {
        contact: mockContact
      }
    });

    const emailLink = getByRole('link', { name: mockContact.email });
    const phoneLink = getByRole('link', { name: mockContact.phone });

    expect(emailLink).toHaveAttribute('href', `mailto:${mockContact.email}`);
    expect(phoneLink).toHaveAttribute('href', `tel:${mockContact.phone}`);
  });
});
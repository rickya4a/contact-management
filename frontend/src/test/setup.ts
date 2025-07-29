import { expect, afterEach, vi } from 'vitest';
import { cleanup } from '@testing-library/vue';
import * as matchers from '@testing-library/jest-dom/matchers';

// Extend Vitest's expect method with methods from @testing-library/jest-dom
expect.extend(matchers as any);

// Mock window.confirm
vi.stubGlobal('confirm', vi.fn());

// Run cleanup after each test case (e.g. clearing jsdom)
afterEach(() => {
  cleanup();
  vi.clearAllMocks();
});
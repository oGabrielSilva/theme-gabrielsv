import { ProfileForm } from './profile/ProfileForm';

document.addEventListener('DOMContentLoaded', () => {
  try {
    new ProfileForm();
  } catch (error) {
    // Form não está na página atual
  }
});

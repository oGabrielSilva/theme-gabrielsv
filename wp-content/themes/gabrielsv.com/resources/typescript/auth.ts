import { LoginForm } from './auth/LoginForm';
import { RegisterForm } from './auth/RegisterForm';
import { ForgotPasswordForm } from './auth/ForgotPasswordForm';
import { ResetPasswordForm } from './auth/ResetPasswordForm';

document.addEventListener('DOMContentLoaded', () => {
  try {
    new LoginForm();
    new RegisterForm();
    new ForgotPasswordForm();
    new ResetPasswordForm();
  } catch (error) {
    // Forms não estão na página atual
  }
});

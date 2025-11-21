import { ForgotPasswordForm } from "./auth/ForgotPasswordForm";
import { LoginForm } from "./auth/LoginForm";
import { RegisterForm } from "./auth/RegisterForm";
import { ResetPasswordForm } from "./auth/ResetPasswordForm";

document.addEventListener("DOMContentLoaded", () => {
  // Try/catch individual para cada form
  try {
    new LoginForm();
  } catch (error) {
    // LoginForm não está na página
  }

  try {
    new RegisterForm();
  } catch (error) {
    // RegisterForm não está na página
  }

  try {
    new ForgotPasswordForm();
  } catch (error) {
    // ForgotPasswordForm não está na página
  }

  try {
    new ResetPasswordForm();
  } catch (error) {
    // ResetPasswordForm não está na página
  }
});

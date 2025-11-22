import { DeleteComment } from './comments/DeleteComment';
import { ReplyComment } from './comments/ReplyComment';
import { SubmitComment } from './comments/SubmitComment';

document.addEventListener('DOMContentLoaded', () => {
  // Try/catch individual para cada componente
  try {
    new DeleteComment();
  } catch (error) {
    // DeleteComment não está na página
  }

  try {
    new ReplyComment();
  } catch (error) {
    // ReplyComment não está na página
  }

  try {
    new SubmitComment();
  } catch (error) {
    // SubmitComment não está na página
  }
});

import { DeleteComment } from './comments/DeleteComment';
import { ReplyComment } from './comments/ReplyComment';

document.addEventListener('DOMContentLoaded', () => {
  try {
    new DeleteComment();
    new ReplyComment();
  } catch (error) {
    // Comments não estão na página atual
  }
});

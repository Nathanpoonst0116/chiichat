window.addEventListener('load', function() {
  const loginBox = document.getElementById('login-box');
  loginBox.classList.add('visible'); // Add visible class on load
});

window.onload = function() {
  const currentUserId = sessionStorage.getItem('user_id');
  if (currentUserId) {
      // Load user-specific data
      console.log("User ID from session: ", currentUserId);
      // You can fetch and display user-specific data here
  }
};

// On successful login, store user info in session storage
function storeUserSession(userId, userName) {
  sessionStorage.setItem('user_id', userId);
  sessionStorage.setItem('user_name', userName);
}
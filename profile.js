const articleButtons = document.querySelectorAll('.liked-snippet-link');

articleButtons.forEach((button) => {
  button.addEventListener('click', () => {
    const snippetId = button.getAttribute('data-snippet-id');
    
    // Use AJAX or fetch to add the snippetId to the session variable
    // Example using fetch:
    fetch('add_snippetid.php?snippetId=' + snippetId, {
      method: 'POST', // Use POST or GET as needed
    }).then(() => {
      // Redirect to discover.php after adding to the session
      window.location.href = 'discover.php';
    });
  });
});
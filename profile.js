const snippetButtons = document.querySelectorAll('.liked-snippet-link');
// Adds an event listener to every button in the liked/bookmarked post divs which redirects the user to the discovery page and places the article ID they wanted to view
// at the front of the priority queue.

snippetButtons.forEach((button) => {
  button.addEventListener('click', () => {
    const snippetId = button.getAttribute('data-snippet-id');
    
    fetch('add_snippetid.php?snippetId=' + snippetId, {
      method: 'POST',
    }).then(() => {
      window.location.href = 'discover.php';
    });
  });
});

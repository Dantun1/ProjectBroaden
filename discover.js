document.getElementById("next-article").addEventListener("click", function() {

// Function generates the next article, along with the associated data (whether it is liked/bookmarked, comments) when the next page button is pressed
    var xhr = new XMLHttpRequest();
    xhr.open("GET", "next_post.php?increment=1", true);
    xhr.onreadystatechange = function() {
      if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
        var response = JSON.parse(xhr.responseText);
        if (response.insession === true && response.snippetId != -1){
            fetchParentComments();
            fillInformation(response)
        } 
        else {
            document.getElementById("snippet").innerHTML = "<div class = 'logged-out-box'><p class = 'logged-out-message'>" + response.body + "</p></div>";
        }
      }
    };
    xhr.send();
});

document.getElementById("previous-post").addEventListener("click", function(){
  // Function generates the previous article
  console.log("aaaa")
  var xhr = new XMLHttpRequest();
  xhr.open("GET", "next_post.php?increment=-1", true);
  xhr.onreadystatechange = function() {
    if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
      var response = JSON.parse(xhr.responseText);
      if (response.insession === true){
          fetchParentComments();
          fillInformation(response)
      } 
      else {
          document.getElementById("snippet").innerHTML = "<div class = 'logged-out-box'><p class = 'logged-out-message'>" + response.body + "</p></div>";
      }
    }
  };
  xhr.send();
});
  


document.getElementById("like").addEventListener("click", function() {
    // Function that handles the like toggler, fills the heart icon if the post is liked, and empties it if it is not.
    var xhr = new XMLHttpRequest();
    xhr.open("GET", "like_post.php");
    xhr.onreadystatechange = function() {
        if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
            var result = JSON.parse(xhr.responseText);
            if (result.liked) {
                document.getElementById("like").style.fontVariationSettings = "'FILL' 30, 'wght' 700, 'GRAD' 0, 'opsz' 48";
            } else if (!result.liked) {
                document.getElementById("like").style.fontVariationSettings = "'FILL' 0, 'wght' 700, 'GRAD' 0, 'opsz' 48";
            }
        }
    };
    xhr.send();
});

document.getElementById("comment").addEventListener("click", function() {
    document.getElementById("comment-section").classList.toggle("comment-section-closed")
    // hides the comment section if it is open, and opens it if it is closed
});

document.getElementById("bookmark").addEventListener("click", function() {
    // Function that handles bookmark toggler, fills the bookmark icon if the post is bookmarked, and empties it if it is not.
    var xhr = new XMLHttpRequest();
    xhr.open("GET", "bookmark_post.php");
    xhr.onreadystatechange = function() {
        if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
            var result = JSON.parse(xhr.responseText);
            if (result.bookmarked) {
                document.getElementById("bookmark").style.fontVariationSettings = "'FILL' 30, 'wght' 700, 'GRAD' 0, 'opsz' 48";
            } else if (!result.bookmarked) {
                document.getElementById("bookmark").style.fontVariationSettings = "'FILL' 0, 'wght' 700, 'GRAD' 0, 'opsz' 48";
            }
        }
    };
    xhr.send();
});

var parentId = null;

function fetchParentComments() {
  // Gets all comments and their replies, adds the html to the comment section div.
    const xhr = new XMLHttpRequest();
    xhr.open('GET', 'fetch-parent-comments.php', true);
    xhr.onload = async function() {

      if (this.status === 200) {
        const parentComments = JSON.parse(this.responseText);
        // console.log(parentComments);
        let output = '';
        for (const parentComment of parentComments) {
          const repliesHtml = await fetchReplies(parentComment.commentId); 
          // For each comment, adds the html then adds the replies below that comment. Asynchronous function call to fetchReplies function because the javascript could execute before the replies have been fetched causing an error.
          const username = await getUsername(parentComment.userId);
          const commentDate = new Date(parentComment.created_at)
          const currentDate = new Date();
          const timeDifference = currentDate - commentDate;
          const daysDifference = Math.floor(timeDifference / (1000 * 60 * 60 * 24));
          output += `
            <div class="comment" data-id="${parentComment.commentId}">
              <div class="comment-header">
                <div class="comment-user">
                  <div class="comment-user-name">
                    <p class="comment-user-name-text" id = "username">${username}</p>
                  </div>
                </div>
                <div class="comment-time">
                  <p class="comment-time-text">${daysDifference} days ago</p>
                </div>
              </div>
              <div class="comment-body">
                <p class="comment-body-text">${parentComment.content}</p>
              </div>
              <div class="comment-footer">
                <div class="comment-reply">
                  <button class="comment-reply-button" id="comment-reply-button" data-id="${parentComment.commentId}">
                    <span class="material-symbols-outlined">reply</span>
                  </button>
                </div>
              </div>
            </div>
            <div class="replies-container">${repliesHtml}</div>
          `;
        }
        document.getElementById('comment-container').innerHTML = output;
        const replyButtons = document.querySelectorAll('.comment-reply-button');
        replyButtons.forEach(function(button) {
          button.addEventListener('click', function() {
            const parentCommentId = button.dataset.id;
            parentId = parentCommentId; // set parentId to the corresponding comment ID of the reply button that was clicked
          });
        });
      }
    }
    xhr.send();

  }
  

  async function fetchReplies(parentCommentId) {
    // this function returns all the replies for a given parent comment id.
    return new Promise((resolve, reject) => {
      const xhr = new XMLHttpRequest();
      xhr.open('GET', `fetch-replies.php?parentCommentId=${parentCommentId}`, true);
      xhr.onload = async function() {
        if (this.status === 200) {
          const replies = JSON.parse(this.responseText);
          let output = '';
          for (const reply of replies) {
            const reply_username = await getUsername(reply.userId);
            const commentDate = new Date(reply.created_at)
            const currentDate = new Date();
            const timeDifference = currentDate - commentDate;
            const daysDifference = Math.floor(timeDifference / (1000 * 60 * 60 * 24));
            output += `
              <div class="reply-comment" data-id="${reply.parentId}"}>
                <div class="reply-header">
                  <div class="reply-user">
                    <div class="reply-user-name">
                      <p class="reply-user-name-text">${reply_username}</p>
                    </div>
                    <div class="reply-time">
                      <p class="reply-time-text">${daysDifference} days ago</p>
                    </div>
                  </div>
                </div>
                <div class="reply-body">
                  <p class="reply-body-text">${reply.content}</p>
                </div>
              </div>
            `;
          }
          resolve(output);
        } else {
          reject('Error getting replies');
        }
      }
      xhr.send();
    });
  }

function fillInformation(response){
    // Function to fill the toggle buttons and add the content of the snippet onto the page.
    document.getElementById("snippet").innerHTML = "<h1 class = 'article-title'>" + response.title + "</h2><p class = 'article'>" + response.body + "</p>";
    if (response.liked === true){
        document.getElementById("like").style.fontVariationSettings = "'FILL' 30, 'wght' 700, 'GRAD' 0, 'opsz' 48";
    }
    else if (!response.liked){
        document.getElementById("like").style.fontVariationSettings = "'FILL' 0, 'wght' 700, 'GRAD' 0, 'opsz' 48";
    }
    if (response.bookmarked === true) {
      document.getElementById("bookmark").style.fontVariationSettings = "'FILL' 30, 'wght' 700, 'GRAD' 0, 'opsz' 48";
    } 
    else if (!response.bookmarked) {
      document.getElementById("bookmark").style.fontVariationSettings = "'FILL' 0, 'wght' 700, 'GRAD' 0, 'opsz' 48";
    }

  }



  
document.getElementById('comment-form').addEventListener('submit', function(event) {
  event.preventDefault(); 

    // Gets the content of the input box thing 
  const content = document.getElementById('comment-input').value.trim();
  
  if (content === '') {
    return; // Do nothing if the input field is empty
    }
  
    // Create a data array to send to the server; it contains the content and the parentId where if the parentId is null, it is a parent comment otherwise it is a reply.
  let data = {};
  if (parentId == null) {
    data = {content: content, parentId: null};
  } else {
    data = {content: content, parentId: parentId};
  }
  // Ajax request passes the data variable into the save-comment.php file and returns the saved comment
  fetch('save-comment.php', {
    method: 'POST',
    body: JSON.stringify(data),
    headers: {
      'Content-Type': 'application/json',
      },
  })
  .then(response => response.json())
  .then(savedComment => {
    // Adds the new comment to the html below the rest of the comments
    let commentHtml;
    const commentContainer = document.getElementById('comment-container');
    if (parentId == null) {
      commentHtml = `<div class="comment" data-id="${savedComment.commentId}">
      <div class="comment-header">
        <div class="comment-user">
          <div class="comment-user-name">
              <p class="reply-user-name-text">${savedComment.username}</p>
          </div>
        </div>
        <div class="comment-time">
          <p class="comment-time-text">Just Now</p>
        </div>
      </div>
      <div class="comment-body">
        <p class="comment-body-text">${savedComment.content}</p>
      </div>
      <div class="comment-footer">
        <div class="comment-reply" data-id = "${savedComment.commentId}>
          <button class="comment-reply-button" id="comment-reply-button">
            <span class="material-symbols-outlined">reply</span>
          </button>
        </div>
      </div>
    </div>
  `;
  commentContainer.insertAdjacentHTML('beforeend', commentHtml);}
    else {
      commentHtml = `div class="reply-comment" data-id="${savedComment.commentId}">
      <div class="reply-header">
        <div class="reply-user">
          <div class="reply-user-name">
            <p class="reply-user-name-text">${savedComment.username}</p>
          </div>
          <div class="reply-time">
            <p class="reply-time-text">${savedComment.created_at}</p>
          </div>
        </div>
      </div>
      <div class="reply-body">
        <p class="reply-body-text">${savedComment.content}</p>
      </div>
    </div>
  `;
    commentContainer.insertAdjacentHTML('beforeend', commentHtml);}
  
    document.getElementById('comment-input').value = '';
  })
  .catch(error => {
    console.error(error);
    alert('error trying to submit comment');
  });
  parentId = null;
});


async function getUsername(userId) {
      // function that gets the username of the current user
      return new Promise((resolve,reject) => {
        var xhr2 = new XMLHttpRequest();
        xhr2.open("GET", `get_username.php?userId=${userId}`);
        xhr2.onreadystatechange = function() {
            if (xhr2.readyState === XMLHttpRequest.DONE) {
              if(xhr2.status === 200){
                var result = JSON.parse(xhr2.responseText);
                var username = result.usersUid;
                resolve(username);
            }
            
              else{
                  reject('Error matey');
              }
            }
        };
        xhr2.send();


      });
    }

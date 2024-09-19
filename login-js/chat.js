let currentReceiverId = null;
let selectedAddUserId = null;

function selectUser(userId, userName, iconUrl) {
  selectedAddUserId = userId;

  console.log(`selected-user: ${userName} (Id: ${selectedAddUserId})`);

  // Remove previous selected receiver
  if (selectedAddUserId) {
      const userBtnAdd = document.querySelector(`.user-btn-add[data-id="${selectedAddUserId}"]`);
      if (userBtnAdd) {
          userBtnAdd.style.display = "none";
      }
  }

  // Create the new contact div
  const contactList = document.querySelector('.contact-user-box');
  const newContactDiv = document.createElement('div');
  newContactDiv.className = "contact-user";
  newContactDiv.setAttribute('data-id', userId);
  newContactDiv.setAttribute('data-name', userName);
  newContactDiv.setAttribute('data-icon', iconUrl); // Add icon URL

  newContactDiv.innerHTML = `
      <div class="icon-box">
          ${iconUrl}
      </div>
      <div class="contact-name">
          <p>${userName}</p>
      </div>
  `;



  

  contactList.appendChild(newContactDiv); // Add the new contact


  newContactDiv.onclick = () => {
    updateChat(newContactDiv);
  }

  let currentReceiverId = null;
  
  function updateChat(div) {
    console.log("You clicked updateChat");
    const userId = div.getAttribute('data-id');
    console.log("Selected User ID: " + userId);

    //currentReceiverId = userId;

    var dataToSend = "user_id=" + encodeURIComponent(userId);

    var xmlhttp = new XMLHttpRequest();
    xmlhttp.open("POST", "index.php", true);

    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

    xmlhttp.onreadystatechange = function() {

      if (xmlhttp.readyState === XMLHttpRequest.DONE) {
        // Check if the request is complete
        if (xmlhttp.status === 200) {
          const tempDiv = document.createElement('div');
          tempDiv.className = 'msg-text';
          tempDiv.innerHTML = xmlhttp.responseText;
  
          const newMessage = tempDiv.querySelector('.msg');
          console.log("new message: ", newMessage);

            if (newMessage) {
              document.getElementById('chat-msg-box').innerHTML =  newMessage.innerHTML;
          } else {
            console.error("No message found in the response.");
          }
        } else {
            console.error("Error:", xmlhttp.status);
            // Log an error if the request was unsuccessful
        }
      }
    };
    xmlhttp.send(dataToSend);

    currentReceiverId = userId;

    document.getElementById('send-btn').onclick = (event) => {
      console.log ('CurrentID from click:', userId);
      sendbtn(event, userId);
      
    }
    scrollToBottom();

  };


  // Initialize a new message thread
  //initializeMessageThread(userId, userName, iconUrl);

  // Optionally, hide the user list
  document.getElementById('user-list').style.display = 'none';


  
  
  function sendbtn(event, currentReceiverId){
    event.preventDefault(); 
    console.log ('CurrentID:', currentReceiverId);
    console.log ('appeared');

    const messageInput = document.querySelector('.message-box'); // Get the message input
    const message = messageInput.value;
    
    if (!currentReceiverId || !message) {
        console.error("No receiver ID or message to send.");
        return;
    }

    console.log('CurrentID:', currentReceiverId);
    console.log('Message:', message);

    var dataToSend = "receiver_id=" + encodeURIComponent(currentReceiverId) + "&message=" + encodeURIComponent(message);

    var xmlhttp = new XMLHttpRequest();
    xmlhttp.open("POST", "index.php", true); // Change to your PHP file for handling messages
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

    xmlhttp.onreadystatechange = function() {
        if (xmlhttp.readyState === XMLHttpRequest.DONE) {
            if (xmlhttp.status === 200) {
                const chatBox = document.getElementById('chat-msg-box'); // Your chat display area
                const newMessage = document.createElement('div');
                newMessage.className = 'msg-outer'; 

                newMessage.innerHTML = `<p class='msg-name-me'>You &nbsp Now</p><div id = "msg-text" class = "msg-text my-text send-text">${message} </div>`; // Display the sent message
                
                chatBox.appendChild(newMessage); // Append the new message to the chat box

                // Clear the input field
                messageInput.value = '';
                scrollToBottom();
            } else {
                console.error("Error:", xmlhttp.status);
            }
        }
    };

    xmlhttp.send(dataToSend);


    

  }




  // document.getElementById('messageForm').addEventListener('submit', function(event) {
  //   event.preventDefault(); 

  //   console.log ('CurrentID:', currentReceiverId);
  //   console.log ('appeared');

    if (!currentReceiverId) {
        console.error("No user selected.");
        return; // Exit if no user is selected
    }

    const formData = new FormData(this);
    formData.append('receiver_id', currentReceiverId); // Add current receiver ID

    // Add message text from the form
    const messageInput = this.querySelector('input[name="message"]'); // Adjust selector based on your input field
    formData.append('message', messageInput.value);

    var xmlhttp = new XMLHttpRequest();
    xmlhttp.open("POST", "index.php", true);
    xmlhttp.setRequestHeader("X-Requested-With", "XMLHttpRequest"); // Optional, but good for identifying AJAX requests

    xmlhttp.onreadystatechange = function() {
        if (xmlhttp.readyState === XMLHttpRequest.DONE) {
            if (xmlhttp.status === 200) {
                console.log("Server response:", xmlhttp.responseText); // Log server response
                
                try {
                    const response = JSON.parse(xmlhttp.responseText);
                    
                    if (response.error) {
                        console.error("Error:", response.error);
                    } else {
                        // Append the new message
                        const messageHtml = `<div class="msg"><strong>${response.sender_id}:</strong> ${response.message_text}</div>`;
                        document.getElementById('chat-msg-box').innerHTML += messageHtml;

                        // Clear the input field after sending
                        messageInput.value = '';
                    }
                } catch (e) {
                    console.error("JSON parsing error:", e);
                }
            } else {
                console.error("Error:", xmlhttp.status);
            }
        }
    };

    // const params = new URLSearchParams(formData).toString();
    // xmlhttp.send(params); // Send the serialized data
  //});
  




}



let selectedContact = null;

document.addEventListener('click', function(event) {
  const contactUser = event.target.closest('.contact-user');
  
  if (contactUser) {
    // Prevent default behavior
    event.preventDefault();

    // Deselect previously selected contact
    if (selectedContact != null) {
      selectedContact.classList.remove('selected');
    }

    // Select the new contact
    selectedContact = contactUser;
    const userId = selectedContact.getAttribute('data-id');
    const userName = selectedContact.getAttribute('data-name');


    // Update the hidden input with the selected user ID
    //document.getElementById('receiverId').value = userId;

    // Highlight the selected contact
    selectedContact.classList.add('selected');

    scrollToBottom();

    

    // Initialize the message thread
    
    initializeMessageThread(userId, userName, selectedContact.querySelector('.icon-box').innerHTML);
  }
});



function initializeMessageThread(userId, userName, iconUrl) {
  console.log(`Starting a message thread with ${userName} (Id: ${userId}) (Icon: ${iconUrl})`);
  
  // Update the chat header
  document.querySelector('.header-text').innerHTML = `${iconUrl} <strong>${userName} </strong> &nbsp (ID: ${userId})`;

  // Set the receiver ID in the hidden input
  document.querySelector('input[name="receiver_id"]').value = userId;
}





// let chatHeader = document.getElementsByClassName('header-text')[0];
// let chatInfo = document.getElementsByClassName('chat-info')[0];
// let isOpened = false;

// chatHeader.onclick = () => {

//   if(isOpened) {
//     chatInfo.style.display = "none";
//   } else {
//     chatInfo.style.display = "flex";
//   }
//   isOpened = !isOpened;
//   console.log ('chat-infobox:' ,isOpened)

// }

let goBottom = document.getElementById('go-bottom');
let msg = document.getElementById('chat-msg-box'); // Updated ID


msg.onscroll = () => {
  let isAtBottom = msg.scrollHeight - msg.scrollTop - 0.5 == msg.clientHeight;    
  // Show/hide button based on scroll position
  document.getElementById('go-bottom').style.display = isAtBottom ? 'none': 'flex';
};


function scrollToBottom() {
  var container = document.getElementById('chat-msg-box');
  container.scrollTop = container.scrollHeight;
}

document.addEventListener('click', (event) => {
  console.log('Clicked element:', event.target); 

  const Btn = event.target.closest('.go-bottom');

  if(Btn) {
    console.log('clicked go bottom');
    scrollToBottom();
    Btn.style.display = 'none';
  }
});


window.addEventListener('load', function() {
  const indexOuter = document.getElementById('index-outer');
  indexOuter.classList.add('visible'); 
});

window.addEventListener('load', function() {
  const chatBox = document.getElementById('msg-text');
  chatBox.classList.add('visible'); 
});






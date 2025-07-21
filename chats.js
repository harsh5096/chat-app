// Image Modal Logic
var modal = document.getElementById("myModal");
var modalImg = document.getElementById("img01");
var captionText = document.getElementById("caption");
var downloadLink = document.getElementById("downloadLink");
var copyButton = document.getElementById("copyButton");
var span = document.getElementsByClassName("close")[0];

// ✅ Make sure openModal is globally defined
function openModal(img) {
    modal.style.display = "flex"; // For centering
    modalImg.src = img.src;
    captionText.innerHTML = img.alt || "Image preview";
    downloadLink.href = img.src;
    downloadLink.download = img.src.substring(img.src.lastIndexOf('/') + 1);
}

// Copy image URL to clipboard
if (copyButton) {
    copyButton.addEventListener('click', function () {
        const imageUrl = modalImg.src;
        navigator.clipboard.writeText(imageUrl).then(function () {
            alert('Image URL copied to clipboard!');
        }).catch(function (err) {
            console.error('Could not copy text: ', err);
            alert('Failed to copy image URL.');
        });
    });
}

// Close modal on 'X'
if (span) {
    span.onclick = function () {
        modal.style.display = "none";
    };
}

// Close modal when clicking outside the image
window.onclick = function (event) {
    if (event.target == modal) {
        modal.style.display = "none";
    }
};

// DOMContentLoaded — emoji picker and auto-scroll
document.addEventListener('DOMContentLoaded', () => {
    const button = document.querySelector('#emoji-btn');
    const input = document.querySelector('#msgInput');
    const picker = new EmojiButton();
    const messageArea = document.getElementById('messageArea');

    picker.on('emoji', emoji => {
        input.value += emoji;
    });

    if (button) {
        button.addEventListener('click', () => {
            picker.togglePicker(button);
        });
    }

    // Auto-scroll to bottom
    function scrollToBottom() {
        messageArea.scrollTop = messageArea.scrollHeight;
    }

    scrollToBottom();
});


 //forward
 
const forwardModal = document.getElementById('forwardModal');
const closeForwardButton = document.querySelector('.close-forward');
const sendForwardButton = document.getElementById('sendForward');
const forwardUserList = document.getElementById('forwardUserList');

let currentMessageToForward = {};

function forwardMessage(element) {
    const messageId = element.getAttribute('data-msg-id');
    const messageText = element.getAttribute('data-msg-text');
    const messageImage = element.getAttribute('data-msg-image');

    currentMessageToForward = {
        id: messageId,
        text: messageText,
        image: messageImage
    };

    console.log("Opening forward modal for message:", currentMessageToForward);

    // Clear any previously selected checkboxes
    forwardUserList.querySelectorAll('input[name="forward_to[]"]').forEach(input => {
        input.checked = false;
    });

    // Disable the current chat partner in the forward list
    const currentChatUserCheckbox = forwardUserList.querySelector(`input[value="${new URLSearchParams(window.location.search).get('user')}"]`);
    if (currentChatUserCheckbox) {
        currentChatUserCheckbox.disabled = true;
    }

    // Show the modal
    forwardModal.style.display = 'block';
}

// Close the modal on "X"
closeForwardButton?.addEventListener('click', () => {
    forwardModal.style.display = 'none';
});

// Close if user clicks outside the modal
window.addEventListener('click', (event) => {
    if (event.target === forwardModal) {
        forwardModal.style.display = 'none';
    }
});

// Send forward request
sendForwardButton.addEventListener('click', () => {
    const checkedUsers = forwardUserList.querySelectorAll('input[name="forward_to[]"]:checked');
    const recipients = Array.from(checkedUsers).map(user => user.value);

    if (recipients.length === 0) {
        alert('Please select at least one recipient to forward to.');
        return;
    }

    if (!currentMessageToForward.id) {
        alert('Invalid message selected for forwarding.');
        return;
    }

    // Disable the button temporarily to prevent double-clicks
    sendForwardButton.disabled = true;
    sendForwardButton.textContent = 'Sending...';

    fetch('forward_message.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            message_id: currentMessageToForward.id,
            recipients: recipients
        }),
    })
    .then(response => response.json())
    .then(data => {
        sendForwardButton.disabled = false;
        sendForwardButton.textContent = 'Send Forward';

        if (data.success) {
            alert('Message forwarded successfully!');
            forwardModal.style.display = 'none';
            // Instead of a full reload, you might want to provide visual feedback
            // or potentially fetch and display the new messages in real-time if you have that functionality.
            // For now, a simple message is shown.
        } else {
            alert('Error: ' + (data.error || 'Something went wrong while forwarding.'));
        }
    })
    .catch(error => {
        console.error('Forward Error:', error);
        sendForwardButton.disabled = false;
        sendForwardButton.textContent = 'Send Forward';
        alert('An unexpected error occurred.');
    });
});
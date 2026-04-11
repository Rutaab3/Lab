// Chat Application JavaScript

let currentConversationId = null;
let messagePolling = null;
let conversationPolling = null;

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    loadConversations();
    setupEventListeners();
    
    // Start polling for updates
    startPolling();
});

// Event Listeners
function setupEventListeners() {
    // New Chat Button
    const newChatBtn = document.getElementById('newChatBtn');
    if (newChatBtn) {
        newChatBtn.addEventListener('click', showNewChatModal);
    }
    
    // Create Group Button (Admin only)
    const createGroupBtn = document.getElementById('createGroupBtn');
    if (createGroupBtn) {
        createGroupBtn.addEventListener('click', showCreateGroupModal);
    }
    
    // Message Form Submit
    const messageForm = document.getElementById('messageForm');
    if (messageForm) {
        messageForm.addEventListener('submit', sendMessage);
    }
    
    // File Input
    const fileInput = document.getElementById('fileInput');
    if (fileInput) {
        fileInput.addEventListener('change', handleFileSelect);
    }
    
    // Remove File - Use event delegation since button is created dynamically
    const filePreview = document.getElementById('filePreview');
    if (filePreview) {
        filePreview.addEventListener('click', function(e) {
            if (e.target.closest('.file-preview-remove') || e.target.closest('#removeFile')) {
                removeFile();
            }
        });
    }
    
    // Search Conversations
    const searchConversations = document.getElementById('searchConversations');
    if (searchConversations) {
        searchConversations.addEventListener('input', filterConversations);
    }
    
    // Search Users in New Chat Modal
    const searchUsers = document.getElementById('searchUsers');
    if (searchUsers) {
        searchUsers.addEventListener('input', filterUsers);
    }
    
    // Search Users in Create Group Modal
    const searchGroupUsers = document.getElementById('searchGroupUsers');
    if (searchGroupUsers) {
        searchGroupUsers.addEventListener('input', filterGroupUsers);
    }
    
    // Create Group Submit
    const createGroupSubmit = document.getElementById('createGroupSubmit');
    if (createGroupSubmit) {
        createGroupSubmit.addEventListener('click', createGroup);
    }

    // Initialize Event Delegation
    initChatDelegation();
}

// Load Conversations
function loadConversations() {
    fetch('ajax/get_conversations.php')
        .then(response => response.json())
        .then(data => {
            const conversationList = document.getElementById('conversationList');
            
            if (data.success && data.conversations.length > 0) {
                // Sort: pinned first, then by updated_at
                data.conversations.sort((a, b) => {
                    if (a.is_pinned !== b.is_pinned) {
                        return b.is_pinned - a.is_pinned; // Pinned first
                    }
                    return new Date(b.updated_at) - new Date(a.updated_at);
                });
                
                conversationList.innerHTML = '';
                data.conversations.forEach(conv => {
                    conversationList.innerHTML += createConversationItem(conv);
                });
                
                // Add click handlers for conversations
                // Note: Event delegation is now handled in initChat to prevent duplicate listeners
                
            } else {
                conversationList.innerHTML = `
                    <div class="empty-state">
                        <i class="bi bi-chat-dots"></i>
                        <p>No conversations yet. Start a new chat!</p>
                    </div>
                `;
            }
        })
        .catch(error => {
            console.error('Error loading conversations:', error);
        });
}

function initChatDelegation() {
    const conversationList = document.getElementById('conversationList');
    
    // Delegation for Conversation Clicks & Menu Actions
    if (conversationList) {
        conversationList.addEventListener('click', function(e) {
            
            // 1. Handle Dropdown Item Click (Actions)
            const dropdownItem = e.target.closest('.dropdown-item');
            if (dropdownItem) {
                // Prevent bubbling to conversation item
                e.stopPropagation(); 
                
                const action = dropdownItem.dataset.action;
                const conversationId = dropdownItem.dataset.conversationId;
                
                if (action === 'pin') togglePin(conversationId);
                if (action === 'delete') deleteConversation(conversationId);
                return;
            }

            // 2. Prevent selection when clicking the toggle button or inside menu
            if (e.target.closest('.conversation-menu')) {
                // Do not propagate click to parent conversation item logic
                return;
            }
            
            // 3. Handle Conversation Item Click (Selection)
            const conversationItem = e.target.closest('.conversation-item');
            if (conversationItem) {
                selectConversation(conversationItem.dataset.conversationId);
            }
        });
    }
}

// Create Conversation Item HTML
function createConversationItem(conv) {
    const isGroup = conv.type === 'group';
    const avatar = conv.avatar || '../uploads/users/default.png';
    const unreadBadge = conv.unread_count > 0 ? 
        `<span class="unread-badge">${conv.unread_count}</span>` : '';
    const pinnedIcon = conv.is_pinned == 1 ? '<i class="bi bi-pin-fill text-primary me-1"></i>' : '';
    
    return `
        <div class="conversation-item ${conv.is_pinned == 1 ? 'pinned' : ''}" data-conversation-id="${conv.id}">
            <img src="../${avatar}" alt="${conv.name}" class="conversation-avatar">
            <div class="conversation-details">
                <div class="conversation-name">
                    <span>${pinnedIcon}${isGroup ? '<i class="bi bi-people-fill me-1"></i>' : ''}${conv.name}</span>
                    ${unreadBadge}
                </div>
                <div class="d-flex justify-content-between align-items-center">
                    <div class="conversation-preview">${conv.last_message || 'No messages yet'}</div>
                    ${conv.last_message_time ? `<small class="conversation-time">${formatTime(conv.last_message_time)}</small>` : ''}
                </div>
            </div>
            <div class="dropdown conversation-menu">
                <button class="btn btn-sm btn-menu" type="button" data-bs-toggle="dropdown" aria-expanded="false" title="Options">
                    <i class="bi bi-three-dots-vertical"></i>
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li>
                        <button class="dropdown-item" type="button" data-action="pin" data-conversation-id="${conv.id}">
                            <i class="bi bi-pin${conv.is_pinned == 1 ? '-fill' : ''} me-2"></i>
                            ${conv.is_pinned == 1 ? 'Unpin' : 'Pin'}
                        </button>
                    </li>
                    <li>
                        <button class="dropdown-item text-danger" type="button" data-action="delete" data-conversation-id="${conv.id}">
                            <i class="bi bi-trash me-2"></i>
                            Delete
                        </button>
                    </li>
                </ul>
            </div>
        </div>
    `;
}

// Select Conversation
function selectConversation(conversationId) {
    currentConversationId = conversationId;
    document.getElementById('currentConversationId').value = conversationId;
    
    // Update UI
    document.querySelectorAll('.conversation-item').forEach(item => {
        item.classList.remove('active');
    });
    document.querySelector(`[data-conversation-id="${conversationId}"]`).classList.add('active');
    
    // Hide empty state, show chat
    document.getElementById('chatWindowEmpty').style.display = 'none';
    document.getElementById('chatActive').style.display = 'flex';
    
    // Load messages - force scroll on first load
    loadMessages(conversationId, true);
    
    // Load conversation info
    loadConversationInfo(conversationId);
}

// Load Messages
function loadMessages(conversationId, forceScroll = false) {
    fetch(`ajax/get_messages.php?conversation_id=${conversationId}`)
        .then(response => response.json())
        .then(data => {
            const chatMessages = document.getElementById('chatMessages');
            
            if (data.success) {
                // Check if user is near bottom before reloading
                const wasNearBottom = isNearBottom(chatMessages);
                
                chatMessages.innerHTML = '';
                
                if (data.messages.length > 0) {
                    let lastDate = '';
                    
                    data.messages.forEach(msg => {
                        // Add date divider if day changed
                        const msgDate = formatDate(msg.sent_at);
                        if (msgDate !== lastDate) {
                            chatMessages.innerHTML += `
                                <div class="message-date-divider">
                                    <span>${msgDate}</span>
                                </div>
                            `;
                            lastDate = msgDate;
                        }
                        
                        chatMessages.innerHTML += createMessageItem(msg);
                    });
                    
                    // Only scroll to bottom if user was already near bottom or forced
                    if (forceScroll || wasNearBottom) {
                        scrollToBottom();
                    }
                } else {
                    chatMessages.innerHTML = `
                        <div class="empty-state">
                            <i class="bi bi-chat-right-text"></i>
                            <p>No messages yet. Start the conversation!</p>
                        </div>
                    `;
                }
            }
        })
        .catch(error => {
            console.error('Error loading messages:', error);
        });
}

// Create Message Item HTML
function createMessageItem(msg) {
    const isSent = msg.sender_id == currentUserId;
    const messageClass = isSent ? 'sent' : 'received';
    const avatar = msg.sender_avatar || '../uploads/users/default.png';
    
    let fileHtml = '';
    if (msg.file_path) {
        const icon = getFileIcon(msg.file_type);
        const fileType = msg.file_type || '';
        
        // Image preview
        if (fileType.startsWith('image/')) {
            fileHtml = `
                <div class="message-file-preview">
                    <a href="../${msg.file_path}" target="_blank">
                        <img src="../${msg.file_path}" alt="${msg.file_name}" class="message-image-preview" loading="lazy">
                    </a>
                    <div class="file-info">
                        <small>${msg.file_name}</small>
                    </div>
                </div>
            `;
        }
        // Video preview
        else if (fileType.startsWith('video/')) {
            fileHtml = `
                <div class="message-file-preview">
                    <video controls class="message-video-preview">
                        <source src="../${msg.file_path}" type="${fileType}">
                        Your browser does not support the video tag.
                    </video>
                    <div class="file-info">
                        <small>${msg.file_name}</small>
                    </div>
                </div>
            `;
        }
        // Audio preview
        else if (fileType.startsWith('audio/')) {
            fileHtml = `
                <div class="message-file">
                    <i class="bi bi-${icon}"></i>
                    <div class="file-details">
                        <a href="../${msg.file_path}" target="_blank" download="${msg.file_name}">
                            ${msg.file_name}
                        </a>
                        <audio controls class="message-audio-preview">
                            <source src="../${msg.file_path}" type="${fileType}">
                        </audio>
                    </div>
                </div>
            `;
        }
        // Other files (documents, PDFs, etc.)
        else {
            const fileSize = msg.file_size ? formatFileSize(msg.file_size) : '';
            fileHtml = `
                <div class="message-file">
                    <i class="bi bi-${icon}"></i>
                    <div class="file-details">
                        <a href="../${msg.file_path}" target="_blank" download="${msg.file_name}">
                            ${msg.file_name}
                        </a>
                        ${fileSize ? `<small class="text-muted">${fileSize}</small>` : ''}
                    </div>
                </div>
            `;
        }
    }
    
    return `
        <div class="message ${messageClass}">
            <img src="../${avatar}" alt="${msg.sender_name}" class="message-avatar">
            <div class="message-content">
                ${!isSent ? `<div class="message-sender">${msg.sender_name}</div>` : ''}
                ${msg.message ? `<div class="message-text">${escapeHtml(msg.message)}</div>` : ''}
                ${fileHtml}
                <div class="message-time">${formatMessageTime(msg.sent_at)}</div>
            </div>
        </div>
    `;
}

// Send Message
function sendMessage(e) {
    e.preventDefault();
    
    const messageInput = document.getElementById('messageInput');
    const fileInput = document.getElementById('fileInput');
    const message = messageInput.value.trim();
    const file = fileInput.files[0];
    
    if (!message && !file) return;
    if (!currentConversationId) return;
    
    const formData = new FormData();
    formData.append('conversation_id', currentConversationId);
    if (message) formData.append('message', message);
    if (file) formData.append('file', file);
    
    fetch('ajax/send_message.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            messageInput.value = '';
            removeFile();
            loadMessages(currentConversationId, true); // Force scroll after sending
            loadConversations(); // Refresh conversation list
        } else {
            alert('Error sending message: ' + (data.error || 'Unknown error'));
        }
    })
    .catch(error => {
        console.error('Error sending message:', error);
        alert('Error sending message');
    });
}

// File Handling
function handleFileSelect(e) {
    const file = e.target.files[0];
    if (file) {
        const filePreviewDiv = document.getElementById('filePreview');
        const fileType = file.type;
        
        // Clear previous preview
        filePreviewDiv.innerHTML = '';
        
        // Create preview based on file type
        let previewHtml = '';
        
        // Image preview
        if (fileType.startsWith('image/')) {
            const reader = new FileReader();
            reader.onload = function(event) {
                previewHtml = `
                    <div class="file-preview-container">
                        <div class="file-preview-image">
                            <img src="${event.target.result}" alt="${file.name}">
                        </div>
                        <div class="file-preview-info">
                            <span class="file-preview-name">${file.name}</span>
                            <span class="file-preview-size">${formatFileSize(file.size)}</span>
                        </div>
                        <button type="button" class="btn btn-sm btn-danger file-preview-remove" id="removeFile">
                            <i class="bi bi-x"></i>
                        </button>
                    </div>
                `;
                filePreviewDiv.innerHTML = previewHtml;
                filePreviewDiv.style.display = 'block';
            };
            reader.readAsDataURL(file);
        }
        // Video preview
        else if (fileType.startsWith('video/')) {
            const reader = new FileReader();
            reader.onload = function(event) {
                previewHtml = `
                    <div class="file-preview-container">
                        <div class="file-preview-video">
                            <video src="${event.target.result}" controls></video>
                        </div>
                        <div class="file-preview-info">
                            <span class="file-preview-name">${file.name}</span>
                            <span class="file-preview-size">${formatFileSize(file.size)}</span>
                        </div>
                        <button type="button" class="btn btn-sm btn-danger file-preview-remove" id="removeFile">
                            <i class="bi bi-x"></i>
                        </button>
                    </div>
                `;
                filePreviewDiv.innerHTML = previewHtml;
                filePreviewDiv.style.display = 'block';
            };
            reader.readAsDataURL(file);
        }
        // PDF preview
        else if (fileType === 'application/pdf') {
            previewHtml = `
                <div class="file-preview-container">
                    <div class="file-preview-document">
                        <i class="bi bi-file-earmark-pdf-fill text-danger"></i>
                        <span class="file-type-label">PDF</span>
                    </div>
                    <div class="file-preview-info">
                        <span class="file-preview-name">${file.name}</span>
                        <span class="file-preview-size">${formatFileSize(file.size)}</span>
                    </div>
                    <button type="button" class="btn btn-sm btn-danger file-preview-remove" id="removeFile">
                        <i class="bi bi-x"></i>
                    </button>
                </div>
            `;
            filePreviewDiv.innerHTML = previewHtml;
            filePreviewDiv.style.display = 'block';
        }
        // Text file preview
        else if (fileType.startsWith('text/') || file.name.endsWith('.txt')) {
            const reader = new FileReader();
            reader.onload = function(event) {
                const textContent = event.target.result.substring(0, 200); // First 200 chars
                previewHtml = `
                    <div class="file-preview-container">
                        <div class="file-preview-text">
                            <i class="bi bi-file-earmark-text"></i>
                            <pre>${escapeHtml(textContent)}${event.target.result.length > 200 ? '...' : ''}</pre>
                        </div>
                        <div class="file-preview-info">
                            <span class="file-preview-name">${file.name}</span>
                            <span class="file-preview-size">${formatFileSize(file.size)}</span>
                        </div>
                        <button type="button" class="btn btn-sm btn-danger file-preview-remove" id="removeFile">
                            <i class="bi bi-x"></i>
                        </button>
                    </div>
                `;
                filePreviewDiv.innerHTML = previewHtml;
                filePreviewDiv.style.display = 'block';
            };
            reader.readAsText(file);
        }
        // Audio preview
        else if (fileType.startsWith('audio/')) {
            const reader = new FileReader();
            reader.onload = function(event) {
                previewHtml = `
                    <div class="file-preview-container">
                        <div class="file-preview-audio">
                            <i class="bi bi-file-earmark-music"></i>
                            <audio src="${event.target.result}" controls></audio>
                        </div>
                        <div class="file-preview-info">
                            <span class="file-preview-name">${file.name}</span>
                            <span class="file-preview-size">${formatFileSize(file.size)}</span>
                        </div>
                        <button type="button" class="btn btn-sm btn-danger file-preview-remove" id="removeFile">
                            <i class="bi bi-x"></i>
                        </button>
                    </div>
                `;
                filePreviewDiv.innerHTML = previewHtml;
                filePreviewDiv.style.display = 'block';
            };
            reader.readAsDataURL(file);
        }
        // Other documents (Word, Excel, etc.)
        else {
            const icon = getFileIconForPreview(fileType, file.name);
            previewHtml = `
                <div class="file-preview-container">
                    <div class="file-preview-document">
                        <i class="bi bi-${icon}"></i>
                        <span class="file-type-label">${getFileTypeLabel(file.name)}</span>
                    </div>
                    <div class="file-preview-info">
                        <span class="file-preview-name">${file.name}</span>
                        <span class="file-preview-size">${formatFileSize(file.size)}</span>
                    </div>
                    <button type="button" class="btn btn-sm btn-danger file-preview-remove" id="removeFile">
                        <i class="bi bi-x"></i>
                    </button>
                </div>
            `;
            filePreviewDiv.innerHTML = previewHtml;
            filePreviewDiv.style.display = 'block';
        }
    }
}

function removeFile() {
    document.getElementById('fileInput').value = '';
    document.getElementById('filePreview').style.display = 'none';
    document.getElementById('filePreview').innerHTML = '';
}

// New Chat Modal
function showNewChatModal() {
    loadUsers();
    new bootstrap.Modal(document.getElementById('newChatModal')).show();
}

function loadUsers() {
    fetch('ajax/get_users.php')
        .then(response => response.json())
        .then(data => {
            const usersList = document.getElementById('usersList');
            
            if (data.success && data.users.length > 0) {
                usersList.innerHTML = '';
                data.users.forEach(user => {
                    usersList.innerHTML += createUserItem(user);
                });
                
                // Add click handlers
                document.querySelectorAll('.user-item').forEach(item => {
                    item.addEventListener('click', function() {
                        startConversation(this.dataset.userId);
                    });
                });
            } else {
                usersList.innerHTML = '<div class="empty-state"><p>No users found</p></div>';
            }
        })
        .catch(error => {
            console.error('Error loading users:', error);
        });
}

function createUserItem(user, withCheckbox = false) {
    const avatar = user.profile_img || 'uploads/users/default.png';
    const checkbox = withCheckbox ? 
        `<input type="checkbox" class="form-check-input user-item-checkbox" data-user-id="${user.id}">` : '';
    
    return `
        <div class="user-item" data-user-id="${user.id}">
            ${checkbox}
            <img src="../${avatar}" alt="${user.username}" class="user-item-avatar">
            <div class="user-item-details">
                <div class="user-item-name">${user.username}</div>
                <div class="user-item-role">${user.role}</div>
            </div>
        </div>
    `;
}

function startConversation(userId) {
    fetch('ajax/create_conversation.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ user_id: userId })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            bootstrap.Modal.getInstance(document.getElementById('newChatModal')).hide();
            loadConversations();
            setTimeout(() => {
                selectConversation(data.conversation_id);
            }, 300);
        } else {
            alert('Error creating conversation: ' + (data.error || 'Unknown error'));
        }
    })
    .catch(error => {
        console.error('Error creating conversation:', error);
    });
}

// Group Creation (Admin only)
function showCreateGroupModal() {
    loadGroupUsers();
    new bootstrap.Modal(document.getElementById('createGroupModal')).show();
}

function loadGroupUsers() {
    fetch('ajax/get_users.php')
        .then(response => response.json())
        .then(data => {
            const groupUsersList = document.getElementById('groupUsersList');
            
            if (data.success && data.users.length > 0) {
                groupUsersList.innerHTML = '';
                data.users.forEach(user => {
                    groupUsersList.innerHTML += createUserItem(user, true);
                });
            } else {
                groupUsersList.innerHTML = '<div class="empty-state"><p>No users found</p></div>';
            }
        })
        .catch(error => {
            console.error('Error loading users:', error);
        });
}

function createGroup() {
    const groupName = document.getElementById('groupName').value.trim();
    const selectedUsers = Array.from(document.querySelectorAll('#groupUsersList .user-item-checkbox:checked'))
        .map(cb => cb.dataset.userId);
    
    if (!groupName) {
        alert('Please enter a group name');
        return;
    }
    
    if (selectedUsers.length === 0) {
        alert('Please select at least one member');
        return;
    }
    
    fetch('ajax/create_group.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
            name: groupName,
            members: selectedUsers
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            bootstrap.Modal.getInstance(document.getElementById('createGroupModal')).hide();
            document.getElementById('groupName').value = '';
            loadConversations();
            setTimeout(() => {
                selectConversation(data.conversation_id);
            }, 300);
        } else {
            alert('Error creating group: ' + (data.error || 'Unknown error'));
        }
    })
    .catch(error => {
        console.error('Error creating group:', error);
    });
}

// Load Conversation Info
function loadConversationInfo(conversationId) {
    fetch(`ajax/get_conversations.php?conversation_id=${conversationId}`)
        .then(response => response.json())
        .then(data => {
            if (data.success && data.conversations.length > 0) {
                const conv = data.conversations[0];
                
                // Update header
                document.getElementById('chatHeaderName').textContent = conv.name;
                document.getElementById('chatHeaderAvatar').src = '../' + (conv.avatar || 'uploads/users/default.png');
                document.getElementById('chatHeaderStatus').textContent = conv.type === 'group' ? 
                    `${conv.participant_count} members` : 'Online';
                
                // Update info panel
                document.getElementById('infoName').textContent = conv.name;
                document.getElementById('infoAvatar').src = '../' + (conv.avatar || 'uploads/users/default.png');
                
                if (conv.type === 'group') {
                    // Show group members
                    document.getElementById('directInfo').style.display = 'none';
                    document.getElementById('groupInfo').style.display = 'block';
                    loadGroupMembers(conversationId);
                } else {
                    // Hide for direct chats
                    document.getElementById('directInfo').style.display = 'block';
                    document.getElementById('groupInfo').style.display = 'none';
                }
            }
        })
        .catch(error => {
            console.error('Error loading conversation info:', error);
        });
}

// Load Group Members
function loadGroupMembers(conversationId) {
    fetch(`ajax/get_members.php?conversation_id=${conversationId}`)
        .then(response => response.json())
        .then(data => {
            const membersList = document.getElementById('membersList');
            
            if (data.success && data.members.length > 0) {
                membersList.innerHTML = '';
                data.members.forEach(member => {
                    const avatar = member.profile_img || 'uploads/users/default.png';
                    membersList.innerHTML += `
                        <div class="member-item">
                            <img src="../${avatar}" alt="${member.username}" class="member-avatar">
                            <div class="member-info">
                                <div class="member-username">${member.username}</div>
                                <div class="member-email">${member.email}</div>
                            </div>
                        </div>
                    `;
                });
            } else {
                membersList.innerHTML = '<p class="text-muted small">No members</p>';
            }
        })
        .catch(error => {
            console.error('Error loading members:', error);
        });
}

// Toggle Chat Info Panel
function toggleChatInfo() {
    const chatInfo = document.getElementById('chatInfo');
    chatInfo.style.display = chatInfo.style.display === 'none' ? 'block' : 'none';
}

// Filter Functions
function filterConversations() {
    const searchTerm = this.value.toLowerCase();
    document.querySelectorAll('.conversation-item').forEach(item => {
        const name = item.querySelector('.conversation-name span').textContent.toLowerCase();
        item.style.display = name.includes(searchTerm) ? 'flex' : 'none';
    });
}

function filterUsers() {
    const searchTerm = this.value.toLowerCase();
    document.querySelectorAll('#usersList .user-item').forEach(item => {
        const name = item.querySelector('.user-item-name').textContent.toLowerCase();
        item.style.display = name.includes(searchTerm) ? 'flex' : 'none';
    });
}

function filterGroupUsers() {
    const searchTerm = this.value.toLowerCase();
    document.querySelectorAll('#groupUsersList .user-item').forEach(item => {
        const name = item.querySelector('.user-item-name').textContent.toLowerCase();
        item.style.display = name.includes(searchTerm) ? 'flex' : 'none';
    });
}

// Polling for Updates
function startPolling() {
    // Poll conversations every 5 seconds
    conversationPolling = setInterval(() => {
        loadConversations();
    }, 5000);
    
    // Poll messages every 3 seconds if conversation is active
    messagePolling = setInterval(() => {
        if (currentConversationId) {
            loadMessages(currentConversationId);
        }
    }, 3000);
}

// Utility Functions
function scrollToBottom() {
    const chatMessages = document.getElementById('chatMessages');
    chatMessages.scrollTop = chatMessages.scrollHeight;
}

function isNearBottom(element) {
    // Check if user is within 100px of the bottom
    const threshold = 100;
    const position = element.scrollTop + element.clientHeight;
    const height = element.scrollHeight;
    return position >= height - threshold;
}

function formatTime(timestamp) {
    const date = new Date(timestamp);
    const now = new Date();
    const diff = now - date;
    
    if (diff < 86400000) { // Less than 24 hours
        return date.toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit' });
    } else if (diff < 604800000) { // Less than 7 days
        return date.toLocaleDateString('en-US', { weekday: 'short' });
    } else {
        return date.toLocaleDateString('en-US', { month: 'short', day: 'numeric' });
    }
}

function formatDate(timestamp) {
    const date = new Date(timestamp);
    const today = new Date();
    const yesterday = new Date(today);
    yesterday.setDate(yesterday.getDate() - 1);
    
    if (date.toDateString() === today.toDateString()) {
        return 'Today';
    } else if (date.toDateString() === yesterday.toDateString()) {
        return 'Yesterday';
    } else {
        return date.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });
    }
}

function formatMessageTime(timestamp) {
    const date = new Date(timestamp);
    return date.toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit' });
}

function getFileIcon(fileType) {
    if (!fileType) return 'file-earmark';
    
    if (fileType.startsWith('image/')) return 'file-earmark-image';
    if (fileType.startsWith('video/')) return 'file-earmark-play';
    if (fileType.startsWith('audio/')) return 'file-earmark-music';
    if (fileType.includes('pdf')) return 'file-earmark-pdf';
    if (fileType.includes('word')) return 'file-earmark-word';
    if (fileType.includes('excel') || fileType.includes('spreadsheet')) return 'file-earmark-excel';
    if (fileType.includes('zip') || fileType.includes('rar')) return 'file-earmark-zip';
    
    return 'file-earmark';
}

function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

// Pin/Unpin Conversation
function togglePin(conversationId) {
    fetch('ajax/pin_conversation.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `conversation_id=${conversationId}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            loadConversations(); // Reload to show updated pin status and sorting
        } else {
            alert('Error: ' + (data.error || 'Failed to pin conversation'));
        }
    })
    .catch(error => {
        console.error('Error toggling pin:', error);
    });
}

// Delete Conversation
function deleteConversation(conversationId) {
    Swal.fire({
        title: 'Are you sure?',
        text: "You are about to delete this conversation",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, proceed!',
        customClass: {
            popup: 'swal2-confirm',
            confirmButton: 'swal2-confirm',
            cancelButton: 'swal2-cancel'
        }
    }).then((result) => {
        if (result.isConfirmed) {
            fetch('ajax/delete_conversation.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `conversation_id=${conversationId}`
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Deleted!',
                        text: 'Conversation has been deleted.',
                        customClass: {
                            popup: 'swal2-success'
                        },
                        timer: 1500,
                        showConfirmButton: false
                    });
                    
                    // If currently viewing this conversation, hide chat window
                    if (currentConversationId == conversationId) {
                        document.getElementById('chatActive').style.display = 'none';
                        document.getElementById('chatWindowEmpty').style.display = 'flex';
                        currentConversationId = null;
                    }
                    loadConversations(); // Reload conversation list
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: data.error || 'Failed to delete conversation',
                        customClass: {
                            popup: 'swal2-error'
                        }
                    });
                }
            })
            .catch(error => {
                console.error('Error deleting conversation:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'An unexpected error occurred',
                    customClass: {
                        popup: 'swal2-error'
                    }
                });
            });
        }
    });
}


function formatFileSize(bytes) {
    if (bytes === 0) return '0 Bytes';
    const k = 1024;
    const sizes = ['Bytes', 'KB', 'MB', 'GB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return Math.round(bytes / Math.pow(k, i) * 100) / 100 + ' ' + sizes[i];
}

function getFileIconForPreview(fileType, fileName) {
    if (fileType.includes('word') || fileName.endsWith('.doc') || fileName.endsWith('.docx')) {
        return 'file-earmark-word-fill text-primary';
    }
    if (fileType.includes('excel') || fileType.includes('spreadsheet') || fileName.endsWith('.xls') || fileName.endsWith('.xlsx')) {
        return 'file-earmark-excel-fill text-success';
    }
    if (fileType.includes('zip') || fileType.includes('rar') || fileName.endsWith('.zip') || fileName.endsWith('.rar')) {
        return 'file-earmark-zip-fill text-warning';
    }
    if (fileName.endsWith('.ppt') || fileName.endsWith('.pptx')) {
        return 'file-earmark-slides-fill text-danger';
    }
    return 'file-earmark-fill text-secondary';
}

function getFileTypeLabel(fileName) {
    const ext = fileName.split('.').pop().toUpperCase();
    return ext || 'FILE';
}

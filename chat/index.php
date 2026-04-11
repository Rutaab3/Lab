<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

include "../config/db.php";

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../users/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$user_role = $_SESSION['role'] ?? 'user';

// Fetch current user info
$user_query = mysqli_query($conn, "SELECT username, profile_img, role FROM users WHERE id = $user_id");
$current_user = mysqli_fetch_assoc($user_query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat - Lab Automation</title>
    <!-- Use the same links as dashboard -->
    <?php include "../xtras/link.php"; ?>
    <link rel="stylesheet" href="../css/board.css">
    <link rel="stylesheet" href="chat.css">
    <link rel="stylesheet" href="alert.css">
    <style>
        /* Ensure chat container is visible */
        #main {
           height: 70vh !important;
            display: flex;
            flex-direction: column;
        }
        .chat-wrapper {
            flex: 1;
        }
    </style>
</head>
<body>
    <?php 
    $role_lower = strtolower($user_role);
    $header_file = "../xtras/{$role_lower}head.php";
    if (file_exists($header_file)) {
        include $header_file;
    } else {
        echo '<nav id="sidebar"></nav><div id="content">';
    }
    ?>

    <main class="container-fluid p-4" id="main">
        <div class="row">
            <div class="col-12 chat-wrapper">
                <div class="chat-container">
                    <!-- Conversations Sidebar -->
                    <div class="chat-sidebar">
                        <div class="chat-sidebar-header">
                            <h5 class="mb-0">Messages</h5>
                            <div class="chat-actions">
                                <?php if ($user_role === 'admin'): ?>
                                <button class="btn btn-sm btn-info" id="createGroupBtn" title="Create Group">
                                    <i class="bi bi-people-fill"></i>
                                </button>
                                <?php endif; ?>
                                <button class="btn btn-sm btn-info" id="newChatBtn" title="New Chat">
                                    <i class="bi bi-pencil-square"></i>
                                </button>
                            </div>
                        </div>
                        <div class="chat-search">
                            <input type="text" class="form-control" id="searchConversations" placeholder="Search conversations...">
                        </div>
                        <div class="conversation-list" id="conversationList">
                            <div class="text-center p-4 text-muted">
                                <i class="bi bi-chat-dots fs-1"></i>
                                <p>Loading conversations...</p>
                            </div>
                        </div>
                    </div>

                    <!-- Chat Window -->
                    <div class="chat-window">
                        <div class="chat-window-empty" id="chatWindowEmpty">
                            <i class="bi bi-chat-text fs-1 text-muted"></i>
                            <h5 class="text-muted mt-3">Select a conversation to start messaging</h5>
                        </div>
                        <div class="chat-active" id="chatActive" style="display: none;">
                            <div class="chat-header">
                                <div class="chat-header-info">
                                    <img src="../uploads/users/default.png" alt="User" class="chat-header-avatar" id="chatHeaderAvatar">
                                    <div>
                                        <h6 class="mb-0" id="chatHeaderName">User Name</h6>
                                        <!-- <small class="text-muted" id="chatHeaderStatus">Online</small> -->
                                    </div>
                                </div>
                            </div>
                            <div class="chat-messages" id="chatMessages">
                                <!-- Messages will be loaded here -->
                            </div>
                            <div class="chat-input">
                                <form id="messageForm" enctype="multipart/form-data">
                                    <input type="hidden" id="currentConversationId" value="">
                                    <div class="input-group">
                                        <label for="fileInput" class="btn btn-outline-secondary" title="Attach file">
                                            <i class="bi bi-paperclip"></i>
                                        </label>
                                        <input type="file" id="fileInput" name="file" style="display: none;">
                                        <input type="text" class="form-control" id="messageInput" name="message" placeholder="Type a message..." autocomplete="off">
                                        <button type="submit" class="btn btn-primary" id="sendBtn">
                                            <i class="bi bi-send-fill"></i>
                                        </button>
                                    </div>
                                    <div id="filePreview" style="display: none;">
                                        <!-- File preview will be dynamically inserted here -->
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Chat Info Panel -->
                    <div class="chat-info" id="chatInfo" style="display: none;">
                        <div class="chat-info-content">
                            <div class="text-center mb-4">
                                <img src="../uploads/users/default.png" alt="User" class="chat-info-avatar mb-3" id="infoAvatar">
                                <h5 id="infoName">User Name</h5>
                            </div>
                            
                            <!-- Direct Chat Info -->
                            <div id="directInfo" style="display: none;">
                                <p><strong>Username:</strong> <span id="infoUsername"></span></p>
                                <p><strong>Email:</strong> <span id="infoEmail"></span></p>
                                <p><strong>Type:</strong> Direct</p>
                            </div>
                            
                            <!-- Group Members List -->
                            <div id="groupInfo" style="display: none;">
                                <h6 class="mb-3">Members</h6>
                                <div id="membersList"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- New Chat Modal -->
    <div class="modal fade" id="newChatModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">New Conversation</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="text" class="form-control mb-3" id="searchUsers" placeholder="Search users...">
                    <div id="usersList" class="users-list">
                        <!-- Users will be loaded here -->
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Create Group Modal (Admin Only) -->
    <?php if ($user_role === 'admin'): ?>
    <div class="modal fade" id="createGroupModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Create Group</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="groupName" class="form-label">Group Name</label>
                        <input type="text" class="form-control" id="groupName" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Add Members</label>
                        <input type="text" class="form-control mb-2" id="searchGroupUsers" placeholder="Search users...">
                        <div id="groupUsersList" class="users-list">
                            <!-- Users with checkboxes will be loaded here -->
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="createGroupSubmit">Create Group</button>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <!-- Scripts -->
    <script src="chat.js"></script>
    <script>
        // Initialize chat with current user info
        const currentUserId = <?php echo $user_id; ?>;
        const currentUserRole = '<?php echo $user_role; ?>';
        const currentUserName = '<?php echo htmlspecialchars($current_user['username']); ?>';
    </script>
</body>
</html>

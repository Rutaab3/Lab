# Google OAuth 2.0 Setup Guide

Follow these steps to create a new Google Cloud Project and get your credentials.

## Step 1: Create a Project
1. Go to the [Google Cloud Console](https://console.cloud.google.com/).
2. Click the project dropdown in the top-left (next to the Google Cloud logo).
3. Click **New Project**.
4. Enter a **Project Name** (e.g., "Lab Login").
5. Click **Create**.

## Step 2: Configure OAuth Consent Screen
1. In the left sidebar, navigate to **APIs & Services > OAuth consent screen**.
2. Select **External** (unless you have a Google Workspace organization).
3. Click **Create**.
4. Fill in the **App Information**:
   - **App name**: Lab Login
   - **User support email**: Your email
   - **Developer contact information**: Your email
5. Click **Save and Continue** through the "Scopes" and "Test Users" sections.
6. On the Summary page, click **Back to Dashboard**.

## Step 3: Create Credentials
1. In the left sidebar, go to **APIs & Services > Credentials**.
2. Click **+ CREATE CREDENTIALS** (top of the screen) and select **OAuth client ID**.
3. **Application type**: Select **Web application**.
4. **Name**: Enter "Lab Web Client" (or any name).
5. **Authorized JavaScript origins**:
   - Click **ADD URI**.
   - Enter: `http://localhost`
   - Click **ADD URI** again.
   - Enter: `http://localhost:82` (if you use this port)
6. **Authorized redirect URIs** (CRITICAL):
   - Click **ADD URI**.
   - Enter: `http://localhost/lab/users/register.php`
   - Click **ADD URI**.
   - Enter: `http://localhost:82/lab/users/register.php`
7. Click **Create**.

## Step 4: Download JSON
1. A popup will appear with your Client ID and Client Secret.
2. Click **DOWNLOAD JSON**.
3. Save the file.

## Step 5: Update Your Project
1. Rename the downloaded file to `google.json`.
2. Move it to your project folder: `c:\xampp\htdocs\lab\users\google.json`.
3. Open the file and ensure the structure matches what the code expects (it usually does, but our code looks for `['web']`). The downloaded file will start with `{"web": ...}` which is perfect.

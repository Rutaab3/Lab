Act as a fully autonomous agent to create a new Google Cloud project, configure OAuth for Google Sign-In, create a Web application OAuth client ID, and extract the client_id and client_secret.

Follow these steps precisely, without asking for confirmation unless blocked:

1. Open https://console.cloud.google.com/ in a new tab (Google Cloud Console).

2. If not logged in, log in to a Google account (use secure autofill; prioritize an existing account).

3. Create a new project:
   - Click the project dropdown (top bar) > "New Project".
   - Project name: "My Google Sign-In Test"
   - Leave organization as default or "No organization".
   - Create the project.

4. Once in the new project dashboard, go to APIs & Services > OAuth consent screen (or directly: https://console.cloud.google.com/apis/consent).

5. If prompted, select User Type: "External" > Create.

6. Fill basic OAuth consent screen (Testing mode is fine):
   - App name: "My Test Sign-In App"
   - User support email: Use the logged-in email
   - Developer contact email: Same email
   - App domain: Skip or use defaults if required
   - Authorized domains: Add nothing or "localhost" if prompted
   - Links (homepage, privacy policy, terms): Use placeholders like "http://localhost" or skip if optional
   - Scopes: Add the default ones or specifically openid, email, profile if prompted
   - Test users: Add your own email
   - Save and Continue through all steps (stay in Testing mode – no verification needed).

7. Go to APIs & Services > Credentials (or https://console.cloud.google.com/apis/credentials).

8. Click "Create Credentials" > "OAuth client ID".

9. Application type: "Web application"

10. Name: "Web Client for Localhost"

11. Authorized JavaScript origins: Add "http://localhost" (and "http://localhost:82" if needed)

12. Authorized redirect URIs: Add "http://localhost" and "http://localhost:82" (or your ports)

13. Create the client.

14. Once created, click the client name or download the JSON (if available) to view/reveal the Client ID and Client Secret.

Finally, output ONLY the credentials in this exact JSON format (replace with the real values):

{
  "web": {
    "client_id": "YOUR_CLIENT_ID_HERE.apps.googleusercontent.com",
    "project_id": "YOUR_PROJECT_ID_HERE",
    "auth_uri": "https://accounts.google.com/o/oauth2/auth",
    "token_uri": "https://oauth2.googleapis.com/token",
    "auth_provider_x509_cert_url": "https://www.googleapis.com/oauth2/v1/certs",
    "client_secret": "YOUR_CLIENT_SECRET_HERE",
    "redirect_uris": ["http://localhost:82", "http://localhost"],
    "javascript_origins": ["http://localhost:82", "http://localhost"]
  },
  "note": "This is Google OAuth 2.0 credentials for Sign in with Google. No phone or card required for basic setup."
}

If any step is blocked (e.g., consent screen issue or credential not created), report the exact error message and stop.
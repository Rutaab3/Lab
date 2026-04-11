Act as an autonomous agent to create a new Meta (Facebook) developer app for "Facebook Login" integration, generate the App ID and App Secret, and output them in a JSON format similar to a Google OAuth credentials file.

Follow these exact steps without asking for confirmation unless absolutely necessary:

1. Open https://developers.facebook.com/ in a new tab.

2. If not logged in, log in using my Facebook account. (Note: Use secure autofill if available; do not expose credentials.)

3. Go to "My Apps" (or directly to https://developers.facebook.com/apps/).

4. Click "Create App".

5. Select app type: "Consumer" or "None" (whichever is the default for personal/developer use; avoid business if possible to skip verification).

6. Enter a display name: "My Test Login App".

7. Complete any captcha or verification if prompted.

8. Once the app is created, go to the app dashboard.

9. Add the product "Facebook Login" by clicking "+ Add Product" and selecting "Facebook Login" > Setup.

10. In the settings (Basic Settings or Dashboard), locate and reveal the "App ID" and "App Secret" (click "Show" for the secret if hidden).

11. Also, note any default settings.

12. Do not submit for review or add platforms yet unless required.

Finally, output ONLY the credentials in this exact JSON format (replace with real values):

{
  "facebook": {
    "app_id": "YOUR_APP_ID_HERE",
    "app_secret": "YOUR_APP_SECRET_HERE",
    "note": "This is equivalent to Google OAuth client credentials for Facebook Login. Core integration is free."
  }
}

If any step fails (e.g., login required or captcha blocks), report the exact issue and stop.
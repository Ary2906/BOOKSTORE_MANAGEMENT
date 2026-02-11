# AI Chatbot Setup Guide

Your e-commerce shop now has an AI-powered chatbot! Follow these steps to activate it.

## 📋 Requirements

- Google Gemini API key (free)
- No additional PHP libraries needed (uses cURL which is standard in PHP)

## 🚀 Setup Steps

### Step 1: Get Your Gemini API Key

1. Go to [Google AI Studio](https://aistudio.google.com/app/apikey)
2. Click "Create API Key"
3. Click "Create API key in new project"
4. Copy the generated API key

### Step 2: Add API Key to Chatbot

1. Open `chatbot.php` in the root directory
2. Find this line (around line 6):
   ```php
   define('GEMINI_API_KEY', 'YOUR_GEMINI_API_KEY_HERE');
   ```
3. Replace `YOUR_GEMINI_API_KEY_HERE` with your actual API key
4. Save the file

**Example:**
```php
define('GEMINI_API_KEY', 'AIzaSyDxxxxxxxxxxxxxxxxxxx');
```

### Step 3: Verify Installation

1. Go to your shop's home page: `http://localhost/pro2/home.php`
2. Look for a **purple chat bubble** in the bottom-right corner
3. Click it to open the chatbot
4. Type a message to test

## 📁 Files Added/Modified

### New Files:
- `chatbot.php` - Backend API handler
- `js/chatbot.js` - Frontend widget logic
- `css/chatbot_style.css` - Chatbot styling

### Modified Files:
- `home.php` - Added chatbot widget
- `shop.php` - Added chatbot widget
- `cart.php` - Added chatbot widget
- `checkout.php` - Added chatbot widget
- `contact.php` - Added chatbot widget

## 💬 Chatbot Features

✅ **24/7 Customer Support** - Answers questions instantly
✅ **Product Help** - Provides product information and recommendations
✅ **Order Assistance** - Helps with checkout and order inquiries
✅ **Chat History** - Remembers conversation context (stored in browser)
✅ **Mobile Friendly** - Works on all devices
✅ **Professional UI** - Modern, interactive chat interface

## 🔧 Customization

### Change Chatbot Appearance

Edit `css/chatbot_style.css`:
- **Colors**: Modify `--chatbot-primary`, `--chatbot-user-bg`, etc.
- **Size**: Adjust `.chatbot-window` width and height
- **Position**: Modify `bottom` and `right` in `#chatbot-widget`

**Example - Change chat bubble color to blue:**
```css
:root {
    --chatbot-primary: #0EA5E9;
    --chatbot-primary-light: #06B6D4;
}
```

### Change System Behavior

Edit `chatbot.php`:
- **Personality**: Modify the `$system_prompt` around line 83
- **Temperature**: Adjust `'temperature' => 0.7` (0=deterministic, 1=creative)
- **Max tokens**: Change `'maxOutputTokens' => 1024` for longer responses

## ⚠️ Important Notes

1. **API Costs**: Google Gemini has a free tier, but monitor usage to avoid charges
2. **Security**: Never commit your API key to version control
3. **Privacy**: Chat history is stored in browser localStorage (not sent to server)
4. **CORS**: If you get CORS errors, your server is correctly isolated
5. **Rate Limits**: Google Gemini free tier has rate limits - implement waiting periods if needed

## 🐛 Troubleshooting

### Chatbot bubble doesn't appear
- Clear browser cache (Ctrl+Shift+R)
- Check console for JavaScript errors (F12)
- Verify `js/chatbot.js` is loaded

### "API key not configured" error
- Confirm you added your key to `chatbot.php`
- Verify there are no extra spaces in the key
- Restart your server if needed

### Chatbot responds slowly
- This is normal on first request (API startup)
- Google Gemini takes 1-3 seconds per response
- Subsequent messages are faster due to caching

### Chat not working at all
- Check browser console for errors (F12 → Console tab)
- Verify `chatbot.php` exists in root directory
- Ensure PHP cURL is enabled on your server

## 📞 API Rate Limits (Free Tier)

- **Requests per minute**: 60
- **Requests per day**: 1,500
- **Requests per year**: 90,000

Upgrade your quota in [Google AI Studio](https://aistudio.google.com/app/apikey) if needed.

## 🔐 Security Best Practices

1. Never share your API key
2. Consider environment variables for production:
   ```php
   define('GEMINI_API_KEY', getenv('GEMINI_API_KEY'));
   ```
3. Add IP whitelisting in Google Cloud Console
4. Implement request validation in production

## 📊 Monitor Usage

1. Go to [Google AI Studio](https://aistudio.google.com/app/apikey)
2. Check "API requests" tab for usage statistics
3. Set up billing alerts if you upgrade from free tier

---

**Happy chatting! 🤖**

For more info: [Google Gemini API Docs](https://ai.google.dev/)

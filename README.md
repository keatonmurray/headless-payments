# 💳 Headless Payments

**Headless Payments** is an open-source, modular WordPress plugin for handling **PayPal payments via REST API**, purpose-built for **headless architectures** — such as React frontends paired with WordPress/Woocommerce backends.

Currently supports **PayPal**, with a clean OOP structure, customizable gateway settings, and a frontend-agnostic design — making it ideal for **JAMstack-style** setups using WordPress as a headless CMS.

---

## 🚀 Features

- ✅ PayPal integration (Sandbox + Live)
- ✅ Admin settings page with configurable credentials
- ✅ Toggle gateways on/off via UI
- ✅ Custom REST API endpoints for:
  - Creating PayPal orders
  - Capturing payments after approval
- ✅ Cleanly namespaced, modular OOP structure
- ✅ Ready for extension: Stripe, Square, etc.
- ✅ React/Vite compatible (but backend-agnostic)

---

# 📦 Coming Soon

Support for Stripe, Authorize.net, and Square.


## 📦 Requirements

- WordPress 6.x+
- WooCommerce (optional)
- PHP 7.4+
- A headless frontend (React, Next.js, Vue, etc.)
- Composer (for local development)

---

## 🛠 Installation

1. Clone this repository or download the ZIP.
2. Unzip the folder and

## 🔧 Setup

1. **Activate the plugin**

   Go to your WordPress dashboard:

   - Navigate to **Plugins → Installed Plugins**
   - Activate **Headless Payments**

2. **Configure payment settings**

   Once activated:

   - Navigate to **Headless Payments** in the WordPress admin sidebar
   - Configure the following fields under the **PayPal** tab:

     - **Mode**: Choose between `Sandbox` (test) or `Live` (production)
     - **Client ID**: Your PayPal REST API client ID
     - **Secret**: Your PayPal REST API secret

   Be sure to save your settings.

3. **Frontend Integration**

   Use the `/paypal/create-order` and `/paypal/capture-order` REST API endpoints in your frontend app (React, Vue, etc.) to handle checkout flow.

   Example `.env.local` for frontend:
   ```env
   VITE_API_BASE_URL=http://your-site.test/wp-json/hp/v1

   ---

## ⚠️ Disclaimer

This plugin is **experimental** and intended primarily for development, testing, and learning purposes. It has **not yet been tested in production environments**.

Use at your own risk. We strongly recommend thorough testing in a staging environment before deploying to a live site.

Contributions, issues, and suggestions are welcome as we continue to improve stability, security, and extensibility.


```bash
wp-content/plugins/headless-payments/


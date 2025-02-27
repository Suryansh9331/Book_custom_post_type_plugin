# Book_custom_post_type_plugin
custom post type plugin for wordpress which allow the functionalities of CPT  at frontend  , only for logged in users 

# 📚 Custom Book Post Type Plugin

A **WordPress plugin** that creates a **Custom Post Type (CPT) "Books"** with **Genre taxonomies** and allows users to **Add, Edit, and Delete** books from the **frontend**. Users can also upload **featured images** for their books. The book list is only visible to **frontend users**, not in the admin panel.

---

## 📌 Features

✅ Creates a **Custom Post Type (CPT)** for Books 📖  
✅ Adds a **Genre Taxonomy** for categorization 🎭  
✅ Allows users to **Add, Edit, Delete** books from the **frontend** ✍️  
✅ Users can **upload a featured image** for each book 🖼️  
✅ The book list is **only visible on the frontend** 👀  
✅ Uses **AJAX for smooth operations** ⚡  
✅ Secure with **nonce verification** 🔐  
✅ Easy to install and use 🔧  

---

## 🚀 Installation

1️⃣ **Download the Plugin** or clone the repository.  
2️⃣ Upload the plugin to your **WordPress plugins directory** (`wp-content/plugins/`).  
3️⃣ Activate the plugin from the **WordPress Admin Panel**.  
4️⃣ The **Books post type** and **Genre taxonomy** will be automatically registered inside the admin dashboard sidebar  ✅

---

## 🛠️ Setup & Usage

### 1️⃣ Display the Add/Edit/Delete Book Form on Frontend 📝
Add the following shortcode to any page where you want users to **manage books**:

```php
[book_frontend_form]
```

### 2️⃣ Display the List of Books 📋
Add this shortcode to any page where you want to **show all books**:

```php
[book_list]
```

📌 *This list will be visible only to frontend users.*

---

## 🛡️ Security Measures

🔹 **Nonce verification** to prevent CSRF attacks 🔒  
🔹 Only logged-in users can add/edit/delete books 👤  
🔹 Image uploads restricted to **allowed file types** (JPEG, PNG) 🖼️  

---

## ⚙️ Plugin Hooks & Filters

### **Action Hooks**:
- `book_before_save` → Fires before saving a book.
- `book_after_save` → Fires after saving a book.
- `book_before_delete` → Fires before deleting a book.

### **Filter Hooks**:
- `book_title_length` → Modify the max length of book titles.
- `book_genre_limit` → Limit the number of genres per book.

---

## 🐞 Troubleshooting & FAQs

### **1️⃣ Books are not visible on the frontend.**
✔️ Make sure you have **added the correct shortcode** `[book_list]` on a page.
✔️ Ensure there are **published books** in the database.
✔️ Flush permalinks by going to **Settings > Permalinks > Save Changes**.

### **2️⃣ Image upload is not working.**
✔️ Check the **uploads folder permissions**.
✔️ Ensure only **JPEG/PNG images** are being uploaded.
✔️ Verify that **file upload size is within WordPress limits**.

### **3️⃣ Users cannot edit or delete their books.**
✔️ Only the **book owner** can edit/delete their book.
✔️ Ensure the **AJAX requests are functioning properly**.

---

## 🎯 Future Enhancements

🔹 Pagination for book list 📄  
🔹 Book rating & reviews ⭐  
🔹 AJAX-based search & filter 🔎  
🔹 Book borrowing system 📚  

---

## 📝 License

This plugin is **open-source** and available under the **MIT License**. Feel free to modify and improve it. 😊

---

## 🤝 Contribution

Want to contribute? 🎉
1️⃣ Fork the repository 🍴
2️⃣ Create a new branch 🚀
3️⃣ Make your improvements 🛠️
4️⃣ Submit a Pull Request 📩

---

## 🏆 Credits

Developed by **@Suryansh9331** 💡

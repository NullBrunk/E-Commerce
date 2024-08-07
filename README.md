<div align="center">

# CyberShop

<div>
 <img width="54" src="https://github.com/user-attachments/assets/4f3a319a-592a-48e1-b9dd-6ae4c1233e30">
    
 <img width="54" src="https://github.com/user-attachments/assets/bfdc4de8-9dc5-4c98-a2ae-2dd41f7d455f">
 
 <img width="54" src="https://github.com/user-attachments/assets/0fbab25d-8122-4913-b780-9757d9fad54e">

 <img width="54" src="https://github.com/user-attachments/assets/030604f6-3d32-444c-b130-31c9d75779e7">

 <img width="54" src="https://github.com/user-attachments/assets/93e81c0d-9c79-4e40-90f8-99ddd703e1bb">

 <img width="54" src="https://github.com/user-attachments/assets/849f46fe-74e4-4632-9820-69ef07c7aa58">

 <img width="54" src="https://github.com/user-attachments/assets/200b7a6d-9ff5-49e6-874e-065f86636e1e">

 <img width="54" src="https://github.com/user-attachments/assets/f5730ce2-bbc4-43cc-851b-b5f20e606737">
 
 <img width="54" src="https://github.com/user-attachments/assets/a2554b15-1528-4e29-8769-1413366eba77">
</div>

<br>
    
![GitHub top language](https://img.shields.io/github/languages/top/NullBrunk/CyberShop?style=for-the-badge)
![GitHub commit activity](https://img.shields.io/github/commit-activity/m/NullBrunk/CyberShop?style=for-the-badge)
![repo size](https://img.shields.io/github/repo-size/NullBrunk/CyberShop?style=for-the-badge)

![commerce](https://github.com/NullBrunk/CyberShop/assets/125673909/eee9fecb-8e8a-4f66-a510-9eca6278f299)

</div>

This project was my first project with the Laravel framework. I wanted to create a dynamic E-Commerce website, so I choose to use the Laravel/Livewire tech stack. Additionally, I used vanilla JS, some library like HTMX and Swiper, and Bootstrap for the front end part. I also used the Pusher websocket to create real-time notifications.

# ⚒️ Installation

> [!TIP]
> **You can use the Dockerfile as well as the docker-compose file if you want to test this app.**

```bash
git clone https://github.com/NullBrunk/CyberShop && cd CyberShop 
docker-compose up --build
```

> [!Note]
> - The Web application is hosted on the port `80`
> - The API is hosted on the port `8000`
> - The SMTP mail client is hosted on the port `8025` (you'll receive the mail confirmation here when you signup)


### 📚 General overview 

The products are sorted by categories. Each category has its own search bar, and there is a general search bar that searches across all categories.
<br>On the product display pages, you can see the products, their names, categories, prices, and average rating. 

https://github.com/NullBrunk/CyberShop/assets/125673909/bb256fa4-6ef4-47b1-a745-e0b5a1dc62ae

### 🔐 Signup/Login

You can create an account with whatever email address you want, but you’ll need to validate it by clicking on the link sent by email. You can reset your password using the same email address.

https://github.com/NullBrunk/CyberShop/assets/125673909/a15e4a32-3035-49fa-99bc-f834218a315c

As you can see, livewire is used for the dynamic validation.

### 🛒 Product

You can sell a product by adding it in the “market” section. Give it a name, a price, a category, and a main image. You can also add secondary images.
<br>Finally, a MD-like editor is available for the product description (bold, italic, list, strikethrough text, links and so on).

https://github.com/NullBrunk/CyberShop/assets/125673909/7ed51d3a-2cf4-4c0e-b333-465cd6b7f975

The drag & drop functionnality is done with filepond.js. 

### 📝 Comments

You can leave a comment on any product by adding a title, a rating, and your comment. Again, a markdown-like editor is available to help you style your text. Finally, you can “heart” other people’s comments.

https://github.com/NullBrunk/CyberShop/assets/125673909/0465e9bc-2540-4ce4-a304-d05e39500112

### ⚙️ Settings

You can customize your profile by changing your profile picture, email, or password. There is also a profile page displaying the products you are selling, the number of hearts your comments have received, your average product rating, the number of comments you have made, and a list of your recent comments.

https://github.com/NullBrunk/CyberShop/assets/125673909/4c6b2c51-15af-4138-8fd4-639f08370a90

### 💳 Payment

When you add a product to your cart, it appears in the small dynamic cart icon in the navbar. Clicking on this cart icon takes you to a more detailed page where you also have the option to buy by making the payment (we have chosen to use Stripe).

https://github.com/NullBrunk/CyberShop/assets/125673909/75af32a3-3840-4cac-a018-9f6a3c27a972

I chose livewire for the dynamic cart page.

### 💬 Chatbox

There is a chat that allows you to interact with sellers or other clients. It is dynamic and supports sending messages as well as images. Additionally, the notification component in the navbar uses WebSockets, making it real-time. Therefore, you will receive notifications instantly as they happen.

https://github.com/NullBrunk/CyberShop/assets/125673909/574b7ca6-082b-4857-97e0-82db359b1f99

The dynamic notification component is updated using livewire + pusher.js websocket. 

# 🤝 Thanks

- Thanks to <a href="https://codepen.io/md-khokon">Md-khokon</a> for <a href="https://codepen.io/md-khokon/pen/bPLqzV">this amazing e-mail template</a>.

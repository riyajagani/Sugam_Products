// Function to get the cart from localStorage
function getCart() {
    return JSON.parse(localStorage.getItem("sugamCart")) || [];
}

// Function to save the cart to localStorage
function saveCart(cart) {
    localStorage.setItem("sugamCart", JSON.stringify(cart));
}

// Function to add product to cart
function addToCart(id, name, price, img, category) {
    let cart = getCart();
    // Create unique ID by combining category and product id
    const uniqueId = `${category}-${id}`;
    let existingItem = cart.find(item => item.uniqueId === uniqueId);

    if (existingItem) {
        existingItem.quantity += 1;
    } else {
        cart.push({ 
            uniqueId,
            id,
            name, 
            price, 
            quantity: 1, 
            img,
            category 
        });
    }

    saveCart(cart);
    alert(`${name} added to cart!`);
    renderCart(); // Refresh cart display if on cart page
}

// Function to render the cart on cart page
function renderCart() {
    const cartContainer = document.getElementById("cart-items");
    const billDetails = document.querySelector(".bill-details");
    
    if (!cartContainer) return; // Skip if not on cart page
    
    let cart = getCart();
    let subtotal = 0;
    
    cartContainer.innerHTML = ""; // Clear previous items

    if (cart.length === 0) {
        cartContainer.innerHTML = "<p>Your cart is empty.</p>";
        billDetails.innerHTML = "";
        return;
    }

    // Group items by category for organized display
    const groupedItems = cart.reduce((acc, item) => {
        if (!acc[item.category]) {
            acc[item.category] = [];
        }
        acc[item.category].push(item);
        return acc;
    }, {});

    // Render items by category
    Object.entries(groupedItems).forEach(([category, items]) => {
        cartContainer.innerHTML += `<h3>${category}</h3>`;
        
        items.forEach(item => {
            const itemTotal = item.price * item.quantity;
            subtotal += itemTotal;
            
            cartContainer.innerHTML += `
                <div class="cart-item" data-id="${item.uniqueId}">
                    <img src="${item.img}" alt="${item.name}">
                    <div class="cart-item-details">
                        <h4>${item.name}</h4>
                        <p>Rs ${item.price}</p>
                    </div>
                    <div class="cart-item-controls">
                        <input type="number" 
                               value="${item.quantity}" 
                               min="1" 
                               onchange="updateQuantity('${item.uniqueId}', this.value)">
                        <button class="remove-btn" 
                                onclick="removeItem('${item.uniqueId}')">Remove</button>
                    </div>
                    <div class="cart-item-price">Rs ${itemTotal}</div>
                </div>
            `;
        });
    });

    // Calculate tax and total
    const tax = subtotal * 0.10;
    const total = subtotal + tax;

    // Update bill summary
    billDetails.innerHTML = `
        <p>Subtotal <span>Rs ${subtotal.toFixed(2)}</span></p>
        <p>Tax (10%) <span>Rs ${tax.toFixed(2)}</span></p>
        <p><strong>Total Amount</strong> <span>Rs ${total.toFixed(2)}</span></p>
    `;
}

// Function to update quantity
function updateQuantity(uniqueId, quantity) {
    let cart = getCart();
    let item = cart.find(i => i.uniqueId === uniqueId);
    if (item) {
        item.quantity = parseInt(quantity);
        saveCart(cart);
        renderCart();
    }
}

// Function to remove item
function removeItem(uniqueId) {
    let cart = getCart();
    cart = cart.filter(i => i.uniqueId !== uniqueId);
    saveCart(cart);
    renderCart();
}

// Initialize cart display if on cart page
if (document.getElementById("cart-items")) {
    renderCart();
}
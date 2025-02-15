// Function to get the cart from localStorage
function getCart() {
    return JSON.parse(localStorage.getItem("iceCreamCart")) || [];
}

// Function to save the cart to localStorage
function saveCart(cart) {
    localStorage.setItem("iceCreamCart", JSON.stringify(cart));
}

// Function to add product to cart
function addToCart(id, name, price, img) {
    let cart = getCart();
    let existingItem = cart.find(item => item.id === id);

    if (existingItem) {
        existingItem.quantity += 1;
    } else {
        cart.push({ id, name, price, quantity: 1, img });
    }

    saveCart(cart);
    alert(`${name} added to cart!`);
    renderCart(); // Refresh cart display
}

// Function to render the cart on cart page
function renderCart() {
    const cartContainer = document.getElementById("cart-items");
    const totalPriceEl = document.getElementById("total-price");
    const billDetails = document.querySelector(".bill-details");
    let cart = getCart();
    let subtotal = 0;
    let tax = 0;
    let total = 0;
    
    if (!cartContainer) return; // Prevents errors on pages without cart

    cartContainer.innerHTML = ""; // Clear previous items

    if (cart.length === 0) {
        cartContainer.innerHTML = "<p>Your cart is empty.</p>";
        billDetails.innerHTML = ""; // Clear bill details when cart is empty
    } else {
        cart.forEach(item => {
            subtotal += item.price * item.quantity;
            cartContainer.innerHTML += `
                <div class="cart-item" data-id="${item.id}">
                    <img src="${item.img}" alt="${item.name}">
                    <div class="cart-item-details">
                        <h4>${item.name}</h4>
                        <p>Rs ${item.price}/lit</p>
                    </div>
                    <input type="number" value="${item.quantity}" min="1" onchange="updateQuantity(${item.id}, this.value)">
                    <button class="remove-btn" onclick="removeItem(${item.id})">Remove</button>
                </div>
            `;
        });

        // Calculate tax and total
        tax = subtotal * 0.10;
        total = subtotal + tax;

        // Update the bill summary
        billDetails.innerHTML = `
            <p>Subtotal <span>Rs ${subtotal.toFixed(2)}</span></p>
            <p>Tax (10%) <span>Rs ${tax.toFixed(2)}</span></p>
            <p><strong>Total Amount</strong> <span>Rs ${total.toFixed(2)}</span></p>
        `;
    }

    totalPriceEl.textContent = `Total: Rs ${total.toFixed(2)}`;
}

// Function to update quantity in cart
function updateQuantity(id, quantity) {
    let cart = getCart();
    let item = cart.find(i => i.id === id);
    if (item) {
        item.quantity = parseInt(quantity);
        saveCart(cart);
        renderCart();
    }
}

// Function to remove item from cart
function removeItem(id) {
    let cart = getCart();
    cart = cart.filter(i => i.id !== id);
    saveCart(cart);
    renderCart();
}

// Load the cart when the cart page loads
if (document.getElementById("cart-items")) {
    renderCart();
}

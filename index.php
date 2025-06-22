<?php
// index.php
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping store</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .card-img-top {
            height: 200px;
            object-fit: contain;
        }
    </style>
</head>
<body>
    <div class="container py-4">
        <h1 class="text-center mb-4">Shopping store</h1>
        <div id="product-container" class="row g-4"></div>
        <nav>
            <ul class="pagination justify-content-center" id="pagination"></ul>
        </nav>
    </div>

    <!-- Panier -->
    <div class="offcanvas offcanvas-end" tabindex="-1" id="cartCanvas">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title">Votre panier</h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
        </div>
        <div class="offcanvas-body" id="cart-items"></div>
    </div>

    <button class="btn btn-primary position-fixed bottom-0 end-0 m-4" data-bs-toggle="offcanvas" data-bs-target="#cartCanvas">
        Voir le panier
    </button>

    <script>
        const container = document.getElementById('product-container');
        const pagination = document.getElementById('pagination');
        const cartItems = document.getElementById('cart-items');
        let currentPage = 1;
        const limit = 12;

        function fetchProducts(page = 1) {
            fetch(`https://dummyjson.com/products?limit=${limit}&skip=${(page - 1) * limit}`)
                .then(res => res.json())
                .then(data => {
                    displayProducts(data.products);
                    setupPagination(data.total, page);
                });
        }

        function displayProducts(products) {
            container.innerHTML = '';
            products.forEach(product => {
                const col = document.createElement('div');
                col.className = 'col-sm-6 col-md-4 col-lg-3';
                col.innerHTML = `
                    <div class="card h-100">
                        <img src="${product.thumbnail}" class="card-img-top" alt="${product.title}">
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title">${product.title}</h5>
                            <p class="card-text">${product.description.substring(0, 60)}...</p>
                            <div class="mt-auto">
                                <p class="fw-bold">${product.price} $</p>
                                <button class="btn btn-sm btn-success w-100" onclick='addToCart(${JSON.stringify(product)})'>Ajouter</button>
                            </div>
                        </div>
                    </div>
                `;
                container.appendChild(col);
            });
        }

        function setupPagination(total, current) {
            const pages = Math.ceil(total / limit);
            pagination.innerHTML = '';
            for (let i = 1; i <= pages; i++) {
                const li = document.createElement('li');
                li.className = `page-item ${i === current ? 'active' : ''}`;
                li.innerHTML = `<a class="page-link" href="#">${i}</a>`;
                li.addEventListener('click', () => {
                    currentPage = i;
                    fetchProducts(i);
                });
                pagination.appendChild(li);
            }
        }

        function addToCart(product) {
            let cart = JSON.parse(localStorage.getItem('cart')) || [];
            const existing = cart.find(p => p.id === product.id);
            if (existing) {
                existing.quantity++;
            } else {
                cart.push({ ...product, quantity: 1 });
            }
            localStorage.setItem('cart', JSON.stringify(cart));
            showCart();
        }

        function showCart() {
            let cart = JSON.parse(localStorage.getItem('cart')) || [];
            cartItems.innerHTML = '';
            if (cart.length === 0) {
                cartItems.innerHTML = '<p class="text-center">Votre panier est vide.</p>';
                return;
            }
            cart.forEach(item => {
                const div = document.createElement('div');
                div.className = 'd-flex justify-content-between align-items-center mb-3';
                div.innerHTML = `
                    <div>
                        <strong>${item.title}</strong><br>
                        ${item.price} $ x ${item.quantity}
                    </div>
                    <button class="btn btn-sm btn-danger" onclick="removeFromCart(${item.id})">Supprimer</button>
                `;
                cartItems.appendChild(div);
            });
        }

        function removeFromCart(id) {
            let cart = JSON.parse(localStorage.getItem('cart')) || [];
            cart = cart.filter(item => item.id !== id);
            localStorage.setItem('cart', JSON.stringify(cart));
            showCart();
        }

        fetchProducts(currentPage);
        showCart();
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

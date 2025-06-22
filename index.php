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
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
  <style>
    .card-img-top {
      height: 200px;
      object-fit: contain;
    }
    body.dark-mode {
      background-color: #121212;
      color: #ffffff;
    }
    body.dark-mode .card {
      background-color: #1e1e1e;
      color: white;
    }
    .sidebar {
      min-height: 100vh;
    }
    body.dark-mode .sidebar {
  background-color: #1e1e1e;
  color: #ffffff;
}

body.dark-mode .sidebar .nav-link {
  color: #ffffff;
}

body.dark-mode .sidebar .nav-link:hover {
  background-color: #333333;
  color: #ffffff;
}

    .search-bar {
      max-width: 400px;
    }
    .modal-img {
      max-width: 100%;
      height: auto;
    }
    .success-popup {
      position: fixed;
      bottom: 20px;
      right: 20px;
      background-color: #d4edda;
      color: #155724;
      padding: 15px 20px;
      border-radius: 8px;
      box-shadow: 0 0 10px rgba(0,0,0,0.2);
      display: none;
      z-index: 9999;
    }

    .modal-content.custom-modal {
      max-width: 800px;
      margin: auto;
      border-radius: 12px;
      box-shadow: 0 0 20px rgba(0,0,0,0.2);
    }

  </style>
</head>
<body>
  <!-- HEADER -->
  <header class="navbar navbar-expand-lg navbar-light bg-light px-4">
    <a class="navbar-brand" href="#"><img src="https://dummyimage.com/40x40/000/fff&text=Logo" alt="Logo" class="me-2">Shopping store</a>
    <div class="ms-auto d-flex align-items-center">
      <button class="btn btn-outline-secondary me-2" onclick="toggleDarkMode()"><i class="fa fa-moon"></i></button>
      <button class="btn btn-outline-primary me-2" data-bs-toggle="modal" data-bs-target="#loginModal"><i class="fa fa-sign-in-alt"></i></button>
      <button class="btn btn-outline-success" data-bs-toggle="modal" data-bs-target="#signupModal"><i class="fa fa-user-plus"></i></button>
    </div>
  </header>

  <div class="container-fluid">
    <div class="row">
      <!-- SIDEBAR -->
      <nav class="col-md-2 sidebar py-4">
        <ul class="nav flex-column">
          <li class="nav-item"><a class="nav-link" href="#" onclick="showPage('home')">🏠 Accueil</a></li>
          <li class="nav-item"><a class="nav-link" href="#" onclick="showPage('cart')">🛒 Panier</a></li>
          <li class="nav-item"><a class="nav-link" href="#" onclick="showPage('profile')">👤 Profil</a></li>
        </ul>
        <hr>
        <input type="text" id="searchInput" class="form-control my-2" placeholder="Rechercher...">
        <select id="sortOption" class="form-select my-2">
          <option value="">Trier par</option>
          <option value="name">Nom</option>
          <option value="price-asc">Prix croissant</option>
          <option value="price-desc">Prix décroissant</option>
        </select>
        <input type="range" id="priceFilter" class="form-range" min="0" max="37000" value="37000">
        <label for="priceFilter">Prix Max: <span id="priceLabel">37000</span>$</label>
        <select id="categoryFilter" class="form-select mt-2">
          <option value="">Toutes catégories</option>
        </select>
      </nav>

      <!-- CONTENU PRINCIPAL -->
      <main class="col-md-10 py-4">
        <div id="home">
          <h2>Nos Produits</h2>
          <div class="row g-4" id="product-container"></div>
          <ul class="pagination justify-content-center" id="pagination"></ul>
        </div>

        <div id="cart" style="display:none">
          <h2>Panier</h2>
          <div id="cart-items"></div>
        </div>

        <div id="profile" style="display:none">
          <h2>Profil</h2>
          <p>Bienvenue, utilisateur !</p>
        </div>
      </main>
    </div>
  </div>

    <!-- MODALE DE DÉTAILS DU PRODUIT -->
  <div class="modal fade" id="productModal" tabindex="-1">
    <div class="modal-dialog modal-xl">
      <div class="modal-content custom-modal">
        <div class="modal-header">
          <h5 class="modal-title" id="modalProductTitle"></h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body row">
          <div class="col-md-6 text-center">
            <img id="modalProductImage" class="modal-img">
            <div class="mt-2" id="imageThumbnails"></div>
          </div>
          <div class="col-md-6">
            <p id="modalProductDesc"></p>
            <span class="badge bg-primary" id="modalProductPrice"></span>
            <span class="badge bg-success" id="modalProductStock"></span>
            <div class="mt-2">
              <label for="modalQty">Quantité :</label>
              <input type="number" id="modalQty" class="form-control w-50" min="1" value="1">
              <button class="btn btn-primary mt-2" onclick="addToCart(modalProduct, parseInt(document.getElementById('modalQty').value));" data-bs-dismiss="modal">Ajouter au panier</button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- POPUP SUCCÈS -->
  <div class="success-popup" id="successPopup">✅ Produit ajouté au panier</div>

  <!-- MODALES LOGIN / SIGNUP -->
  <div class="modal fade" id="loginModal">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header"><h5 class="modal-title">Connexion</h5></div>
        <div class="modal-body">
          <input type="email" class="form-control mb-2" placeholder="Email">
          <input type="password" class="form-control" placeholder="Mot de passe">
        </div>
        <div class="modal-footer">
          <button class="btn btn-primary">Se connecter</button>
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade" id="signupModal">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header"><h5 class="modal-title">Inscription</h5></div>
        <div class="modal-body">
          <input type="text" class="form-control mb-2" placeholder="Nom">
          <input type="email" class="form-control mb-2" placeholder="Email">
          <input type="password" class="form-control" placeholder="Mot de passe">
        </div>
        <div class="modal-footer">
          <button class="btn btn-success">S'inscrire</button>
        </div>
      </div>
    </div>
  </div>

  <script>
let currentPage = 1;
const limit = 12;
let allProducts = [];
let modalProduct = null;

function fetchProducts(page = 1) {
  fetch(`https://dummyjson.com/products?limit=1000`)
    .then(res => res.json())
    .then(data => {
      allProducts = data.products;
      populateCategories();
      displayFilteredProducts();
    });
}

function populateCategories() {
  const unique = [...new Set(allProducts.map(p => p.category))];
  const select = document.getElementById('categoryFilter');
  select.innerHTML = '<option value="">Toutes catégories</option>'; // reset options
  unique.forEach(c => {
    let opt = document.createElement('option');
    opt.value = c;
    opt.textContent = c;
    select.appendChild(opt);
  });
}

function displayPagination(totalItems) {
  const pagination = document.getElementById('pagination');
  pagination.innerHTML = '';

  const totalPages = Math.ceil(totalItems / limit);

  for (let i = 1; i <= totalPages; i++) {
    const li = document.createElement('li');
    li.className = 'page-item' + (i === currentPage ? ' active' : '');

    const a = document.createElement('a');
    a.className = 'page-link';
    a.href = '#';
    a.textContent = i;
    a.onclick = (e) => {
      e.preventDefault();
      currentPage = i;
      displayFilteredProducts();
      window.scrollTo({ top: 0, behavior: 'smooth' });
    };

    li.appendChild(a);
    pagination.appendChild(li);
  }
}

function displayFilteredProducts() {
  let products = [...allProducts];
  const search = document.getElementById('searchInput').value.toLowerCase();
  const sort = document.getElementById('sortOption').value;
  const maxPrice = document.getElementById('priceFilter').value;
  const category = document.getElementById('categoryFilter').value;

  if (search) products = products.filter(p => p.title.toLowerCase().includes(search));
  if (category) products = products.filter(p => p.category === category);
  products = products.filter(p => p.price <= maxPrice);

  if (sort === 'name') products.sort((a, b) => a.title.localeCompare(b.title));
  else if (sort === 'price-asc') products.sort((a, b) => a.price - b.price);
  else if (sort === 'price-desc') products.sort((a, b) => b.price - a.price);

  const totalPages = Math.ceil(products.length / limit);
  if (currentPage > totalPages) currentPage = 1;

  displayPagination(products.length);

  const container = document.getElementById('product-container');
  container.innerHTML = '';
  const paginated = products.slice((currentPage - 1) * limit, currentPage * limit);
  paginated.forEach(product => {
    const col = document.createElement('div');
    col.className = 'col-sm-6 col-md-4 col-lg-3';
    col.innerHTML = `
      <div class="card h-100">
        <img src="${product.thumbnail}" class="card-img-top" alt="${product.title}">
        <div class="card-body d-flex flex-column">
          <h5>${product.title}</h5>
          <p>${product.description.substring(0, 60)}...</p>
          <div class="mt-auto">
            <p class="fw-bold">${product.price}$</p>
            <button class="btn btn-success w-100 mb-1" onclick='addToCart(${JSON.stringify(product)})'>Ajouter</button>
            <button class="btn btn-outline-primary w-100" onclick='showProductDetails(${JSON.stringify(product)})' data-bs-toggle="modal" data-bs-target="#productModal">Voir les détails</button>
          </div>
        </div>
      </div>
    `;
    container.appendChild(col);
  });
}

function toggleDarkMode() {
  document.body.classList.toggle('dark-mode');
}

function showPage(id) {
  ['home', 'cart', 'profile'].forEach(p => document.getElementById(p).style.display = 'none');
  document.getElementById(id).style.display = 'block';
  if (id === 'cart') showCart();
}

function showProductDetails(product) {
  modalProduct = product;
  document.getElementById('modalProductTitle').textContent = product.title;
  document.getElementById('modalProductDesc').textContent = product.description;
  document.getElementById('modalProductPrice').textContent = `${product.price} €`;
  document.getElementById('modalProductStock').textContent = `STOCK: ${product.stock}`;
  document.getElementById('modalProductImage').src = product.images[0];

  const thumbnails = document.getElementById('imageThumbnails');
  thumbnails.innerHTML = '';
  product.images.forEach((img, idx) => {
    const thumb = document.createElement('img');
    thumb.src = img;
    thumb.style.width = '60px';
    thumb.className = 'm-1 border';
    thumb.style.cursor = 'pointer';
    thumb.onclick = () => document.getElementById('modalProductImage').src = img;
    thumbnails.appendChild(thumb);
  });
}

function showSuccessPopup() {
  const popup = document.getElementById('successPopup');
  popup.style.display = 'block';
  setTimeout(() => popup.style.display = 'none', 2500);
}

function addToCart(product, qty = 1) {
  let cart = JSON.parse(localStorage.getItem('cart')) || [];
  const found = cart.find(p => p.id === product.id);
  if (found) found.quantity += qty;
  else cart.push({ ...product, quantity: qty });
  localStorage.setItem('cart', JSON.stringify(cart));
  showSuccessPopup();
}

function showCart() {
  const cart = JSON.parse(localStorage.getItem('cart')) || [];
  const area = document.getElementById('cart-items');
  area.innerHTML = cart.length ? '' : '<p>Panier vide.</p>';
  cart.forEach(item => {
    const div = document.createElement('div');
    div.className = 'd-flex justify-content-between border-bottom mb-2';
    div.innerHTML = `
      <div>
        <strong>${item.title}</strong><br>${item.price}$ x ${item.quantity}
      </div>
      <button class="btn btn-sm btn-danger" onclick="removeFromCart(${item.id})">Supprimer</button>
    `;
    area.appendChild(div);
  });
}

function removeFromCart(id) {
  let cart = JSON.parse(localStorage.getItem('cart')) || [];
  cart = cart.filter(p => p.id !== id);
  localStorage.setItem('cart', JSON.stringify(cart));
  showCart();
}

document.getElementById('searchInput').addEventListener('input', () => {
  currentPage = 1;
  displayFilteredProducts();
});
document.getElementById('sortOption').addEventListener('change', () => {
  currentPage = 1;
  displayFilteredProducts();
});
document.getElementById('priceFilter').addEventListener('input', e => {
  document.getElementById('priceLabel').textContent = e.target.value;
  currentPage = 1;
  displayFilteredProducts();
});
document.getElementById('categoryFilter').addEventListener('change', () => {
  currentPage = 1;
  displayFilteredProducts();
});

fetchProducts();

  </script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

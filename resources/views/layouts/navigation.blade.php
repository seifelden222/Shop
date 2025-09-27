 <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
      <div class="container-fluid">
        <a class="navbar-brand fw-bold" href="#">Shop</a>

        <div class="navbar justify-content-center" id="mainNav">
          <ul class="navbar-nav mb-2 mb-lg-0">
            <li class="nav-item px-2">
              <a class="nav-link active" aria-current="page" href="{{ route('welcome') }}">Home</a>
            </li>
            <li class="nav-item px-2">
              <a class="nav-link" href="{{ route('categories.index') }}">Categories</a>
            </li>
            <li class="nav-item px-2">
              <a class="nav-link" href="{{ route('products.index') }}">Products</a>
            </li>
            <li class="nav-item px-2">
              <a class="nav-link" href="{{ route('brands.index') }}">Brands</a>
            </li>
            <li class="nav-item px-2">
              <a class="nav-link" href="#">Contact</a>
            </li>
          </ul>
        </div>

        <div class="d-flex align-items-center gap-4">
          <a class="text-dark" href="#" title="Search"
            ><i class="bi bi-search fs-5"></i
            ><span class="visually-hidden">Search</span></a
          >
          <a class="position-relative text-dark" href="#" title="Cart">
            <i class="bi bi-cart4 fs-5"></i>
            <span
              class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger"
              >3</span
            >
            <span class="visually-hidden">Cart</span>
          </a>
          <a class="text-dark" href="#" title="Account"
            ><i class="bi bi-person-circle fs-5"></i
            ><span class="visually-hidden">Account</span></a
          >
        </div>
      </div>
    </nav>

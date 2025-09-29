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
          <a class="position-relative text-dark" href="{{ route('carts.index') }}" title="Cart">
            <i class="bi bi-cart4 fs-5"></i>
            @auth
              @if(auth()->user()->cart_count > 0)
                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                  {{ auth()->user()->cart_count }}
                </span>
              @endif
            @endauth
            <span class="visually-hidden">Cart</span>
          </a>
          @auth
            <div class="dropdown">
              <a class="text-dark dropdown-toggle" href="#" role="button" id="accountDropdown" data-bs-toggle="dropdown" aria-expanded="false" title="Account">
                <i class="bi bi-person-circle fs-5"></i>
                <span class="visually-hidden">Account</span>
              </a>
              <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="accountDropdown">
                @if(Route::has('dashboard'))
                  <li><a class="dropdown-item" href="{{ route('dashboard') }}">Dashboard</a></li>
                @endif
                <li><a class="dropdown-item" href="{{ route('test.index') }}">My Cart</a></li>
                <li><hr class="dropdown-divider"></li>
                <li>
                  <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="dropdown-item">Logout</button>
                  </form>
                </li>
              </ul>
            </div>
          @else
            <a class="text-dark me-2" href="{{ route('login') }}" title="Login">
              <i class="bi bi-box-arrow-in-right fs-5"></i>
              <span class="visually-hidden">Login</span>
            </a>
            <a class="text-dark" href="{{ route('register') }}" title="Register">
              <i class="bi bi-person-plus fs-5"></i>
              <span class="visually-hidden">Register</span>
            </a>
          @endauth
        </div>
      </div>
    </nav>

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
           <a class="nav-link" href="{{ route('contact') }}">Contact</a>
         </li>
         @auth
         <li class="nav-item px-2">
           <a class="nav-link" href="{{ route('orders.index') }}">Orders</a>
         </li>
         <li class="nav-item px-2">
           <a class="nav-link" href="{{ route('favorites.index') }}">Favorite</a>
         </li>
         @endauth
       </ul>
     </div>
     <?php
      ?>
     <div class="d-flex align-items-center gap-4">
      
     
       <a class="position-relative text-dark" href="{{ route('dashboard') }}" title="Cart">
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
       <div class="d-flex align-items-center gap-2">
         <a class="text-dark d-flex align-items-center" href="{{ route('profile.edit') }}" title="Account">
           <i class="bi bi-person-circle fs-5 me-1"></i>
           <span class="small">{{ auth()->user()->name ?? auth()->user()->email }}</span>
         </a>

         <form method="POST" action="{{ route('logout') }}" class="m-0">
           @csrf
           <button type="submit" class="btn btn-outline-secondary btn-sm">Logout</button>
         </form>
       </div>
       @else
       <div class="d-flex align-items-center gap-2">
         <a class="btn btn-outline-primary btn-sm" href="{{ route('login') }}" title="Login">Login</a>
         <a class="btn btn-primary btn-sm" href="{{ route('register') }}" title="Register">Create account</a>
       </div>
       @endauth
     </div>
   </div>
 </nav>
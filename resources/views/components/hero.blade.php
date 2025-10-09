@props([
  'title' => 'Welcome to our shop',
  'subtitle' => '',
  'primaryLabel' => null,
  'primaryLink' => null,
  'secondaryLabel' => null,
  'secondaryLink' => null,
  'image' => '/images/istockphoto-1428709516-612x612.jpg',
])

<section class="py-5 bg-white">
  <div class="container">
    <div class="row align-items-center">
      <div class="col-lg-6">
        <div class="badge bg-danger text-white mb-3">Sale 25% OFF</div>
        <h1 class="display-5 fw-bold">{{ $title }}</h1>
        @if($subtitle)
          <p class="lead text-muted">{{ $subtitle }}</p>
        @endif

        <form id="ajax-search-form" class="mb-4 position-relative" role="search" aria-label="Site search" method="get" action="{{ route('search') }}">
          <div class="input-group input-group-lg shadow-sm">
            <input id="ajax-search-input" type="search" name="q" class="form-control rounded-pill"  placeholder="Search products, brands and categories" aria-label="Search" autocomplete="off" >
            <button class="btn btn-primary rounded-pill ms-2" type="submit"><i class="bi bi-search"></i></button>
          </div>
          {{-- AJAX results dropdown (will be positioned under the input) --}}
          <div id="ajax-search-results" class="d-none ajax-search-dropdown" style="position: absolute; left:0; right:0; z-index:2000;"></div>
        </form>

        @push('scripts')
        <script>
        (function(){
          function initAjaxSearch(){
            try{
              const form = document.getElementById('ajax-search-form');
              const input = document.getElementById('ajax-search-input');
              const results = document.getElementById('ajax-search-results');
              if (!form || !input || !results) return console.debug('AJAX search: elements not found yet');

              console.debug('AJAX search: initializing');
              let debounceTimer = null;

              function hideResults(){
                results.classList.add('d-none');
                results.innerHTML = '';
              }

              function showResults(html){
                results.innerHTML = html;
                results.classList.remove('d-none');
              }

              function showNotFound(){
                results.innerHTML = '<div class="p-2 text-center text-muted">\n  <div class="mb-1"><i class="bi bi-search" style="font-size:1.2rem"></i></div>\n  <div>No results found</div>\n</div>';
                results.classList.remove('d-none');
                positionResults();
              }

              function positionResults(){
                // Position the dropdown directly under the search input and match its width
                // Use offset values relative to the form container
                const inputRect = input.getBoundingClientRect();
                const formRect = form.getBoundingClientRect();
                const left = input.offsetLeft;
                const top = input.offsetTop + input.offsetHeight + 8;
                results.style.left = left + 'px';
                results.style.top = top + 'px';
                results.style.width = input.offsetWidth + 'px';
                results.style.right = 'auto';
              }

              // allow full navigation when user explicitly clicks the submit button or presses Enter
              let allowFullSubmit = false;
              const submitButton = form.querySelector('button[type="submit"]');
              if (submitButton) {
                // use mousedown so it's set before the submit event fires
                submitButton.addEventListener('mousedown', function(){ allowFullSubmit = true; });
                submitButton.addEventListener('touchstart', function(){ allowFullSubmit = true; });
              }

              // also allow Enter to trigger full navigation (useful for keyboard users)
              input.addEventListener('keydown', function(e){
                if (e.key === 'Enter') {
                  allowFullSubmit = true;
                }
              });

              form.addEventListener('submit', function(ev){
                // if the user requested a full submit, allow the browser to navigate normally
                if (allowFullSubmit) {
                  return; // default navigation
                }
                if (!input.value.trim()) return; // allow normal submit if empty
                ev.preventDefault();
                fetch(form.action + '?q=' + encodeURIComponent(input.value), {
                  headers: { 'X-Requested-With': 'XMLHttpRequest' }
                }).then(r => r.json()).then(data => {
                  if (data.html) {
                    history.replaceState({}, '', form.action + '?q=' + encodeURIComponent(input.value));
                    showResults(data.html);
                  } else {
                    // show not found message instead of hiding
                    showNotFound();
                  }
                }).catch(()=>{ window.location = form.action + '?q=' + encodeURIComponent(input.value); });
              });

              // Debounced live search
              input.addEventListener('input', function(){
                const q = input.value.trim();
                positionResults();
                if (debounceTimer) clearTimeout(debounceTimer);
                if (!q) { hideResults(); return; }
                debounceTimer = setTimeout(()=>{
                  fetch(form.action + '?q=' + encodeURIComponent(q), {
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                  }).then(r => r.json()).then(data => {
                    if (data.html) showResults(data.html);
                    else showNotFound();
                  }).catch(()=> showNotFound());
                }, 300);
              });

              // hide when clicking outside
              document.addEventListener('click', function(e){
                if (!form.contains(e.target)) hideResults();
              });

              // reposition on resize
              window.addEventListener('resize', positionResults);
            }catch(err){ console.error('AJAX search init error', err); }
          }

          if (document.readyState === 'loading'){
            document.addEventListener('DOMContentLoaded', initAjaxSearch);
          } else {
            initAjaxSearch();
          }
        })();
        </script>
        <style>
          /* quick dropdown styles scoped to the hero component */
          .ajax-search-dropdown{
            background: #fff;
            border: 1px solid rgba(0,0,0,0.08);
            border-radius: .6rem;
            box-shadow: 0 6px 18px rgba(18,38,63,0.08);
            max-height: 420px;
            overflow: auto;
            padding: .35rem;
            box-sizing: border-box;
          }
          .ajax-search-dropdown .list-group-item{
            border: 0;
            border-radius: .45rem;
            margin-bottom: .22rem;
            padding: .4rem .5rem;
            align-items: center;
          }
          /* ensure the growable content can shrink with ellipsis */
          .ajax-search-dropdown .flex-grow-1{ min-width: 0; }
          .ajax-search-dropdown .flex-grow-1 strong,
          .ajax-search-dropdown .flex-grow-1 small{
            display:block; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;
          }
          /* fix image/icon size */
          .ajax-search-dropdown .me-3{ width:48px; height:48px; flex:0 0 48px; }
          .ajax-search-dropdown .me-3 img{ width:48px; height:48px; border-radius:6px; }
          .ajax-search-dropdown .result-price{ color: #1f9d69; font-weight:700; }
          /* ensure images in the dropdown are small and aligned */
          .ajax-search-dropdown img{ width:100%; height:100%; object-fit:cover; border-radius:4px; }
          /* reduce font-size inside dropdown for compactness */
          .ajax-search-dropdown .list-group-item strong{ font-size: .95rem; }
          .ajax-search-dropdown h6{ margin:0 0 .4rem 0; font-size: .9rem; }
          /* not-found styling */
          .ajax-search-dropdown .no-results { padding: .75rem; color: #6c757d; }
        </style>
        @endpush

        @php
          $showPrimary = true;
          if (Auth::check() && Auth::user()->role === 'admin') {
              $showPrimary = false; // do not show primary button to admins by default
          }
          // fallback labels
          $primaryLabel = $primaryLabel ?? 'Get Started';
          $secondaryLabel = $secondaryLabel ?? 'Learn more';
        @endphp

        <div class="d-flex gap-2">
          @if($showPrimary && $primaryLink && $primaryLabel)
            <a href="{{ $primaryLink }}" class="btn btn-primary btn-lg">{{ $primaryLabel }}</a>
          @endif
          @if($secondaryLink && $secondaryLabel)
            <a href="{{ $secondaryLink }}" class="btn btn-outline-secondary btn-lg">{{ $secondaryLabel }}</a>
          @endif
        </div>
      </div>

      <div class="col-lg-6 mt-4 mt-lg-0">
        <div class="position-relative">
          <img src="{{ $image }}" alt="shop" class="img-fluid rounded shadow-lg" style="max-height:420px; object-fit:cover; width:100%" loading="lazy">
          <div class="position-absolute top-0 end-0 m-3 p-3 bg-white rounded shadow-sm text-end">
            <div class="small text-muted">Free Shipping</div>
            <div class="fw-bold">On orders above $50</div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

@props(['productId'])

@auth
<form method="POST" action="{{ route('favorites.store') }}" class="d-inline" aria-label="Add to favorites">
    @csrf
    <input type="hidden" name="product_id" value="{{ $productId }}">
    <button type="submit" title="Add to favorites" class="btn rounded-circle favorite">
        <i class="bi bi-heart-fill" aria-hidden="true" style="font-size:12px;color:inherit;"></i>
    </button>
</form>
@else
</a>
<a href="{{ route('login') }}" title="Login to add to favorites" class="d-inline-flex rounded-circle" style="width:32px;height:32px;background:#ffffff;border:1px solid rgba(0,0,0,0.06);color:#6b7280;align-items:center;justify-content:center;box-shadow:0 2px 6px rgba(0,0,0,0.06);z-index:1060;text-decoration:none;">
    <i class="bi bi-heart-fill" aria-hidden="true" style="font-size:12px;color:inherit;"></i>
</a>
@endauth
<style>
    .favorite{
        display: none;
    }
    .favorite:haver {
        
        width: 32px;
        height: 32px;
        background: #ffffff;
        border: 1px solid rgba(0, 0, 0, 0.06);
        color: #6b7280;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.06);
        z-index: 1060;

    }
</style>
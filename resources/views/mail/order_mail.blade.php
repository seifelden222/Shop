<!doctype html>
<html>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmation</title>
    <style>
      /* Simple, widely supported email styles */
      body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif; background-color: #f6f6f6; margin:0; padding:20px; }
      .email-container { max-width: 680px; margin: 0 auto; background: #ffffff; border-radius: 6px; overflow: hidden; box-shadow: 0 2px 6px rgba(0,0,0,0.06); }
      .email-header { background: #0d6efd; color: #fff; padding: 18px 24px; }
      .email-body { padding: 20px 24px; color: #333; }
      .btn { display:inline-block; padding:10px 16px; background:#0d6efd; color:#fff; text-decoration:none; border-radius:4px; }
      table { width:100%; border-collapse: collapse; margin-top: 12px; }
      th, td { text-align:left; padding:10px; border-bottom: 1px solid #eaeaea; }
      th { background:#fafafa; font-weight:600; }
      .text-right { text-align: right; }
      .muted { color:#6c757d; font-size:13px; }
      .footer { padding: 12px 20px; font-size:13px; color:#888; text-align:center; }
      @media screen and (max-width:480px){ .email-container{ padding:0 8px; } th, td { padding:8px; } }
    </style>
  </head>
  <body>
    <div class="email-container">
      <div class="email-header">
        <h2 style="margin:0; font-size:18px;">{{ config('app.name', 'Shop') }} — Order Confirmation</h2>
      </div>

      <div class="email-body">
        <p style="margin:0 0 8px 0;">Hello {{ $order->customer_name ?? ($user->name ?? 'Customer') }},</p>
        <p class="muted" style="margin:0 0 16px 0;">Thank you for your order. Below are the details of your purchase.</p>

        <h4 style="margin:8px 0 12px 0;">Order #{{ $order->order_number }}</h4>

        <table role="presentation">
          <thead>
            <tr>
              <th style="width:50%">Item</th>
              <th style="width:15%">Qty</th>
              <th style="width:15%" class="text-right">Unit</th>
              <th style="width:20%" class="text-right">Subtotal</th>
            </tr>
          </thead>
          <tbody>
            @if(is_iterable($cart))
              @foreach($cart as $item)
                <tr>
                  <td>
                    <strong>{{ $item->product_name ?? 'Product' }}</strong>
                    @if(!empty($item->image))
                      <div class="muted" style="font-size:12px; margin-top:6px;">Image: {{ $item->image }}</div>
                    @endif
                  </td>
                  <td>{{ $item->quantity ?? 1 }}</td>
                  <td class="text-right">{{ isset($item->unit_price) ? number_format($item->unit_price, 2) : '-' }}</td>
                  <td class="text-right">{{ isset($item->unit_price, $item->quantity) ? number_format($item->unit_price * $item->quantity, 2) : '-' }}</td>
                </tr>
              @endforeach
            @else
              <tr>
                <td>
                  <strong>{{ $cart->product_name ?? 'Product' }}</strong>
                  @if(!empty($cart->image))
                    <div class="muted" style="font-size:12px; margin-top:6px;">Image: {{ $cart->image }}</div>
                  @endif
                </td>
                <td>{{ $cart->quantity ?? 1 }}</td>
                <td class="text-right">{{ isset($cart->unit_price) ? number_format($cart->unit_price, 2) : '-' }}</td>
                <td class="text-right">{{ isset($cart->unit_price, $cart->quantity) ? number_format($cart->unit_price * $cart->quantity, 2) : '-' }}</td>
              </tr>
            @endif
          </tbody>
          <tfoot>
            <tr>
              <th colspan="3" class="text-right">Total</th>
              <th class="text-right">{{ isset($order->total_price) ? number_format($order->total_price, 2) : '-' }}</th>
            </tr>
          </tfoot>
        </table>

        <div style="margin:18px 0;">
          <p style="margin:0 0 6px 0;"><strong>Shipping address</strong></p>
          <p class="muted" style="margin:0 0 6px 0;">{{ $order->address ?? '—' }}</p>

          <p style="margin:8px 0 0 0;"><strong>Order date</strong></p>
          <p class="muted" style="margin:0 0 6px 0;">{{ optional($order->created_at)->toDayDateTimeString() ?? '—' }}</p>

          <p style="margin:8px 0 0 0;"><strong>Status</strong></p>
          <p class="muted" style="margin:0 0 6px 0;">{{ $order->status ?? 'pending' }}</p>
        </div>

        <div style="margin-top:14px;">
          <a class="btn" href="{{ url('/') }}">Visit Store</a>
        </div>
      </div>

      <div class="footer">
        <div>{{ config('app.name', 'Shop') }} • {{ config('app.url') ?? url('/') }}</div>
        <div style="margin-top:6px;">If you have any questions, reply to this email or contact our support.</div>
      </div>
    </div>
  </body>
</html>

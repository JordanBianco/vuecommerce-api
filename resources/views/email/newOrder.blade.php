@component('mail::message')

# {{ config('app.name') }}

Hey {{ $order->first_name }},
grazie per il tuo ordine!<br>
Ti manderemo una nuova email, non appena verrà spedito.
<br>
<br>
## Ordine numero {{ $order->order_number }}<br>
{{ $order->created_at->format('d/m/Y') }}

@component('mail::subcopy')
@endcomponent

@component('mail::table')
<table>
@foreach ($order->products as $product)
<tr>
<td>
{{ $product->name }}
</td>
<td>
x{{ $product->pivot->quantity }}
</td>
<td>
€{{ $product->price }}
</td>
</tr>
@endforeach
</table>
@endcomponent

@component('mail::subcopy')
@endcomponent

### Dettagli Spedizione
<span>{{ $order->first_name . ' ' . $order->last_name }}</span>
<span>{{ $order->address }}</span>
<span>{{ $order->city }}, {{ $order->province }}</span>
<span>{{ $order->country }}</span>
<span>{{ $order->zipcode }}</span>
<span>{{ $order->email }}</span>

@component('mail::subcopy')
@endcomponent

Per qualsiasi informazione, non esitare a contattarci,<br>
mandaci un email a vueshop@gmail.com<br>
Grazie

@endcomponent

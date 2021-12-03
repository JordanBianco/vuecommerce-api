@component('mail::message')

# {{ config('app.name') }}

Hey {{ $order->first_name }},
grazie per il tuo ordine!<br>
Ti manderemo una nuova email, non appena verrà spedito.
<br>
<br>
## Ordine numero {{ $order->order_number }}<br>
{{ $order->created_at->format('d/m/Y') }}

@component('mail::table')
| Articolo      | Quantità      | Prezzo   |
|:------------- |:-------------:| --------:|
@foreach ($order->products as $product)
| {{ $product->name }} | {{ $product->pivot->quantity }} | €{{ $product->price }} |
@endforeach
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
manda un email a vueshop@gmail.com<br>
Grazie

@endcomponent

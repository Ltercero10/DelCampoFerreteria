<script src="https://www.paypal.com/sdk/js?client-id=sb&currency=USD"></script>
<h1>Tu Carrito</h1>

<table>
    <thead>
        <tr>
            <th>Producto</th>
            <th>Precio</th>
            <th>Cantidad</th>
            <th>Subtotal</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        {{foreach items}}
        <tr>
            <td>{{productName}}</td>
            <td>{{crrprc}}</td>
            <td>{{crrctd}}</td>
            <td>{{itemSubtotal}}</td>
            <td>

                <form action="index.php?page=Checkout_Checkout" method="post" style="display:inline">
                    <input type="hidden" name="productId" value="{{productId}}">
                    <button type="submit" name="decrease">−</button>
                </form>

                <form action="index.php?page=Checkout_Checkout" method="post" style="display:inline">
                    <input type="hidden" name="productId" value="{{productId}}">
                    <button type="submit" name="increase">+</button>
                </form>
            </td>
        </tr>
        {{endfor items}}

        <tr class="total-row">
            <td class="total-label">TOTAL:</td>
            <td></td>
            <td></td>
            <td class="total-amount">L.{{subTotal}}</td>
            <td></td>
        </tr>
    </tbody>
</table>

<div>
    <a href="index.php?page=index" class="continue-btn">
        ← Continuar comprando
    </a>
</div>


<div style="margin-top: 20px; margin-bottom: 20px;">
    <form action="index.php?page=Checkout_Checkout" method="post" onsubmit="return confirm('¿Estás seguro de vaciar el carrito?');">
        <button type="submit" name="empty_cart" class="btn btn-danger" style="background-color: #dc3545; color: white;">
            🗑️ Vaciar Carrito
        </button>
    </form>
</div>

<div id="paypal-button-container"></div>

<script>
    paypal.Buttons({
        createOrder: function (data, actions) {
            return actions.order.create({
                purchase_units: [{ amount: { value: "{{total}}" } }]
            });
        },
        onApprove: function (data, actions) {
            return actions.order.capture().then(function (details) {
                window.location.href = "index.php?page=Checkout_Capture&token=" + data.orderID;
            });
        },
        onError: function (err) {
            console.error(err);
            alert("Error al procesar el pago con PayPal.");
        }
    }).render("#paypal-button-container");
</script>F
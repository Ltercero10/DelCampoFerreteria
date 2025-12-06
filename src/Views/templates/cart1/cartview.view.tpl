<h1 class="title">Mi Carrito</h1>

{{if cart}}
<table class="cart-table">
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
        {{foreach cart}}
        <tr>
            <td>{{productName}}</td>
            <td>$ {{crrprc}}</td>
            <td>{{crrctd}}</td>
            <td>$ {{subtotal}}</td>

            <td>
                <form action="index.php?page=cartview" method="post">
                    <input type="hidden" name="productId" value="{{productId}}">
                    <button name="deleteItem" class="delete-btn">Eliminar</button>
                </form>

            </td>
        </tr>
        {{endfor cart}}
    </tbody>
</table>

<div class="cart-total">
    <h2>Total: $ {{total}}</h2>
</div>

{{endif cart}}

{{ifnot cart}}
<p>No tienes productos en el carrito.</p>
{{endifnot}}
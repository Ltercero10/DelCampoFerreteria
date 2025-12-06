<h1>{{SITE_TITLE}}</h1>

<div class="product-list">
    {{foreach products}}
    <div class="product" data-productId="{{productId}}">
        <img src="{{productImgUrl}}" alt="{{productName}}">
        <h2>{{productName}}</h2>
        <p>{{productDescription}}</p>

        <div class="product-details">
            <span class="price">${{productPrice}}</span>

        </div>
        <span class="stock">{{stockText}}</span>


        <form action="index.php?page=index" method="POST">
            <input type="hidden" name="productId" value="{{productId}}">
            <button type="submit" name="addTocart" class="add-to-cart">Agregar al carrito</button>

            </button>
        </form>
    </div>
    {{endfor products}}
</div>

{{#if products.length == 0}}
<div class="no-products">
    <p>📦 No hay productos disponibles en este momento.</p>
</div>
{{/if}}
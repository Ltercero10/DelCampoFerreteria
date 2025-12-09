<div class="container">
    {{if success}}
    <div class="success">
        <div class="success-icon">✓</div>
        <h1>¡Pago Completado!</h1>
        <p>Transacción procesada exitosamente</p>
    </div>
    
    <div class="info-box">
        <h3>Detalles de la Transacción</h3>
        <p><strong>ID de Orden:</strong> {{order_id}}</p>
        <p><strong>Fecha:</strong> {{fecha}}</p>
        <p><strong>Total:</strong> L.{{total}}</p>
        <p><strong>Estado:</strong> <span style="color:#28a745;">Completado</span></p>
    </div>
    
    <div class="info-box">
        <h3>Productos Comprados</h3>
        {{foreach productos}}
        <div class="producto-item">
            <span>{{nombre}}</span>
            <span>L.{{precio}} x {{cantidad}} = L.{{subtotal}}</span>
        </div>
        {{endfor}}
        <div class="producto-item" style="font-weight:bold; border-top:2px solid #333;">
            <span>TOTAL</span>
            <span>L.{{total}}</span>
        </div>
    </div>
    
    <div class="botones">
        <a href="index.php?page=Checkout_Historial" class="btn">Ver Historial</a>
        <a href="index.php?page=index" class="btn btn-success">Seguir Comprando</a>
        <a href="index.php?page=index" class="btn btn-secondary">Inicio</a>
    </div>
    
    {{else}}
    <div class="error">
        <div class="error-icon">✗</div>
        <h1>Error en el Pago</h1>
        <p>{{mensaje}}</p>
    </div>
    
    <div class="botones">
        <a href="index.php?page=Checkout_Checkout" class="btn">Volver al Carrito</a>
        <a href="index.php?page=index" class="btn btn-secondary">Inicio</a>
    </div>
    {{endif}}
</div>
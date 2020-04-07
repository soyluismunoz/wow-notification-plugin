<?php

// Actualiza automáticamente el estado de los pedidos a COMPLETADO
add_action( 'woocommerce_order_status_processing', 'actualiza_estado_pedidos_a_completado' );
add_action( 'woocommerce_order_status_on-hold', 'actualiza_estado_pedidos_a_completado' );

function actualiza_estado_pedidos_a_completado( $order_id ) {
    global $woocommerce;
    
    //ID's de las pasarelas de pago a las que afecta
    $paymentMethods = array( 'bacs', 'cheque', 'cod', 'paypal' );
    
    if ( !$order_id ) return;
    $order = new WC_Order( $order_id );

    if ( !in_array( $order->payment_method, $paymentMethods ) ) return;
    $order->update_status( 'completed' );
}


<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if ( ! class_exists( ' WEL_Order_Meta' ) ) {
    /**
     * Class WEL_Order_Meta
     */
    class WEL_Order_Meta
    {
        function __construct()
        {
            add_filter( 'woocommerce_my_account_my_orders_actions', [$this, 'add_orders_actions_from_meta'], 10, 2);
        }

        function add_orders_actions_from_meta(array $actions, WC_Order $order = null) {
            if(isset($order))
            {
                $tracking_url = $order->get_meta('tracking_url');
                if(!empty($tracking_url)){
                    $actions[] = array (
                        'url' => $tracking_url,
                        'name' => __('Tracking', 'woocommerce-external-logistic')
                    );
                }

                return $actions;
            }
        }
    }
}

new WEL_Order_Meta();
<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if ( ! class_exists( ' WEL_Order_Status' ) ) {
    /**
     * Class WEL_Order_Status
     */
    class WEL_Order_Status {

        private $core_status;

        function __construct() {

            $this->core_status = wc_get_order_statuses();

            add_action( 'init', [$this, 'register_custom_statuses'] );

            // add custom order statuses
            add_filter( 'wc_order_statuses', [$this, 'get_order_statuses'] );

            // add custom paid statuses
            add_filter( 'woocommerce_order_is_download_permitted', [$this, 'order_is_download_permitted'], 10, 2 );
        }

        /**
         * Register statuses
         *
         * @since 0.1.0
         */
        function register_custom_statuses() {

            $statuses = wc_get_order_statuses();

            foreach ($statuses as $slug => $name) {

                // Do not register core status
                if(isset($this->core_status[$slug]))
                    continue;

                register_post_status($slug, array(
                    'label' => $name,
                    'public' => false,
                    'exclude_from_search' => false,
                    'show_in_admin_all_list' => true,
                    'show_in_admin_status_list' => true,
                    'label_count' => _n_noop($name . ' <span class="count">(%s)</span>', $name . ' <span class="count">(%s)</span>', 'woocommerce_external_logistic'),
                ));
            }
        }


        /**
         * Add custom order statuses to WooCommerce order statuses
         *
         * @since 0.1.0
         * @param array $order_statuses (core statuses)
         * @return array
         *
         * Cores statuses
         * Array (
         *  [wc-pending] => Pending payment
         *  [wc-processing] => Processing
         *  [wc-on-hold] => On hold
         *  [wc-completed] => Completed
         *  [wc-cancelled] => Cancelled
         *  [wc-refunded] => Refunded
         *  [wc-failed] => Failed
         * )
         *
         * TODO: put text in languages/
         */
        public function get_order_statuses( array $order_statuses ) {

            $new_statuses = [
                //Core
                'wc-pending' => 'Attente paiement',
                'wc-processing' => 'Payée',
                'wc-on-hold' => 'En attente',
                'wc-completed' => 'Terminée',
                'wc-cancelled' => 'Paiement non reçu',
                'wc-refunded' => 'Remboursée',
                'wc-failed' => 'Échouée',

                //Custom
                'wc-imported' => 'En cours de traitement',
                'wc-preparing' => 'En cours de préparation',
                'wc-restocking' => 'En cours de réapprovisionnement',
                'wc-merged' => 'Regroupée',
                'wc-cancelled-by' => 'Annulée',
                'wc-partially-shipped' => 'Partiellement expédié',
                'wc-awaiting-shipment' => 'En attente d\'expédition',
                'wc-shipped' => 'Expédiée',
                'wc-delivered' => 'Livrée',
                'wc-hand-delivery' => 'Remise en main propre',
            ];

            return $new_statuses  + $order_statuses;
        }

        /**
         * Add custom order paid statuses to WooCommerce paid order statuses
         *
         * @since 0.2.0
         * @return array
         */
        public function get_order_paid_statuses() {
            return $this->get_custom_paid_statuses();
        }

        /**
         * Add custom order downloadable statuses to WooCommerce paid order downloadable statuses
         *
         * @since 0.2.0
         * @return boolean
         */
        public function order_is_download_permitted($permitted, $order) {
            if(in_array($order->get_status(), $this->get_custom_paid_statuses()))
                return true;

            return $permitted;
        }


        /**
         * Return custom paid statuses
         *
         * @since 0.2.0
         * @return array
         */
        public function get_custom_paid_statuses() {

            $paid_statuses = [

                'imported',
                'preparing',
                'restocking',
                'merged',
                'partially-shipped',
                'awaiting-shipment',
                'shipped',
                'delivered',
                'hand-delivery',
            ];

            return $paid_statuses;
        }

    }
}

new WEL_Order_Status();



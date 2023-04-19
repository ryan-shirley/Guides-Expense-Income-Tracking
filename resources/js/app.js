/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

import Vue from "vue";
import axios from "axios";

window.Vue = Vue;

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

Vue.component('chat-bot', require('./components/ChatbotComponent.vue').default);
Vue.component('data-export', require('./components/DataExportComponent.vue').default);
Vue.component('payments-component', require('./components/PaymentsComponent.vue').default);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: '#app',
});

var Chart = require('chart.js')


$(document).ready(function () {

    window.setTimeout(function() {
        $(".alert").fadeTo(1000, 0).slideUp(500, function(){
            $(this).remove();
        });
    }, 3000);

});


// Payment type hiding based on type of money used
// Guides | Allow All
const guideMoney = "#guide_money1";
const personalMoney = "#guide_money2";
const cashPayment = "#is_cash1";
const bankPayment = "#is_cash2";
$(guideMoney).click(function() {
    TogglePaymentTypeInput(false)
});

// Personal | Disable input and force cash checked
$(personalMoney).click(function() {
    $(cashPayment).prop('checked', true);
    TogglePaymentTypeInput(true)
});

function TogglePaymentTypeInput(disable) {
    if(disable) {
        $(cashPayment).attr("disabled", true);
        $(bankPayment).attr("disabled", true);

        return;
    }

    $(cashPayment).removeAttr("disabled");
    $(bankPayment).removeAttr("disabled");
}

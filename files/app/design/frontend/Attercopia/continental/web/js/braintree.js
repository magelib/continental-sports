// braintree.js

require.config({
  paths: {
    braintree: 'https://js.braintreegateway.com/js/braintree-2.32.1.min'
  }
});

require(['braintree'], function (braintree) {
  braintree.setup('CLIENT-TOKEN-FROM-SERVER', 'INTEGRATION_TYPE', options);
});

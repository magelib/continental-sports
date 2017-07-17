alert("loaded");
require(['braintreeClient', 'hostedFields'], function (client, hostedFields) {
  client.create({
    authorization: 'CLIENT_AUTHORIZATION'
  }, function (err, clientInstance) {
    hostedFields.create(/* ... */);
  });
});

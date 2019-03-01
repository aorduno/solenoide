import Route from '@ember/routing/route';

export default Route.extend({
  model: function () {
    let addTransactionRequest = this.store.peekRecord('addTransactionRequest', 1);
    if (addTransactionRequest == null) {
      return this.store.createRecord('addTransactionRequest', {
        id: 1
      });
    }

    return addTransactionRequest;
  }
});

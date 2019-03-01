import Route from '@ember/routing/route';

export default Route.extend({
  model: function () {
    let user = this.store.peekRecord('user', 2);
    if (user == null) {
      return this.store.createRecord('user', {
        id: 2,
        name: 'aorduno'
      });
    }

    return user;
  }
});

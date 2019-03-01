import Service from '@ember/service';

export default Service.extend({
  store: Ember.inject.service(),

  notifications: null,

  pollNotifications: function () {
    let self = this;

    function poll() {
      self.get('store').findAll('notification').then(function (notifications) {
        notifications.forEach(function (notification) {
          self.add(notification);
        });
      }).catch(function (error) {
        console.log('error fetching notification');
        console.log(error);
      }).finally(function () {
        self.setTimeout(poll, 10);
      });
    }

    poll();
  },

  init() {
    this._super(...arguments);
    this.set('notifications', []);
  },

  add(item) {
    if (!this.get('notifications').isAny('id', item.get('id'))) {
      this.get('notifications').pushObject(item);
    }
  },

  remove(item) {
    this.get('notifications').removeObject(item);
  },

  empty() {
    this.get('notifications').setObjects([]);
  },

  setTimeout: function (poll, waitSeconds) {
    return setTimeout(function () {
      poll();
    }, waitSeconds * 1000);
  }
});

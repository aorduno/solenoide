import Component from '@ember/component';
import {inject as service} from '@ember/service';
import {computed} from '@ember/object';
import {later} from '@ember/runloop';

export default Component.extend({
  pollNotificationService: service(),

  showExpanded:false,

  hasNotifications: computed('pollNotificationService.notifications.[]', function () {
    return !Ember.isEmpty(this.get('pollNotificationService.notifications'));
  }),

  init() {
    this._super(...arguments);

    later(this, function () {
      this.get('pollNotificationService').pollNotifications();
    }, 5000);
  },

  actions: {
    expand() {
      this.toggleProperty('showExpanded');
    }

  }
});

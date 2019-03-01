import Component from '@ember/component';
import {inject as service} from '@ember/service';

export default Component.extend({
  store: Ember.inject.service(),
  pollNotificationService: service(),
  fullScreenInterstitialService: service(),

  item: null,

  actions: {
    delete() {
      let item = this.get('item');
      let store = this.get('store');
      let pollNotificationService = this.get('pollNotificationService');
      let fullScreenInterstitialService = this.get('fullScreenInterstitialService');
      fullScreenInterstitialService.show();
      item.destroyRecord()
        .then(function (response) {
          pollNotificationService.remove(item);
          store.unloadRecord(item);
          console.log(response);
        })
        .catch(function (error) {
          console.log(error);
        }).finally(function () {
        fullScreenInterstitialService.hide();
      });
    }
  }
});

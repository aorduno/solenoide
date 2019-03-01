import Component from '@ember/component';
import {inject as service} from '@ember/service';
import {computed} from '@ember/object';

export default Component.extend({
  fullScreenInterstitialService: service(),
  isShowing: computed('fullScreenInterstitialService.isShowing', function () {
    return this.get('fullScreenInterstitialService.isShowing');
  })
});

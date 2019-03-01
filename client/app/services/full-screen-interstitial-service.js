import Service from '@ember/service';
import {computed} from '@ember/object';

export default Service.extend({
  _isShowing: false,

  isShowing: computed('_isShowing', function () {
    return !!this.get('_isShowing');
  }),

  show() {
    this.set('_isShowing', true);
  },

  hide() {
    this.set('_isShowing', false);
  }
});
